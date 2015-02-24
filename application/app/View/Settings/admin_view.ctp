<?php /* @var $this View */ ?>
<!-- admin/record_panel custom para settings -->
<div class="row-fluid">
  <div class="span12">
    <ul class="nav nav-tabs">
      <li>
        <?php echo $this->BootstrapHtml->link( 'Lista', array( 'action' => 'index' ), array( 'icon' => 'list' ) ); ?>
      </li>
      <li class="active">
        <?php echo $this->BootstrapHtml->link( 'Ver', array( 'action' => 'view', $setting[ 'Setting' ][ 'id' ] ), array( 'icon' => 'eye-open' ) ); ?>
      </li>
      <?php if ( $setting[ 'Setting' ][ 'overridable' ] ): ?>
        <li>
          <?php echo $this->BootstrapHtml->link( 'Editar', array( 'action' => 'edit', $setting[ 'Setting' ][ 'id' ] ), array( 'icon' => 'pencil' ) ); ?>
        </li>
      <?php endif; ?>
      <?php if ( $setting[ 'Setting' ][ 'overwritten' ] ): ?>
        <li>
          <?php echo $this->BootstrapForm->postLink( 'Limpiar', array( 'action' => 'delete', $setting[ 'Setting' ][ 'id' ] ), array( 'icon' => 'trash' ) ); ?>
        </li>
      <?php endif; ?>
    </ul>
  </div>
</div>
<!-- fin admin/record_panel custom para settings -->
<div class="row-fluid">
  <div class="span12">
    <h2><em>Configuración: <?php echo $setting[ 'Setting' ][ 'name' ] ?></em></h2>
    <dl class="dl-horizontal well">
      <dt>Id</dt>
      <dd><?php echo h( $setting[ 'Setting' ][ 'id' ] ); ?>&nbsp;</dd>
      <dt>Nombre</dt>
      <dd><?php echo h( $setting[ 'Setting' ][ 'name' ] ); ?>&nbsp;</dd>
      <dt>Descripción</dt>
      <dd><?php echo h( $setting[ 'Setting' ][ 'description' ] ); ?>&nbsp;</dd>
      <dt>Tipo</dt>
      <dd><?php echo h( $setting[ 'Setting' ][ 'type' ] ); ?>&nbsp;</dd>
      <dt>Clave</dt>
      <dd><?php echo h( $setting[ 'Setting' ][ 'key' ] ); ?>&nbsp;</dd>
      <dt>Valor</dt>
      <dd><?php echo h( $setting[ 'Setting' ][ 'value' ] ); ?>&nbsp;</dd>
      <dt>Sobre escribible</dt>
      <dd><?php echo '<i class="icon-' . ( $setting[ 'Setting' ][ 'overridable' ] ? "ok" : "remove" ) . '"></i>' ?></dd>
      <dt>Sobre escrito</dt>
      <dd><?php echo '<i class="icon-' . ( $setting[ 'Setting' ][ 'overwritten' ] ? "ok" : "remove" ) . '"></i>' ?></dd>
    </dl>
  </div>
</div>