<?php
/**
 * Command-line code injector utility to add funcionality to existing CRUD.
 *
 * PHP 5
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @since         CakePHP(tm) v 1.2.0.5012
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */

App::uses( 'AppShell', 'Console/Command' );
App::uses( 'Model', 'Model' );

class InjectShell extends AppShell
{

  /**
   * Contains tasks to load and instantiate
   *
   * @var array
   */
  public $tasks = array( 'InjectReports', 'InjectEmails', 'InjectSorting', 'InjectToggle', 'InjectUploaderPlugin', 'InjectWysiwyg', 'InjectRelatedModel', 'DbConfig' );

  /**
   * The connection being used.
   *
   * @var string
   */
  public $connection = 'default';


  /**
   * Assign $this->connection to the active task if a connection param is set.
   *
   * @return void
   */
  public function startup()
  {
    parent::startup();
    Configure::write( 'debug', 2 );
    Configure::write( 'Cache.disable', 1 );

    $task = Inflector::classify( $this->command );
  }


  /**
   * Override main() to handle action
   *
   * @return mixed
   */
  public function main()
  {
    if ( !config( 'database' ) )
    {
      $this->out( __d( 'cake_console', 'Your database configuration was not found. Take a moment to create one.' ) );
      $this->args = NULL;

      return $this->DbConfig->execute();
    }

    $this->out( 'Interactive Inject Shell' );
    $this->hr();
//    $this->out( '[D] Inject Dashboard' );
    $this->out( '[R] Inject Reports' );
    $this->out( '[E] Inject Emails' );
    $this->out( '[U] Inject Uploader Plugin' );
    $this->out( '[S] Inject Record Sorting functionality' );
    $this->out( '[T] Inject Record boolean toggle functionality' );
    $this->out( '[Y] Inject WYSIWYG functionality' );
    $this->out( '[L] Inject Related Model functionality' );
//    $this->out( '[W] Inject Webservices Explorer and Exporter' );
    $this->out( '[Q]uit' );

    $classToBake = strtoupper( $this->in( __( 'What would you like to Inject?', TRUE ), array( 'D', 'R', 'E', 'U', 'S', 'T', 'Y', 'W', 'L', 'Q' ) ) );
    switch ( $classToBake )
    {
//      case 'D':
//        $this->InjectDashboard->execute();
//        break;
      case 'R':
        $this->InjectReports->execute();
        break;
      case 'E':
        $this->InjectEmails->execute();
        break;
      case 'U':
        $this->InjectUploaderPlugin->execute();
        break;
      case 'S':
        $this->InjectSorting->execute();
        break;
      case 'T':
        $this->InjectToggle->execute();
        break;
      case 'Y':
        $this->InjectWysiwyg->execute();
        break;
      case 'L':
        $this->InjectRelatedModel->execute();
        break;
//      case 'W':
//        $this->InjectWebservices->execute();
//        break;
      case 'Q':
        exit( 0 );
        break;
      default:
        $this->out( __( 'You have made an invalid selection. Please try again.', TRUE ) );
    }
    $this->hr();
    $this->main();
  }
}
