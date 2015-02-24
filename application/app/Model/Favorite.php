<?php
App::uses('AppModel', 'Model');

/**
 * Favorite Model
 *
 * @property User $User
 */
class Favorite extends AppModel
{
    /**
     * Singular name for collection
     *
     * @var string
     */
    public $singularName = 'Favorita';

    /**
     * Plural name for collection
     *
     * @var string
     */
    public $pluralName = 'Favoritas';

    /**
     * name mapping for fields
     *
     * @var array
     */
    public $fieldNames = array(
        'id' => 'id',
        'user_id' => 'user_id',
        'band' => 'Banda',
        'created' => 'Creado',
    );
    /**
     * Display field
     *
     * @var string
     */
    public $displayField = 'user_id';


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
        '*' => array('type' => 'like', 'field' => array('user_id', 'band_id')),
        'user_id' => array('type' => 'like'),
        'band_id' => array('type' => 'like'),
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
        ),
        'Band' => array(
            'className' => 'Band',
            'foreignKey' => 'band_id',
        )
    );
    /**
     * Validation rules
     *
     * @var array
     */
    public $validate = array(
        'user_id' => array(
            'isForeignKey' => array(
                'rule' => array('isForeignKey'),
                'message' => 'El registro asociado no existe',
                'allowEmpty' => false,
                'required' => true,
                'last' => true,
                //'on' => 'create',
            ),
        ),
        'band_id' => array(
            'notempty' => array(
                'rule' => array('notempty'),
                'message' => 'Completá el campo',
                'allowEmpty' => false,
                'required' => true,
                'last' => true,
                //'on' => 'create',
            ),
        ),
    );
}
