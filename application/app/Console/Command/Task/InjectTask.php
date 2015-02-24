<?php
/**
 * Base class for Inject Tasks.
 */

App::uses( 'AppShell', 'Console/Command' );

class InjectTask extends AppShell
{
  /**
   * Name of plugin
   *
   * @var string
   */
  public $plugin = NULL;

  /**
   * The db connection being used for baking
   *
   * @var string
   */
  public $connection = NULL;

  /**
   * Flag for interactive mode
   *
   * @var boolean
   */
  public $interactive = FALSE;

  /**
   * Holds tables found on connection.
   *
   * @var array
   */
  protected $_tables = array();
  /**
   * Holds the model names
   *
   * @var array
   */
  protected $_modelNames = array();


  /**
   * Disable caching and enable debug for baking.
   * This forces the most current database schema to be used.
   *
   * @return void
   */
  public function startup()
  {
    Configure::write( 'debug', 2 );
    Configure::write( 'Cache.disable', 1 );
    parent::startup();
  }


  /**
   * tasks
   *
   * @public array
   * @access public
   */
  public $tasks = array( 'DbConfig' );

  /** @property Model $model */
  public $model;

  /** @property File $modelFile */
  public $modelFile;

  /** @property Controller $controller */
  public $controller;

  /** @property File $controllerFile */
  public $controllerFile;

  /** @property File $viewIndexFile */
  public $viewIndexFile;

  /** @property File $viewViewFile */
  public $viewViewFile;

  /** @property File $viewAddFile */
  public $viewAddFile;

  /** @property File $viewEditFile */
  public $viewEditFile;

  /** @property File $viewDeleteFile */
  public $viewDeleteFile;

  public $modelText;

  public $controllerText;

  public $viewIndexText;

  public $viewViewText;

  public $viewAddText;

  public $viewEditText;

  public $viewDeleteText;


  /**
   * outputs the a list of possible models or controllers from database
   *
   * @param string $useDbConfig Database configuration name
   *
   * @return array
   */
  public function listAll( $useDbConfig = NULL )
  {
    $this->_tables = (array)$this->getAllTables( $useDbConfig );

    $this->_modelNames = array();
    $count = count( $this->_tables );
    for ( $i = 0; $i < $count; $i++ )
    {
      $this->_modelNames[ ] = $this->_modelName( $this->_tables[ $i ] );
    }
    if ( $this->interactive === TRUE )
    {
      $this->out( __d( 'cake_console', 'Possible Models based on your current database:' ) );
      $len = strlen( $count + 1 );
      for ( $i = 0; $i < $count; $i++ )
      {
        $this->out( sprintf( "%${len}d. %s", $i + 1, $this->_modelNames[ $i ] ) );
      }
    }

    return $this->_tables;
  }


  /**
   * Interact with the user to determine the table name of a particular model
   *
   * @param string $modelName   Name of the model you want a table for.
   * @param string $useDbConfig Name of the database config you want to get tables from.
   *
   * @return string Table name
   */
  public function getTable( $modelName, $useDbConfig = NULL )
  {
    $useTable = Inflector::tableize( $modelName );
    if ( in_array( $modelName, $this->_modelNames ) )
    {
      $modelNames = array_flip( $this->_modelNames );
      $useTable = $this->_tables[ $modelNames[ $modelName ] ];
    }

    if ( $this->interactive === TRUE )
    {
      if ( !isset( $useDbConfig ) )
      {
        $useDbConfig = $this->connection;
      }
      $db = ConnectionManager::getDataSource( $useDbConfig );
      $fullTableName = $db->fullTableName( $useTable, FALSE );
      $tableIsGood = FALSE;
      if ( array_search( $useTable, $this->_tables ) === FALSE )
      {
        $this->out();
        $this->out( __d( 'cake_console', "Given your model named '%s',\nCake would expect a database table named '%s'", $modelName, $fullTableName ) );
        $tableIsGood = $this->in( __d( 'cake_console', 'Do you want to use this table?' ), array( 'y', 'n' ), 'y' );
      }
      if ( strtolower( $tableIsGood ) === 'n' )
      {
        $useTable = $this->in( __d( 'cake_console', 'What is the name of the table?' ) );
      }
    }

    return $useTable;
  }


  /**
   * Get an Array of all the tables in the supplied connection
   * will halt the script if no tables are found.
   *
   * @param string $useDbConfig Connection name to scan.
   *
   * @return array Array of tables in the database.
   */
  public function getAllTables( $useDbConfig = NULL )
  {
    if ( !isset( $useDbConfig ) )
    {
      $useDbConfig = $this->connection;
    }

    $tables = array();
    $db = ConnectionManager::getDataSource( $useDbConfig );
    $db->cacheSources = FALSE;
    $usePrefix = empty( $db->config[ 'prefix' ] ) ? '' : $db->config[ 'prefix' ];
    if ( $usePrefix )
    {
      foreach ( $db->listSources() as $table )
      {
        if ( !strncmp( $table, $usePrefix, strlen( $usePrefix ) ) )
        {
          $tables[ ] = substr( $table, strlen( $usePrefix ) );
        }
      }
    }
    else
    {
      $tables = $db->listSources();
    }
    if ( empty( $tables ) )
    {
      $this->err( __d( 'cake_console', 'Your database does not have any tables.' ) );
      $this->_stop();
    }

    return $tables;
  }


  /**
   * Forces the user to specify the model he wants to bake, and returns the selected model name.
   *
   * @param string $useDbConfig Database config name
   *
   * @return string the model name
   */
  public function getName( $useDbConfig = NULL )
  {
    $this->listAll( $useDbConfig );

    $enteredModel = '';

    while ( !$enteredModel )
    {
      $enteredModel = $this->in( __d( 'cake_console', "Enter a number from the list above,\n" .
          "type in the name of another model, or 'q' to exit" ), NULL, 'q' );

      if ( $enteredModel === 'q' )
      {
        $this->out( __d( 'cake_console', 'Exit' ) );
        $this->_stop();
      }

      if ( !$enteredModel || intval( $enteredModel ) > count( $this->_modelNames ) )
      {
        $this->err( __d( 'cake_console', "The model name you supplied was empty,\n" .
            "or the number you selected was not an option. Please try again." ) );
        $enteredModel = '';
      }
    }
    if ( intval( $enteredModel ) > 0 && intval( $enteredModel ) <= count( $this->_modelNames ) )
    {
      return $this->_modelNames[ intval( $enteredModel ) - 1 ];
    }

    return $enteredModel;
  }


  function getFieldName()
  {
    $this->out( __( 'Possible fields based on your current model:', TRUE ) );

    $fields = array_keys( $this->model->schema() );
    $count = count( $fields );
    for ( $i = 0; $i < $count; $i++ )
    {
      $this->out( $i + 1 . ". " . $fields[ $i ] );
    }

    $enteredField = '';
    while ( $enteredField == '' )
    {
      $enteredField = $this->in( __( "Enter a number from the list above or 'q' to exit", TRUE ), NULL, 'q' );

      if ( $enteredField === 'q' )
      {
        $this->out( __( "Exit", TRUE ) );
        $this->_stop();
      }

      if ( $enteredField == '' || intval( $enteredField ) > count( $fields ) )
      {
        $this->err( __( "The model name you supplied was empty,\nor the number you selected was not an option. Please try again.", TRUE ) );
        $enteredField = '';
      }
    }
    if ( intval( $enteredField ) > 0 && intval( $enteredField ) <= count( $fields ) )
    {
      $currentField = $fields[ intval( $enteredField ) - 1 ];
    }
    else
    {
      $currentField = $enteredField;
    }

    return $currentField;
  }


  //CONTEXTS


  function setContext( $modelName )
  {
    $this->model = new Model( array( 'name' => $modelName ) );
    $modelPath = array_shift( App::path( 'Model' ) ) . $modelName . '.php';

    $controllerName = Inflector::pluralize( $modelName ) . 'Controller';
    App::uses( $controllerName, 'Controller' );
    $this->controller = new $controllerName();

    $controllerPath = array_shift( App::path( 'Controller' ) ) . Inflector::pluralize( $modelName ) . 'Controller.php';
    $viewIndexPath = array_shift( App::path( 'View' ) ) . Inflector::pluralize( $modelName ) . DS . 'admin_index.ctp';
    $viewViewPath = array_shift( App::path( 'View' ) ) . Inflector::pluralize( $modelName ) . DS . 'admin_view.ctp';
    $viewAddPath = array_shift( App::path( 'View' ) ) . Inflector::pluralize( $modelName ) . DS . 'admin_add.ctp';
    $viewEditPath = array_shift( App::path( 'View' ) ) . Inflector::pluralize( $modelName ) . DS . 'admin_edit.ctp';
    $viewDeletePath = array_shift( App::path( 'View' ) ) . Inflector::pluralize( $modelName ) . DS . 'admin_delete.ctp';

    $this->modelFile = file_exists( $modelPath ) ? new File( $modelPath ) : NULL;
    $this->controllerFile = file_exists( $controllerPath ) ? new File( $controllerPath ) : NULL;
    $this->viewIndexFile = file_exists( $viewIndexPath ) ? new File( $viewIndexPath ) : NULL;
    $this->viewViewFile = file_exists( $viewViewPath ) ? new File( $viewViewPath ) : NULL;
    $this->viewAddFile = file_exists( $viewAddPath ) ? new File( $viewAddPath ) : NULL;
    $this->viewEditFile = file_exists( $viewEditPath ) ? new File( $viewEditPath ) : NULL;
    $this->viewDeleteFile = file_exists( $viewDeletePath ) ? new File( $viewDeletePath ) : NULL;

    if ( $this->modelFile ) $this->modelText = $this->modelFile->read();
    if ( $this->controllerFile ) $this->controllerText = $this->controllerFile->read();
    if ( $this->viewIndexFile ) $this->viewIndexText = $this->viewIndexFile->read();
    if ( $this->viewViewFile ) $this->viewViewText = $this->viewViewFile->read();
    if ( $this->viewAddFile ) $this->viewAddText = $this->viewAddFile->read();
    if ( $this->viewEditFile ) $this->viewEditText = $this->viewEditFile->read();
    if ( $this->viewDeleteFile ) $this->viewDeleteText = $this->viewDeleteFile->read();
  }


  /**
   *
   * @param File $file
   */
  function saveContext( &$file = NULL )
  {
    if ( !$file )
    {
      if ( $this->modelFile ) $this->modelFile->write( $this->modelText );
      if ( $this->controllerFile ) $this->controllerFile->write( $this->controllerText );
      if ( $this->viewIndexFile ) $this->viewIndexFile->write( $this->viewIndexText );
      if ( $this->viewViewFile ) $this->viewViewFile->write( $this->viewViewText );
      if ( $this->viewAddFile ) $this->viewAddFile->write( $this->viewAddText );
      if ( $this->viewEditFile ) $this->viewEditFile->write( $this->viewEditText );
      if ( $this->viewDeleteFile ) $this->viewDeleteFile->write( $this->viewDeleteText );
    }
    else
    {
      switch ( $file )
      {
        case $this->modelFile:
          $file->write( $this->modelText );
          break;

        case $this->controllerFile:
          $file->write( $this->controllerText );
          break;

        case $this->viewIndexFile:
          $file->write( $this->viewIndexText );
          break;

        case $this->viewViewFile:
          $file->write( $this->viewViewText );
          break;

        case $this->viewAddFile:
          $file->write( $this->viewAddText );
          break;

        case $this->viewEditFile:
          $file->write( $this->viewEditText );
          break;
        case $this->viewDeleteFile:
          $file->write( $this->viewDeleteText );
          break;
      }
    }
  }


  //CHECKS

  function hasProperty( $name, $context )
  {
    $pattern = '/ \$var \s* ' . $name . ' /mxs';

    return preg_match( $pattern, $context );
  }


  function hasMethod( $name, $context )
  {
    $pattern = '/ function \s* ' . $name . ' \s* \( /mxs';

    return preg_match( $pattern, $context );
  }


  //quito property, behavior, model, helper, component

  function addProperty( $name, $value = NULL, $addAfterPropertyName = NULL, $class, &$context = NULL )
  {
    $exists = FALSE;
    if ( property_exists( $class, $name ) )
    {
      $exists = TRUE;
      if ( is_array( $class->{$name} ) )
      {
        $prop = $class->{$name};
        if ( $value )
        {
          $prop = array_merge( $prop, $value );
        }
        else
        {
          array_push( $prop, $name );
        }
        $inject = "public \${$name} = " . var_export( $prop, TRUE ) . ";";
      }
      else
      {
        $inject = "public \${$name} = " . var_export( $value, TRUE ) . ";";
      }
    }
    else
    {
      $inject = "public \${$name} = " . var_export( $value, TRUE ) . ";";
    }

    $inject = $this->format( $inject );

    if ( $exists )
    {
      $pattern = '/ \s* public \ \$' . $name . ' .+?;/mxs';
      $replacement = "\n\t$inject";
    }
    else
    {
      $pattern = $addAfterPropertyName ? '/(var \s+ \$' . $addAfterPropertyName . ' \s* = .+?;)/mxs' : '/(\/\* inject \*\/)/m';
      $replacement = "$1\n";
      $replacement .= "\t\n";
      $replacement .= "\t$inject";
    }


    if ( preg_match( $pattern, $context ) ) $context = preg_replace( $pattern, $replacement, $context );
  }


  function addMethod( $name, $args, $content, &$context )
  {
    if ( is_array( $args ) )
    {
      $argItems = array();
      $keys = array_keys( $args );
      $values = array_values( $args );
      for ( $i = 0; $i < count( $values ); $i++ )
      {
        if ( !is_numeric( $keys[ $i ] ) )
        {
          $argItems[ ] = '$' . $keys[ $i ] . ' = ' . $values[ $i ];
        }
        else
        {
          $argItems[ ] = '$' . $values[ $i ];
        }
      }
      $args = implode( ', ', $argItems );
    }
    $method = "\n";
    $method = "  \n";
    $method .= "  public function {$name} ({$args})\n";
    $method .= "  {\n";
    $method .= $content;
    $method .= "  }\n";

    $pattern = '/( } \s* ) $/Dxs'; //uso el modificador D para que el signo $ matchee solo el EOF
    $replacement = "{$method}$1";

    if ( preg_match( $pattern, $context ) )
    {
      $context = preg_replace( $pattern, $replacement, $context );
    }
  }


  function injectInMethod( $methodName, $insertIn = 'top', $content, &$context )
  {
    $lines = explode( "\n", $context );
    $methodStartLine = -1;
    $methodInsertLine = -1;
    $methodEndLine = -1;

    foreach ( $lines as $index => $line )
    {
      if ( strpos( $line, "function {$methodName}" ) > -1 )
      {
        $methodStartLine = $index;
        break;
      }
    }
    if ( $methodStartLine == -1 ) return FALSE;

    $bracesBalance = 0;
    $bracesInit = FALSE;
    for ( $i = $methodStartLine; $i < count( $lines ); $i++ )
    {
      $in = substr_count( $lines[ $i ], '{' );
      $out = substr_count( $lines[ $i ], '}' );
      if ( $in > 0 )
      {
        $bracesInit = TRUE;
        if ( $insertIn == 'top' ) $methodInsertLine = $i + 1;
      }
      $bracesBalance += $in;
      $bracesBalance -= $out;

      if ( $bracesBalance === 0 && $bracesInit )
      {
        if ( $insertIn == 'bottom' ) $methodInsertLine = $i;
        $methodEndLine = $i + 1;
        break;
      }
    }

    array_splice( $lines, $methodInsertLine, 0, $content );
    $context = implode( "\n", $lines );
  }


  protected function format( $inject )
  {
    $inject = preg_replace( '/^(\s*)\d+ => /m', '$1', $inject );
    $inject = preg_replace( '/\n\s*array \(/m', 'array (', $inject );

    return $inject;
  }
}

?>
