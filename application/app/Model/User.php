<?php
App::uses('AppModel', 'Model');

/**
 * User Model
 *
 */
class User extends AppModel
{
    /**
     * Singular name for collection
     *
     * @var string
     */
    public $singularName = 'Usuario';

    /**
     * Plural name for collection
     *
     * @var string
     */
    public $pluralName = 'Usuarios';

    /**
     * name mapping for fields
     *
     * @var array
     */
    public $fieldNames = array(
        'id' => 'id',
        'username' => 'Usuario',
        'password' => 'Password',
        'created' => 'Creado',
        'modified' => 'Modificado',
    );

    /**
     * hasMany associations
     *
     * @var array
     */
    public $hasMany = array(
        'Playlist' => array(
            'className' => 'Playlist',
            'foreignKey' => 'user_id',
            'dependent' => false,
        ),
        'Favorite' => array(
            'className' => 'Favorite',
            'foreignKey' => 'user_id',
            'dependent' => false,
        )
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
        '*' => array('type' => 'like', 'field' => array('username', 'password')),
        'username' => array('type' => 'like'),
        'password' => array('type' => 'like'),
    );

    /**
     * Validation rules
     *
     * @var array
     */
    public $validate = array(
        'username' => array(
            'notempty' => array(
                'rule' => array('notempty'),
                'message' => 'Forgot something?',
                'allowEmpty' => false,
                'required' => true,
                'last' => true,
                //'on' => 'create',
            ),
            'between' => array(
                'rule' => array('between', 3, 15),
                'message' => 'Username must be between 3 to 15 characters',
                'last' => true,
            ),
            'alphaNumeric' => array(
                'rule' => array('alphaNumeric'),
                'message' => 'Username must be alphanumeric',
                'last' => true,
            ),
            'isUnique' => array(
                'rule' => array('isUnique'),
                'message' => 'Username already exists :(',
                'last' => true,
            ),
        ),
        'password' => array(
            'notempty' => array(
                'rule' => array('notempty'),
                'message' => 'Forgot something?',
                'allowEmpty' => false,
                'required' => true,
                //'on' => 'create',
            ),
            'between' => array(
                'rule' => array('between', 6, 64),
                'message' => 'Password must be between 6 to 64 characters'
            ),
        ),
        'email' => array(
            'email' => array(
                'rule' => array('email'),
                'message' => 'Valid email address only',
                'allowEmpty' => true,
                'required' => false,
                //'on' => 'create',
            ),
        ),
    );

    public function login($user = null, $pass = null)
    {
        $user = $this->find('first', array(
            'fields' => array('User.*'),
            'recursive' => 2,
            'contain' => array(
                'Favorite.Band',
                'Playlist'),
            'conditions' => array('User.username' => $user),
        ));

        if (!$user) {
            return false;
        }

        if ($user['User']['password'] == Security::hash($pass, 'sha1', true)) {
            $this->id = $user['User']['id'];
            $this->save(array('User' => array(
                'lastlogin' => date('c'),
                'modified' => false,
            )), false, array('lastlogin'));

            return $user;
        } else {
            return false;
        }
    }
}
