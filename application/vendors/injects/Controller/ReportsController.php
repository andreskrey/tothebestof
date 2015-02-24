<?php
App::uses( 'AdminAppController', 'Controller' );
/**
 * @property AdminComponent $Admin
 */
class ReportsController extends AdminAppController
{
  public $helpers = array();

  public $components = array(
    'Admin'
  );

  private $reports;

  private $typeMap = array(
    'date'     => array(
      'type'                      => 'text',
      'class'                     => 'date',
      'append'                    => '<i class="icon-calendar"></i>'
    ),
    'datetime' => array(
      'type'                      => 'text',
      'class'                     => 'datetime',
      'append'                    => '<i class="icon-calendar"></i>'
    ),
    'select'   => array(
      'type' => 'select',
    )
  );

  private $separatorMap = array(
    'colon'     => ',',
    'semicolon' => ';',
    'tab'       => "\t"
  );


  function beforeFilter()
  {
    parent::beforeFilter();

    $this->reports = array(
      'examples' => array(
        'name'        => 'Reporte de Examples',
        'description' => 'Acá va una descripción base para explicar en que consiste este reporte, nada del otro mundo',
        'query'       =>
        "SELECT *
        FROM `examples`
        WHERE `examples`.`created` BETWEEN :`from` AND :`to` OR `name` = :`name`;",
        'parameters'  => array(
          'from' => array(
            'label'     => 'Desde',
            'type'      => 'date',
            'value'     => date( 'Y-m-d', strtotime( '-1 month' ) ),
            'helpBlock' => 'Fecha <em>desde</em>, incluida'
          ),
          'to'   => array(
            'label'     => 'Hasta',
            'type'      => 'datetime',
            'value'     => date( 'Y-m-d H:i:s' ),
            'helpBlock' => 'Fecha <em>hasta</em>, no incluida'
          ),
          'name' => array(
            'label'     => 'Nombre',
            'type'      => 'select',
            'value'     => '',
            'options'   => $this->request->params[ 'action' ] == 'admin_export' ? ClassRegistry::init( 'Example' )->find( 'list' ) : array(),
            'helpBlock' => 'Elejí un nombre para filtrar'
          ),
        ),
      ),
    );

  }


  public function admin_index()
  {
    $this->set( 'reports', $this->reports );
  }


  public function admin_export( $key )
  {
    if ( !$key )
    {
      throw new InvalidArgumentException();
    }
    $report = $this->reports[ $key ];

    if ( $this->request->is( 'post' ) )
    {
      App::uses( 'ConnectionManager', 'Model' );
      App::uses( 'File', 'Utility' );

      /**
       * @var $noheader
       * @var $separator
       */
      extract( $this->request->data[ 'Export' ] );

      /** @var Mysql $db */
      $db = ConnectionManager::getDataSource( 'default' );

      //query
      $query = $report[ 'query' ];
      $parameters = array_intersect_key( $this->request->data[ 'Export' ], $report[ 'parameters' ] );

      /** @var PDOStatement $result */
      $result = $db->rawQuery( $query, $parameters );

      $file = new File( TMP . String::uuid(), TRUE );

      $separator = $this->separatorMap[ $separator ];

      $firstRow = $noheader ? FALSE : TRUE;

      while ( $row = $result->fetch( PDO::FETCH_ASSOC ) )
      {
        if ( $firstRow )
        {
          $firstRow = FALSE;
          $file->append( "\"" . join( "\"{$separator}\"", array_keys( $row ) ) . "\"\n" );
        }

        $line = array();
        foreach ( $row as $value )
        {
          $value = str_replace( '"', '""', $value );
          $line[ ] = $value;
        }
        $file->append( "\"" . join( "\"{$separator}\"", $line ) . "\"\r\n" );
      }

      $file->close();

      $this->autoRender = FALSE;
      $this->response->type( 'text/csv' );
      $this->response->download( "{$key}.csv" );
      $this->response->body( utf8_decode( $file->read() ) );

      $file->delete();
    }

    $this->set( 'typeMap', $this->typeMap );
    $this->set( 'report', $report );
  }
}