<?php
App::uses( 'AppShell', 'Console/Command' );
App::uses( 'InjectTask', 'Console/Command/Task' );
App::uses( 'AppModel', 'Model' );

/**
 * Task class for creating and updating controller files.
 *
 * @package       Cake.Console.Command.Task
 */
class InjectUploaderPluginTask extends InjectTask
{

  public function execute()
  {
    $this->hr();
    $this->out( "Inject Uploader Plugin Functionality" );
    $this->hr();
    $this->interactive = TRUE;

    if ( empty( $this->connection ) )
    {
      $this->connection = $this->DbConfig->getConfig();
    }

    $modelName = $this->getName(); //pide elegir la tabla
    $this->setContext( $modelName );

    $fields = array();

    while ( TRUE )
    {
      $field = $this->getFieldName();

      $defaultFolder = strtolower( Inflector::slug( Inflector::pluralize( Inflector::humanize( $field ) ) ) );
      $folder = $this->in( __d( 'inject', 'Enter the name for the folder to place this files. If it doesn\'t exists, I will be created' ), NULL, $defaultFolder );


      $isImage = $this->in( __d( 'inject', 'Proccess the file upload as an image?' ), NULL, 'n' );
      $isImage = strtolower( $isImage ) == 'y';

      $size = FALSE;
      $inList = FALSE;
      $usedFieldsForTransforms = array( $field );
      $transforms = array();
      if ( $isImage )
      {
        $size = array();
        $size[ 'width' ] = $this->in( __d( 'inject', 'Width for the image:' ) . $field, NULL, '640' );
        $size[ 'height' ] = $this->in( __d( 'inject', 'Height for the image:' ) . $field, NULL, '480' );

        $inList = $this->in( __d( 'inject', 'Inject the image in the list (index) view?' ), NULL, 'n' );
        $inList = strtolower( $inList ) == 'y';

        while ( TRUE )
        {
          $another = count( $transforms ) == 0 ? 'a' : 'another';
          $doTransform = $this->in( __d( 'inject', "Inject $another transformation for this image?" ), NULL, 'n' );
          if ( strtolower( $doTransform ) == 'y' )
          {
            $transformField = $this->getFieldName();
            while ( in_array( $transformField, $usedFieldsForTransforms ) )
            {
              $this->out( 'Field already selected' );
              $field = $this->getFieldName();
            }
            $transform = array();
            $transform[ 'field' ] = $transformField;
            $transform[ 'width' ] = $this->in( __d( 'inject', 'Width for the transformed image:' ) . $field, NULL, '160' );
            $transform[ 'height' ] = $this->in( __d( 'inject', 'Height for the transformed image:' ) . $field, NULL, '120' );
            $transform[ 'inList' ] = $this->in( __d( 'inject', 'Inject the transformation in the list (index) view?' ), NULL, 'n' );

            $transforms[ ] = $transform;
          }
          else
          {
            break;
          }
        }
      }
      $upload = array(
        'field'      => $field,
        'folder'     => $folder,
        'isImage'    => $isImage,
        'size'       => $size,
        'inList'     => $inList,
        'transforms' => $transforms
      );

      $fields[ ] = $upload;

      $another = $this->in( __d( 'inject', 'Select another field?' ), NULL, 'n' );
      if ( strtolower( $another ) === 'n' ) break;
    }

    if ( !$this->modelFile || !$this->controllerFile || !$this->viewIndexFile || !$this->viewAddFile || !$this->viewEditFile || !$this->viewViewFile )
    {
      $this->err( __d( 'inject', 'Some of the required files are missing. Please bake them first.' ) );
      $this->_stop();

      return FALSE;
    }

    $this->hr();
    $this->out( 'Inject Uploader Plugin Functionality to ' . $modelName . ' for the field: ' . $field );
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
    /**
     * Steps
     * 1. copy plugin and vendor if not present
     * 2. add autoload to core.php (require_once dirname( __DIR__ ) . '/Vendor/autoload.php';)
     * 3. add upload behavior, with selected configuration. Check for current behavior configured, don't overwrite
     * 4.
     */

    $modelName = $data[ 'modelName' ];
    $varName = $modelName;
    $varName[ 0 ] = strtolower( $varName[ 0 ] );
    $fields = $data[ 'fields' ];

    App::uses( 'Folder', 'Utility' );
    App::uses( 'File', 'Utility' );

    //checks for plugin existance
    if ( !is_dir( APP . 'Plugin' . DS . 'Uploader' . DS ) )
    {
      //copies plugin if not exists in the app
      $plugin = new Folder( ROOT . DS . 'plugins' . DS . 'Uploader' . DS );
      $plugin->copy( APP . 'Plugin' . DS . 'Uploader' . DS );

      //copies vendor and autoloader
      $composer = new Folder( ROOT . DS . 'vendors' . DS . 'composer' . DS );
      $composer->copy( APP . 'Vendor' . DS . 'composer' . DS );

      $mjohnson = new Folder( ROOT . DS . 'vendors' . DS . 'mjohnson' . DS );
      $mjohnson->copy( APP . 'Vendor' . DS . 'mjohnson' . DS );

      $autoloader = new File( ROOT . DS . 'vendors' . DS . 'autoload.php' );
      $autoloader->copy( APP . 'Vendor' . DS . 'autoload.php' );
    }

    //add autoload to core ? esperemos a ver si es necesario
    $core = new File( APP . 'Config' . DS . 'core.php' );
    $coreText = $core->read();
    $pattern = '/^require_once \s+ dirname\( \s+ __DIR__ \s+ \) \s+ \. \s+ \'\/Vendor\/autoload\.php\';/mxs';
    if ( !preg_match( $pattern, $coreText ) )
    {
      $coreText .= "\n";
      $coreText .= "\n";
      $coreText .= "require_once dirname( __DIR__ ) . '/Vendor/autoload.php';";
      $core->write( $coreText );
    }

    //guarda behaviors y validates
    App::uses( $modelName, 'Model' );
    $model = new $modelName();
    $behaviors = $model->actsAs;
    $validate = $model->validate;


    foreach ( $fields as $upload )
    {
      $field = $upload[ 'field' ];
      $folder = $upload[ 'folder' ];
      $isImage = $upload[ 'isImage' ];
      $size = $upload[ 'size' ];
      $transforms = $upload[ 'transforms' ];
      $inList = $upload[ 'inList' ];

      //config attachment
      $attachment = array(
        'tempDir'   => TMP,
        'uploadDir' => "files/{$folder}/",
        'finalPath' => "/files/{$folder}/",
        'overwrite' => FALSE,
        'stopSave'  => TRUE,
      );

      $validation = array(
        'required' => array(
          'value' => TRUE,
          'error' => 'Suba un archivo',
          'on'    => 'create',
        ),
        'filesize' => array(
          'value' => 4194304,
          'error' => 'El archivo es demasiado grande. El peso máximo es %s.'
        ),
      );

      if ( $isImage )
      {
        $attachment[ 'transforms' ] = array(
          $field => array(
            'nameCallback' => 'formatNameFromUpload',
            'quality'      => 75,
            'method'       => 'crop', // or crop
            'overwrite'    => TRUE,
            'self'         => TRUE,
            'width'        => $size[ 'width' ],
            'height'       => $size[ 'height' ],
          )
        );
        foreach ( $transforms as $transform )
        {
          $attachment[ 'transforms' ][ $transform[ 'field' ] ] = array(
            'nameCallback' => 'formatNameFromUpload',
            'quality'      => 75,
            'method'       => 'crop', // or crop
            'overwrite'    => TRUE,
            'self'         => FALSE,
            'width'        => $transform[ 'width' ],
            'height'       => $transform[ 'height' ],
          );
        }

        $validation[ 'extension' ] = array(
          'value' => array( 'gif', 'jpg', 'png', 'jpeg' ),
          'error' => 'Extensión de archivo inválida'
        );
        $validation[ 'mimeType' ] = array(
          'value' => array( 'image/gif', 'image/jpeg', 'image/png' ),
          'error' => 'Tipo de archivo inválido'
        );
      }

      //agrega o modifica behaviors
      $behaviors[ 'Uploader.Attachment' ][ $field ] = $attachment;
      $behaviors[ 'Uploader.FileValidation' ][ $field ] = $validation;

      //removes model valitation
      if ( isset( $validation[ $field ] ) ) unset( $validation[ $field ] );
      foreach ( $transforms as $transform ) if ( isset( $validation[ $transform[ 'field' ] ] ) ) unset( $validation[ $transform[ 'field' ] ] );


      //cambia add y edit form
      $actions = array( 'Add', 'Edit' );
      foreach ( $actions as $action )
      {
        $action = 'view' . $action . 'Text';

        //form init
        $matches = array();
        $pattern = "/ Form-\>create \s* \( \s* '{$modelName}' .+? \?\> /mxs";
        preg_match( $pattern, $this->{$action}, $matches );

        $pattern = "/ 'type' \s* =\> \s* 'file' /mxs";
        if ( !@preg_match( $pattern, $matches[ 0 ] ) )
        {
          $pattern = "/ ( Form-\>create \s* \( \s* '{$modelName}' \s* , \s* array \s* \( ) /mxs";
          $replacement = "$1'type' => 'file', ";
          $this->{$action} = preg_replace( $pattern, $replacement, $this->{$action} );
        }

        //item input
        $matches = array();
        $pattern = "/ Form-\>input \s* \( \s* '{$modelName}.{$field}' .+? ; /mxs";
        preg_match( $pattern, $this->{$action}, $matches );

        $pattern = "/ 'type' \s* =\> \s* 'file' /mxs";
        if ( !@preg_match( $pattern, $matches[ 0 ] ) )
        {
          //adds type => file y title
          $pattern = "/ ( Form-\>input \s* \( \s* '{$modelName}.{$field}' \s* , \s* array \s* \( ) /mxs";
          $replacement = "$1\n\t\t\t\t'type' => 'file', ";
          $replacement .= "\n\t\t\t\t'title' => '" . ( $isImage ? 'Seleccione una imagen' : 'Seleccione un archivo' ) . "', ";
          $this->{$action} = preg_replace( $pattern, $replacement, $this->{$action} );

          //removes span
          $pattern = "/ ( Form-\>input \s* \( \s* '{$modelName}.{$field}' .+? ) (span\\d) (.+?;) /mxs";
          $replacement = "$1$3";
          $this->{$action} = preg_replace( $pattern, $replacement, $this->{$action} );

          //en edit, agrega iconos para ver y borrar la imagen
          if ( $action == 'viewEditText' )
          {
            $pattern = "/ ( Form-\>input \s* \( \s* '{$modelName}.{$field}' .+? ) \s* \/?\/?'helpBlock' .+? \\n (.+?;) /mxs";
            $replacement = "$1\n\t\t\t\t'helpBlock'  => \\\$this->BootstrapForm->value('{$modelName}.{$field}') ? \\\$this->BootstrapHtml->link( 'Borrar archivo', array( 'action' => 'delete_file', \\\$this->Form->value( '{$modelName}.id' ), '{$field}' ), array( 'class' => 'btn btn-small btn-danger btn-edit-delete' ) ) : ''\n$2";
            $this->{$action} = preg_replace( $pattern, $replacement, $this->{$action} );
          }
        }

        //quita fields de transforms extra
        foreach ( $transforms as $transform )
        {
          $pattern = "/\s* echo \s+ \\\$this->BootstrapForm->input\( \s* '{$modelName}.{$transform['field']}/mxs";
          if ( preg_match( $pattern, $this->{$action} ) )
          {
            $pattern = "/(.+\n)\s* echo \s+ \\\$this->BootstrapForm->input\( \s* '{$modelName}.{$transform['field']}.+?;(.+)/mxs";
            $replacement = "$1$2";
            $this->{$action} = preg_replace( $pattern, $replacement, $this->{$action} );
          }
        }
      }

      //cambia view
      $pattern = "/ \<\?php \s+ echo \s+ h\( \s* \\\${$varName} \s* \[ \s* '{$modelName}' \s* \]\[ \s* '{$field}' \s* \] .+? \?\> /mxs";
      if ( $isImage )
      {
        $replacement = "\n\t\t\t\t<?php if ( \${$varName}[ '{$modelName}' ][ '{$field}' ] ): ?>\n";
        $replacement .= "\n\t\t\t\t\t<a data-toggle=\"lightbox\" href=\"#{$modelName}{$field}\">Ver imagen</a>\n";
        $replacement .= "\n\t\t\t\t\t<div id=\"{$modelName}{$field}\" class=\"lightbox hide fade\" tabindex=\"-1\" role=\"dialog\" aria-hidden=\"true\">\n";
        $replacement .= "\n\t\t\t\t\t\t<div class=\"lightbox-content\">\n";
        $replacement .= "\n\t\t\t\t\t\t\t<img src=\"<?php echo Router::url( \${$varName}[ '{$modelName}' ][ '{$field}' ] ); ?>\">\n";
        $replacement .= "\n\t\t\t\t\t\t</div>\n";
        $replacement .= "\n\t\t\t\t\t</div>\n";
        $replacement .= "\t\t\t\t<?php endif; ?>\n";
      }
      else
      {
        $replacement = "\n\t\t\t\t<a href=\"<?php echo Router::url( \${$varName}[ '{$modelName}' ][ '{$field}' ] ); ?>\">Descargar archivo</a>\n";
      }
      $this->viewViewText = preg_replace( $pattern, $replacement, $this->viewViewText );

      if ( $inList )
      {
        $pattern = "/ \<\?php \s+ echo \s+ h\( \s* \\\${$varName} \s* \[ \s* '{$modelName}' \s* \]\[ \s* '{$field}' \s* \] .+? \?\> /mxs";

        $replacement = "<?php if ( \${$varName}[ '{$modelName}' ][ '{$field}' ] ): ?>\n";
        $replacement .= "<a href=\"<?php echo Router::url( array( 'action' => 'view', \${$varName}[ '{$modelName}' ][ 'id' ] ) ); ?>\">\n";
        $replacement .= "<img class=\"thumbnail\" src=\"<?php echo Router::url( \${$varName}[ '{$modelName}' ][ '{$field}' ] ); ?>\" alt=\"\"/>\n";
        $replacement .= "</a>\n";
        $replacement .= "<?php endif; ?>\n";
        $this->viewIndexText = preg_replace( $pattern, $replacement, $this->viewIndexText );
      }
      foreach ( $transforms as $transform )
      {
        if ( strtolower( $transform[ 'inList' ] ) == 'y' )
        {
          $pattern = "/ \<\?php \s+ echo \s+ h\( \s* \\\${$varName} \s* \[ \s* '{$modelName}' \s* \]\[ \s* '{$transform['field']}' \s* \] .+? \?\> /mxs";
          $replacement = "<?php if ( \${$varName}[ '{$modelName}' ][ '{$transform['field']}' ] ): ?>\n";
          $replacement .= "<a href=\"<?php echo Router::url( array( 'action' => 'view', \${$varName}[ '{$modelName}' ][ 'id' ] ) ); ?>\">\n";
          $replacement .= "<img class=\"thumbnail\" src=\"<?php echo Router::url( \${$varName}[ '{$modelName}' ][ '{$transform['field']}' ] ); ?>\" alt=\"\"/>\n";
          $replacement .= "</a>\n";
          $replacement .= "<?php endif; ?>\n";
          $this->viewIndexText = preg_replace( $pattern, $replacement, $this->viewIndexText );
        }
      }
    }

    //aplica transformaciones en actAs y validates
    $this->addProperty( 'actsAs', $behaviors, NULL, $this->model, $this->modelText );
    $this->addProperty( 'validate', $validate, NULL, $this->model, $this->modelText );

    //lamentablemente no se como arrastrar TMP como constante, asi q la arrastramos como string y reemplazamos a manopla
    $this->modelText = preg_replace( "/'tempDir'\s*=\>\s*'.+?\/app\/tmp\/'/m", "'tempDir' => TMP", $this->modelText );

    $this->saveContext();
  }
}
