<?php
App::uses('AppModel', 'Model');

/**
 * Songid Model
 *
 * @property Band $Band
 */
class Songid extends AppModel
{
    /**
     * Singular name for collection
     *
     * @var string
     */
    public $singularName = 'Songid';

    /**
     * Plural name for collection
     *
     * @var string
     */
    public $pluralName = 'Songids';

    /**
     * name mapping for fields
     *
     * @var array
     */
    public $fieldNames = array(
        'id' => 'id',
        'band_id' => 'band_id',
        'name' => 'nombre',
        'songid' => 'songid',
        'created' => 'creado',
    );
    /**
     * Display field
     *
     * @var string
     */
    public $displayField = 'name';


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
        '*' => array('type' => 'like', 'field' => array('band_id', 'name', 'songid')),
        'band_id' => array('type' => 'like'),
        'name' => array('type' => 'like'),
        'songid' => array('type' => 'like'),
    );

    /**
     * belongsTo associations
     *
     * @var array
     */
    public $belongsTo = array(
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
        'band_id' => array(
            'isForeignKey' => array(
                'rule' => array('isForeignKey'),
                'message' => 'El registro asociado no existe',
                'allowEmpty' => false,
                'required' => true,
                'last' => true,
                //'on' => 'create',
            ),
        ),
        'name' => array(
            'notempty' => array(
                'rule' => array('notempty'),
                'message' => 'Completá el campo',
                'allowEmpty' => false,
                'required' => true,
                'last' => true,
                //'on' => 'create',
            ),
        ),
        'songid' => array(
            'numeric' => array(
                'rule' => array('numeric'),
                'message' => 'Ingresá un número',
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
    );
}
