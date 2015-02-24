<?php
App::uses('AppModel', 'Model');

/**
 * Band Model
 *
 * @property Songid $Songid
 */
class Band extends AppModel
{
    /**
     * Singular name for collection
     *
     * @var string
     */
    public $singularName = 'Banda';

    /**
     * Plural name for collection
     *
     * @var string
     */
    public $pluralName = 'Bandas';

    /**
     * name mapping for fields
     *
     * @var array
     */
    public $fieldNames = array(
        'id' => 'id',
        'band' => 'Banda',
        'bio' => 'Bio',
        'pic' => 'Imagen',
        'topten' => 'Top ten',
        'hits' => 'Hits',
        'created' => 'creado',
    );
    /**
     * Display field
     *
     * @var string
     */
    public $displayField = 'id';


    /**
     * Behaviors
     *
     * @var array
     */
    public $actsAs = array(
        'Search.Searchable',
    );

    /**
     * Filter Arguments
     * from the Search Filter, see https://github.com/CakeDC/search for help and examples
     *
     * @var array
     */
    public $filterArgs = array(
        '*' => array('type' => 'like', 'field' => array('band', 'bio', 'pic', 'hits')),
        'band' => array('type' => 'like'),
        'bio' => array('type' => 'like'),
        'pic' => array('type' => 'like'),
        'topten' => array('type' => 'like'),
        'hits' => array('type' => 'like'),
    );


    /**
     * hasMany associations
     *
     * @var array
     */
    public $hasMany = array(
        'Songid' => array(
            'className' => 'Songid',
            'foreignKey' => 'band_id',
            'dependent' => true,
        ),
        'Favorite' => array(
            'className' => 'Favorite',
            'foreignKey' => 'band_id',
            'dependent' => false,
        ),
        'Hit' => array(
            'className' => 'Hit',
            'foreignKey' => 'band_id',
            'dependent' => false,
        )
    );

    /**
     * Validation rules
     *
     * @var array
     */
    public $validate = array(
        'band' => array(
            'notempty' => array(
                'rule' => array('notempty'),
                'message' => 'Completá el campo',
                'allowEmpty' => false,
                'required' => true,
                'last' => true,
                //'on' => 'create',
            ),
            'isUnique' => array(
                'rule' => array('isUnique'),
                'message' => 'El valor debe ser único entre todos los registros cargados',
                'last' => true,
                //'on' => 'create',
            ),
        ),
        'topten' => array(
            'notempty' => array(
                'rule' => array('notempty'),
                'message' => 'Completá el campo',
                'allowEmpty' => false,
                'required' => true,
                'last' => true,
                //'on' => 'create',
            ),
        ),
        'hits' => array(
            'numeric' => array(
                'rule' => array('numeric'),
                'message' => 'Ingresá un número',
                'allowEmpty' => false,
                'required' => true,
                'last' => true,
                //'on' => 'create',
            ),
        ),
    );


    public function searchSongids($name, $songids)
    {
        foreach ($songids as $key => $val) {
            similar_text(strtolower($val['SongName']), strtolower($name), $percent);
            if ($percent > 75) {
                return $key;
            }
        }
        return null;
    }

    public function getBandinfo($band = NULL)
    {
        App::uses('HttpSocket', 'Network/Http');
        $LastFMKey = 'YOUR LASTFM KEY HERE';

        $connect = new HttpSocket();
        $result = $connect->get('http://ws.audioscrobbler.com/2.0/', array(
            'method' => 'artist.getinfo',
            'artist' => $band,
            'api_key' => $LastFMKey,
            'autocorrect' => 1,
        ));

        if ($result->code != '200' && $result->code != '400') {

            debug("server error: " . $result->code);
            debug($result);

            $data = array(
                'success' => FALSE,
                'data' => array(
                    'artist_name' => $band,
                    'bio' => 'Something horrible happened while searching for that band. Try again in a few minutes while I clean the mess.',
                    'pic' => Router::url('/img/what.jpg', true),
                    'title_for_layout' => 'BEST OF ' . strtoupper($band),
                ));

            return $data;
        }

        $xml = Xml::toArray(Xml::build($result->body));

        if (array_key_exists("error", $xml['lfm'])) {
            switch ($xml['lfm']['error']['@code']) {
                case 6:
                    debug("no existe esa banda");
                    break;
            }
            $data = array(
                'success' => FALSE,
                'data' => array(
                    'artist_name' => $band,
                    'bio' => "Sorry, I don't have any information or songs about that band :(",
                    'pic' => Router::url('/img/what.jpg', true),
                    'title_for_layout' => 'BEST OF ' . strtoupper($band),
                ));

            return $data;
        }

        if (empty($xml['lfm']['artist']['bio']['summary'])) $xml['lfm']['artist']['bio']['summary'] = "Sorry, I don't have any info about this awesome band :(";

        $data = array(
            'success' => TRUE,
            'data' => $xml);

        return $data;

    }

    public function getTopten($band = NULL)
    {
        App::uses('HttpSocket', 'Network/Http');
        $LastFMKey = 'YOUR LASTFM KEY HERE';

        $connect = new HttpSocket();
        $result = $connect->get('http://ws.audioscrobbler.com/2.0/', array(
            'method' => 'artist.gettoptracks',
            'artist' => $band,
            'limit' => '20',
            'autocorrect' => '1',
            'api_key' => $LastFMKey,
        ));

        if ($result->code != '200' && $result->code != '400') {

            debug("server error: " . $result->code);
            debug($result);

            $data = array(
                'success' => FALSE,
                'data' => array(
                    'artist_name' => $band,
                    'bio' => 'Something horrible happened while searching for that band. Try again in a few minutes while I clean the mess.',
                    'pic' => Router::url('/img/what.jpg', true),
                    'title_for_layout' => 'BEST OF ' . strtoupper($band),
                ));

            return $data;
        }

        $xml = Xml::toArray(Xml::build($result->body));

        if ($xml['lfm']['toptracks']['@total'] == '0') {

            debug("Cero resultados");
            $data = array(
                'success' => FALSE,
                'data' => array(
                    'artist_name' => $band,
                    'bio' => "Sorry, I don't have any information or songs about that band :(",
                    'pic' => Router::url('/img/what.jpg', true),
                    'title_for_layout' => 'BEST OF ' . strtoupper($band),
                ));

            return $data;
        }

        $data = array(
            'success' => TRUE,
            'data' => $xml,
        );

        return $data;
    }


    public function getSongids($band = NULL, $song = NULL)
    {

        if ($song != NULL) $band = $band . ' ' . $song;

        App::uses('HttpSocket', 'Network/Http');
        $gskey = 'YOUR GS KEY HERE';

        $connect = new HttpSocket();

        $data = array(
            'method' => 'getSongSearchResults',
            'header' => array(
                'wsKey' => 'bestof'
            ),
            'parameters' => array(
                'query' => $band,
                'country' => '',
                'limit' => '50',
                'offset' => ''
            ),
        );
        $data = json_encode($data);
        $md5 = hash_hmac('md5', $data, $gskey);


        //Comentado porque GS se pone la gorra re rapido. Capaz despues del dev lo vuelvo a habilitar (TODO) y que me avise por mail para ver cada cuanto pasa.
//        if (Configure::read('Config.environment') == 'local' || Configure::read('Config.environment') == 'staging') {

        $XClientIP = array(
            'X-Client-IP' => '' . mt_rand(1, 255) . '.' . mt_rand(1, 255) . '.' . mt_rand(1, 255) . '.' . mt_rand(1, 255),
        );
//        } else {
//            $XClientIP = array(
//                'X-Client-IP' => $_SERVER['REMOTE_ADDR'],
//            );
//        }

        $headers = array(
            'header' => $XClientIP,
        );

        $result = $connect->post('http://api.grooveshark.com/ws/3.0/?sig=' . $md5, $data, $headers);
        $json = json_decode($result, true);

        if (array_key_exists("errors", $json)) {
            switch ($json['errors'][0]['code']) {
                case 11:
                    debug("easy tiger, you are making waaaay too many requests");
                    break;
            }

            $data = array(
                'success' => FALSE,
                'data' => array(
                    'artist_name' => $band,
                    'bio' => "Something horrible happened while searching for that band. Try again in a few minutes while I clean the mess.",
                    'pic' => Router::url('/img/what.jpg', true),
                    'title_for_layout' => 'BEST OF ' . strtoupper($band),
                ));

            return $data;
        }

        $data = array(
            'success' => TRUE,
            'data' => $json,
        );

        return $data;
    }
}
