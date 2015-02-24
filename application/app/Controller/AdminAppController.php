<?php
App::uses( 'AppController', 'Controller' );
App::uses( 'CakeSession', 'Model/Datasource' );

/**
 * @property AdminComponent $Admin
 */
class AdminAppController extends AppController
{

  /**
   * Search Plugin, zero config
   */
  public $presetVars = TRUE;


  /**
   * Before Filter default para todos los controllers del admin
   */
  public function beforeFilter()
  {
    parent::beforeFilter();

    //helpers propios del admin
    $this->helpers[ 'BootstrapHtml' ] = array( 'className' => 'TwitterBootstrap.BootstrapHtml' );
    $this->helpers[ 'BootstrapForm' ] = array( 'className' => 'TwitterBootstrap.BootstrapForm' );
    $this->helpers[ 'BootstrapPaginator' ] = array( 'className' => 'TwitterBootstrap.BootstrapPaginator' );
    $this->helpers[ 'Admin' ] = NULL;


    //unlock para boton add another con Security Component
    $this->Security->unlockedFields = array( '_addAnother', '_wysihtml5_mode' );
  }


  /**
   * Metodo generico de busqueda para Admin.
   * Si llega sin parametros redirecciona al index.
   * Usa el PRG para recibir busquedas por POST y redireccionar a GET pasando los parametros por URL
   */
  public function admin_search()
  {
    if ( $this->request->is( 'get' ) && !count( $this->passedArgs ) )
    {
      $this->redirect( array( 'action' => 'index' ) );
    }

    $this->Prg->commonProcess( NULL, array( 'filterEmpty' => TRUE ) );
    $this->paginate[ $this->modelClass ][ 'conditions' ] = $this->{$this->modelClass}->parseCriteria( $this->passedArgs );
    $this->set( Inflector::variable( Inflector::pluralize( $this->modelClass ) ), $this->paginate( $this->modelClass ) );
    $this->set( 'conditions', json_encode( $this->paginate[ $this->modelClass ][ 'conditions' ] ) );
    $this->render( 'admin_index' );
  }


  /**
   * Metodo generico para filtrar registros asociados. Por atras usa una búsqueda tradicional, pero tira
   * error si no trae parametros, y el View reacciona diferente si está renderando Filter en vez de Search,
   * básicamente saca los paneles de Search y cambia un poco el UI de los tabs.
   *
   * Nota: filter SOLO funciona con UN foreignKey
   *
   * @throws NotFoundException
   * @throws NotImplementedException
   */
  public function admin_filter( $parent, $id )
  {
    if ( $this->request->is( 'get' ) && count( $this->request->params[ 'pass' ] ) != 2 )
    {
      throw new NotImplementedException( 'No se puede pasar más de un parametro para filtros' );
    }

    $parentModelName = Inflector::classify( $parent );
    $key = $this->{$this->modelClass}->belongsTo[ $parentModelName ][ 'foreignKey' ];
    $belongsTo = array(
      'controller' => $parent,
      'id'         => $id
    );
    $this->set( 'belongsTo', $belongsTo );

    $this->passedArgs = array( $key => $id );

    //sobreescribe navTree
    array_splice( $this->navTree, 1, 1, array(
      array(
        'name'  => ClassRegistry::init( $parentModelName )->pluralName,
        'route' => array( 'controller' => $parent, 'action' => 'index' )
      ),
      array(
        'name'  => ClassRegistry::init( $parentModelName )->singularName . ' #' . $id,
        'route' => array( 'controller' => $parent, 'action' => 'view', $id )
      )
    ) );

    $this->admin_search();
  }


  public function admin_add()
  {

  }


  /**
   * Metodo admin_add para llamar en los que aplique (o todos) y post-popular request data
   * con passed arguments que vengan por URL.
   * Es importante saber que estos SOBREESCRIBEN los datos de request data, independientemente
   * de las elecciones en el formulario.
   */
  public function admin_add_related( $parent, $id )
  {
    $parentModelName = Inflector::classify( $parent );
    $controller = Inflector::tableize( $this->name );
    $key = $this->{$this->modelClass}->belongsTo[ $parentModelName ][ 'foreignKey' ];
    $belongsTo = array(
      'controller' => $parent,
      'id'         => $id
    );
    $this->set( 'belongsTo', $belongsTo );
    $this->passedArgs = array( $key => $id );

    $this->request->data[ $this->modelClass ] = Hash::check( $this->request->data, $this->modelClass ) ? Hash::merge( $this->request->data[ $this->modelClass ], $this->passedArgs ) : $this->request->data[ $this->modelClass ] = $this->passedArgs;

    //sobreescribe navTree
    array_splice( $this->navTree, 1, 1, array(
      array(
        'name'  => ClassRegistry::init( $parentModelName )->pluralName,
        'route' => array( 'controller' => $parent, 'action' => 'index' )
      ),
      array(
        'name'  => ClassRegistry::init( $parentModelName )->singularName . ' #' . $id,
        'route' => array( 'controller' => $parent, 'action' => 'view', $id )
      ),
      array(
        'name'  => $this->{$this->modelClass}->pluralName . ' relacionados',
        'route' => array( 'controller' => $controller, 'action' => 'filter', 'parent' => $parent, 'id' => $id )
      ),
    ) );

    $this->admin_add();
    $this->render( 'admin_add' );
  }


  /**
   * @param $id
   * @param $field
   * @param $value
   * @throws MethodNotAllowedException
   */
  public function admin_sort( $id, $field, $value )
  {
    if ( !$this->request->is( 'post' ) )
    {
      throw new MethodNotAllowedException( 'Only Post' );
    }

    /** @var $currentModel Model */
    $currentModel = $this->{$this->modelClass};

    $currentModel->id = $id;
    $current = $currentModel->field( $field );
    $saved = $currentModel->saveField( $field, $current + $value, FALSE );

    if ( $saved )
    {
      $this->Admin->setFlashSuccess( 'Registro actualizado.' );
    }
    else
    {
      $this->Admin->setFlashError( 'El registro no se pudo guardar. Por favor intenta más tarde.' );
    }

    $this->redirect( $this->referer() );
  }


  /**
   * @param $id
   * @param $field
   * @throws NotFoundException
   * @throws MethodNotAllowedException
   */
  public function admin_toggle( $id, $field )
  {
    $this->autoRender = FALSE;

    if ( !$this->request->is( 'post' ) )
    {
      throw new MethodNotAllowedException( 'Only Post' );
    }

    /** @var $currentModel Model */
    $currentModel = $this->{$this->modelClass};

    $currentModel->id = $id;
    if ( !$currentModel->exists() ) throw new NotFoundException();

    //tomo el valor, y lo invierto
    $value = (int)!$currentModel->field( $field );

    //escribo el valor modificado
    $saved = $currentModel->saveField( $field, $value );

    if ( $saved )
    {
      $this->Admin->setFlashSuccess( 'Registro actualizado.' );
    }
    else
    {
      $this->Admin->setFlashError( 'El registro no se pudo guardar. Por favor intenta más tarde.' );
    }

    $this->redirect( $this->referer() );
  }


  /**
   * @param $id
   * @param $field
   */
  public function admin_delete_file( $id, $field )
  {
    /** @var $currentModel Model */
    $currentModel = $this->{$this->modelClass};

    $record = $currentModel->read( NULL, $id );
    if ( !$record )
    {
      throw new NotFoundException();
    }

    // @todo borrar fields asociados si existen...
    $saved = $currentModel->deleteFiles( $id, array( $field ) );

    if ( $saved )
    {
      $this->Admin->setFlashSuccess( 'Archivo borrado' );
    }
    else
    {
      $this->Admin->setFlashError( 'El archivo no se pudo borrar. Por favor intenta más tarde.' );
    }

    $this->redirect( $this->referer() );
  }


  /**
   * Método para exportar todos los registros de un model
   */
  public function admin_export()
  {
    /** @var $currentModel Model */
    $currentModel = $this->{$this->modelClass};

    if ( $this->request->is( 'post' ) )
    {
      //procesa, y ok
      /**
       * @var $fields
       * @var $conditions
       * @var $noheader
       * @var $separator
       */
      extract( $this->request->data[ 'Export' ] );

      $query = $currentModel->buildQuery( 'all', array(
        'fields' => $fields,
        'order'  => "{$currentModel->name}.id"
      ) );
      $data = $currentModel->find( 'all', $query );
      $this->generateCsv( $data, $noheader, $separator, 'export', $currentModel );

      return;
    }
    $count = $currentModel->find( 'count' );
    if ( !$count )
    {
      $this->Admin->setFlashError( 'No hay registros para exportar' );
      $this->redirect( array( 'action' => 'index' ) );
    }

    $this->set( 'controller', Inflector::tableize( $this->name ) );
    $this->set( 'model', $currentModel );
    $this->set( 'count', $count );

    $this->viewPath = 'Admin';
  }


  private function generateCsv( $data, $noheader, $separator, $filename = 'export', $currentModel = NULL )
  {
    App::uses( 'File', 'Utility' );

    $separatorMap = array(
      'colon'     => ',',
      'semicolon' => ';',
      'tab'       => "\t"
    );

    $separator = $separatorMap[ $separator ];

    $key = key( $data[ 0 ] );

    $file = new File( TMP . String::uuid(), TRUE );
    if ( !$noheader )
    {
      $fieldnames = $data[ 0 ][ $key ];
      if ( $currentModel ) $fieldnames = array_flip( $currentModel->fieldNames );
      $file->append( "\"" . join( "\"{$separator}\"", array_keys( $fieldnames ) ) . "\"\n" );
    }

    foreach ( $data as $row )
    {
      $line = array();
      foreach ( $row[ $key ] as $value )
      {
        $value = str_replace( '"', '""', $value );
        $line[ ] = $value;
      }
      $file->append( "\"" . join( "\"{$separator}\"", $line ) . "\"\n" );
    }

    $file->close();

    $this->autoRender = FALSE;
    $this->response->type( 'text/csv' );
    $this->response->download( "{$filename}.csv" );
    $this->response->body( utf8_decode( $file->read() ) );

    $file->delete();
  }


  /**
   * Método para exportar resultados de búsquedas y elementos seleccionados
   *
   * @throws MethodNotAllowedException
   */
  public function admin_bulk_export()
  {
    if ( !$this->request->is( 'post' ) )
    {
      throw new MethodNotAllowedException();
    }

    /** @var $currentModel Model */
    $currentModel = $this->{$this->modelClass};

    if ( Hash::check( $this->request->data, 'Export.process' ) )
    {
      //procesa el download
      /**
       * @var $fields
       * @var $conditions
       * @var $noheader
       * @var $separator
       */
      extract( $this->request->data[ 'Export' ] );

      $query = $currentModel->buildQuery( 'all', array(
        'fields'     => $fields,
        'conditions' => json_decode( $conditions, TRUE ),
        'order'      => "{$currentModel->name}.id"
      ) );

      $data = $currentModel->find( 'all', $query );
      $this->generateCsv( $data, $noheader, $separator, $currentModel );

      return;
    }
    else
    {
      if ( Hash::get( $this->request->data, 'Export.type' ) == 'id' )
      {
        $conditions = array( "$currentModel->name.id" => explode( ',', Hash::get( $this->request->data, 'Export.conditions' ) ) );
      }
      else if ( Hash::get( $this->request->data, 'Export.type' ) == 'search' )
      {
        $conditions = json_decode( Hash::get( $this->request->data, 'Export.conditions' ), TRUE );
      }

      $this->request->data[ 'Export' ][ 'conditions' ] = json_encode( $conditions );
    }

    $count = $currentModel->find( 'count', array( 'conditions' => $conditions ) );
    if ( !$count )
    {
      $this->Admin->setFlashError( 'No hay registros para exportar' );
      $this->redirect( $this->referer( array( 'action' => 'index' ) ) );
    }

    $this->set( 'controller', $this->name );
    $this->set( 'model', $currentModel );
    $this->set( 'count', $count );

    $this->viewPath = 'Admin';
    $this->render( 'admin_export' );
  }


  /**
   * @throws MethodNotAllowedException
   */
  public function admin_bulk_delete()
  {
    if ( !$this->request->is( 'post' ) )
    {
      throw new MethodNotAllowedException();
    }

    /** @var $currentModel Model */
    $currentModel = $this->{$this->modelClass};

    $controllerName = Inflector::tableize( Inflector::pluralize( $currentModel->name ) );

    if ( Hash::check( $this->request->data, 'Delete.process' ) )
    {
      extract( $this->request->data[ 'Delete' ] );

      //borra los registros
      $deleted = $currentModel->deleteAll( json_decode( $conditions, TRUE ), TRUE, TRUE );
      if ( $deleted )
      {
        $this->Admin->setFlashSuccess( 'Los registros han sido borrados' );
      }
      else
      {
        $this->Admin->setFlashError( 'Los registros no se pudieron borrar' );
      }
      $this->redirect( array( 'controller' => $controllerName, 'action' => 'index' ) );
    }
    else
    {
      $conditions = array( "$currentModel->name.id" => explode( ',', Hash::get( $this->request->data, 'Delete.conditions' ) ) );
      $this->request->data[ 'Delete' ][ 'conditions' ] = json_encode( $conditions );
    }

    $records = $currentModel->find( 'all', array(
      'fields'     => array( "$currentModel->name.*" ),
      'conditions' => $conditions,
      'contain'    => FALSE
    ) );
    if ( !$records )
    {
      $this->Admin->setFlashError( 'No hay registros para borrar' );
      $this->redirect( $this->referer( array( 'action' => 'index' ) ) );
    }

    $this->set( 'controller', $this->name );
    $this->set( 'model', $currentModel );
    $this->set( 'records', $records );

    $this->viewPath = 'Admin';
  }


  protected function getPreviousUrl()
  {
    return $this->navTree[ count( $this->navTree ) - 2 ][ 'route' ];
  }
}