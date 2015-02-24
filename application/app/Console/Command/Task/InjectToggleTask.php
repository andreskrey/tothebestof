<?php
App::uses( 'AppShell', 'Console/Command' );
App::uses( 'InjectTask', 'Console/Command/Task' );
App::uses( 'AppModel', 'Model' );

/**
 * Task class for creating and updating controller files.
 *
 * @package       Cake.Console.Command.Task
 */
class InjectToggleTask extends InjectTask
{
  public function execute()
  {
    $this->hr();
    $this->out( "Inject Record Toggle Processing" );
    $this->hr();
    $this->interactive = TRUE;

    $primaryKey = 'id';
    $validate = $associations = array();

    if ( empty( $this->connection ) )
    {
      $this->connection = $this->DbConfig->getConfig();
    }

    $modelName = $this->getName(); //pide elegir la tabla
    $this->setContext( $modelName );

    $field = $this->getFieldName();


    if ( !$this->viewViewFile )
    {
      $this->err( __d( 'inject', 'Some of the required files are missing. Please bake them first.' ) );
      $this->_stop();

      return FALSE;
    }

    $this->hr();
    $this->out( 'Inject Record Toggle functionality to ' . $modelName . ' for the field: ' . $field );
    $looksGood = $this->in( __( 'Look okay?', TRUE ), array( 'y', 'n' ), 'y' );

    if ( strtolower( $looksGood ) == 'y' )
    {
      $data = compact( 'modelName', 'field' );
      $this->inject( $data );
    }
  }


  /**
   * Creates the actual injection in all the needed files
   *
   * @access private
   */
  private function inject( $data = NULL )
  {
    $modelName = $data[ 'modelName' ];
    $model = $modelName;
    $model[ 0 ] = strtolower( $model[ 0 ] );
    $field = $data[ 'field' ];

    //chances the view
    $varName = $modelName;
    $varName[ 0 ] = strtolower( $varName );
    $pattern = "/\<td\> .+? h\( \s* \\\${$varName} \s* \[ \s* '{$modelName}' \s* \]\[ \s* '{$field}' \s* \] .+? <\/td\>/mx";
    $replace = "<td style=\"text-align: center\"><?php echo \$this->element( 'admin/toggle_field', array( 'field' => '{$field}', 'value' => \${$varName}[ '{$modelName}' ][ '{$field}' ], 'id' => \${$varName}[ '{$modelName}' ][ 'id' ] ) ) ?></td>";
    if ( preg_match( $pattern, $this->viewIndexText ) )
    {
      $this->viewIndexText = preg_replace( $pattern, $replace, $this->viewIndexText );
    }

    $pattern = "/(\\\$this->Admin->sort\(\s*'{$modelName}\.{$field}'\s*,\s*'{$field}')/mxi";
    $replace = "$1, array('class' => 'center')";
    if ( preg_match( $pattern, $this->viewIndexText ) )
    {
      $this->viewIndexText = preg_replace( $pattern, $replace, $this->viewIndexText );
    }

    $this->saveContext();
  }
}
