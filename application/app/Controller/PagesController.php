<?php
/**
 * Static content controller.
 *
 * This file will render views from views/pages/
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
App::uses('AppController', 'Controller');

/**
 * Static content controller
 *
 * Override this controller by placing a copy in controllers directory of an application
 *
 * @package       app.Controller
 * @link http://book.cakephp.org/2.0/en/controllers/pages-controller.html
 */
class PagesController extends AppController
{

    /**
     * Controller name
     *
     * @var string
     */
    public $name = 'Pages';

    public $helpers = array();

    /**
     * This controller does not use a model
     *
     * @var array
     */
    public $uses = array();


    public function beforeFilter()
    {
        parent::beforeFilter(); // TODO: Change the autogenerated stub
        $this->Auth->allow('home', 'about', 'stats', 'halp', 'thanks', 'privacy', 'terms', 'faq', 'contact');
        $this->Security->unlockedActions = array('contact');
    }

    public function home()
    {

        $model = $this;
        $bandas = Cache::remember('home', function () use ($model) {
            $model->loadModel('Band');
            $random = $model->Band->find('all', array(
                'fields' => array('Band.*'),
                'limit' => 50,
                'order' => 'rand()'
            ));

            $bandas = array();
            $enough = 0;
            foreach ($random as $i) {
                if ($enough >= 4) break;
                if (count($i['Songid']) > 11) {
                    $bandas[] = $i['Band']['band'];
                    $enough++;
                }
            }
            return $bandas;
        }, 'home');

        $this->set('homeBands', $bandas);
    }

    public function about()
    {

    }

    public function halp()
    {
        $data = array('title_for_layout' => 'Halp me!');
        $this->set('data', $data);
    }

    public function thanks()
    {

    }

    public function terms()
    {

    }

    public function privacy()
    {

    }

    public function faq()
    {

    }

    public function contact()
    {
        if ($this->request->is('post') || $this->request->is('put')) {

            App::uses('ZenEmail', 'Network/Email');

            $sent = ZenEmail::deliver(
                'contact',
                'andreskrey@gmail.com',
                null,
                array(
                    'name' => $this->request->data['Contact']['namemail'],
                    'message' => $this->request->data['Contact']['message'],
                ),
                null,
                'Contacto desde tothebestof.com'
            );
            if ($sent) {
                CakeSession::write('Message.flash', array('message' => 'Sent, thanks!', 'element' => 'alert', 'params' => array('plugin' => 'TwitterBootstrap', 'class' => 'alert-success')));
            } else {
                CakeSession::write('Message.flash', array('message' => 'Something went wrong... Try again?', 'element' => 'alert', 'params' => array('plugin' => 'TwitterBootstrap',
                    'class' => 'alert-error')));
            }
        }
    }
}