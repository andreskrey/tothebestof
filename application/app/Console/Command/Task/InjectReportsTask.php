<?php
App::uses( 'AppShell', 'Console/Command' );
App::uses( 'InjectTask', 'Console/Command/Task' );
App::uses( 'AppModel', 'Model' );

/**
 * Task class for creating and updating controller files.
 *
 * @package       Cake.Console.Command.Task
 */
class InjectReportsTask extends InjectTask
{

  public function execute()
  {
    $this->hr();
    $this->out( "Inject Reports Functionality" );
    $this->hr();
    $this->interactive = TRUE;

    $primaryKey = 'id';
    $validate = $associations = array();

    $this->hr();
    $this->out( 'Inject Reports Functionality to project?' );
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
    App::uses('Folder', 'Utility');
    App::uses('File', 'Utility');

    $views = new Folder( ROOT . DS . 'vendors' . DS . 'injects' . DS . 'View' . DS . 'Reports' . DS );
    $views->copy( APP . DS . 'View' . DS . 'Reports' . DS );

    $controller = new File( ROOT . DS . 'vendors' . DS . 'injects' . DS . 'Controller' . DS . 'ReportsController.php' );
    $controller->copy( APP . 'Controller' . DS . 'ReportsController.php' );

    //modifica admin layout
    $layout = new File( APP . 'View' . DS . 'Layouts' . DS . 'admin.ctp' );
    $layoutText = $layout->read();
    $pattern = "/<!-- settings injects >> -->.+?'controller' ?=> ?'reports' .+?<!-- << settings injects -->/ms";
    if ( !preg_match( $pattern, $layoutText ) )
    {
      $pattern = "/(<!-- settings injects >> -->)/ms";
      $replace = "$1\n" . str_repeat( "\t", 10 ) . "<?php echo \$this->Admin->navListLink( 'Reportes', array( 'controller' => 'reports', 'action' => 'index' ) ); ?>";
      $layoutText = preg_replace( $pattern, $replace, $layoutText );
      $layout->write( $layoutText );
    }

    $this->saveContext();
  }
}
