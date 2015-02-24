<?php
/**
 * built-in Server Shell
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
 * @since         CakePHP(tm) v 2.3.0
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */

App::uses( 'AppShell', 'Console/Command' );

/**
 * Hash password Shell
 * A simple CLI app for hashing passwords using the Application SALT, thus
 * creating valid hashed password in order to hardcode them
 * (in hashed + salted form) in the application
 *
 * @package       Cake.Console.Command
 */
class PasswordShell extends AppShell
{
	/**
	 * document root
	 *
	 * @var string
	 */
	protected $_documentRoot = NULL;


	/**
	 * Override initialize of the Shell
	 *
	 * @return void
	 */
	public function initialize()
	{
		$this->_documentRoot = WWW_ROOT;
	}


	/**
	 * Starts up the Shell and displays the welcome message.
	 * Allows for checking and configuring prior to command or main execution
	 *
	 * Override this method if you want to remove the welcome information,
	 * or otherwise modify the pre-command flow.
	 *
	 * @return void
	 * @link http://book.cakephp.org/2.0/en/console-and-shells.html#Shell::startup
	 */
	public function startup()
	{

		parent::startup();
	}


	/**
	 * Displays a header for the shell
	 *
	 * @return void
	 */
	protected function _welcome()
	{
		$this->out();
		$this->out( __d( 'cake_console', '<info>Welcome to CakePHP %s Console</info>', 'v' . Configure::version() ) );
		$this->hr();
		$this->out( __d( 'cake_console', 'App : %s', APP_DIR ) );
		$this->out( __d( 'cake_console', 'Path: %s', APP ) );
		$this->out( __d( 'cake_console', 'DocumentRoot: %s', $this->_documentRoot ) );
		$this->hr();
	}


	/**
	 * Override main() to handle action
	 *
	 * @return void
	 */
	public function main()
	{
		$this->out( 'Hashed Password:' );
		$this->out( Security::hash( $this->args[ 0 ], NULL, TRUE ) );
	}


	/**
	 * Get and configure the optionparser.
	 *
	 * @return ConsoleOptionParser
	 */
	public function getOptionParser()
	{
		$parser = parent::getOptionParser();

		$parser->addArgument( 'password', array(
			'required' => TRUE,
			'help'     => __d( 'cake_console', 'The password to hash' )
		) );

		$parser->description( array(
			__d( 'cake_console', 'Hashes a password using the application Salt' ),
			__d( 'cake_console', 'The resulting hash can be used to hardcode passwords' ),
			__d( 'cake_console', 'in a secure way within the application' ),
		) );

		return $parser;
	}
}
