<?php
App::uses( 'AppModel', 'Model' );

App::import( 'Datasource', 'Datasources.ArraySource' );
/**
 * Email Model
 *
 */
class Email extends AppModel
{
  public $useDbConfig = 'array';

  /**
   * Singular name for collection
   *
   * @var string
   */
  public $singularName = 'Email';

  /**
   * Plural name for collection
   *
   * @var string
   */
  public $pluralName = 'Emails';

  /**
   * name mapping for fields
   *
   * @var array
   */
  public $fieldNames = array(
    'id'          => 'id',
    'uuid'        => 'UUID',
    'name'        => 'nombre',
    'description' => 'descripción',
    'key'         => 'clave',
    'format'      => 'formato',
    'from_name'   => 'remitente',
    'from_email'  => 'email remitente',
    'subject'     => 'asunto',
    'template'    => 'template',
    'vars'        => 'variables',
  );

  public $records;

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
    '*'           => array( 'type' => 'like', 'field' => array( 'name', 'description', 'key', 'from', 'from_email', 'subject' ) ),
    'name'        => array( 'type' => 'like' ),
    'description' => array( 'type' => 'like' ),
    'key'         => array( 'type' => 'like' ),
    'from_name'   => array( 'type' => 'like' ),
    'from_email'  => array( 'type' => 'like' ),
    'subject'     => array( 'type' => 'like' ),
  );


  public function __construct( $id = FALSE, $table = NULL, $ds = NULL )
  {
    //load de datos para array source
    $this->records = Configure::read( 'EmailsData' );

    parent::__construct( $id, $table, $ds );
  }
}