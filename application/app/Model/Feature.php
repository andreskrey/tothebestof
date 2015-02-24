<?php
App::uses('AppModel', 'Model');
/**
 * Feature Model
 *
 */
class Feature extends AppModel
{
  /**
	 * Singular name for collection
	 *
	 * @var string
	 */
	public $singularName = 'Feature';

  /**
	 * Plural name for collection
	 *
	 * @var string
	 */
	public $pluralName = 'Features';

  /**
   * name mapping for fields
   *
   * @var array
   */
  public $fieldNames = array(
		'id' => 'id',
		'description' => 'Descripcion',
		'by' => 'By',
		'votes' => 'Votos',
		'status' => 'Estado',
		'enabled' => 'Habilitado',
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
		'*' => array( 'type' => 'like', 'field' => array( 'description', 'by', 'votes', 'status' ) ),
		'description' => array( 'type' => 'like' ),
		'by' => array( 'type' => 'like' ),
		'votes' => array( 'type' => 'like' ),
		'status' => array( 'type' => 'like' ),
	);

	/**
	 * Validation rules
	 *
	 * @var array
	 */
	public $validate = array(
		'description' => array(
			'notempty' => array(
				'rule' => array('notempty'),
				'message' => 'Completá el campo',
				'allowEmpty' => false,
				'required' => true,
				'last' => true, 
				//'on' => 'create', 
			),
		),
		'enabled' => array(
			'boolean' => array(
				'rule' => array('boolean'),
				'message' => 'Valor incorrecto',
				'allowEmpty' => false,
				'required' => true,
				'last' => true, 
				//'on' => 'create', 
			),
		),
	);
}
