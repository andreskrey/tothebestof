<?php /* @var $this View */ ?>
<div class="row-fluid">
  <div class="span12">
    <ul class="nav nav-tabs">
      <li>
        <?php echo $this->BootstrapHtml->link( 'Lista', array( 'action' => 'index' ), array( 'icon' => 'list' ) ); ?>
      </li>

      <li class="<?php echo $this->view == 'admin_view' ? 'active' : '' ?>">
        <?php echo $this->BootstrapHtml->link( 'Ver', array( 'action' => 'view', $id ), array( 'icon' => 'eye-open' ) ); ?>
      </li>

      <li class="<?php echo $this->view == 'admin_edit' ? 'active' : '' ?>">
        <?php echo $this->BootstrapHtml->link( 'Editar', array( 'action' => 'edit', $id ), array( 'icon' => 'pencil' ) ); ?>
      </li>

      <li class="<?php echo $this->view == 'admin_delete' ? 'active' : '' ?>">
        <?php echo $this->BootstrapHtml->link( 'Borrar', array( 'action' => 'delete', $id ), array( 'icon' => 'remove' ) ); ?>
      </li>
    </ul>
  </div>
</div>