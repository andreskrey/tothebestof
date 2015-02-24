<?php

App::uses( 'AppController', 'Controller' );

class AdministrationController extends AppController
{
  public $uses = 'Administrator';

  public $components = array(
    'Attempt'
  );


  public function beforeFilter()
  {
    parent::beforeFilter();
    $this->Auth->allow( 'login' );

    $this->helpers[ 'BootstrapHtml' ] = array( 'className' => 'TwitterBootstrap.BootstrapHtml' );
    $this->helpers[ 'BootstrapForm' ] = array( 'className' => 'TwitterBootstrap.BootstrapForm' );
    $this->helpers[ 'BootstrapPaginator' ] = array( 'className' => 'TwitterBootstrap.BootstrapPaginator' );
    $this->helpers[ 'Admin' ] = NULL;
  }


  public function login()
  {
    if ( $this->Auth->loggedIn() ) $this->redirect( $this->Auth->redirectUrl() );

    $this->layout = 'admin';

    if ( $this->request->is( 'post' ) )
    {
      //checks attemps
      $this->Attempt->check( 10 );

      if ( $this->Auth->login() )
      {
        // remove "remember me checkbox"
        if ( $this->request->data[ 'Administrator' ][ 'remember_me' ] )
        {
          unset( $this->request->data[ 'Administrator' ][ 'remember_me' ] );

          // saves remember me cookie, expires tomorrow, but if it's less than 4 hours, then it's four hours
          $expires = max( strtotime( 'tomorrow' ) - time(), 3600 * 4 );
          $this->Cookie->write( 'remember_me', $this->request->data, TRUE, $expires );
        }

        //fuerza redirect al dashboard, siempre
        $this->Session->delete( 'Auth.redirect' );

        $this->redirect( $this->Auth->redirectUrl() );
      }
      else
      {
        $this->Session->setFlash( 'Usuario o password incorrecto', 'alert', array( 'plugin' => 'TwitterBootstrap', 'class' => 'alert-error' ), 'auth' );
      }
    }
  }


  public function logout()
  {
    $this->Auth->logout();
    $this->Cookie->delete( 'remember_me' );
    if ( isset( $_COOKIE[ "override_" . Configure::read( 'Session.cookie' ) ] ) )
    {
      $this->Cookie->name = "override_" . Configure::read( 'Session.cookie' );
      $this->Cookie->destroy();
    }
    $this->redirect( $this->Auth->logoutRedirect );
  }


  public function admin_dashboard()
  {
    //--
  }

  /**
   * Metodos para agregar
   *
   * Reportes graficos? Por ahi atado a Reports
   * Ver Logs
   * Limpieza de Cache
   * Limpieza de Logs
   *
   *
   */
}