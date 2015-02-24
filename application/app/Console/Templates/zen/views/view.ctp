<?php
/**
 *
 * PHP 5
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For fTRUEcopyright and license information, please see the LICENSE.txt
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
<?php if (empty($associations['belongsTo']) && empty($associations['hasOne']) && empty($associations['hasMany']) && empty($associations['hasAndBelongsToMany'])): ?>
  <div class="span12">
<?php else: ?>
  <div class="span8">
<?php endif; ?>
    <h2><em><?php echo $singularHumanName ?>: <?php echo "<?php echo \${$singularVar}['{$modelClass}']['{$displayField}'] ?>" ?></em></h2>
    <dl class="dl-horizontal well">
<?php
foreach ($fields as $field) :
  $isKey = FALSE;
  if (!empty($associations['belongsTo'])) :
    foreach ($associations['belongsTo'] as $alias => $details) :
      if ($field === $details['foreignKey']) :
        $isKey = TRUE;
        break;
      endif;
    endforeach;
    endif;
    if ($isKey !== TRUE) :
?>
      <dt><?php echo $fieldNames[$field] ?></dt>
      <dd><?php echo "<?php echo h(\${$singularVar}['{$modelClass}']['{$field}']); ?>" ?>&nbsp;</dd>
<?php
  endif;
endforeach;
?>
    </dl>
  </div>
<?php if (!empty($associations['belongsTo']) || !empty($associations['hasOne']) || !empty($associations['hasMany']) || !empty($associations['hasAndBelongsToMany'])): ?>
  <div class="span4">
    <h3>Relacionados</h3>
<?php if (!empty($associations['belongsTo'])): ?>
    <div class="well">
      <p>Pertenece a:</p>
<?php foreach ($associations['belongsTo'] as $alias => $details) : ?>
      <?php echo "<?php if ( \${$singularVar}[ '{$alias}' ][ '{$details['primaryKey']}' ] ): ?>\n" ?>
        <div class="clearfix">
          <p class="pull-left"><strong><?php echo "<?php echo \${$singularVar}['{$alias}']['{$details['displayField']}']; ?>" ?></strong></p>

          <div class="btn-group pull-right">
            <?php echo "<?php echo \$this->BootstrapHtml->link('', array( 'controller' => '{$details['controller']}', 'action' => 'view', \${$singularVar}['{$alias}']['{$details['primaryKey']}'] ), array( 'class' => 'btn btn-small', 'icon' => 'eye-open', 'title' => 'Ver')); ?>\n" ?>
            <?php echo "<?php echo \$this->BootstrapHtml->link('', array( 'controller' => '{$details['controller']}', 'action' => 'edit', \${$singularVar}['{$alias}']['{$details['primaryKey']}'] ), array( 'class' => 'btn btn-small', 'icon' => 'pencil', 'title' => 'Editar')); ?>\n" ?>
            <?php echo "<?php echo \$this->BootstrapHtml->link('', array( 'controller' => '{$details['controller']}', 'action' => 'delete', \${$singularVar}['{$alias}']['{$details['primaryKey']}'] ), array( 'class' => 'btn btn-small', 'icon' => 'remove', 'title' => 'Borrar')); ?>\n" ?>
          </div>
        </div>
      <?php echo "<?php endif; ?>\n" ?>
<?php endforeach; ?>
    </div>
<?php endif; ?>
<?php if (!empty($associations['hasOne'])): ?>
<?php foreach ($associations['hasOne'] as $alias => $details) : ?>
    <?php echo "<?php if ( \${$singularVar}[ '{$alias}' ][ '{$details['primaryKey']}' ] ): ?>\n" ?>
      <div class="well">
        <p>Tiene un:</p>
        <div class="clearfix">
          <p class="pull-left"><strong><?php echo "<?php echo \${$singularVar}['{$alias}']['{$details['displayField']}']; ?>" ?></strong></p>

          <div class="btn-group pull-right">
            <?php echo "<?php echo \$this->BootstrapHtml->link('', array( 'controller' => '{$details['controller']}', 'action' => 'view', \${$singularVar}['{$alias}']['{$details['primaryKey']}'] ), array( 'class' => 'btn btn-small', 'icon' => 'eye-open', 'title' => 'Ver')); ?>\n" ?>
            <?php echo "<?php echo \$this->BootstrapHtml->link('', array( 'controller' => '{$details['controller']}', 'action' => 'edit', \${$singularVar}['{$alias}']['{$details['primaryKey']}'] ), array( 'class' => 'btn btn-small', 'icon' => 'pencil', 'title' => 'Editar')); ?>\n" ?>
            <?php echo "<?php echo \$this->BootstrapHtml->link('', array( 'controller' => '{$details['controller']}', 'action' => 'delete', \${$singularVar}['{$alias}']['{$details['primaryKey']}'] ), array( 'class' => 'btn btn-small', 'icon' => 'remove', 'title' => 'Borrar')); ?>\n" ?>
          </div>
        </div>
      </div>
    <?php echo "<?php endif; ?>\n" ?>
<?php endforeach; ?>
<?php endif; ?>
<?php if (!empty($associations['hasMany'])): ?>
<?php foreach ($associations['hasMany'] as $alias => $details) : ?>
    <?php echo "<?php if ( count( \${$singularVar}[ '{$alias}' ] ) ): ?>\n" ?>
      <div class="well">
        <p>Tiene muchos:</p>
        <ul class="unstyled">
          <?php echo "<?php foreach ( \${$singularVar}['{$alias}'] as \$related ): ?>\n" ?>
            <li>
              <div class="clearfix">
                <p class="pull-left"><strong><?php echo "<?php echo \$related['{$details['displayField']}']; ?>" ?></strong></p>

                <div class="btn-group pull-right">
                  <?php echo "<?php echo \$this->BootstrapHtml->link('', array( 'controller' => '{$details['controller']}', 'action' => 'view', \$related['{$details['primaryKey']}'] ), array( 'class' => 'btn btn-small', 'icon' => 'eye-open', 'title' => 'Ver')); ?>\n" ?>
                  <?php echo "<?php echo \$this->BootstrapHtml->link('', array( 'controller' => '{$details['controller']}', 'action' => 'edit', \$related['{$details['primaryKey']}'] ), array( 'class' => 'btn btn-small', 'icon' => 'pencil', 'title' => 'Editar')); ?>\n" ?>
                  <?php echo "<?php echo \$this->BootstrapHtml->link('', array( 'controller' => '{$details['controller']}', 'action' => 'delete', \$related['{$details['primaryKey']}'] ), array( 'class' => 'btn btn-small', 'icon' => 'remove', 'title' => 'Borrar')); ?>\n" ?>
                </div>
              </div>
            </li>
          <?php echo "<?php endforeach; ?>\n" ?>
        </ul>
        <div class="clearfix"></div>
      </div>
    <?php echo "<?php endif; ?>\n" ?>
<?php endforeach; ?>
<?php endif; ?>
<?php if (!empty($associations['hasAndBelongsToMany'])): ?>
<?php foreach ($associations['hasAndBelongsToMany'] as $alias => $details) : ?>
    <?php echo "<?php if ( count( \${$singularVar}[ '{$alias}' ] ) ): ?>\n" ?>
      <div class="well">
        <p>Tiene muchos:</p>
        <ul class="unstyled">
          <?php echo "<?php foreach ( \${$singularVar}['{$alias}'] as \$related ): ?>\n" ?>
            <li>
              <div class="clearfix">
                <p class="pull-left"><strong><?php echo "<?php echo \$related['{$details['displayField']}']; ?>" ?></strong></p>

                <div class="btn-group pull-right">
                  <?php echo "<?php echo \$this->BootstrapHtml->link('', array( 'controller' => '{$details['controller']}', 'action' => 'view', \$related['{$details['primaryKey']}'] ), array( 'class' => 'btn btn-small', 'icon' => 'eye-open', 'title' => 'Ver')); ?>\n" ?>
                  <?php echo "<?php echo \$this->BootstrapHtml->link('', array( 'controller' => '{$details['controller']}', 'action' => 'edit', \$related['{$details['primaryKey']}'] ), array( 'class' => 'btn btn-small', 'icon' => 'pencil', 'title' => 'Editar')); ?>\n" ?>
                  <?php echo "<?php echo \$this->BootstrapHtml->link('', array( 'controller' => '{$details['controller']}', 'action' => 'delete', \$related['{$details['primaryKey']}'] ), array( 'class' => 'btn btn-small', 'icon' => 'remove', 'title' => 'Borrar')); ?>\n" ?>
                </div>
              </div>
            </li>
          <?php echo "<?php endforeach; ?>\n" ?>
        </ul>
        <div class="clearfix"></div>
      </div>
    <?php echo "<?php endif; ?>\n" ?>
<?php endforeach; ?>
<?php endif; ?>
  </div>
<?php endif; ?>
</div>