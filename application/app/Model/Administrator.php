<?php
App::uses( 'AppModel', 'Model' );
/**
 * Email Model
 *
 */
class Administrator extends AppModel
{
  public $useDbConfig = 'array';

  public $records;


  public function __construct( $id = FALSE, $table = NULL, $ds = NULL )
  {
    //load de datos para array source
    $this->records = Configure::read( 'AdministratorsData' );

    parent::__construct( $id, $table, $ds );
  }


}