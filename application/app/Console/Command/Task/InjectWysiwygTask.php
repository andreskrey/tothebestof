<?php
App::uses( 'AppShell', 'Console/Command' );
App::uses( 'InjectTask', 'Console/Command/Task' );
App::uses( 'AppModel', 'Model' );

/**
 * Task class for creating and updating controller files.
 *
 * @package       Cake.Console.Command.Task
 */
class InjectWysiwygTask extends InjectTask
{

  public function execute()
  {
    $this->hr();
    $this->out( "Inject WYSIWYG Functionality" );
    $this->hr();
    $this->interactive = TRUE;

    $primaryKey = 'id';
    $validate = $associations = array();

    if ( empty( $this->connection ) )
    {
      $this->connection = $this->DbConfig->getConfig();
    }

    $modelName = $this->getName(); //pide elegir la tabla
    $this->setContext( $modelName );

    $fields = array();
    $fields[ ] = $this->getFieldName();
    while ( TRUE )
    {
      $another = $this->in( __d( 'inject', 'Select another field?' ), NULL, 'n' );
      if ( strtolower( $another ) === 'n' ) break;

      $field = $this->getFieldName();
      while ( in_array( $field, $fields ) )
      {
        $this->out( 'Field already selected' );
        $field = $this->getFieldName();
      }
      $fields[] = $field;
    }

    $this->hr();
    $this->out( 'Inject WYSIWYG Functionality to ' . $modelName . ' for the fields: ' . join( ', ', $fields ) );
    $looksGood = $this->in( __( 'Look okay?', TRUE ), array( 'y', 'n' ), 'y' );

    if ( strtolower( $looksGood ) == 'y' )
    {
      $data = compact( 'modelName', 'fields' );
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
    $modelName = $data[ 'modelName' ];
    $fields = $data[ 'fields' ];
    $varName = $modelName;
    $varName[ 0 ] = strtolower( $varName[ 0 ] );


    if ( !is_dir( WWW_ROOT . 'js' . DS . 'wysihtml5' . DS ) )
    {
      $plugin = new Folder( ROOT . DS . 'vendors' . DS . 'injects' . DS . 'webroot' . DS . 'js' . DS . 'wysihtml5' . DS );
      $plugin->copy( WWW_ROOT . 'js' . DS . 'wysihtml5' . DS );
    }

    if ( !is_dir( WWW_ROOT . 'css' . DS . 'wysihtml5' . DS ) )
    {
      $plugin = new Folder( ROOT . DS . 'vendors' . DS . 'injects' . DS . 'webroot' . DS . 'css' . DS . 'wysihtml5' . DS );
      $plugin->copy( WWW_ROOT . 'css' . DS . 'wysihtml5' . DS );
    }

    //agrega a asset_compress los archivos para concatenar y comprimir
    $asset_compress = new File( APP . 'Config' . DS . 'asset_compress.ini' );
    $asset_compress_text = $asset_compress->read();

    $pattern = '/\[wysihtml5\.js\]/ms';
    if ( !preg_match( $pattern, $asset_compress_text ) )
    {
      $pattern = '/(.+; js injects)(.+?)/ms';
      $replacement = "$1\n";
      $replacement .= "\n";
      $replacement .= "[wysihtml5.js]\n";
      $replacement .= "files[] = wysihtml5/wysihtml5-0.3.js\n";
      $replacement .= "files[] = wysihtml5/bootstrap-wysihtml5-0.0.2.js\n";
      $replacement .= "files[] = wysihtml5/bootstrap-wysihtml5.es-AR.js\n";
      $replacement .= "files[] = wysihtml5/wysihtml5-setup.js\n$2";
      $asset_compress_text = preg_replace( $pattern, $replacement, $asset_compress_text );
    }

    $pattern = '/\[wysihtml5\.css\]/ms';
    if ( !preg_match( $pattern, $asset_compress_text ) )
    {
      $pattern = '/(.+; css injects)(.+?)/ms';
      $replacement = "$1\n";
      $replacement .= "\n";
      $replacement .= "[wysihtml5.css]\n";
      $replacement .= "files[] = wysihtml5/bootstrap-wysihtml5-0.0.2.css\n";
      $replacement .= "files[] = wysihtml5/wysiwyg-color.css\n$2";
      $asset_compress_text = preg_replace( $pattern, $replacement, $asset_compress_text );
    }
    $asset_compress->write( $asset_compress_text );

    foreach ( $fields as $field )
    {
      //agrega css y js a admin_add y admin_edit
      $actions = array( 'Add', 'Edit' );
      foreach ( $actions as $action )
      {
        $action = 'view' . $action . 'Text';

        //includes
        $pattern = "/echo \s+ \\\$this->AssetCompress->css\( \s* 'wysihtml5.css'/mxs";
        if ( !preg_match( $pattern, $this->{$action} ) )
        {
          $pattern = "/ ( <\?php \s* \/\* \s* @var \s+ \\\$this \s+ View \s* \*\/ \s* \?> ) /mxs";
          $replacement = "$1\n";
          $replacement .= "<?php echo \$this->AssetCompress->css( 'wysihtml5.css', array( 'block' => 'css', 'raw' => Configure::read( 'AssetCompress.raw' ) ) ); ?>\n";
          $replacement .= "<?php echo \$this->AssetCompress->script( 'wysihtml5.js', array( 'block' => 'scriptBottom', 'raw' => Configure::read( 'AssetCompress.raw' ) ) ); ?>\n";
          $this->{$action} = preg_replace( $pattern, $replacement, $this->{$action} );
        }

        //class
        $pattern = "/Form-\>input \s* \( \s* '{$modelName}.{$field}'.+?richtext.+?;/mxs";
        if ( !preg_match( $pattern, $this->{$action} ) )
        {
          $pattern = "/ ( Form-\>input \s* \( \s* '{$modelName}.{$field}' .+? ) \s* \/?\/?'class' \s* => \s* '(.+?)'.*? \\n (.+?;) /mxs";
          $replacement = "$1\n\t\t\t\t'class'  => '$2 richtext',\n\t\t\t\t'style' => 'height: 200px',\n\t\t\t\t'required' => false,\n$3";
          $this->{$action} = preg_replace( $pattern, $replacement, $this->{$action} );
        }
      }

      //cambia view
      $pattern = "/ h\( \s* (\\\${$varName} \s* \[ \s* '{$modelName}' \s* \]\[ \s* '{$field}' \s* \]) \s* \) /mxs";
      $replacement = "$1";
      $this->viewViewText = preg_replace( $pattern, $replacement, $this->viewViewText );
    }

    $this->saveContext();
  }
}