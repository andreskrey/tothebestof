<?php /* @var $this View */ ?>
<div class="row-fluid">
  <div class="span12">

    <!-- Tabs -->
    <ul class="nav nav-tabs">

      <li class="<?php echo $this->view == 'admin_index' ? 'active' : '' ?>">
        <?php echo $this->BootstrapHtml->link( 'Lista', array( 'action' => 'index' ), array( 'icon' => 'list' ) ); ?>
      </li>

      <?php if ( $this->view == 'admin_search' ): ?>
        <li class="active">
          <?php echo $this->BootstrapHtml->link( 'Búsqueda', $this->passedArgs, array( 'icon' => 'search' ) ); ?>
        </li>
      <?php endif; ?>

      <li class="<?php echo $this->view == 'admin_add' ? 'active' : '' ?>">
        <?php echo $this->BootstrapHtml->link( 'Agregar', array( 'action' => 'add' ), array( 'icon' => 'plus' ) ); ?>
      </li>

      <li class="<?php echo $this->view == 'admin_export' ? 'active' : '' ?>">
        <?php echo $this->BootstrapHtml->link( 'Exportar', array( 'action' => 'export' ), array( 'icon' => 'share' ) ); ?>
      </li>

      <?php if ( $this->view == 'admin_bulk_delete' ): ?>
        <li class="active">
          <?php echo $this->BootstrapHtml->link( 'Borrar', '#', array( 'icon' => 'remove' ) ); ?>
        </li>
      <?php endif; ?>

      <?php if ( $this->view == 'admin_bulk_export' ): ?>
        <li class="active">
          <?php echo $this->BootstrapHtml->link( 'Exportar', '#', array( 'icon' => 'share' ) ); ?>
        </li>
      <?php endif; ?>

      <!-- Pagination and Bulk actions for index and search -->
      <?php if ( in_array( $this->view, array( 'admin_index', 'admin_search' ) ) ): ?>
        <li class="dropdown pull-right">
          <a class="dropdown-toggle" data-toggle="dropdown" href="#">Seleccionados<b class="caret"></b></a>
          <ul class="dropdown-menu" style="left:auto; right:0;">
            <li><a href="#" onclick="$('#bulk_form_export').submit(); return false;">Exportar seleccionados</a></li>
            <li><a href="#" onclick="$('#bulk_form_delete').submit(); return false;">Borrar Seleccionados</a></li>
          </ul>
        </li>

        <li class="dropdown pull-right">
          <a class="dropdown-toggle" data-toggle="dropdown" href="#">Paginación<b class="caret"></b></a>
          <ul class="dropdown-menu" style="left:auto; right:0;">
            <?php $pagination = array( 10, 20, 50, 100, 200, 500, 1000 ); ?>
            <?php foreach ( $pagination as $number ): ?>
              <?php $current = Hash::get( $this->BootstrapPaginator->params(), 'limit' ) == $number ? ' class="current"' : ''; ?>
              <li<?php echo $current; ?>><?php echo $this->BootstrapHtml->link( "{$number} Elementos", array_merge( $this->passedArgs, array( 'limit' => $number ) ) ) ?></li>
            <?php endforeach; ?>
          </ul>
        </li>
        <div style="display: none;">
          <?php echo $this->BootstrapForm->create( 'Export', array( 'id' => 'bulk_form_export', 'url' => array( 'controller' => Inflector::tableize( $this->name ), 'action' => 'bulk_export' ) ) ); ?>
          <?php echo $this->BootstrapForm->text( 'type', array( 'id' => 'bulk_export_type', 'value' => 'id' ) ); ?>
          <?php
          if ( $this->view == 'admin_index' )
          {
            echo $this->BootstrapForm->text( 'conditions', array( 'value' => '', 'class' => 'bulk_form_value' ) );
          }
          else
          {
            echo $this->BootstrapForm->text( 'conditions', array( 'value' => $conditions, 'class' => 'bulk_form_value' ) );
          }
          ?>
          <?php echo $this->BootstrapForm->end(); ?>
          <?php echo $this->BootstrapForm->create( 'Delete', array( 'id' => 'bulk_form_delete', 'url' => array( 'controller' => Inflector::tableize( $this->name ), 'action' => 'bulk_delete' ) ) ); ?>
          <?php echo $this->BootstrapForm->text( 'conditions', array( 'value' => '', 'class' => 'bulk_form_value' ) ); ?>
          <?php echo $this->BootstrapForm->end(); ?>
        </div>
      <?php endif; ?>
    </ul>
  </div>
</div>