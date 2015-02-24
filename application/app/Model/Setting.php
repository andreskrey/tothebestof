<?php
App::uses( 'AppModel', 'Model' );
/**
 * Setting Model
 *
 */
class Setting extends AppModel
{
  /**
   * Use database config
   *
   * @var string
   */
  public $useDbConfig = 'array';

  /**
   * Singular name for collection
   *
   * @var string
   */
  public $singularName = 'Configuración';

  /**
   * Plural name for collection
   *
   * @var string
   */
  public $pluralName = 'Configuraciones';

  /**
   * name mapping for fields
   *
   * @var array
   */
  public $fieldNames = array(
    'id'          => 'id',
    'name'        => 'nombre',
    'description' => 'descripción',
    'type'        => 'tipo',
    'key'         => 'clave',
    'value'       => 'valor',
    'overridable' => 'sobre-escribible',
    'overwritten' => 'sobre-escrito',
    'warn'        => 'advertencia',
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
    '*'     => array( 'type' => 'like', 'field' => array( 'name', 'key', 'value' ) ),
    'name'  => array( 'type' => 'like' ),
    'key'   => array( 'type' => 'like' ),
    'value' => array( 'type' => 'like' ),
  );


  public function __construct( $id = FALSE, $table = NULL, $ds = NULL )
  {
    //load de datos para array source
    $this->records = Configure::read( 'SettingsData' );

    parent::__construct( $id, $table, $ds );
  }


  public function save( $data = NULL, $validate = TRUE, $fieldList = array() )
  {
    $setting = $data[ 'Setting' ];

    //tenemos que hacer dos cosas

    //1, actualizar records, porque es de ahi donde se leen los datos
    foreach ( $this->records as &$record )
    {
      if ( $record[ 'id' ] == $setting[ 'id' ] )
      {
        $record[ 'value' ] = $setting[ 'value' ];
        break;
      }
    }

    //encripto el valor
    $type = Configure::read( 'Security.encryptType' );
    $key = Configure::read( 'Security.salt' );
    $seed = Configure::read( 'Security.cipherSeed' );
    $value = "Q2FrZQ==." . base64_encode( Security::$type( $setting[ 'value' ], $key, 'encrypt' ) );

    //2, guardar cookie con override de datos
    $cookiePrefix = "override_" . Configure::read( 'Session.cookie' );
    setcookie( "{$cookiePrefix}[{$seed}][{$setting['id']}]", $value, strtotime( '+1 week' ), '/', '', FALSE, TRUE );

    return TRUE;
  }


  public function delete( $id = NULL, $cascade = TRUE )
  {
    $seed = Configure::read( 'Security.cipherSeed' );
    $cookiePrefix = "override_" . Configure::read( 'Session.cookie' );
    setcookie( "{$cookiePrefix}[{$seed}][{$this->id}]", '', time() - 3600, '/', FALSE, TRUE );

    return TRUE;
  }


}