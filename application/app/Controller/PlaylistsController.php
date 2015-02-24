<?php
App::uses('AdminAppController', 'Controller');

/**
 * Playlists Controller
 *
 * @property Playlist $Playlist
 * @property Search.PrgComponent $Search.Prg
 * @property AdminComponent $Admin
 */
class PlaylistsController extends AdminAppController
{
    /* inject */
    /**
     * Paginate
     *
     * @var array
     */
    public $paginate = array(
        'Playlist' => array(
            'order' => array('Playlist.id' => 'asc'),
            'limit' => 20,
        )
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
     * Cached actions
     *
     * @var array
     */
    //public $cacheAction = array();


    /**
     * beforeFilter method
     *
     * @return void
     */
    public function beforeFilter()
    {
        $this->breadcrumbControllerNames['playlists'] = $this->Playlist->pluralName;

        parent::beforeFilter();

        if ($this->admin) $this->set('model', $this->Playlist);

        $this->Auth->allow('add', 'view', 'confirm', 'del', 'edit', 'save', 'destroy', 'select');
        $this->Security->unlockedActions = array('add');

    }

    public function destroy()
    {
        $this->autoRender = false;

        $session = $this->Session->read('playlist');

        $session[] = null;

        $this->Session->delete('playlist');
        $this->Session->write('playlist', $session);

        end($session);
        $this->redirect(array('action' => 'add', 'sessionId' => key($session)));
    }

    public function del()
    {
        $this->autoRender = false;

        if ($this->Session->check('playlist.' . $this->request->params['sessionId'] . '.ddbb')) {
            $this->Playlist->delete($this->Session->read('playlist.' . $this->request->params['sessionId'] . '.ddbb.id'));
        }

        $this->Session->delete('playlist.' . $this->request->params['sessionId']);

        $this->Admin->setFlashSuccess('Deleted!');
        $this->redirect(array('action' => 'select'));
    }

    public function save()
    {
        $this->autoRender = false;

        if (!$this->Session->check('playlist.' . $this->request->params['sessionId'])) $this->redirect(array('action' => 'add', 'sessionId' => $this->request->params['sessionId']));

        if ($this->Session->check('playlist.' . $this->request->params['sessionId'] . '.ddbb')) {
            $data['id'] = $this->Session->read('playlist.' . $this->request->params['sessionId'] . '.ddbb.id');
            $data['playlist_uuid'] = $this->Session->read('playlist.' . $this->request->params['sessionId'] . '.ddbb.uuid');
        } else {
            $data['playlist_uuid'] = uniqid();
            $this->Playlist->create();
        }

        $data['songids'] = json_encode($this->Session->read('playlist.' . $this->request->params['sessionId'] . '.data'));

        if ($this->Session->read('user.logged') === TRUE) {
            $this->loadModel('User');
            $this->User->Playlist->create();
            $data['user_id'] = $this->Session->read('user.id');
            $this->User->Playlist->save($data);

            $this->Session->delete('playlist.' . $this->request->params['sessionId']);
            $this->Session->write('playlist.' . $this->request->params['sessionId'] . '.saved', true);
            $this->redirect(array('action' => 'view', $data['playlist_uuid']));
        }

        if ($this->Playlist->save($data, false)) {
            $this->Session->delete('playlist.' . $this->request->params['sessionId']);
            $this->Session->write('playlist.' . $this->request->params['sessionId'] . '.saved', true);
            $this->redirect(array('action' => 'view', $data['playlist_uuid']));
        }
    }

    public function add()
    {
        if ($this->request->is('post')) {

            $this->loadModel('Band');
            $data = $this->Band->find('first', array(
                'fields' => array('Band.*'),
                'conditions' => array('Band.band' => $this->request->data['Playlist']['band']),
            ));

            if (!$data) {
                $this->Admin->setFlashError('Zero results with that band name!');
                $this->redirect(array('action' => 'add', 'sessionId' => $this->request->params['sessionId']));
            }

            $session = NULL;
            if ($this->Session->check('playlist.' . $this->request->params['sessionId'])) $session = $this->Session->read('playlist.' . $this->request->params['sessionId']);

            $session['validate'] = $data;

            $this->Session->write('playlist.' . $this->request->params['sessionId'], $session);

            $this->redirect(array('action' => 'confirm', 'sessionId' => $this->request->params['sessionId']));

        }

        $data = NULL;

        if ($this->Session->check('playlist.' . $this->request->params['sessionId'] . '.validate')) $this->Session->delete('playlist.' . $this->request->params['sessionId'] . '.validate');

        if ($this->Session->check('playlist.' . $this->request->params['sessionId'] . '.data')) {
            foreach ($this->Session->read('playlist.' . $this->request->params['sessionId'] . '.data') as $i) {
                $data[] = $i['songid'];
            }
        }

        $this->set('data', $data);
        $this->set('ddbb', $this->Session->read('playlist.' . $this->request->params['sessionId'] . '.ddbb'));
    }

    public function edit($sessionId, $action = NULL, $id = NULL, $order = NULL)
    {
        if (!$this->Session->check('playlist.' . $this->request->params['sessionId'])) $this->redirect(array('action' => 'add', 'sessionId' => $this->request->params['sessionId']));

        if ($action) {
            switch ($action) {
                case 'remove':
                    if ($id === NULL) throw new BadRequestException;

                    $data = $this->Session->read('playlist.' . $sessionId . '.data');
                    $this->Session->delete('playlist.' . $sessionId . '.data');
                    unset($data[$id]);
                    if (count($data) == 0) {
                        $this->Session->delete('playlist.' . $sessionId);
                        $this->Admin->setFlashSuccess('Empty playlist!');
                        $this->redirect(array('action' => 'add', 'sessionId' => $this->request->params['sessionId']));
                    }
                    $this->Session->write('playlist.' . $sessionId . '.data', $data);
                    $this->redirect(array('action' => 'edit', 'sessionId' => $sessionId));
                    break;
                case 'sort':
                    if ($order === NULL) throw new BadRequestException;
                    if ($id === NULL) throw new BadRequestException;

                    $order = $id + $order;
                    if ($order == -1) break;

                    $data = $this->Session->read('playlist.' . $sessionId . '.data');
                    $this->Session->delete('playlist.' . $sessionId . '.data');

                    $to_restore[] = $data[$id];
                    unset($data[$id]);
                    array_splice($data, $order, 0, $to_restore);

                    $this->Session->write('playlist.' . $sessionId . '.data', $data);
                    $this->redirect(array('action' => 'edit', 'sessionId' => $sessionId));
                    break;
                case 'shuffle':
                    $data = $this->Session->read('playlist.' . $sessionId . '.data');
                    $this->Session->delete('playlist.' . $sessionId . '.data');

                    shuffle($data);

                    $this->Session->write('playlist.' . $sessionId . '.data', $data);
                    if (!$this->Session->check('playlist.' . $sessionId . '.ddbb')) $this->redirect(array('action' => 'add', 'sessionId' => $sessionId));
                    $this->redirect(array('action' => 'save', 'sessionId' => $sessionId));
                    break;
                case 'reorder':
                    $data = $this->Session->read('playlist.' . $sessionId . '.data');
                    $this->Session->delete('playlist.' . $sessionId . '.data');

                    $data = Hash::sort($data, '{n}.band', 'asc', 'string');

                    $this->Session->write('playlist.' . $sessionId . '.data', $data);
                    if (!$this->Session->check('playlist.' . $sessionId . '.ddbb')) $this->redirect(array('action' => 'add', 'sessionId' => $sessionId));
                    $this->redirect(array('action' => 'save', 'sessionId' => $sessionId));
                    break;
                case 'name':
                    if (!$this->Session->check('playlist.' . $sessionId . '.ddbb')) throw new BadRequestException;
                    if (empty($this->request->data['Playlist']['name'])) $this->redirect(array('action' => 'edit', 'sessionId' => $sessionId));

                    $this->Playlist->id = $this->Session->read('playlist.' . $sessionId . '.ddbb.id');
                    if ($this->Playlist->saveField('name', $this->request->data['Playlist']['name'])) {
                        $this->Session->write('playlist.' . $sessionId . '.ddbb.name', $this->request->data['Playlist']['name']);
                        $this->Admin->setFlashSuccess('Playlist name saved correctly!');
                        $this->redirect(array('action' => 'add', 'sessionId' => $sessionId));
                    }
                    break;
                default:
                    throw new BadRequestException;
                    break;
            }
        }

        $this->set('data', $this->Session->read('playlist.' . $sessionId . '.data'));
        $this->set('ddbb', $this->Session->read('playlist.' . $sessionId . '.ddbb'));

    }

    public function confirm()
    {
        if (!$this->Session->check('playlist.' . $this->request->params['sessionId'] . '.validate')) $this->redirect(array('action' => 'add', 'sessionId' => $this->request->params['sessionId']));

        if ($this->request->is('post')) {
            $band = $this->Session->read('playlist.' . $this->request->params['sessionId'] . '.validate');
            $data = $this->Session->read('playlist.' . $this->request->params['sessionId'] . '.data');
            $this->Session->delete('playlist.' . $this->request->params['sessionId'] . '.data');

            unset($this->request->data['Playlist']['selectAll']);
            foreach ($this->request->data['Playlist'] as $k => $i) {
                if ($i) {
                    $already = FALSE;
                    if ($data !== NULL) {
                        foreach ($data as $p) {
                            if ($p['songid'] == $band['Songid'][$k]['songid']) $already = TRUE;
                        }
                    }
                    if (!$already) {
                        $band['Songid'][$k]['band'] = $band['Band']['band'];
                        $data[] = $band['Songid'][$k];
                    }
                }
            }
            $this->Session->delete('playlist.' . $this->request->params['sessionId'] . '.validate');
            $this->Session->write('playlist.' . $this->request->params['sessionId'] . '.data', $data);
            if ($this->Session->check('playlist.' . $this->request->params['sessionId'] . '.ddbb')) $this->redirect(array('action' => 'save', 'sessionId' => $this->request->params['sessionId']));
            $this->redirect(array('action' => 'add', 'sessionId' => $this->request->params['sessionId']));
        }

        $this->set('data', $this->Session->read('playlist.' . $this->request->params['sessionId'] . '.validate'));
    }

    public function view($uuid)
    {
        $this->autoRender = false;

        if (isset($this->request->params['sessionId']) && $this->Session->check('playlist.' . $this->request->params['sessionId'] . '.saved')) $saved = TRUE;

        $session = $this->Session->read('playlist');

        $result = $this->Playlist->find('first', array(
            'fields' => array('Playlist.*'),
            'conditions' => array('Playlist.playlist_uuid' => $uuid),
        ));

        if (!$result) throw new NotFoundException;

        $this->Playlist->id = $result['Playlist']['id'];
        $this->Playlist->save(array('Playlist' => array(
            'lastload' => date('c'),
        )), false, array('lastload'));

        if ($session) {
            $search = array_search($result['Playlist']['playlist_uuid'], Hash::extract($session, '{n}.ddbb.uuid'));
            if ($search !== false) $this->redirect(array('action' => 'add', 'sessionId' => $search));
        }

        $session[] = array(
            'data' => json_decode($result['Playlist']['songids'], true),
            'id' => $result['Playlist']['id'],
            'ddbb' => array(
                'uuid' => $result['Playlist']['playlist_uuid'],
                'id' => $result['Playlist']['id'],
                'name' => $result['Playlist']['name'],
            )
        );

        $this->Session->delete('playlist');

        //TODO seria mas piola que en vez de unsetear el id, reciclarlo.
        //Esto permitiria seguir usando el mismo id de sesion por mucho que cambie la playlist
        foreach ($session as $k => $v) {
            if (count($v) == 1 && isset($v['saved'])) {
                unset($session[$k]);
                $saved = TRUE;
            }
        }
        $this->Session->write('playlist', $session);

        if (isset($saved)) $this->Admin->setFlashSuccess('Saved! You can find the link below here');

        end($session);
        $this->redirect(array('action' => 'add', 'sessionId' => key($session)));
    }

    public function select()
    {
        $session = $this->Session->read('playlist');
        if (!$session) $this->destroy();

        foreach ($session as $k => $v) {
            if ($v) $session[$k]['bands'] = array_unique(Hash::extract($v['data'], '{n}.band'));
        }

        $this->set('data', $session);
    }

    /**
     * admin_index method
     *
     * @return void
     */
    public function admin_index()
    {
        $this->Playlist->recursive = 0;
        try {
            $records = $this->paginate('Playlist');
        } catch (NotFoundException $e) {
            $this->Admin->setFlashInfo('<strong>Sin Resultados para esa página!</strong> Ahora estamos en la primera página.');
            $this->redirect(Set::merge(Set::get($this->request->params, 'named'), array('page' => 1)));
        }
        $this->set('playlists', $records);
    }

    /**
     * admin_view method
     *
     * @throws NotFoundException
     * @param string $id
     * @return void
     */
    public function admin_view($id = null)
    {
        if (!$this->Playlist->exists($id)) {
            throw new NotFoundException('El registro no existe');
        }
        $this->set('playlist', $this->Playlist->find('first', array('conditions' => array('Playlist.id' => $id))));
    }

    /**
     * admin_add method
     *
     * @return void
     */
    public function admin_add()
    {
        if ($this->request->is('post')) {
            $this->Playlist->create();
            if ($this->Playlist->save($this->request->data)) {
                $this->Admin->setFlashSuccess('El registro ha sido guardado');
                $this->redirect(isset($this->request->data['_addAnother']) ? array('action' => 'add') : $this->getPreviousUrl());
            } else {
                $this->Admin->setFlashError('El registro no se pudo guardar. Por favor intenta más tarde.');
            }
        }
    }

    /**
     * admin_edit method
     *
     * @throws NotFoundException
     * @param string $id
     * @return void
     */
    public function admin_edit($id = null)
    {
        if (!$this->Playlist->exists($id)) {
            throw new NotFoundException('Registro inválido');
        }
        if ($this->request->is('post') || $this->request->is('put')) {
            if ($this->Playlist->save($this->request->data)) {
                $this->Admin->setFlashSuccess('El registro ha sido guardado');
                $this->redirect($this->getPreviousUrl());
            } else {
                $this->Admin->setFlashError('El registro no se pudo guardar. Por favor intenta más tarde.');
            }
        } else {
            $this->request->data = $this->Playlist->find('first', array('conditions' => array('Playlist.id' => $id)));
        }
    }

    /**
     * admin_delete method
     *
     * @throws NotFoundException
     * @param string $id
     * @return void
     */
    public function admin_delete($id = null)
    {
        if (!$this->Playlist->exists($id)) {
            throw new NotFoundException('Registro inválido');
        }
        if ($this->request->is('post')) {
            $this->Playlist->id = $id;
            if ($this->Playlist->delete()) {
                $this->Admin->setFlashSuccess('El registro ha sido borrado');
                $this->redirect($this->getPreviousUrl());
            }
            $this->Admin->setFlashError('El registro no se pudo borrar. Por favor intenta más tarde.');
        }
        $this->set('playlist', $this->Playlist->read(NULL, $id));
    }
}
