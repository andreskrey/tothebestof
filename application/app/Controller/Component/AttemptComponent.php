<?php

App::uses('Component', 'Controller/Component');

/**
 * Component para medir via Cache intentos de fuerza bruta
 */
class AttemptComponent extends Component
{
    /** @var  Controller */
    protected $_controller;


    public function initialize(Controller $controller)
    {
        $this->_controller = $controller;
    }


    public function check($attempts = 10)
    {

            $request = $this->_controller->request;
            $id = $request->clientIp() . "_{$request['controller']}_{$request['action']}";
            $history = Cache::read($id, 'attempts');
            if ($history >= $attempts) {
                CakeLog::critical('banned ' . $request->clientIp(), 'security');
                Cache::write($_SERVER['REMOTE_ADDR'], 1, 'attempts');
                throw new ForbiddenException();
            }
            Cache::write($id, (int)$history + 1, 'attempts');
        }

}