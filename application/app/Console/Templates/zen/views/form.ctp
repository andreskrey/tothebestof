<?php
/**
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
 * @package       Cake.Console.Templates.default.views
 * @since         CakePHP(tm) v 1.2.0.5234
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */
?>
<?php echo "<?php /* @var \$this View */ ?>\n" ?>
<?php if (strpos($action, 'add') !== FALSE): ?>
<?php echo "<?php echo \$this->element( 'admin/panels/model_panel' ); ?>\n" ?>
<?php else: ?>
<?php echo "<?php echo \$this->element( 'admin/panels/record_panel', array( 'id' => \$this->BootstrapForm->value( '{$modelClass}.{$primaryKey}' ) ) ); ?>\n" ?>
<?php endif; ?>
<div class="row-fluid">
	<div class="span12">
<?php if (strpos($action, 'add') !== FALSE): ?>
    <h2>Agregar <em><?php echo $singularHumanName ?></em></h2>
<?php else: ?>
    <h2>Editar <em><?php echo $singularHumanName ?></em>: <em><?php echo "<?php echo \$this->BootstrapForm->value('{$modelClass}.{$displayField}') ?>" ?></em></h2>
<?php endif; ?>
		<?php echo "<?php echo \$this->BootstrapForm->create( '{$modelClass}', array( 'class' => 'form-horizontal' ) ); ?>\n" ?>
		<fieldset>
			<?php echo "<?php\n" ?>
<?php $n = 0; ?>
<?php foreach ($fields as $field) :
if (strpos($action, 'add') !== FALSE && $field == $primaryKey) :
  continue;
elseif (!in_array($field, array('created', 'modified', 'updated'))) :
?>
			<?php echo "echo \$this->BootstrapForm->input( '{$modelClass}.{$field}', array(\n" ?>
        'label' => '<?php echo $fieldNames[$field] ?>',
				'class' => 'span8',
<?php if ($n == 0 && $field != 'id' && !preg_match('/_id$/', $field)) { echo "\t\t\t\t'autofocus' => true,\n"; $n++; } ?>
        //'helpInline' => '',
<?php if (isset($schema[$field]['comment'])): ?>
        'helpBlock' => '<span class="label label-info">Nota: </span> <?php echo $schema[$field]['comment'] ?>',
<?php else: ?>
        //'helpBlock' => '',
<?php endif; ?>
			) );
<?php
endif;
endforeach;
if (!empty($associations['hasAndBelongsToMany'])) :
  foreach ($associations['hasAndBelongsToMany'] as $assocName => $assocData):
?>
      <?php echo "echo \$this->BootstrapForm->input( '{$assocName}', array(\n" ?>
        'label' => '<?php echo $assocData['pluralName'] ?>',
        'class' => 'span8',
<?php if ($n++ == 0) echo "\t\t\t\t'autofocus' => true,\n" ?>
        //'helpInline' => '',
        //'helpBlock' => '',
      ) );
<?php endforeach; ?>
<?php endif; ?>
      <?php echo "?>\n" ?>
			<div class="form-actions">
				<?php echo "<?php echo \$this->BootstrapForm->button( 'Guardar', array( 'class' => 'btn btn-primary' ) ) ?>\n" ?>
<?php if (strpos($action, 'add') !== FALSE): ?>
				<?php echo "<?php echo \$this->BootstrapForm->button( 'Guardar y crear otro', array( 'name' => '_addAnother', 'value' => TRUE, 'class' => 'btn btn-info btn-primary' ) ) ?>\n" ?>
<?php endif; ?>
				<?php echo "<?php echo \$this->BootstrapHtml->link( 'Volver', \$this->Admin->getPreviousUrl(), array( 'class' => 'btn' ) ); ?>\n" ?>
			</div>
		</fieldset>
		<?php echo "<?php echo \$this->BootstrapForm->end(); ?>\n" ?>
	</div>
</div>