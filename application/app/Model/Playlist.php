<?php
App::uses('AppModel', 'Model');

/**
 * Playlist Model
 *
 */
class Playlist extends AppModel
{
    /**
     * Singular name for collection
     *
     * @var string
     */
    public $singularName = 'Playlist';

    /**
     * Plural name for collection
     *
     * @var string
     */
    public $pluralName = 'Playlists';

    /**
     * name mapping for fields
     *
     * @var array
     */
    public $fieldNames = array(
        'id' => 'id',
        'playlist_uuid' => 'UUID',
        'songids' => 'Songids',
        'created' => 'Creado',
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
        '*' => array('type' => 'like', 'field' => array('playlist_uuid', 'songids')),
        'playlist_uuid' => array('type' => 'like'),
        'songids' => array('type' => 'like'),
    );

    /**
     * belongsTo associations
     *
     * @var array
     */
    public $belongsTo = array(
        'User' => array(
            'className' => 'User',
            'foreignKey' => 'user_id',
        )
    );

    /**
     * Validation rules
     *
     * @var array
     */
    public $validate = array(
        'playlist_uuid' => array(
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
        'songids' => array(
            'notempty' => array(
                'rule' => array('notempty'),
                'message' => 'Completá el campo',
                'allowEmpty' => false,
                'required' => true,
                'last' => true,
                //'on' => 'create',
            ),
        ),
        'name' => array(
            'between' => array(
                'rule' => array('between', 1, 64),
                'message' => 'Between 1 to 64 characters',
                'allowEmpty' => true,
            )
        ),
    );
}
