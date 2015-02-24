<?php /* @var $this View */ ?>
<!-- admin/model_panel custom para settings list -->
<div class="row-fluid">
  <div class="span12">
    <ul class="nav nav-tabs">

      <?php if ( $this->view == 'admin_index' ): ?>
        <li class="active">
          <?php echo $this->BootstrapHtml->link( 'Lista', array( 'action' => 'index' ), array( 'icon' => 'list' ) ); ?>
        </li>
      <?php else: ?>
        <li>
          <?php echo $this->BootstrapHtml->link( 'Lista', array( 'action' => 'index' ), array( 'icon' => 'list' ) ); ?>
        </li>
      <?php endif; ?>

      <?php if ( $this->view == 'admin_search' ): ?>
        <li class="active">
          <?php echo $this->BootstrapHtml->link( 'Búsqueda', $this->passedArgs, array( 'icon' => 'search' ) ); ?>
        </li>
      <?php endif; ?>
      <li>
        <?php echo $this->BootstrapForm->postLink( 'Borrar Cache', array( 'action' => 'clear_cache' ), array( 'icon' => 'remove' ) ); ?>
      </li>
      <li class="dropdown" style="float:right">
        <a class="dropdown-toggle" data-toggle="dropdown" href="#">Seleccionados<b class="caret"></b></a>
        <ul class="dropdown-menu" style="left:auto; right:0;">
          <li><a href="#" onclick="$('#bulk_form_delete').submit(); return false;">Limpiar Seleccionados</a></li>
        </ul>
      </li>
      <li class="dropdown" style="float:right">
        <a class="dropdown-toggle" data-toggle="dropdown" href="#">Paginación<b class="caret"></b></a>
        <ul class="dropdown-menu" style="left:auto; right:0;">
          <?php $pagination = array( 10, 20, 50, 100, 200, 500, 1000 ); ?>
          <?php foreach ( $pagination as $number ): ?>
            <?php $current = Hash::get( $this->BootstrapPaginator->params(), 'limit' ) == $number ? ' class="current"' : ''; ?>
            <li<?php echo $current; ?>><?php echo $this->BootstrapHtml->link( "{$number} Elementos", array_merge( $this->passedArgs, array( 'limit' => $number ) ) ) ?></li>
          <?php endforeach; ?>
        </ul>
      </li>
    </ul>
  </div>
</div>
<div class="hide"><?php echo $this->BootstrapForm->create( 'Delete', array( 'id' => 'bulk_form_delete', 'url' => array( 'controller' => Inflector::tableize( $this->name ), 'action' => 'bulk_delete' ) ) ); ?>
  <?php echo $this->BootstrapForm->text( 'conditions', array( 'value' => '', 'class' => 'bulk_form_value' ) ); ?>
  <?php echo $this->BootstrapForm->end(); ?></div>
<!-- fin admin/model_panel custom para settings list -->
<div class="row-fluid">
  <?php echo $this->element( 'admin/panels/search_panel', array( 'model' => $model, 'data' => $settings, 'export' => FALSE ) ); ?>
  <div class="row-fluid">
    <div class="span12">
      <h2>Listar Configuraciones</h2>
      <p class="text-info">
        <span class="label label-info">Información!</span> Desde acá podes ver datos de configuraciones para la aplicación,
        y sobreescribir algunas de ellas temporalmente y <em>solo</em> para tu sesión. Cuidado, porque el funcionamiento
        de la aplicación puede cambiar solo para vos!
      </p>
      <?php if ( $this->BootstrapPaginator->hasPage( NULL, 2 ) ): ?>
        <p>
          <?php echo $this->BootstrapPaginator->counter( array( 'format' => 'Página {:page} de {:pages}, mostrando {:current} registros de un total de {:count}, desde el registro {:start} hasta {:end}' ) ); ?>
        </p>
      <?php endif; ?>
      <?php if ( count( $settings ) ): ?>
        <table class="table table-bordered table-striped table-hover auto-responsive">
          <thead>
            <tr>
              <th>
                <input id="bulk_checkbox" type="checkbox" rel="tooltip" data-title="Seleccionar o deseleccionar todos los elementos">
              </th>
              <th>nombre</th>
              <th>tipo</th>
              <th>clave</th>
              <th class="center">valor</th>
              <th>sobreescrito</th>
              <th class="actions"><span class="pull-right">Acciones</span></th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ( $settings as $setting ): ?>
              <tr>
                <td>
                  <input type="checkbox" class="bulk_checkbox" name="bulk_ids[]" value="<?php echo $setting[ 'Setting' ][ 'id' ]; ?>">
                </td>
                <td><?php echo h( $setting[ 'Setting' ][ 'name' ] ); ?></td>
                <td><?php echo h( $setting[ 'Setting' ][ 'type' ] ); ?></td>
                <td><?php echo h( $setting[ 'Setting' ][ 'key' ] ); ?></td>
                <td><?php echo h( $setting[ 'Setting' ][ 'value' ] ); ?></td>
                <td style="text-align: center"><?php echo '<i class="icon-' . ( $setting[ 'Setting' ][ 'overwritten' ] ? "ok" : "remove" ) . '"></i>' ?></td>
                <td class="actions">
                  <div class="btn-group pull-right">
                    <?php echo $this->BootstrapHtml->link( 'Ver', array( 'action' => 'view', $setting[ 'Setting' ][ 'id' ] ), array( 'class' => 'btn', 'icon' => 'eye-open' ) ); ?>
                    <?php if ( $setting[ 'Setting' ][ 'overwritten' ] || $setting[ 'Setting' ][ 'overridable' ] ): ?>
                      <button class="btn dropdown-toggle" data-toggle="dropdown">
                        <span class="caret"></span>
                      </button>
                      <ul class="dropdown-menu pull-right">
                        <li><?php if ( $setting[ 'Setting' ][ 'overridable' ] ) echo $this->BootstrapHtml->link( 'Editar', array( 'action' => 'edit', $setting[ 'Setting' ][ 'id' ] ), array( 'icon' => 'pencil' ) ); ?></li>
                        <li><?php if ( $setting[ 'Setting' ][ 'overwritten' ] ) echo $this->BootstrapForm->postLink( 'Limpiar', array( 'action' => 'delete', $setting[ 'Setting' ][ 'id' ] ), array( 'icon' => 'trash' ) ); ?></li>
                      </ul>
                    <?php endif; ?>
                  </div>
                </td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      <?php else: ?>
        <div class="alert alert-info">
          <strong>Sin Resultados!</strong> No se encontraron resultados para tu búsqueda
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