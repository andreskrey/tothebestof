<?php
App::uses('AdminAppController', 'Controller');
/**
 * Emails Controller
 *
 * @property Email          $Email
 * @property Search.PrgComponent $Search.Prg
 * @property AdminComponent $Admin
 */
class EmailsController extends AdminAppController
{
    /* inject */
    /**
     * Paginate
     *
     * @var array
     */
    public $paginate = array(
        'limit' => 20,
    );

    /**
     * Helpers
     *
     * @var array
     */
    public $helpers = array('Admin');

    /**
     * Components
     *
     * @var array
     */
    public $components = array('Search.Prg', 'Admin');


    /**
     * beforeFilter method
     *
     * @return void
     */
    function beforeFilter()
    {
        $this->breadcrumbControllerNames['emails'] = 'Emails';
        $this->breadcrumbActionNames['admin_test'] = 'Enviar Prueba';
        $this->breadcrumbActionNames['admin_preview'] = 'Previsualizar';
        $this->breadcrumbActionNames['admin_render_preview'] = 'Previsualizar';

        $this->Auth->allow('view');

        parent::beforeFilter();
    }


    public function view($uuid, $format = NULL)
    {
        $email = $this->Email->find('first', array('conditions' => array('uuid' => $uuid)));
        if (!$email) {
            throw new NotFoundException('El Email no existe');
        }

        $format = $format ? $format : ($email['Email']['format'] == 'both' ? 'html' : $email['Email']['format']);
        $this->autoRender = FALSE;

        App::uses('ZenEmail', 'Network/Email');
        $response = ZenEmail::render(
            $email['Email']['key'],
            $this->request->query,
            $format
        );
        $this->response->type($format);
        $this->response->body($response);
    }


    public function admin_preview($id)
    {
        if (!$this->Email->exists($id)) {
            throw new NotFoundException('El registro no existe');
        }

        if ($this->request->is('post')) {
            $uuid = $this->request->data['Email']['uuid'];
            $format = $this->request->data['Email']['format'];
            $query = $this->request->data['Email']['query'];
            $this->redirect(array('action' => 'render_preview', $uuid, $format, '?' => $query));
        }
        $this->set('email', $this->Email->find('first', array('conditions' => array('id' => $id))));
    }


    public function admin_render_preview($uuid, $format = NULL)
    {
        $email = $this->Email->find('first', array('conditions' => array('uuid' => $uuid)));
        if (!$email) {
            throw new NotFoundException('El registro no existe');
        }
        $this->set('uuid', $uuid);
        $this->set('format', $format);
        $this->set('query', $this->request->query);
        $this->set('email', $email);
    }


    public function admin_test($id = NULL)
    {
        App::uses('ZenEmail', 'Network/Email');

        if (!$this->Email->exists($id)) {
            throw new NotFoundException('El registro no existe');
        }

        if ($this->request->is('post')) {
            //prepara datos y manda el email
            $data = $this->request->data['Email'];

            $sent = ZenEmail::deliver(
                $data['key'],
                $data['to_email'],
                $data['to_name'],
                $data['vars'],
                null,
                $data['subject'],
                $data['from_email'],
                $data['from_name'],
                $data['config']
            );
            if ($sent) {
                $this->Admin->setFlashSuccess('Email de prueba enviado con éxito!');
                $this->redirect(array('action' => 'index'));
            } else {
                $this->Admin->setFlashError('El Email de prueba no se pudo enviar.');
            }
        }

        $this->set('email', $this->Email->find('first', array('conditions' => array('id' => $id))));
        $this->set('configs', ZenEmail::getConfigs());
    }


    /**
     * admin_index method
     *
     * @return void
     */
    public function admin_index()
    {
        $this->Email->recursive = 0;
        try {
            $records = $this->paginate();
        } catch (NotFoundException $e) {
            $this->Admin->setFlashInfo('<strong>Sin Resultados para esa página!</strong> Ahora estamos en la primera página.');
            $this->redirect(Set::merge(Set::get($this->request->params, 'named'), array('page' => 1)));
        }
        $this->set('emails', $records);
        $this->set('model', $this->Email);
    }


    /**
     * admin_view method
     *
     * @throws NotFoundException
     *
     * @param string $id
     *
     * @return void
     */
    public function admin_view($id = NULL)
    {
        if (!$this->Email->exists($id)) {
            throw new NotFoundException('El registro no existe');
        }
        $options = array('conditions' => array('id' => $id));
        $this->set('email', $this->Email->find('first', $options));
        $this->set('model', $this->Email);
    }
}
