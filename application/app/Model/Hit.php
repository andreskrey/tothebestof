<?php
App::uses('AppModel', 'Model');
/**
 * Hit Model
 *
 * @property Band $Band
 */
class Hit extends AppModel
{
  /**
	 * Singular name for collection
	 *
	 * @var string
	 */
	public $singularName = 'Hit';

  /**
	 * Plural name for collection
	 *
	 * @var string
	 */
	public $pluralName = 'Hits';

  /**
   * name mapping for fields
   *
   * @var array
   */
  public $fieldNames = array(
		'id' => 'id',
		'band_id' => 'band_id',
		'name' => 'nombre',
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
		'*' => array( 'type' => 'like', 'field' => array( 'band_id', 'name' ) ),
		'band_id' => array( 'type' => 'like' ),
		'name' => array( 'type' => 'like' ),
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
	);
}
