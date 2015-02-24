<?php
/**
 * Application model for Cake.
 *
 * This file is application-wide model file. You can put all
 * application-wide model-related methods here.
 *
 * PHP 5
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       app.Model
 * @since         CakePHP(tm) v 0.2.9
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */

App::uses( 'Model', 'Model' );
App::uses( 'CakeSession', 'Model/Datasource' );
App::uses( 'CakeLog', 'Log' );
/**
 * Application model for Cake.
 *
 * Add your application-wide methods in the class below, your models
 * will inherit them.
 *
 * @package       app.Model
 */
class AppModel extends Model
{
  /**
   * @var array
   */
  public $actsAs = array( 'Containable' );


  /**
   * @param bool $created
   */
  public function afterSave( $created )
  {
    $this->logCrudActivity( $created ? 'add' : 'edit' );

    parent::afterSave( $created );
  }


  public function afterDelete()
  {
    $this->logCrudActivity( 'delete' );

    parent::afterDelete();
  }


  /**
   * @param $type
   */
  private function logCrudActivity( $type )
  {
    if ( !$username = CakeSession::read( 'Auth.User.username' ) ) return;

    $verbMap = array(
      'add'    => 'added',
      'edit'   => 'modified',
      'delete' => 'deleted'
    );
    $message = "user {$username} {$verbMap[$type]} model {$this->name} record #{$this->id}";
    CakeLog::info( $message, 'crud' );
  }


  /**
   * @param $name
   * @param $file
   * @return RFC
   */
  public function formatNameFromUpload( $name, $file )
  {
    return String::uuid();
  }


  /**
   * @param string $data
   * @return bool
   */
  public function isForeignKey( $data )
  {
    foreach ( $data as $key => $value )
    {
      foreach ( $this->belongsTo as $alias => $assoc )
      {
        if ( $assoc[ 'foreignKey' ] == $key )
        {
          $this->{$alias}->id = $value;

          return $this->{$alias}->exists();
        }
      }
    }

    return FALSE;
  }
}
