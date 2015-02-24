<?php /* @var $this View */ ?>
<div class="row-fluid">
  <?php echo $this->element( 'admin/panels/model_panel' ); ?>
  <?php echo $this->element( 'admin/panels/search_panel', array( 'data' => $hits ) ); ?>
  <div class="row-fluid">
    <div class="span12">
      <h2>Listar Hits</h2>

      <?php if ( $this->BootstrapPaginator->hasPage( NULL, 2 ) ): ?>
        <p>
          <?php echo $this->BootstrapPaginator->counter( array( 'format' => 'Página {:page} de {:pages}, mostrando {:current} registros de un total de {:count}, desde el registro {:start} hasta {:end}' ) ); ?>
        </p>
      <?php endif; ?>
      <?php if ( count( $hits ) ): ?>
        <table class="table table-bordered table-striped table-hover auto-responsive">
          <thead>
            <tr>
              <th><input id="bulk_checkbox" type="checkbox" rel="tooltip" data-placement="right" data-toggle="tooltip" data-title="Seleccionar o deseleccionar todos los elementos"></th>
              <?php echo $this->Admin->sort('Hit.band_id', 'band_id'); ?>
              <?php echo $this->Admin->sort('Hit.name', 'nombre'); ?>
              <?php echo $this->Admin->sort('Hit.created', 'creado'); ?>
              <th class="actions"><span class="pull-right">Acciones</span></th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($hits as $hit): ?>
              <tr>
                <td><input type="checkbox" class="bulk_checkbox" name="bulk_ids[]" value="<?php echo $hit['Hit']['id']; ?>"></td>
                <td><?php echo $this->BootstrapHtml->link($hit['Band']['id'], array('controller' => 'bands', 'action' => 'view', $hit['Band']['id'])); ?></td>
                <td><?php echo h($hit['Hit']['name']); ?></td>
                <td><?php echo h($hit['Hit']['created']); ?></td>
                <td class="actions">
                  <div class="btn-group pull-right">
                    <?php echo $this->BootstrapHtml->link( 'Ver', array( 'action' => 'view', $hit['Hit']['id'] ), array( 'class' => 'btn', 'icon' => 'eye-open' ) ); ?>
                    <button class="btn dropdown-toggle" data-toggle="dropdown">
                      <span class="caret"></span>
                    </button>
                    <ul class="dropdown-menu pull-right">
                      <li><?php echo $this->BootstrapHtml->link( 'Editar', array( 'action' => 'edit', $hit['Hit']['id'] ), array( 'icon' => 'pencil' ) ); ?></li>
                      <li><?php echo $this->BootstrapHtml->link( 'Borrar', array( 'action' => 'delete', $hit['Hit']['id'] ), array( 'icon' => 'remove' ) ); ?></li>
                    </ul>
                  </div>
                </td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      <?php else: ?>
        <div class="alert alert-info">
          <?php if ( $this->request->params[ 'action' ] == 'admin_index' ): ?>
            <strong>No hay registros!</strong> No hay ningún registro cargado en Hits          <?php else: ?>
            <strong>Sin Resultados!</strong> No se encontraron resultados para tu búsqueda
          <?php endif; ?>
        </div>
      <?php endif; ?>

      <?php echo $this->BootstrapPaginator->pagination( array( 'div' => 'pagination' ) ); ?>
      <?php if ( $this->BootstrapPaginator->hasNext() || $this->BootstrapPaginator->hasPrev() ): ?>
        <p>
          <a class="btn" href="<?php echo Router::url( array_merge( $this->passedArgs, array( 'limit' => '1000000000', 'page' => 1 ) ) ); ?>" rel="tooltip" title="Se listará un total de <?php echo Hash::get( $this->BootstrapPaginator->params(), 'count' ); ?> registros">Mostrar todos los registros</a>
        </p>
        <?php endif; ?>
    </div>
  </div>
</div>