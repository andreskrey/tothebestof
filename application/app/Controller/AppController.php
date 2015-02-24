<?php
/**
 * Application level Controller
 *
 * This file is application-wide controller file. You can put all
 * application-wide controller-related methods here.
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
 * @package       app.Controller
 * @since         CakePHP(tm) v 0.2.9
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */
App::uses('Controller', 'Controller');

/**
 * Application Controller
 *
 * Add your application-wide methods in the class below, your controllers
 * will inherit them.
 *
 * @package        app.Controller
 * @link           http://book.cakephp.org/2.0/en/controllers.html#the-app-controller
 *
 */
class AppController extends Controller
{
    public $components = array(
        'Session',
        'Cookie',
        'Auth',
        'Security' => array(
            'csrfUseOnce' => FALSE
        ),
        'DebugKit.Toolbar',
    );

    public $helpers = array(
        'AssetCompress.AssetCompress',
    );

    protected $breadcrumbControllerNames = array(
        'administration' => 'Administración',
        'reports' => 'Reportes',
        'emails' => 'Emails',
        'settings' => 'Configuración',
    );

    protected $breadcrumbActionNames = array(
        'admin_search' => 'Buscar',
        'admin_filter' => 'Relacionados',
        'admin_add' => 'Agregar',
        'admin_add_related' => 'Agregar',
        'admin_edit' => 'Editar',
        'admin_view' => 'Ver',
        'admin_delete' => 'Borrar',
        'admin_export' => 'Exportar',
        'admin_bulk_delete' => 'Borrar',
        'admin_bulk_export' => 'Exportar',
    );

    protected $admin = FALSE;

    protected $navTree = array();


    public function beforeFilter()
    {
        //app side redirect since no globally compatible server side redirect was feasable
        if (substr($this->request->url, -1) === '/') return $this->redirect('/' . substr($this->request->url, 0, -1), 301);

        //Auth Config
        $this->Auth->loginAction = array('admin' => FALSE, 'controller' => 'administration', 'action' => 'login');
        $this->Auth->authError = 'No tiene permisos para acceder. Por favor ingrese sus credenciales';
        $this->Auth->flash = array('element' => 'TwitterBootstrap.alert', 'key' => 'auth');
        $this->Auth->authenticate = array('Form' => array('userModel' => 'Administrator'));
        $this->Auth->logoutRedirect = array('controller' => 'administration', 'action' => 'login');
        $this->Auth->loginRedirect = array('admin' => 'admin', 'controller' => 'administration', 'action' => 'dashboard');

        //remember me
        $this->Cookie->name = Configure::read('Session.cookie') . '_app';
        $this->Cookie->httpOnly = TRUE;
        $this->Cookie->type(Configure::read('Security.encryptType'));
        if (Hash::get($this->request->params, 'prefix') == 'admin' && !$this->Auth->loggedIn() && $this->Cookie->check('remember_me')) {
            $remember_me = $this->Cookie->read('remember_me');
            if (!$this->Auth->login($remember_me)) {
                //destroy session & cookie
                $this->redirect(array('controller' => 'administrators', 'action' => 'logout'));
            }
        }

        //admin
        if (Hash::get($this->request->params, 'prefix') == 'admin') {
            $this->admin = TRUE;

            //layout
            $this->layout = 'admin';

            //titulo automatico en admin
            $this->set('title_for_layout', $this->breadcrumbControllerNames[$this->request->params['controller']]);

            //navTree para breadcrumbs y demás
            $controller = $this->request->params['controller'];
            $action = $this->request->params['action'];
            if (!($controller == 'administration' && $action == 'admin_dashboard')) {
                $this->navTree[] = array(
                    'name' => 'Dashboard',
                    'route' => array('admin' => 'admin', 'controller' => 'administration', 'action' => 'dashboard')
                );
                $this->navTree[] = array(
                    'name' => isset($this->breadcrumbControllerNames[$controller]) ? $this->breadcrumbControllerNames[$controller] : $controller,
                    'route' => array('admin' => 'admin', 'controller' => $controller, 'action' => 'admin_index')
                );

                if ($action != 'admin_index') {
                    $this->navTree[] = array(
                        'name' => isset($this->breadcrumbActionNames[$action]) ? $this->breadcrumbActionNames[$action] : $action,
                        'route' => array('admin' => 'admin', 'controller' => $controller, 'action' => $action)
                    );
                }
            }
        }

        $this->Cookie->name = 'history';
        $this->Cookie->time = 3600 * 24 * 30;
        $this->Cookie->path = '/';
        $this->Cookie->domain = $_SERVER['HTTP_HOST'];
        $this->Cookie->secure = false;

        if (!$this->Session->read('user.logged')) {
            $remember = $this->Cookie->read('rememberMe');
            if ($remember) {
                $this->loadModel('User');
                $login = $this->User->login($remember['user'], $remember['pass']);

                if ($login) {
                    $this->Session->destroy();

                    $data = array(
                        'user' => array(
                            'logged' => true,
                            'id' => $login['User']['id'],
                            'username' => $login['User']['username'],
                            'email' => $login['User']['email'],
                            'favs' => Hash::extract($login['Favorite'], '{n}.Band.band'),
                            'donated' => $login['User']['donation'],
                        )
                    );
                    $this->Session->write($data);

                    $this->redirect(array('controller' => 'bands', 'action' => 'topten'));

                } else {
                    $this->Cookie->delete('rememberMe');
                }
            }
        }
    }


    public function beforeRender()
    {
        parent::beforeRender();

        if ($this->admin) {
            $this->set('navTree', $this->navTree);
        }

        $this->set('history', $this->Cookie->read('history'));
    }
}
