<?php
App::uses('AdminAppController', 'Controller');

/**
 * Favorites Controller
 *
 * @property Favorite $Favorite
 * @property Search.PrgComponent $Search.Prg
 * @property AdminComponent $Admin
 */
class FavoritesController extends AdminAppController
{
    /* inject */
    /**
     * Paginate
     *
     * @var array
     */
    public $paginate = array(
        'Favorite' => array(
            'order' => array('Favorite.id' => 'asc'),
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
        $this->breadcrumbControllerNames['favorites'] = $this->Favorite->pluralName;

        parent::beforeFilter();

        if ($this->admin) $this->set('model', $this->Favorite);

        $this->Auth->allow('edit');
    }

    public function edit()
    {
        if ($this->Session->read('user.logged') != TRUE) $this->redirect(array('controller' => 'users', 'action' => 'login'));




    }

    /**
     * admin_index method
     *
     * @return void
     */
    public function admin_index()
    {
        $this->Favorite->recursive = 0;
        try {
            $records = $this->paginate('Favorite');
        } catch (NotFoundException $e) {
            $this->Admin->setFlashInfo('<strong>Sin Resultados para esa página!</strong> Ahora estamos en la primera página.');
            $this->redirect(Set::merge(Set::get($this->request->params, 'named'), array('page' => 1)));
        }
        $this->set('favorites', $records);
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
        if (!$this->Favorite->exists($id)) {
            throw new NotFoundException('El registro no existe');
        }
        $this->set('favorite', $this->Favorite->find('first', array('conditions' => array('Favorite.id' => $id))));
    }

    /**
     * admin_add method
     *
     * @return void
     */
    public function admin_add()
    {
        if ($this->request->is('post')) {
            $this->Favorite->create();
            if ($this->Favorite->save($this->request->data)) {
                $this->Admin->setFlashSuccess('El registro ha sido guardado');
                $this->redirect(isset($this->request->data['_addAnother']) ? array('action' => 'add') : $this->getPreviousUrl());
            } else {
                $this->Admin->setFlashError('El registro no se pudo guardar. Por favor intenta más tarde.');
            }
        }
        $users = $this->Favorite->User->find('list');
        $this->set(compact('users'));
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
        if (!$this->Favorite->exists($id)) {
            throw new NotFoundException('Registro inválido');
        }
        if ($this->request->is('post') || $this->request->is('put')) {
            if ($this->Favorite->save($this->request->data)) {
                $this->Admin->setFlashSuccess('El registro ha sido guardado');
                $this->redirect($this->getPreviousUrl());
            } else {
                $this->Admin->setFlashError('El registro no se pudo guardar. Por favor intenta más tarde.');
            }
        } else {
            $this->request->data = $this->Favorite->find('first', array('conditions' => array('Favorite.id' => $id)));
        }
        $users = $this->Favorite->User->find('list');
        $this->set(compact('users'));
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
        if (!$this->Favorite->exists($id)) {
            throw new NotFoundException('Registro inválido');
        }
        if ($this->request->is('post')) {
            $this->Favorite->id = $id;
            if ($this->Favorite->delete()) {
                $this->Admin->setFlashSuccess('El registro ha sido borrado');
                $this->redirect($this->getPreviousUrl());
            }
            $this->Admin->setFlashError('El registro no se pudo borrar. Por favor intenta más tarde.');
        }
        $this->set('favorite', $this->Favorite->read(NULL, $id));
    }
}
