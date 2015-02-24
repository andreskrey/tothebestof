<?php
App::uses( 'AppShell', 'Console/Command' );
App::uses( 'InjectTask', 'Console/Command/Task' );
App::uses( 'AppModel', 'Model' );

/**
 * Task class for creating and updating controller files.
 *
 * @package       Cake.Console.Command.Task
 */
class InjectRelatedModelTask extends InjectTask
{

  public function execute()
  {
    $this->hr();
    $this->out( "Inject Related Model Functionality" );
    $this->hr();
    $this->interactive = TRUE;

    $primaryKey = 'id';
    $validate = $associations = array();

    if ( empty( $this->connection ) )
    {
      $this->connection = $this->DbConfig->getConfig();
    }

    $this->out( __d( 'inject', 'Select the Parent Model' ) );

    $modelName = $this->getName(); //pide elegir la tabla
    $this->setContext( $modelName );


    App::uses( $modelName, 'Model' );
    $model = new $modelName();

    $this->out( __d( 'inject', 'Select the Child Model' ) );
    $relatedModels = Hash::extract( $model->hasMany, '{s}.className' );
    $related = '';
    while ( !$related )
    {
      foreach ( $relatedModels as $i => $relatedModelName )
      {
        $this->out( __d( 'inject', ( $i + 1 ) . ". {$relatedModelName}" ) );
      }
      $related = $this->in( __d( 'cake_console', "Enter a number from the list above,\n" .
          "type in the name of another model, or 'q' to exit" ), NULL, 'q' );

      if ( $related === 'q' )
      {
        $this->out( __d( 'cake_console', 'Exit' ) );
        $this->_stop();
      }
    }
    $relatedName = $relatedModels[ $related - 1 ];


    $this->hr();
    $this->out( 'Inject Related Model Functionality to parent model ' . $modelName . ' and related model ' . $relatedModelName );
    $looksGood = $this->in( __( 'Look okay?', TRUE ), array( 'y', 'n' ), 'y' );

    if ( strtolower( $looksGood ) == 'y' )
    {
      $data = compact( 'modelName', 'relatedName' );
      $this->inject( $data );
    }
  }


  /**
   * Creates the actual injection in all the needed files
   *
   * @access private
   */
  private function inject( $data = NULL )
  {
    $relatedModelName = $data[ 'relatedName' ];

    //agrega a asset_compress los archivos para concatenar y comprimir
    $routes = new File( APP . 'Config' . DS . 'routes.php' );
    $routesText = $routes->read();

    $pattern = '/Custom Routes for related listing/ms';
    if ( !preg_match( $pattern, $routesText ) )
    {
      $pattern = '/(\/\*\* injects \*\/)(.+?)(\/\*\* end-injects \*\/)/ms';
      $replacement = "$1\n";
      $replacement .= "\n";
      $replacement .= "/**\n";
      $replacement .= " * Custom Routes for related listing\n";
      $replacement .= " */\n";
      $replacement .= "Router::connect(\n";
      $replacement .= "  '/admin/:parent/related/:controller/:id/*',\n";
      $replacement .= "  array( 'prefix' => 'admin', 'admin' => TRUE, 'action' => 'filter' ),\n";
      $replacement .= "  array(\n";
      $replacement .= "    'pass'   => array( 'parent', 'id' ),\n";
      $replacement .= "    'parent' => '[a-zA-Z0-9_]+',\n";
      $replacement .= "    'id'     => '[0-9]+',\n";
      $replacement .= "  )\n";
      $replacement .= ");\n";
      $replacement .= "\n";
      $replacement .= "Router::connect(\n";
      $replacement .= "  '/admin/:parent/related/:controller/add/:id',\n";
      $replacement .= "  array( 'prefix' => 'admin', 'admin' => TRUE, 'action' => 'add_related' ),\n";
      $replacement .= "  array(\n";
      $replacement .= "    'pass'   => array( 'parent', 'id' ),\n";
      $replacement .= "    'parent' => '[a-zA-Z0-9_]+',\n";
      $replacement .= "    'id'     => '[0-9]+',\n";
      $replacement .= "  )\n";
      $replacement .= ");\n\n$3";
      $routesText = preg_replace( $pattern, $replacement, $routesText );
    }
    $routes->write( $routesText );

    //controller
    if ( preg_match( '/\$this->set\(\s*\'relatedChildren\',\s*array\(\s*\'.+?\'\s*\)\s*\)/', $this->controllerText ) )
    {
      preg_match( '/\$this->set\(\s*\'relatedChildren\',\s*(array\(\s*\'.+?\'\s*\))\s*\)/', $this->controllerText, $matches );
      eval( '$relatedChildren = ' . $matches[ 1 ] . ';' );
      if ( !in_array( $relatedModelName, $relatedChildren ) )
      {
        $relatedChildren[ ] = $relatedModelName;
        $replace = "\t\t\$this->set( 'relatedChildren', " . str_replace( "\n", '', $this->format( var_export( $relatedChildren, TRUE ) ) ) . ")";
        $this->controllerText = str_replace( $matches[ 0 ], $replace, $this->controllerText );
      }
    }
    else
    {
      $content = "\t\t\$this->set( 'relatedChildren', array('{$relatedModelName}') );";
      $this->injectInMethod( 'beforeFilter', 'bottom', $content, $this->controllerText );
    }

    //reemplaza en admin_view el element
    $pattern = '/<\?php echo \$this->element\s*\(\s*\'admin\/panels\/record_panel\'(.+)/m';
    if ( preg_match( $pattern, $this->viewViewText, $matches ) )
    {
      $replacement = "<?php echo \$this->element( 'admin/panels/related_record_panel'{$matches[1]}";
      $this->viewViewText = preg_replace( $pattern, $replacement, $this->viewViewText );
    }

    $this->saveContext();

    $this->setContext( $relatedModelName );

    //reemplaza en admin_index
    $pattern = '/<\?php echo \$this->element\s*\(\s*\'admin\/panels\/model_panel\'.+?\?>/m';
    if ( preg_match( $pattern, $this->viewIndexText ) )
    {
      $replacement = "<?php echo \$this->request->params[ 'action' ] != 'admin_filter' ? \$this->element( 'admin/panels/model_panel' ) : \$this->element( 'admin/panels/related_model_panel' ) ?>";
      $this->viewIndexText = preg_replace( $pattern, $replacement, $this->viewIndexText );
    }

    $pattern = '/<\?php\s+echo\s+\$this->element\s*\(\s*\'admin\/panels\/search_panel\',\s*array\(\s*\'data\'\s*=>\s*\$(.+?)\s*\)\s*\);?\s*\?>/m';
    if ( preg_match( $pattern, $this->viewIndexText ) )
    {
      $replacement = "<?php if ( \$this->request->params[ 'action' ] != 'admin_filter' ) echo \$this->element( 'admin/panels/search_panel', array( 'data' => \\$$1 ) ) ?>";
      $this->viewIndexText = preg_replace( $pattern, $replacement, $this->viewIndexText );
    }

    //reemplaza en admin_add
    $pattern = '/<\?php echo \$this->element\s*\(\s*\'admin\/panels\/model_panel\'.+?\?>/m';
    if ( preg_match( $pattern, $this->viewAddText ) )
    {
      $replacement = "<?php echo \$this->request->params[ 'action' ] != 'admin_add_related' ? \$this->element( 'admin/panels/model_panel' ) : \$this->element( 'admin/panels/related_model_panel' ) ?>";
      $this->viewAddText = preg_replace( $pattern, $replacement, $this->viewAddText );
    }

    $this->saveContext();
  }
}

