<?php
App::uses('AdminAppController', 'Controller');

/**
 * Users Controller
 *
 * @property User $User
 * @property Search.PrgComponent $Search.Prg
 * @property AdminComponent $Admin
 */
class UsersController extends AdminAppController
{
    /* inject */
    /**
     * Paginate
     *
     * @var array
     */
    public $paginate = array(
        'User' => array(
            'order' => array('User.id' => 'asc'),
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
    public $components = array('Search.Prg',
        'Admin',
        'Security' => array(
            'csrfUseOnce' => false,
            'csrfExpires' => '+1 day'
        ),
        'AttemptLogin'
    );

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
        $this->breadcrumbControllerNames['users'] = $this->User->pluralName;

        parent::beforeFilter();

        if ($this->admin) $this->set('model', $this->User);

        $this->Auth->allow('add', 'login', 'logout', 'edit', 'remove', 'recover', 'done', 'info', 'favorite', 'unfav', 'welcome');

    }

    public function add()
    {
        if ($this->request->is('post') || $this->request->is('put')) {
            $this->User->create();

            $this->User->set($this->request->data);
            if ($this->User->validates(array('fieldList' => array('password')))) {

                $this->request->data['User']['password'] = Security::hash($this->request->data['User']['password'], 'sha1', true);
                $this->request->data['User']['donation'] = FALSE;
                if ($this->User->save($this->request->data)) {

                    $this->Session->destroy();
//                    $this->Admin->setFlashSuccess('User created, welcome!');
                    $data = array(
                        'user' => array(
                            'logged' => true,
                            'id' => $this->User->id,
                            'username' => $this->request->data['User']['username'],
                            'email' => $this->request->data['User']['email'],
                            'favs' => null,
                            'donated' => false,
                        )
                    );
                    $this->Session->write($data);

                    $this->redirect(array('controller' => 'users', 'action' => 'welcome'));
                }
            }
        }
    }

    public function welcome()
    {

    }

    public function edit()
    {
        if ($this->Session->read('user.logged') != TRUE) $this->redirect(array('controller' => 'users', 'action' => 'login'));

        if ($this->request->is('post') || $this->request->is('put')) {

            $fields = null;
            foreach ($this->request->data['User'] as $k => $i) {
                if ($k == 'id') continue;
                if (!empty($i)) $fields[] = $k;
            }

            if ($fields) {
                $this->User->set($this->request->data);
                if ($this->User->validates(array('fieldList' => $fields))) {
                    if (in_array('password', $fields)) $this->request->data['User']['password'] = Security::hash($this->request->data['User']['password'], 'sha1', true);
                    if ($this->User->save($this->request->data, true, $fields)) {
                        if (in_array('email', $fields)) $this->Session->write('user.email', $this->request->data['User']['email']);
                        $this->Admin->setFlashSuccess('Changes saved!');
                        $this->redirect(array('controller' => 'users', 'action' => 'edit'));
                    }
                }
            }
        }

        $user = $this->User->find('first', array(
            'fields' => array('User.*'),
            'conditions' => array('User.username' => $this->Session->read('user.username')),
        ));

        if (!$user) {
            $this->Session->destroy();
            throw new InternalErrorException('HWAT? This should not happen. Never.');
        }

        unset($user['User']['password']);

        $this->set('data', $user);

    }

    public function remove()
    {
        if ($this->Session->read('user.logged') != TRUE) $this->redirect(array('controller' => 'users', 'action' => 'login'));

        if ($this->request->is('post') || $this->request->is('put')) {
            if ($this->request->data['delete'] == TRUE) {
                $user = $this->User->find('first', array(
                    'fields' => array('User.*'),
                    'conditions' => array('User.username' => $this->Session->read('user.username')),
                ));

                if (!$user) {
                    $this->Session->destroy();
                    throw new InternalErrorException('Stop doing that.');
                }

                $this->User->delete($user['User']['id']);
                $this->Session->destroy();
                $this->Admin->setFlashSuccess('User deleted. Bye bye!');
                $this->redirect(array('controller' => 'users', 'action' => 'login'));
            }
        }
    }

    public function recover()
    {
        if ($this->Session->read('user.logged') === TRUE) $this->redirect(array('controller' => 'bands', 'action' => 'topten'));

        if ($this->request->is('post') || $this->request->is('put')) {

            if (!Validation::email($this->request->data['User']['email'])) {
                $this->Admin->setFlashError("Uhmmm, I don't have any user with that email...");
            } else {

                $user = $this->User->find('first', array(
                    'fields' => array('User.*'),
                    'conditions' => array('User.email' => $this->request->data['User']['email']),
                ));

                if (!$user) {

                    $this->Admin->setFlashError("Uhmmm, I don't have any user with that email...");

                } else {

                    $pass = substr(Security::hash(mt_rand(), 'sha1', true), 0, 8);
                    $user['User']['password'] = Security::hash($pass, 'sha1', true);

                    $this->User->set($user);
                    $this->User->save($this->request->data, true, array('password', 'email'));

                    App::uses('ZenEmail', 'Network/Email');

                    $dest = $user['User']['email'];

                    $sent = ZenEmail::deliver(
                        'default',
                        $dest,
                        null,
                        array(
                            'pass' => $pass,
                            'user' => $user['User']['username'],
                        ),
                        null,
                        'Password restore for tothebestof.com'
                    );
                    if ($sent) {
                        $this->redirect(array('action' => 'done'));
                    } else {
                        $this->Admin->setFlashError('Something went wrong... Try again in a few seconds, please.');
                    }
                }
            }
        }
    }

    public function done()
    {

    }

    public function info()
    {
        if ($this->Session->read('user.logged') != TRUE) $this->redirect(array('controller' => 'users', 'action' => 'login'));

        $user = $this->User->find('first', array(
            'fields' => array('User.*'),
            'recursive' => 2,
            'contain' => array(
                'Favorite.Band',
                'Playlist'),
            'conditions' => array('User.username' => $this->Session->read('user.username')),
        ));

        if (!$user) {
            $this->Session->destroy();
            throw new InternalErrorException('HWAT? This should not happen. Never.');
        }

        if (!empty($user['Playlist'])) {
            foreach (Hash::extract($user['Playlist'], '{n}.songids') as $k => $i) {
                $user['Playlist'][$k]['bands'] = array_unique(Hash::extract(json_decode($i, true), '{n}.band'));
            }
        }

        $this->set('data', $user);
    }

    public function unfav($id)
    {
        $this->autoRender = false;
        $this->response->type('json');

        if ($this->Session->read('user.logged') !== TRUE) return json_encode(false);
        if (!$id) return json_encode(false);

        $this->loadModel('Band');
        $band = $this->Band->find('first', array(
            'fields' => array('Band.*'),
            'conditions' => array('Band.id' => $id),
            'contain' => false
        ));
        if (!$band) return json_encode(false);

        $this->loadModel('Favorite');
        $id = $this->Favorite->find('first', array(
            'fields' => array('Favorite.*'),
            'conditions' => array(
                'Favorite.user_id' => $this->Session->read('user.id'),
                'Favorite.band_id' => $id),
        ));

        if (!$id) return json_encode(true);

        $del = $this->Favorite->delete($id['Favorite']['id']);
        if ($del) {
            $favs = $this->Session->read('user.favs');
            $pos = array_search($band['Band']['band'], $favs);
            unset($favs[$pos]);
            $this->Session->write('user.favs', $favs);

            return json_encode(true);
        }

        return json_encode(false);
    }

    public function favorite($id)
    {
        $this->autoRender = false;
        $this->response->type('json');

        if ($this->Session->read('user.logged') != TRUE) return json_encode(false);
        if (!$id) return json_encode(false);

        $this->loadModel('Band');
        $band = $this->Band->find('first', array(
            'fields' => array('Band.*'),
            'conditions' => array('Band.id' => $id),
            'contain' => false
        ));
        if (!$band) return json_encode(false);

        $this->loadModel('Favorite');
        if ($this->Favorite->hasAny(array(
            'Favorite.user_id' => $this->Session->read('user.id'),
            'Favorite.band_id' => $id))
        ) return json_encode(true);

        $data = array(
            'user_id' => $this->Session->read('user.id'),
            'band_id' => $id
        );

        $save = $this->User->Favorite->save($data);

        if ($save) {
            $favs = $this->Session->read('user.favs');
            $favs[] = $band['Band']['band'];
            $this->Session->write('user.favs', $favs);
            return json_encode(true);
        }

        return json_encode(false);
    }

    public function login()
    {
        if ($this->Session->read('user.logged') === TRUE) $this->redirect(array('controller' => 'bands', 'action' => 'topten'));

        if ($this->request->is('post') || $this->request->is('put')) {

            $this->AttemptLogin->check(15, $this->request->data['User']['username']);

            $login = $this->User->login($this->request->data['User']['username'], $this->request->data['User']['password']);
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

                if ($this->request->data['User']['rememberMe']) {
                    $remember = array(
                        'user' => $this->request->data['User']['username'],
                        'pass' => $this->request->data['User']['password'],
                    );
                    $this->Cookie->write('rememberMe', $remember, $encrypt = false, $expires = null);
                }

                $this->Admin->setFlashSuccess('Welcome back!');
                $this->redirect(array('controller' => 'bands', 'action' => 'topten'));

            } else {
                $this->Admin->setFlashError('Incorrect username/password');
            }
        }
    }

    public function logout()
    {
        $this->Session->destroy();
        $this->Cookie->delete('rememberMe');
        $this->Admin->setFlashSuccess('Logged out');
        $this->redirect(array('controller' => 'users', 'action' => 'login'));
    }

    /**
     * admin_index method
     *
     * @return void
     */
    public function admin_index()
    {
        $this->User->recursive = 0;
        try {
            $records = $this->paginate('User');
        } catch (NotFoundException $e) {
            $this->Admin->setFlashInfo('<strong>Sin Resultados para esa página!</strong> Ahora estamos en la primera página.');
            $this->redirect(Set::merge(Set::get($this->request->params, 'named'), array('page' => 1)));
        }
        $this->set('users', $records);
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
        if (!$this->User->exists($id)) {
            throw new NotFoundException('El registro no existe');
        }
        $this->set('user', $this->User->find('first', array('conditions' => array('User.id' => $id))));
    }

    /**
     * admin_add method
     *
     * @return void
     */
    public function admin_add()
    {
        if ($this->request->is('post')) {
            $this->User->create();
            if ($this->User->save($this->request->data)) {
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
        if (!$this->User->exists($id)) {
            throw new NotFoundException('Registro inválido');
        }
        if ($this->request->is('post') || $this->request->is('put')) {
            if ($this->User->save($this->request->data)) {
                $this->Admin->setFlashSuccess('El registro ha sido guardado');
                $this->redirect($this->getPreviousUrl());
            } else {
                $this->Admin->setFlashError('El registro no se pudo guardar. Por favor intenta más tarde.');
            }
        } else {
            $this->request->data = $this->User->find('first', array('conditions' => array('User.id' => $id)));
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
        if (!$this->User->exists($id)) {
            throw new NotFoundException('Registro inválido');
        }
        if ($this->request->is('post')) {
            $this->User->id = $id;
            if ($this->User->delete()) {
                $this->Admin->setFlashSuccess('El registro ha sido borrado');
                $this->redirect($this->getPreviousUrl());
            }
            $this->Admin->setFlashError('El registro no se pudo borrar. Por favor intenta más tarde.');
        }
        $this->set('user', $this->User->read(NULL, $id));
    }
}
