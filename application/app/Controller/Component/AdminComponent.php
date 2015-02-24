<?php

App::uses( 'Component', 'Controller/Component' );
App::uses( 'CakeSession', 'Model/Datasource' );

/**
 * Component helper para producir mensajes Flash para el admin, ala bootstrap, sin escribir tanto boilerplate code
 */
class AdminComponent extends Component
{
	public function setFlashSuccess( $message )
	{
		CakeSession::write( 'Message.flash', array( 'message' => $message, 'element' => 'alert', 'params' => array( 'plugin' => 'TwitterBootstrap', 'class' => 'alert-success' ) ) );
	}


	public function setFlashError( $message )
	{
		CakeSession::write( 'Message.flash', array( 'message' => $message, 'element' => 'alert', 'params' => array( 'plugin' => 'TwitterBootstrap', 'class' => 'alert-error' ) ) );
	}


	public function setFlashWarning( $message )
	{
		CakeSession::write( 'Message.flash', array( 'message' => $message, 'element' => 'alert', 'params' => array( 'plugin' => 'TwitterBootstrap', 'class' => 'alert-warning' ) ) );
	}


	public function setFlashInfo( $message )
	{
		CakeSession::write( 'Message.flash', array( 'message' => $message, 'element' => 'alert', 'params' => array( 'plugin' => 'TwitterBootstrap', 'class' => 'alert-info' ) ) );
	}
}
