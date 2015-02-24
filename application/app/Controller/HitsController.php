<?php
App::uses('AdminAppController', 'Controller');

/**
 * Hits Controller
 *
 * @property Hit $Hit
 * @property Search.PrgComponent $Search.Prg
 * @property AdminComponent $Admin
 */
class HitsController extends AdminAppController
{
    /* inject */
    /**
     * Paginate
     *
     * @var array
     */
    public $paginate = array(
        'Hit' => array(
            'order' => array('Hit.id' => 'asc'),
            'limit' => 20,
        )
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
    public $components = array('Search.Prg', 'Admin');

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
        $this->breadcrumbControllerNames['hits'] = $this->Hit->pluralName;

        parent::beforeFilter();

        if ($this->admin) $this->set('model', $this->Hit);

        $this->Auth->allow('index');
    }

    public function index()
    {
        $to = date('Y-m-d 23:59:59', strtotime('last week + 6 days'));
        $from = date('Y-m-d 00:00:00', strtotime('last week'));
        $dates = array(
            'from' => date('Y-M-d', strtotime('last week')),
            'to' => date('Y-M-d', strtotime('last week + 6 days'))
        );
        $bandName = null;

        if ($this->request->is('post') || $this->request->is('put')) {
            if (!empty($this->request->data['Hit']['dateTo']) || !empty($this->request->data['Hit']['dateFrom'])) {
                $timestampFrom = strtotime($this->request->data['Hit']['dateFrom']);
                $timestampTo = strtotime($this->request->data['Hit']['dateTo']);

                $error = false;

                if (!$timestampFrom || !$timestampTo) {
                    $error = true;
                    $this->Admin->setFlashError('Invalid date');
                }

                if ($timestampTo < $timestampFrom) {
                    $error = true;
                    $this->Admin->setFlashError('Nigga, the dates are reversed.');
                }

                if ($timestampFrom < strtotime('2014-11-11')) {
                    $error = true;
                    $this->Admin->setFlashError('No info before Nov-2014, sorry.');
                }

                if ($timestampFrom === false || $timestampTo === false) {
                    $error = true;
                    $this->Admin->setFlashError('One of the dates is missing');
                }

                if (!$error) {
                    $from = date('Y-m-d 00:00:00', $timestampFrom);
                    $to = date('Y-m-d 23:59:59', $timestampTo);
                    $dates = array(
                        'from' => date('Y-M-d', $timestampFrom),
                        'to' => date('Y-M-d', $timestampTo)
                    );
                }
            }

            if (!empty($this->request->data['Hit']['band'])) {
                $bandName = array('Hit.name' => array_map('trim', explode(',', $this->request->data['Hit']['band'])));
            }
        }

        $search = $this->Hit->find('all', array(
            'fields' => array(
                'Hit.*',
                'COUNT(band_id) AS `count`'
            ),
            'conditions' => array(
                'Hit.created between ? and ?' => array($from, $to),
                $bandName
            ),
            'group' => array('Hit.band_id'),
            'contain' => false,
            'limit' => 50
        ));

        $total = $this->Hit->find('count', array(
            'conditions' => array('Hit.created between ? and ?' => array($from, $to)),
        ));

        $data = Hash::merge(Hash::extract($search, '{n}.Hit'), Hash::extract($search, '{n}.{n}'));
        usort($data, function (array $a, array $b) {
            return $b['count'] - $a['count'];
        });

        $this->set('data', $data);
        $this->set('total', $total);
        $this->set('dates', $dates);
    }

    /**
     * admin_index method
     *
     * @return void
     */
    public function admin_index()
    {
        $this->Hit->recursive = 0;
        try {
            $records = $this->paginate('Hit');
        } catch (NotFoundException $e) {
            $this->Admin->setFlashInfo('<strong>Sin Resultados para esa página!</strong> Ahora estamos en la primera página.');
            $this->redirect(Set::merge(Set::get($this->request->params, 'named'), array('page' => 1)));
        }
        $this->set('hits', $records);
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
        if (!$this->Hit->exists($id)) {
            throw new NotFoundException('El registro no existe');
        }
        $this->set('hit', $this->Hit->find('first', array('conditions' => array('Hit.id' => $id))));
    }

    /**
     * admin_add method
     *
     * @return void
     */
    public function admin_add()
    {
        if ($this->request->is('post')) {
            $this->Hit->create();
            if ($this->Hit->save($this->request->data)) {
                $this->Admin->setFlashSuccess('El registro ha sido guardado');
                $this->redirect(isset($this->request->data['_addAnother']) ? array('action' => 'add') : $this->getPreviousUrl());
            } else {
                $this->Admin->setFlashError('El registro no se pudo guardar. Por favor intenta más tarde.');
            }
        }
        $bands = $this->Hit->Band->find('list');
        $this->set(compact('bands'));
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
        if (!$this->Hit->exists($id)) {
            throw new NotFoundException('Registro inválido');
        }
        if ($this->request->is('post') || $this->request->is('put')) {
            if ($this->Hit->save($this->request->data)) {
                $this->Admin->setFlashSuccess('El registro ha sido guardado');
                $this->redirect($this->getPreviousUrl());
            } else {
                $this->Admin->setFlashError('El registro no se pudo guardar. Por favor intenta más tarde.');
            }
        } else {
            $this->request->data = $this->Hit->find('first', array('conditions' => array('Hit.id' => $id)));
        }
        $bands = $this->Hit->Band->find('list');
        $this->set(compact('bands'));
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
        if (!$this->Hit->exists($id)) {
            throw new NotFoundException('Registro inválido');
        }
        if ($this->request->is('post')) {
            $this->Hit->id = $id;
            if ($this->Hit->delete()) {
                $this->Admin->setFlashSuccess('El registro ha sido borrado');
                $this->redirect($this->getPreviousUrl());
            }
            $this->Admin->setFlashError('El registro no se pudo borrar. Por favor intenta más tarde.');
        }
        $this->set('hit', $this->Hit->read(NULL, $id));
    }
}
