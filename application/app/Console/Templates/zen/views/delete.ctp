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
<?php echo "<?php echo \$this->element( 'admin/panels/record_panel', array( 'id' => \${$singularVar}['{$modelClass}']['{$primaryKey}'] ) ); ?>\n" ?>
<div class="row-fluid">
	<div class="span12">
		<h2><?php echo "<?php echo sprintf( '¿Está seguro que desea borrar de la colección <em>%s</em> el registro <em>%s</em>?', '{$singularHumanName}', \${$singularVar}['{$modelClass}']['{$displayField}'] ) ?>" ?></h2>
		<!-- if si hay relacionados de tipo hasOne y hasMany -->

		<?php echo "<?php if ( count( \${$singularVar} ) > 1 ): ?>\n" ?>
			<h5>Quedarán huérfanos o se borrarán los siguientes registros asociados</h5>
			<ul>
				<!-- loopear por cada hasOne -->
<?php if (!empty($associations['hasOne'])): ?>
<?php foreach ($associations['hasOne'] as $alias => $details) : ?>
        <?php echo "<?php if ( \${$singularVar}[ '{$alias}' ][ '{$details['displayField']}' ] ): ?>\n" ?>
          <li><?php echo "<?php echo \$this->BootstrapHtml->link( \${$singularVar}[ '{$alias}' ][ '{$details['displayField']}' ], array( 'controller' => '{$details['controller']}', 'action' => 'view', \${$singularVar}[ '{$alias}' ][ '{$details['primaryKey']}' ] ) ) ?>" ?></li>
        <?php echo "<?php endif; ?>\n" ?>
<?php endforeach; ?>
<?php endif; ?>
				<!-- end loop -->
				<!-- loopear por cada hasMany -->
<?php if (!empty($associations['hasMany'])): ?>
<?php foreach ($associations['hasMany'] as $alias => $details) : ?>
        <?php echo "<?php if ( count( \${$singularVar}[ '{$alias}' ] ) ): ?>\n" ?>
          <?php echo "<?php foreach ( \${$singularVar}[ '{$alias}' ] as \$related ): ?>\n" ?>
            <li><?php echo "<?php echo \$this->BootstrapHtml->link( \$related[ '{$details['displayField']}' ], array( 'controller' => '{$details['controller']}', 'action' => 'view', \$related[ '{$details['primaryKey']}' ] ) ) ?>" ?></li>
          <?php echo "<?php endforeach; ?>\n" ?>
        <?php echo "<?php endif; ?>\n" ?>
<?php endforeach; ?>
<?php endif; ?>
			</ul>
		<?php echo "<?php endif; ?>\n" ?>
		<!-- end if -->
		<div class="form-actions">
			<?php echo "<?php echo \$this->BootstrapForm->postLink( 'Estoy seguro', array( 'action' => 'delete', \${$singularVar}['{$modelClass}']['{$primaryKey}'] ), array( 'class' => 'btn btn-danger', 'icon' => 'ok white' ) ); ?>\n" ?>
			<?php echo "<?php echo \$this->BootstrapHtml->link( 'Mejor no', array( 'action' => 'index' ), array( 'class' => 'btn' ) ); ?>\n" ?>
		</div>
	</div>
</div>
