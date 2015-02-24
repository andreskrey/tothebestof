<?php

App::uses('Component', 'Controller/Component');

/**
 * Component para medir via Cache intentos de fuerza bruta
 */
class AttemptLoginComponent extends Component
{
    /** @var  Controller */
    protected $_controller;


    public function initialize(Controller $controller)
    {
        $this->_controller = $controller;
    }


    public function check($attempts = 15, $username)
    {
        $request = $this->_controller->request;
        $id = $request->clientIp() . "_{$request['controller']}_{$request['action']}_{$username}";
        $history = Cache::read($id, 'attemptslogin');
        if ($history >= $attempts) {
            CakeLog::critical('banned ' . $request->clientIp(), 'security');
            Cache::write($_SERVER['REMOTE_ADDR'], 1, 'attemptslogin');
            throw new ForbiddenException();
        }
        Cache::write($id, (int)$history + 1, 'attemptslogin');
    }

}