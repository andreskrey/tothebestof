<?php
App::uses( 'AdminAppController', 'Controller' );
/**
 * Songids Controller
 *
 * @property Songid $Songid
 * @property Search.PrgComponent $Search.Prg
 * @property AdminComponent $Admin
 */
class SongidsController extends AdminAppController
{
  /* inject */
  /**
	 * Paginate
	 *
	 * @var array
	 */
  public $paginate = array(
    'Songid' => array(
      'order' => array( 'Songid.id' => 'asc' ),
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
    $this->breadcrumbControllerNames['songids'] = $this->Songid->pluralName;

    parent::beforeFilter();

    if ( $this->admin ) $this->set( 'model', $this->Songid );
	}

	/**
	 * admin_index method
	 *
	 * @return void
	 */
	public function admin_index()
	{
    $this->Songid->recursive = 0;
    try
    {
      $records = $this->paginate('Songid');
    }
    catch ( NotFoundException $e )
    {
      $this->Admin->setFlashInfo( '<strong>Sin Resultados para esa página!</strong> Ahora estamos en la primera página.' );
      $this->redirect( Set::merge( Set::get( $this->request->params, 'named' ), array('page' => 1) ) );
    }
    $this->set( 'songids', $records );
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
		if (!$this->Songid->exists($id)) {
			throw new NotFoundException( 'El registro no existe' );
		}
		$this->set('songid', $this->Songid->find('first', array('conditions' => array('Songid.id' => $id))));
	}

	/**
	 * admin_add method
	 *
	 * @return void
	 */
	public function admin_add()
	{
		if ($this->request->is('post')) {
			$this->Songid->create();
			if ($this->Songid->save($this->request->data)) {
				$this->Admin->setFlashSuccess( 'El registro ha sido guardado' );
				$this->redirect( isset( $this->request->data[ '_addAnother' ] ) ? array( 'action' => 'add' ) : $this->getPreviousUrl() );
			} else {
				$this->Admin->setFlashError( 'El registro no se pudo guardar. Por favor intenta más tarde.' );
			}
		}
		$bands = $this->Songid->Band->find('list');
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
		if (!$this->Songid->exists($id)) {
			throw new NotFoundException( 'Registro inválido' );
		}
		if ($this->request->is('post') || $this->request->is('put')) {
			if ($this->Songid->save($this->request->data)) {
				$this->Admin->setFlashSuccess( 'El registro ha sido guardado' );
				$this->redirect($this->getPreviousUrl());
			} else {
				$this->Admin->setFlashError( 'El registro no se pudo guardar. Por favor intenta más tarde.' );
			}
		} else {
			$this->request->data = $this->Songid->find('first', array('conditions' => array('Songid.id' => $id)));
		}
		$bands = $this->Songid->Band->find('list');
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
    if ( !$this->Songid->exists($id) ) {
      throw new NotFoundException( 'Registro inválido' );
    }
		if ( $this->request->is( 'post' ) ) {
			$this->Songid->id = $id;
			if ( $this->Songid->delete() ) {
				$this->Admin->setFlashSuccess( 'El registro ha sido borrado' );
				$this->redirect( $this->getPreviousUrl() );
			}
			$this->Admin->setFlashError( 'El registro no se pudo borrar. Por favor intenta más tarde.' );
		}
		$this->set( 'songid', $this->Songid->read( NULL, $id ) );
	}
}
