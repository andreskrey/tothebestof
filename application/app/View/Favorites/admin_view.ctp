<?php /* @var $this View */ ?>
<?php echo $this->element( 'admin/panels/record_panel', array( 'id' => $favorite['Favorite']['id'] ) ); ?>
<div class="row-fluid">
  <div class="span8">
    <h2><em>Favorita: <?php echo $favorite['Favorite']['user_id'] ?></em></h2>
    <dl class="dl-horizontal well">
      <dt>id</dt>
      <dd><?php echo h($favorite['Favorite']['id']); ?>&nbsp;</dd>
      <dt>Banda</dt>
      <dd><?php echo h($favorite['Favorite']['band']); ?>&nbsp;</dd>
      <dt>Creado</dt>
      <dd><?php echo h($favorite['Favorite']['created']); ?>&nbsp;</dd>
    </dl>
  </div>
  <div class="span4">
    <h3>Relacionados</h3>
    <div class="well">
      <p>Pertenece a:</p>
      <?php if ( $favorite[ 'User' ][ 'id' ] ): ?>
        <div class="clearfix">
          <p class="pull-left"><strong><?php echo $favorite['User']['id']; ?></strong></p>

          <div class="btn-group pull-right">
            <?php echo $this->BootstrapHtml->link('', array( 'controller' => 'users', 'action' => 'view', $favorite['User']['id'] ), array( 'class' => 'btn btn-small', 'icon' => 'eye-open', 'title' => 'Ver')); ?>
            <?php echo $this->BootstrapHtml->link('', array( 'controller' => 'users', 'action' => 'edit', $favorite['User']['id'] ), array( 'class' => 'btn btn-small', 'icon' => 'pencil', 'title' => 'Editar')); ?>
            <?php echo $this->BootstrapHtml->link('', array( 'controller' => 'users', 'action' => 'delete', $favorite['User']['id'] ), array( 'class' => 'btn btn-small', 'icon' => 'remove', 'title' => 'Borrar')); ?>
          </div>
        </div>
      <?php endif; ?>
    </div>
  </div>
</div>