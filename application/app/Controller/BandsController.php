<?php
App::uses('AdminAppController', 'Controller');

/**
 * Bands Controller
 *
 * @property Band $Band
 * @property Search.PrgComponent $Search.Prg
 * @property AdminComponent $Admin
 */
class BandsController extends AdminAppController
{
    /* inject */
    /**
     * Paginate
     *
     * @var array
     */
    public $paginate = array(
        'Band' => array(
            'order' => array('Band.id' => 'asc'),
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
    public $components = array('Search.Prg', 'Admin', 'Cookie');

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
        $this->breadcrumbControllerNames['bands'] = $this->Band->pluralName;

        parent::beforeFilter();

        if ($this->admin) $this->set('model', $this->Band);

        $this->Auth->allow('topten', 'genre', 'ajax', 'clearCookie', 'random', 'testarea');

        $this->Security->unlockedActions = array('ajax');
    }

    public function saveBand($data = NULL)
    {
        if ($this->Band->Songid->saveAssociated($data)) {
            debug("ok guardado songid");
            return TRUE;
        } else {
            debug("Error al guardar id " . $data['songid']);
            debug($this->Band->Songid->validationErrors);
            return FALSE;
        }
    }

    public function hit($id, $name)
    {
        $data = array(
            'band_id' => $id,
            'name' => $name,
        );
        $this->Band->Hit->save($data);
    }

    public function parse($string = NULL)
    {
        if (strpos($string, '/!')) {
            $band = substr($string, 0, strpos($string, '/!'));
            $options = explode('/!', substr($string, strpos($string, '/!')));
            unset($options[0]);
        } else {
            $band = $string;
            $options = NULL;
        }

        $data = array(
            'band' => $band,
            'options' => $options,
        );

        return $data;
    }

    public function topten($band = NULL, $shuffle = FALSE, $tryagain = FALSE)
    {

        if ($band === NULL) {

            //No se pasaron parametros
            $this->redirect(array('controller' => 'pages', 'action' => 'home'));

        } else {

            $result = $this->parse($band);

            $band = $result['band'];
            $options = $result['options'];

            if ($options) {
                foreach ($options as $k => $v) {
                    switch ($v) {
                        case 'tryagain':
                            $tryagain = TRUE;
                            break;
                        case 'shuffle':
                            $shuffle = TRUE;
                            break;
                    }
                }
            }

            if ($this->Session->read('user.logged') === TRUE) {
                $contain = array(
                    'Songid',
                    'Favorite.user_id = "' . $this->Session->read('user.id') . '"'
                );
                $result = $this->_searchCache($band, $contain, $options);

            } else {
                $contain = array('Songid');
                $model = $this;
                if ($tryagain) Cache::delete(Inflector::slug($band . '_' . serialize($options)), 'bands');

                $result = Cache::remember(Inflector::slug($band . '_' . serialize($options)), function () use ($model, $band, $options, $contain) {
                    return $model->_searchCache($band, $contain, $options);
                }, 'bands');

                if ($shuffle) Cache::delete(Inflector::slug($band . '_' . serialize($options)), 'bands');
            }

            if ($result['sucess'] === TRUE) {
                $this->hit($result['data']['id'], $result['data']['artist_name']);
                $this->_setCookie($result['data']['artist_name']);
            }

            $this->set('data', $result['data']);

        }
    }

    public function _searchCache($band, $contain, $options)
    {
        // Busqueda en cache
        $cache = $this->Band->find('first', array(
            'fields' => array('Band.*'),
            'conditions' => array('Band.band' => $band),
            'contain' => $contain,
        ));

        if (!empty($cache)) {
            $result = $this->cached($cache, $options);
        } else {
            $result = $this->nonCached($band, $options);
        }

        $result['data']['favorite'] = FALSE;
        if (!empty($cache['Favorite'])) $result['data']['favorite'] = TRUE;

        return $result;
    }

    public function cached($cache = NULL, $options = NULL, $shuffle = FALSE, $tryagain = FALSE)
    {
        $amount = 9;
        $count = count($cache['Songid']);
        if ($options) {
            foreach ($options as $k => $v) {
                switch ($v) {
                    case 'more':
                        $amount = 19;
                        break;
                    case 'shuffle':
                        $shuffle = TRUE;
                        break;
                    case 'tryagain':
                        $tryagain = TRUE;
                        break;
                }
            }
        }

        // Si el registro es mas viejo de una semana, se vuelve a pedir
        if (strtotime($cache['Band']['created']) < strtotime(date('r', strtotime('-1 week'))) || $tryagain) {

            // band too old
            $info = $this->Band->getBandinfo($cache['Band']['band']);

            if ($info['success'] === FALSE) {
                return $info['data'];
            }

            // Request top ten de la banda
            $topten = $this->Band->getTopten($cache['Band']['band']);

            if ($topten['success'] === FALSE) {
                return $topten['data'];
            }

            // Guardar en cache

            $topten_json = array();
            foreach ($topten['data']['lfm']['toptracks']['track'] as $i => $f) {
                $topten_json[] = $f['name'];
            }

            if (count($info['data']['lfm']['artist']['similar']['artist']) > 3) {
                $related_json = array();

                $counter = 0;
                foreach ($info['data']['lfm']['artist']['similar']['artist'] as $i) {
                    if ($counter >= 6) break;

                    $related_search = $this->Band->find('first', array(
                        'fields' => array('Band.*'),
                        'conditions' => array('Band.band' => $i['name']),
                        'contain' => false
                    ));

                    if ($related_search) {
                        $related_json[] = array(
                            'band' => $i['name'],
                            'bio' => $related_search['Band']['bio'],
                        );
                    } else {
                        $related_json[] = array(
                            'band' => $i['name'],
                            'bio' => NULL,
                        );
                    }
                    $counter++;
                }
            } else {
                $related_json = NULL;
            }

            if (count($info['data']['lfm']['artist']['tags']['tag']) > 3) {
                $genre_json = array();
                $counter = 0;

                foreach ($info['data']['lfm']['artist']['tags']['tag'] as $i) {
                    if ($counter >= 3) {
                        break;
                    }
                    $genre_json[] = array(
                        $i['name'],
                    );
                    $counter++;
                }

            } else {
                $genre_json = NULL;
            }


            $data = array(
                'id' => $cache['Band']['id'],
                'band' => $info['data']['lfm']['artist']['name'],
                'bio' => $info['data']['lfm']['artist']['bio']['summary'],
                'pic' => $info['data']['lfm']['artist']['image'][4]['@'],
                'topten' => json_encode($topten_json),
                'genre' => json_encode($genre_json),
                'related' => json_encode($related_json),
                'hits' => $cache['Band']['hits'] + 1,
                'created' => date('Y-m-d H:i:s'),
            );

            if ($this->Band->save($data)) {
                debug("ok guardado banda actualizado cache");
            } else {
                debug("Error al guardar, intente más tarde.");
                debug($this->Band->validationErrors);
            }
        }

        $idsplayer = '';
        $counter = 0;
        foreach ($cache['Songid'] as $i => $f) {
            if ($counter > $amount) {
                break;
            } else {

                if (strtotime($f['created']) < strtotime(date('r', strtotime('-1 week'))) || $tryagain) {
                    //  songid too old

                    $songids = $this->Band->getSongids($cache['Band']['band'], $f['name']);
                    $search = $this->Band->searchSongids($f['name'], $songids['data']['result']['songs']);

                    if ($search === NULL) {

                        $counter--;

                        //A la bosta con el registro viejo que no se encuentra mas
                        if ($this->Band->Songid->delete($f['id'])) {
                            $count--;
                            debug("deleteado!");
                        } else {
                            debug("Error al borrar, WTF");
                            debug($this->Band->Songid->validationErrors);
                        }

                    } else {

                        $f['songid'] = $songids['data']['result']['songs'][$search]['SongID'];
                        debug($f['name'] . " cache viejo, actualizado");
                        $data = array(

                            'id' => $f['id'],
                            'band_id' => $f['band_id'],
                            'name' => $f['name'],
                            'songid' => $songids['data']['result']['songs'][$search]['SongID'],
                            'created' => date('Y-m-d H:i:s'),

                        );

                        $this->saveBand($data);

                    }
                }
                $idsplayer = $idsplayer . ',' . $f['songid'];
                $counter++;
            }
        }

        if ($shuffle) $idsplayer = $this->_shuffle($idsplayer);

        debug("Cached result!");

        $data = array(
            'sucess' => TRUE,
            'data' => array(
                'id' => $cache['Band']['id'],
                'idsplayer' => $idsplayer,
                'artist_name' => $cache['Band']['band'],
                'bio' => $cache['Band']['bio'],
                'pic' => $cache['Band']['pic'],
                'related' => json_decode($cache['Band']['related']),
                'title_for_layout' => 'BEST OF ' . strtoupper($cache['Band']['band']),
                'count' => $count,
            ),
        );

        return $data;
    }

    public function nonCached($band = NULL, $options = NULL, $shuffle = FALSE)
    {
        //no hay cache

        if ($options) {
            foreach ($options as $k => $v) {
                switch ($v) {
                    case 'shuffle':
                        $shuffle = TRUE;
                        break;
                }
            }
        }

        $info = $this->Band->getBandinfo($band);

        if ($info['success'] === FALSE) {
            return $info;
        }

        if ($band != $info['data']['lfm']['artist']['name']) {
            $exists = $this->Band->find('first', array(
                'fields' => array('Band.*'),
                'conditions' => array('Band.band' => $info['data']['lfm']['artist']['name']),
            ));

            if ($exists) return $this->cached($exists);

        }
        $topten = $this->Band->getTopten($info['data']['lfm']['artist']['name']);

        if (!$topten['success']) return $topten['data'];

        // Guardar en cache

        $topten_json = array();

        foreach ($topten['data']['lfm']['toptracks']['track'] as $i => $f) {
            $topten_json[] = $f['name'];
        }

        $genre_json = NULL;
        if (count($info['data']['lfm']['artist']['tags']['tag']) > 3) {
            $counter = 0;
            foreach ($info['data']['lfm']['artist']['tags']['tag'] as $i) {
                if ($counter >= 3) break;
                $genre_json[] = array($i['name'],);
                $counter++;
            }
        }

        $related_json = NULL;

        if (count($info['data']['lfm']['artist']['similar']['artist']) > 3) {
            $related_json = array();
            $counter = 0;

            foreach ($info['data']['lfm']['artist']['similar']['artist'] as $i) {
                if ($counter >= 6) break;

                $related_search = $this->Band->find('first', array(
                    'fields' => array('Band.*'),
                    'conditions' => array('Band.band' => $i['name']),
                    'contain' => false
                ));

                if ($related_search) {
                    $related_json[] = array(
                        'band' => $i['name'],
                        'bio' => $related_search['Band']['bio'],
                    );
                } else {
                    $related_json[] = array(
                        'band' => $i['name'],
                        'bio' => NULL,
                    );
                }
                $counter++;
            }
        }

        $data = array(
            'band' => $info['data']['lfm']['artist']['name'],
            'bio' => $info['data']['lfm']['artist']['bio']['summary'],
            'pic' => $info['data']['lfm']['artist']['image'][4]['@'],
            'topten' => json_encode($topten_json),
            'genre' => json_encode($genre_json),
            'related' => json_encode($related_json),
            'hits' => '1',
        );

        $save = $this->Band->save($data);
        if ($save) {
            debug("ok guardado banda");
        } else {
            debug("Error al guardar, intente más tarde.");
            debug($this->Band->validationErrors);
        }

        $songids = $this->Band->getSongids($band);

        if (!$songids['success']) return $songids['data'];

        $idsplayer = '';
        $counter = '0';
        $enough = '0';
        $count = 0;

        foreach ($topten['data']['lfm']['toptracks']['track'] as $i => $f) {
            if ($counter > 19) break;

            $search = $this->Band->searchSongids($f['name'], $songids['data']['result']['songs']);

            if ($search === NULL) {

                $retry = $this->Band->getSongids($info['data']['lfm']['artist']['name'], $f['name']);

                debug($f['name'] . " dio null, retying");
                $search = $this->Band->searchSongids($f['name'], $retry['data']['result']['songs']);

                if ($search === NULL) {

                    debug($f['name'] . " no hay caso, da null igual");

                } else {

                    $counter++;
                    $count++;
                    debug($f['name'] . " found after retry!");

                    $data = array(

                        'band_id' => $this->Band->id,
                        'name' => $f['name'],
                        'songid' => $retry['data']['result']['songs'][$search]['SongID'],

                    );

                    if ($this->saveBand($data)) {
                        $enough++;
                        if ($enough < 11) {
                            $idsplayer = $idsplayer . ',' . $retry['data']['result']['songs'][$search]['SongID'];
                        }
                    }
                }

            } else {
                $count++;
                $counter++;

                $data = array(
                    'band_id' => $this->Band->id,
                    'name' => $f['name'],
                    'songid' => $songids['data']['result']['songs'][$search]['SongID'],
                );
                if ($this->saveBand($data)) {
                    $enough++;
                    if ($enough < 11) {
                        $idsplayer = $idsplayer . ',' . $songids['data']['result']['songs'][$search]['SongID'];
                    }
                }


            }
        }

        if ($shuffle) $idsplayer = $this->_shuffle($idsplayer);

        $data = array(
            'sucess' => TRUE,
            'data' => array(
                'id' => $save['Band']['id'],
                'idsplayer' => $idsplayer,
                'artist_name' => $info['data']['lfm']['artist']['name'],
                'bio' => $info['data']['lfm']['artist']['bio']['summary'],
                'pic' => $info['data']['lfm']['artist']['image'][4]['@'],
                'related' => json_decode(json_encode($related_json)),
                'title_for_layout' => 'BEST OF ' . strtoupper($info['data']['lfm']['artist']['name']),
                'count' => $count,
            ),
        );

        return $data;
    }


    public function _setCookie($band)
    {
        $history = $this->Cookie->read('history');
        $user = $this->Session->read('user.username');
        if (!$user) $user = 'default';

        if (!empty($history[$user]['bands'])) {
            $search = array_search($band, $history[$user]['bands']);
            if ($search !== FALSE) {
                $to_restore = $history[$user]['bands'][$search];
                unset($history[$user]['bands'][$search]);
                $history[$user]['bands'][] = $to_restore;
                $this->Cookie->write('history', $history, $encrypt = false, $expires = null);
                return;
            }
        }

        $history[$user]['bands'][] = $band;
        if (count($history[$user]['bands']) >= 11) array_shift($history[$user]['bands']);

        $this->Cookie->write('history', $history, $encrypt = false, $expires = null);
    }

    public function clearCookie()
    {
        $this->autoRender = false;
        $this->response->type('json');

        $user = $this->Session->read('user.username');
        if (!$user) $user = 'default';

        $this->Cookie->write('history.' . $user, null);
        return json_encode(true);
    }

    public function _shuffle($idsplayer)
    {
        $array = explode(',', $idsplayer);
        shuffle($array);
        return implode(',', $array);
    }

    public function genre($genre = NULL)
    {
        if (!$genre) $this->redirect(array('controller' => 'pages', 'action' => 'home'));

        $bands = $this->Band->find('all', array(
            'fields' => array('Band.*'),
            'conditions' => array('Band.genre LIKE' => '%"' . $genre . '"%'),
            'limit' => 30,
            'order' => 'rand()',
        ));

        $data = array('genre' => strtoupper($genre));

        if (!$bands || count($bands) < 10) {
            $data['success'] = FALSE;
            $data['pic'] = Router::url('/img/what.jpg', true);
        } else {
            $data['success'] = TRUE;
            $data['idsplayer'] = NULL;
            foreach ($bands as $i) {
                if (isset($i['Songid'][0]['songid'])) {
                    $data['idsplayer'][] = $i['Songid'][0]['songid'];
                    $data['bands'][] = $i['Band']['band'];
                }
            }

            foreach ($bands as $i) {
                if (!empty($i['Band']['pic'])) {
                    $data['pic'] = $i['Band']['pic'];
                    break;
                }
            }
            if (empty($data['pic'])) $data['pic'] = Router::url('/img/what.jpg', true);
        }

        $this->set('data', $data);

    }

    public function ajax()
    {
        if (!$this->request->is('post')) throw new MethodNotAllowedException;

        switch (key($this->request->data)) {
            case 'suggestBand':
                $data = $this->_suggestBand($this->data['suggestBand']);
                break;

            case 'suggestGenre':
                $data = $this->_suggestGenre($this->data['suggestGenre']);
                break;

            default:
                $data = array(
                    'result' => 'error',
                    'error' => 'No conozco esa funcion',
                );
                break;
        }

        $this->autoRender = false;
        $this->response->type('json');
        $this->response->body(json_encode($data));

    }

    public function _suggestBand($data)
    {
        $model = $this;
        return Cache::remember($data, function () use ($model, $data) {
            $guess = $model->Band->find('all', array(
                'fields' => array('Band.band'),
                'conditions' => array('Band.band LIKE' => $data . '%'),
                'limit' => 10,
                'contain' => false,
            ));

            $results = array();

            if ($guess) {
                foreach (Hash::extract($guess, '{n}.Band.band') as $i) {
                    $results[] = array(
                        'title' => $i,
                        'url' => Router::url(array('controller' => 'bands', 'action' => 'topten', $i), true),
                    );
                }
            }

            return array(
                'result' => 'ok',
                'data' => array(
                    'results' => $results
                ),
            );
        }, 'guess');
    }

    public function _suggestGenre($data)
    {
        $model = $this;
        return Cache::remember($data, function () use ($model, $data) {
            $genre = $model->Band->find('all', array(
                'fields' => array('Band.genre'),
                'conditions' => array('Band.genre LIKE' => '%' . $data . '%'),
                'limit' => 10,
                'contain' => false,
            ));

            $results = array();
            $catch = array();
            foreach (Hash::extract($genre, '{n}.Band.genre') as $i) {
                foreach (json_decode($i) as $o) {
                    if (strpos($o[0], $data) !== false) {
                        if (!in_array($o, $catch)) {
                            $catch[] = $o;
                            $results[] = array(
                                'title' => $o[0],
                                'url' => Router::url(array('controller' => 'bands', 'action' => 'genre', $o[0]), true)
                            );
                        }
                    }
                }
            }

            return array(
                'result' => 'ok',
                'data' => array(
                    'results' => $results
                ),
            );
        }, 'genreguess');
    }

    public function random()
    {
        $this->autoRender = false;

        $random = $this->Band->find('all', array(
            'fields' => array('Band.*'),
            'limit' => 20,
            'order' => 'rand()'
        ));

        foreach ($random as $i) {
            if (count($i['Songid']) > 11) $this->redirect(array('action' => 'topten', $i['Band']['band']), array('status' => 302));
        }

        $this->redirect(array('action' => 'random'), array('status' => 302));

    }

    public function testarea()
    {
        $random = $this->Band->find('all', array(
            'fields' => array('Band.*'),
            'limit' => 50,
            'order' => 'rand()'
        ));
    }


    /**
     * admin_index method
     *
     * @return void
     */
    public function admin_index()
    {
        $this->Band->recursive = 0;
        try {
            $records = $this->paginate('Band');
        } catch (NotFoundException $e) {
            $this->Admin->setFlashInfo('<strong>Sin Resultados para esa página!</strong> Ahora estamos en la primera página.');
            $this->redirect(Set::merge(Set::get($this->request->params, 'named'), array('page' => 1)));
        }
        $this->set('bands', $records);
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
        if (!$this->Band->exists($id)) {
            throw new NotFoundException('El registro no existe');
        }
        $this->set('band', $this->Band->find('first', array('conditions' => array('Band.id' => $id))));
    }

    /**
     * admin_add method
     *
     * @return void
     */
    public function admin_add()
    {
        if ($this->request->is('post')) {
            $this->Band->create();
            if ($this->Band->save($this->request->data)) {
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
        if (!$this->Band->exists($id)) {
            throw new NotFoundException('Registro inválido');
        }
        if ($this->request->is('post') || $this->request->is('put')) {
            if ($this->Band->save($this->request->data)) {
                $this->Admin->setFlashSuccess('El registro ha sido guardado');
                $this->redirect($this->getPreviousUrl());
            } else {
                $this->Admin->setFlashError('El registro no se pudo guardar. Por favor intenta más tarde.');
            }
        } else {
            $this->request->data = $this->Band->find('first', array('conditions' => array('Band.id' => $id)));
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
        if (!$this->Band->exists($id)) {
            throw new NotFoundException('Registro inválido');
        }
        if ($this->request->is('post')) {
            $this->Band->id = $id;
            if ($this->Band->delete()) {
                $this->Admin->setFlashSuccess('El registro ha sido borrado');
                $this->redirect($this->getPreviousUrl());
            }
            $this->Admin->setFlashError('El registro no se pudo borrar. Por favor intenta más tarde.');
        }
        $this->set('band', $this->Band->read(NULL, $id));
    }
}
