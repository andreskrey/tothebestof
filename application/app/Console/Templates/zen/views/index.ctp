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
<div class="row-fluid">
  <?php echo "<?php echo \$this->element( 'admin/panels/model_panel' ); ?>\n" ?>
  <?php echo "<?php echo \$this->element( 'admin/panels/search_panel', array( 'data' => \${$pluralVar} ) ); ?>\n" ?>
  <div class="row-fluid">
    <div class="span12">
      <h2>Listar <?php echo $pluralHumanName ?></h2>

      <?php echo "<?php if ( \$this->BootstrapPaginator->hasPage( NULL, 2 ) ): ?>\n" ?>
        <p>
          <?php echo "<?php echo \$this->BootstrapPaginator->counter( array( 'format' => 'Página {:page} de {:pages}, mostrando {:current} registros de un total de {:count}, desde el registro {:start} hasta {:end}' ) ); ?>\n" ?>
        </p>
      <?php echo "<?php endif; ?>\n" ?>
      <?php echo "<?php if ( count( \${$pluralVar} ) ): ?>\n" ?>
        <table class="table table-bordered table-striped table-hover auto-responsive">
          <thead>
            <tr>
              <th><input id="bulk_checkbox" type="checkbox" rel="tooltip" data-placement="right" data-toggle="tooltip" data-title="Seleccionar o deseleccionar todos los elementos"></th>
<?php foreach ( $fields as $field ): ?>
              <?php echo "<?php echo \$this->Admin->sort('{$modelClass}.{$field}', '{$fieldNames[$field]}'); ?>\n"; ?>
<?php endforeach; ?>
              <th class="actions"><span class="pull-right">Acciones</span></th>
            </tr>
          </thead>
          <tbody>
            <?php echo "<?php foreach (\${$pluralVar} as \${$singularVar}): ?>\n"; ?>
              <tr>
                <td><input type="checkbox" class="bulk_checkbox" name="bulk_ids[]" value="<?php echo "<?php echo \${$singularVar}['{$modelClass}']['{$primaryKey}']; ?>"?>"></td>
<?php
            foreach ( $fields as $field )
            {
              $isKey = FALSE;
              if ( !empty( $associations[ 'belongsTo' ] ) )
              {
                foreach ( $associations[ 'belongsTo' ] as $alias => $details )
                {
                  if ( $field === $details[ 'foreignKey' ] )
                  {
                    $isKey = TRUE;
?>
                <td><?php echo "<?php echo \$this->BootstrapHtml->link(\${$singularVar}['{$alias}']['{$details[ 'displayField' ]}'], array('controller' => '{$details[ 'controller' ]}', 'action' => 'view', \${$singularVar}['{$alias}']['{$details[ 'primaryKey' ]}'])); ?>" ?></td>
<?php
                    break;
                  }
                }
              }
              if ( $isKey !== TRUE )
              {
?>
                <td><?php echo "<?php echo h(\${$singularVar}['{$modelClass}']['{$field}']); ?>" ?></td>
<?php
              }
            }
              ?>
                <td class="actions">
                  <div class="btn-group pull-right">
                    <?php echo "<?php echo \$this->BootstrapHtml->link( 'Ver', array( 'action' => 'view', \${$singularVar}['{$modelClass}']['{$primaryKey}'] ), array( 'class' => 'btn', 'icon' => 'eye-open' ) ); ?>\n" ?>
                    <button class="btn dropdown-toggle" data-toggle="dropdown">
                      <span class="caret"></span>
                    </button>
                    <ul class="dropdown-menu pull-right">
                      <li><?php echo "<?php echo \$this->BootstrapHtml->link( 'Editar', array( 'action' => 'edit', \${$singularVar}['{$modelClass}']['{$primaryKey}'] ), array( 'icon' => 'pencil' ) ); ?>" ?></li>
                      <li><?php echo "<?php echo \$this->BootstrapHtml->link( 'Borrar', array( 'action' => 'delete', \${$singularVar}['{$modelClass}']['{$primaryKey}'] ), array( 'icon' => 'remove' ) ); ?>" ?></li>
                    </ul>
                  </div>
                </td>
              </tr>
            <?php echo "<?php endforeach; ?>\n"?>
          </tbody>
        </table>
      <?php echo "<?php else: ?>\n" ?>
        <div class="alert alert-info">
          <?php echo "<?php if ( \$this->request->params[ 'action' ] == 'admin_index' ): ?>\n" ?>
            <strong>No hay registros!</strong> No hay ningún registro cargado en <?php echo $pluralHumanName ?>
          <?php echo "<?php else: ?>\n" ?>
            <strong>Sin Resultados!</strong> No se encontraron resultados para tu búsqueda
          <?php echo "<?php endif; ?>\n" ?>
        </div>
      <?php echo "<?php endif; ?>\n" ?>

      <?php echo "<?php echo \$this->BootstrapPaginator->pagination( array( 'div' => 'pagination' ) ); ?>\n" ?>
      <?php echo "<?php if ( \$this->BootstrapPaginator->hasNext() || \$this->BootstrapPaginator->hasPrev() ): ?>\n" ?>
        <p>
          <?php echo "<a class=\"btn\" href=\"<?php echo Router::url( array_merge( \$this->passedArgs, array( 'limit' => '1000000000', 'page' => 1 ) ) ); ?>\" rel=\"tooltip\" title=\"Se listará un total de <?php echo Hash::get( \$this->BootstrapPaginator->params(), 'count' ); ?> registros\">Mostrar" ?> todos los registros</a>
        </p>
        <?php echo "<?php endif; ?>\n" ?>
    </div>
  </div>
</div>