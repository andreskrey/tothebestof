<?php
/**
 * Routes configuration
 *
 * In this file, you set up routes to your controllers and their actions.
 * Routes are very important mechanism that allows you to freely connect
 * different urls to chosen controllers and their actions (functions).
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
 * @package       app.Config
 * @since         CakePHP(tm) v 0.2.9
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */
/**
 * Here, we are connecting '/' (base path) to controller called 'Pages',
 * its action called 'display', and we pass a param to select the view file
 * to use (in this case, /app/View/Pages/home.ctp)...
 */

Router::connect('/band/**', array('controller' => 'bands', 'action' => 'topten'));

Router::connect('/genre/**', array('controller' => 'bands', 'action' => 'genre'));

Router::connect('/', array('controller' => 'pages', 'action' => 'home'));
Router::connect('/admin', array('controller' => 'administration', 'action' => 'login'));

Router::connect('/about', array('controller' => 'pages', 'action' => 'about'));
Router::connect('/halp', array('controller' => 'pages', 'action' => 'halp'));
Router::connect('/thanks', array('controller' => 'pages', 'action' => 'thanks'));
Router::connect('/privacy-policy', array('controller' => 'pages', 'action' => 'privacy'));
Router::connect('/terms-and-conditions', array('controller' => 'pages', 'action' => 'terms'));
Router::connect('/contact', array('controller' => 'pages', 'action' => 'contact'));
Router::connect('/faq', array('controller' => 'pages', 'action' => 'faq'));
Router::connect('/random', array('controller' => 'bands', 'action' => 'random'));

Router::connect('/request-a-feature', array('controller' => 'features', 'action' => 'index'));
Router::connect('/request-a-feature/thanks', array('controller' => 'features', 'action' => 'thanks'));
Router::connect('/request-a-feature/ranking', array('controller' => 'features', 'action' => 'ranking'));
Router::connect('/request-a-feature/sort', array('controller' => 'features', 'action' => 'ranking_sort'));

Router::connect('/ajax', array('controller' => 'bands', 'action' => 'ajax'));

$pass = array(
    'sessionId' => '\d+',
    'pass' => array('sessionId')
);

Router::connect('/playlist', array('controller' => 'playlists', 'action' => 'select'));
Router::connect('/playlist/:sessionId', array('controller' => 'playlists', 'action' => 'add'), $pass);
Router::connect('/playlist/:sessionId/delete', array('controller' => 'playlists', 'action' => 'del'), $pass);
Router::connect('/playlist/:sessionId/save', array('controller' => 'playlists', 'action' => 'save'), $pass);
Router::connect('/playlist/:sessionId/confirm', array('controller' => 'playlists', 'action' => 'confirm'), $pass);
Router::connect('/playlist/new', array('controller' => 'playlists', 'action' => 'destroy'));
Router::connect('/playlist/:sessionId/edit/*', array('controller' => 'playlists', 'action' => 'edit'), $pass);
Router::connect('/playlist/view/*', array('controller' => 'playlists', 'action' => 'view'));

Router::connect('/user/new', array('controller' => 'users', 'action' => 'add'));
Router::connect('/user/recover', array('controller' => 'users', 'action' => 'recover'));
Router::connect('/user/recover/done', array('controller' => 'users', 'action' => 'done'));
Router::connect('/user/delete', array('controller' => 'users', 'action' => 'remove'));
Router::connect('/user/edit', array('controller' => 'users', 'action' => 'edit'));
Router::connect('/user/info', array('controller' => 'users', 'action' => 'info'));
Router::connect('/user/favorites/edit', array('controller' => 'favorites', 'action' => 'edit'));
Router::connect('/user/fav/*', array('controller' => 'users', 'action' => 'favorite'));
Router::connect('/user/unfav/*', array('controller' => 'users', 'action' => 'unfav'));
Router::connect('/user/clear', array('controller' => 'bands', 'action' => 'clearCookie'));
Router::connect('/user/welcome', array('controller' => 'users', 'action' => 'welcome'));
Router::connect('/login', array('controller' => 'users', 'action' => 'login'));
Router::connect('/logout', array('controller' => 'users', 'action' => 'logout'));

Router::connect('/stats', array('controller' => 'hits', 'action' => 'index'));


/** injects */
/** end-injects */

/**
 * Load all plugin routes. See the CakePlugin documentation on
 * how to customize the loading of plugin routes.
 */
CakePlugin::routes();

/**
 * Load the CakePHP default routes. Only remove this if you do not want to use
 * the built-in default routes.
 */
require CAKE . 'Config' . DS . 'routes.php';

/**
 * After adding custom routes, comment de 'require' line above and uncomment the code below
 * to enable default CakePHP routes ONLY in the admin area
 */
//$params = array( 'prefix' => 'admin', 'admin' => TRUE );
//$indexParams = $params + array( 'action' => 'index' );
//Router::connect( "/admin/:controller", $indexParams );
//Router::connect( "/admin/:controller/:action/*", $params );