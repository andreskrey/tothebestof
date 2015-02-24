<?php
App::uses( 'AppShell', 'Console/Command' );
App::uses( 'InjectTask', 'Console/Command/Task' );
App::uses( 'AppModel', 'Model' );

/**
 * Task class for creating and updating controller files.
 *
 * @package       Cake.Console.Command.Task
 */
class InjectEmailsTask extends InjectTask
{

  public function execute()
  {
    $this->hr();
    $this->out( "Inject Emails Functionality" );
    $this->hr();
    $this->interactive = TRUE;

    $primaryKey = 'id';
    $validate = $associations = array();

    $this->hr();
    $this->out( 'Inject Emails Functionality to project?' );
    $looksGood = $this->in( __( 'Look okay?', TRUE ), array( 'y', 'n' ), 'y' );

    if ( strtolower( $looksGood ) == 'y' )
    {
      $this->inject();
    }
  }


  /**
   * Creates the actual injection in all the needed files
   *
   * @access private
   */
  private function inject( $data = NULL )
  {
    App::uses( 'Folder', 'Utility' );
    App::uses( 'File', 'Utility' );

    //controller
    $controller = new File( ROOT . DS . 'vendors' . DS . 'injects' . DS . 'Controller' . DS . 'EmailsController.php' );
    $controller->copy( APP . DS . 'Controller' . DS . 'EmailsController.php' );

    //model
    $model = new File( ROOT . DS . 'vendors' . DS . 'injects' . DS . 'Model' . DS . 'Email.php' );
    $model->copy( APP . DS . 'Model' . DS . 'Email.php' );

    //mail lib
    $lib = new Folder( ROOT . DS . 'vendors' . DS . 'injects' . DS . 'Lib' . DS . 'Network' . DS . 'Email' . DS );
    $lib->copy( APP . DS . 'Lib' . DS . 'Network' . DS . 'Email' . DS );

    //views & layouts
    $views = new Folder( ROOT . DS . 'vendors' . DS . 'injects' . DS . 'View' . DS . 'Emails' . DS );
    $views->copy( APP . DS . 'View' . DS . 'Emails' . DS );

    $layouts = new Folder( ROOT . DS . 'vendors' . DS . 'injects' . DS . 'View' . DS . 'Layouts' . DS . 'Emails' . DS );
    $layouts->copy( APP . DS . 'View' . DS . 'Layouts' . DS . 'Emails' . DS );

    //config & core
    $config = new File( ROOT . DS . 'vendors' . DS . 'injects' . DS . 'Config' . DS . 'data' . DS . 'emails.php' );
    $config->copy( APP . DS . 'Config' . DS . 'data' . DS . 'emails.php' );

    $core = new File( APP . 'Config' . DS . 'bootstrap.php' );
    $coreText = $core->read();
    $pattern = '/Configure::load\s*\(\s*\'data\/emails\'\s*,\s*\'default\'\s*\)/ms';
    if ( !preg_match( $pattern, $coreText ) )
    {
      $coreText .= "\n";
      $coreText .= "\n";
      $coreText .= "//reads emails data to config\n";
      $coreText .= "Configure::load( 'data/emails', 'default' );";
      $core->write( $coreText );
    }

    //modifica admin layout
    $layout = new File( APP . 'View' . DS . 'Layouts' . DS . 'admin.ctp' );
    $layoutText = $layout->read();
    $pattern = "/<!-- settings injects >> -->.+?'controller' ?=> ?'emails' .+?<!-- << settings injects -->/ms";
    if ( !preg_match( $pattern, $layoutText ) )
    {
      $pattern = "/(<!-- settings injects >> -->)/ms";
      $replace = "$1\n" . str_repeat( "\t", 10 ) . "<?php echo \$this->Admin->navListLink( 'Emails', array( 'controller' => 'emails', 'action' => 'index' ) ); ?>";
      $layoutText = preg_replace( $pattern, $replace, $layoutText );
      $layout->write( $layoutText );
    }

    $this->saveContext();
  }
}
