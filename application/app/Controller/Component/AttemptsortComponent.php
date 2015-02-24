<?php

App::uses('Component', 'Controller/Component');

/**
 * Component para medir via Cache intentos de fuerza bruta
 */
class AttemptsortComponent extends Component
{
    /** @var  Controller */
    protected $_controller;


    public function initialize(Controller $controller)
    {
        $this->_controller = $controller;
    }


    public function check($attempts = 10, $id_vote)
    {

        $request = $this->_controller->request;
        $id = $request->clientIp() . "_{$request['controller']}_{$request['action']}_{$id_vote}";
        $history = Cache::read($id, 'attemptssort');
        if ($history >= $attempts) {
            Cache::write($_SERVER['REMOTE_ADDR'], 1, 'attemptssort');
            return FALSE;
        }
        Cache::write($id, (int)$history + 1, 'attemptssort');

    }
}