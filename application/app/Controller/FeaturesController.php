<?php
App::uses('AdminAppController', 'Controller');

/**
 * Features Controller
 *
 * @property Feature $Feature
 * @property Search.PrgComponent $Search.Prg
 * @property AdminComponent $Admin
 */
class FeaturesController extends AdminAppController
{
    /* inject */
    /**
     * Paginate
     *
     * @var array
     */
    public $paginate = array(
        'Feature' => array(
            'order' => array(
                'Feature.enabled' => 'asc',
            ),
            'limit' => 20,
        ),
    );

    /**
     * Helpers
     *
     * @var array
     */
    public $helpers = array('Admin');

    /**
     * Components
     *
     * @var array
     */
    public $components = array('Search.Prg', 'Admin', 'Attemptsort');

    /**
     * Cached actions
     *
     * @var array
     */
    //public $cacheAction = array();


    /**
     * beforeFilter method
     *
     * @return void
     */
    public function beforeFilter()
    {
        $this->breadcrumbControllerNames['features'] = $this->Feature->pluralName;

        parent::beforeFilter();

        if ($this->admin) $this->set('model', $this->Feature);

        $this->Auth->allow('index', 'thanks', 'ranking', 'ranking_sort');

    }


    public function index()
    {
        if ($this->request->is('post')) {
            $this->Feature->create();
            $this->request->data['Feature']['enabled'] = TRUE;
            $this->request->data['Feature']['votes'] = 1;
            $this->request->data['Feature']['status'] = 'review';
            if ($this->Feature->save($this->request->data)) {
                $this->redirect('thanks');
            }
        }
    }


    public function thanks()
    {

    }

    public function ranking()
    {
        $data = array(
            'progress' => null,
            'rejected' => null,
            'done' => null,
            'review' => null,
        );
        $dump = $this->Feature->find('all', array(
            'fields' => array('Feature.*'),
            'conditions' => array('Feature.enabled' => TRUE),
            'order' => array('Feature.votes' => 'desc'),
        ));

        foreach ($dump as $i) {
            $data[$i['Feature']['status']][] = $i['Feature'];
        }

        $this->set('data', $data);
    }

    public function ranking_sort($id, $value)
    {
        if (!$this->request->is('post')) {
            throw new MethodNotAllowedException('Only Post');
        }

        if ($this->Attemptsort->check(1, $id) === FALSE) {
            $this->Admin->setFlashError('You can only vote an idea once per day :(');
            $this->redirect($this->referer());
        } else {

            /** @var $currentModel Model */
            $currentModel = $this->{$this->modelClass};

            $currentModel->id = $id;
            $current = $currentModel->field('votes');
            $saved = $currentModel->saveField('votes', $current + $value, FALSE);

            if ($saved) {
                $this->Admin->setFlashSuccess('Voted!');
            } else {
                $this->Admin->setFlashError('Error');
            }

            $this->redirect($this->referer());
        }
    }

    /**
     * admin_index method
     *
     * @return void
     */
    public function admin_index()
    {
        $this->Feature->recursive = 0;
        try {
            $records = $this->paginate('Feature');
        } catch (NotFoundException $e) {
            $this->Admin->setFlashInfo('<strong>Sin Resultados para esa página!</strong> Ahora estamos en la primera página.');
            $this->redirect(Set::merge(Set::get($this->request->params, 'named'), array('page' => 1)));
        }
        $this->set('features', $records);
    }

    /**
     * admin_view method
     *
     * @throws NotFoundException
     * @param string $id
     * @return void
     */
    public function admin_view($id = null)
    {
        if (!$this->Feature->exists($id)) {
            throw new NotFoundException('El registro no existe');
        }
        $this->set('feature', $this->Feature->find('first', array('conditions' => array('Feature.id' => $id))));
    }

    /**
     * admin_add method
     *
     * @return void
     */
    public function admin_add()
    {
        if ($this->request->is('post')) {
            $this->Feature->create();
            if ($this->Feature->save($this->request->data)) {
                $this->Admin->setFlashSuccess('El registro ha sido guardado');
                $this->redirect(isset($this->request->data['_addAnother']) ? array('action' => 'add') : $this->getPreviousUrl());
            } else {
                $this->Admin->setFlashError('El registro no se pudo guardar. Por favor intenta más tarde.');
            }
        }
    }

    /**
     * admin_edit method
     *
     * @throws NotFoundException
     * @param string $id
     * @return void
     */
    public function admin_edit($id = null)
    {
        if (!$this->Feature->exists($id)) {
            throw new NotFoundException('Registro inválido');
        }
        if ($this->request->is('post') || $this->request->is('put')) {
            if ($this->Feature->save($this->request->data)) {
                $this->Admin->setFlashSuccess('El registro ha sido guardado');
                $this->redirect($this->getPreviousUrl());
            } else {
                $this->Admin->setFlashError('El registro no se pudo guardar. Por favor intenta más tarde.');
            }
        } else {
            $this->request->data = $this->Feature->find('first', array('conditions' => array('Feature.id' => $id)));
        }
    }

    /**
     * admin_delete method
     *
     * @throws NotFoundException
     * @param string $id
     * @return void
     */
    public function admin_delete($id = null)
    {
        if (!$this->Feature->exists($id)) {
            throw new NotFoundException('Registro inválido');
        }
        if ($this->request->is('post')) {
            $this->Feature->id = $id;
            if ($this->Feature->delete()) {
                $this->Admin->setFlashSuccess('El registro ha sido borrado');
                $this->redirect($this->getPreviousUrl());
            }
            $this->Admin->setFlashError('El registro no se pudo borrar. Por favor intenta más tarde.');
        }
        $this->set('feature', $this->Feature->read(NULL, $id));
    }
}
