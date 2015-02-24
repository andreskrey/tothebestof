<?php
/**
 * Bake Template for Controller action generation.
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
 * @package       Cake.Console.Templates.default.actions
 * @since         CakePHP(tm) v 1.3
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */
?>
	/**
	 * admin_index method
	 *
	 * @return void
	 */
	public function admin_index()
	{
    $this-><?php echo $currentModelName ?>->recursive = 0;
    try
    {
      $records = $this->paginate('<?php echo $currentModelName; ?>');
    }
    catch ( NotFoundException $e )
    {
      $this->Admin->setFlashInfo( '<strong>Sin Resultados para esa página!</strong> Ahora estamos en la primera página.' );
      $this->redirect( Set::merge( Set::get( $this->request->params, 'named' ), array('page' => 1) ) );
    }
    $this->set( '<?php echo $pluralName ?>', $records );
	}

	/**
	 * admin_view method
	 *
	 * @throws NotFoundException
	 * @param string $id
	 * @return void
	 */
	public function admin_view($id = null)
	{
		if (!$this-><?php echo $currentModelName; ?>->exists($id)) {
			throw new NotFoundException( 'El registro no existe' );
		}
		$this->set('<?php echo $singularName; ?>', $this-><?php echo $currentModelName; ?>->find('first', array('conditions' => array('<?php echo $currentModelName; ?>.id' => $id))));
	}

<?php $compact = array(); ?>
	/**
	 * admin_add method
	 *
	 * @return void
	 */
	public function admin_add()
	{
		if ($this->request->is('post')) {
			$this-><?php echo $currentModelName; ?>->create();
			if ($this-><?php echo $currentModelName; ?>->save($this->request->data)) {
				$this->Admin->setFlashSuccess( 'El registro ha sido guardado' );
				$this->redirect( isset( $this->request->data[ '_addAnother' ] ) ? array( 'action' => 'add' ) : $this->getPreviousUrl() );
			} else {
				$this->Admin->setFlashError( 'El registro no se pudo guardar. Por favor intenta más tarde.' );
			}
		}
<?php
	foreach (array('belongsTo', 'hasAndBelongsToMany') as $assoc):
		foreach ($modelObj->{$assoc} as $associationName => $relation):
			if (!empty($associationName)):
				$otherModelName = $this->_modelName($associationName);
				$otherPluralName = $this->_pluralName($associationName);
				echo "\t\t\${$otherPluralName} = \$this->{$currentModelName}->{$otherModelName}->find('list');\n";
				$compact[] = "'{$otherPluralName}'";
			endif;
		endforeach;
	endforeach;
	if (!empty($compact)):
		echo "\t\t\$this->set(compact(".join(', ', $compact)."));\n";
	endif;
?>
	}

<?php $compact = array(); ?>
	/**
	 * admin_edit method
	 *
	 * @throws NotFoundException
	 * @param string $id
	 * @return void
	 */
	public function admin_edit($id = null)
	{
		if (!$this-><?php echo $currentModelName; ?>->exists($id)) {
			throw new NotFoundException( 'Registro inválido' );
		}
		if ($this->request->is('post') || $this->request->is('put')) {
			if ($this-><?php echo $currentModelName; ?>->save($this->request->data)) {
				$this->Admin->setFlashSuccess( 'El registro ha sido guardado' );
				$this->redirect($this->getPreviousUrl());
			} else {
				$this->Admin->setFlashError( 'El registro no se pudo guardar. Por favor intenta más tarde.' );
			}
		} else {
			$this->request->data = $this-><?php echo $currentModelName; ?>->find('first', array('conditions' => array('<?php echo $currentModelName; ?>.id' => $id)));
		}
<?php
		foreach (array('belongsTo', 'hasAndBelongsToMany') as $assoc):
			foreach ($modelObj->{$assoc} as $associationName => $relation):
				if (!empty($associationName)):
					$otherModelName = $this->_modelName($associationName);
					$otherPluralName = $this->_pluralName($associationName);
					echo "\t\t\${$otherPluralName} = \$this->{$currentModelName}->{$otherModelName}->find('list');\n";
					$compact[] = "'{$otherPluralName}'";
				endif;
			endforeach;
		endforeach;
		if (!empty($compact)):
			echo "\t\t\$this->set(compact(".join(', ', $compact)."));\n";
		endif;
	?>
	}

	/**
	 * admin_delete method
	 *
	 * @throws NotFoundException
	 * @param string $id
	 * @return void
	 */
	public function admin_delete($id = null)
	{
    if ( !$this-><?php echo $currentModelName; ?>->exists($id) ) {
      throw new NotFoundException( 'Registro inválido' );
    }
		if ( $this->request->is( 'post' ) ) {
			$this-><?php echo $currentModelName; ?>->id = $id;
			if ( $this-><?php echo $currentModelName; ?>->delete() ) {
				$this->Admin->setFlashSuccess( 'El registro ha sido borrado' );
				$this->redirect( $this->getPreviousUrl() );
			}
			$this->Admin->setFlashError( 'El registro no se pudo borrar. Por favor intenta más tarde.' );
		}
		$this->set( '<?php echo $singularName; ?>', $this-><?php echo $currentModelName; ?>->read( NULL, $id ) );
	}