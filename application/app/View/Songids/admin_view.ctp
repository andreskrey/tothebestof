<?php /* @var $this View */ ?>
<?php echo $this->element( 'admin/panels/record_panel', array( 'id' => $songid['Songid']['id'] ) ); ?>
<div class="row-fluid">
  <div class="span8">
    <h2><em>Songid: <?php echo $songid['Songid']['name'] ?></em></h2>
    <dl class="dl-horizontal well">
      <dt>id</dt>
      <dd><?php echo h($songid['Songid']['id']); ?>&nbsp;</dd>
      <dt>nombre</dt>
      <dd><?php echo h($songid['Songid']['name']); ?>&nbsp;</dd>
      <dt>songid</dt>
      <dd><?php echo h($songid['Songid']['songid']); ?>&nbsp;</dd>
      <dt>creado</dt>
      <dd><?php echo h($songid['Songid']['created']); ?>&nbsp;</dd>
    </dl>
  </div>
  <div class="span4">
    <h3>Relacionados</h3>
    <div class="well">
      <p>Pertenece a:</p>
      <?php if ( $songid[ 'Band' ][ 'id' ] ): ?>
        <div class="clearfix">
          <p class="pull-left"><strong><?php echo $songid['Band']['id']; ?></strong></p>

          <div class="btn-group pull-right">
            <?php echo $this->BootstrapHtml->link('', array( 'controller' => 'bands', 'action' => 'view', $songid['Band']['id'] ), array( 'class' => 'btn btn-small', 'icon' => 'eye-open', 'title' => 'Ver')); ?>
            <?php echo $this->BootstrapHtml->link('', array( 'controller' => 'bands', 'action' => 'edit', $songid['Band']['id'] ), array( 'class' => 'btn btn-small', 'icon' => 'pencil', 'title' => 'Editar')); ?>
            <?php echo $this->BootstrapHtml->link('', array( 'controller' => 'bands', 'action' => 'delete', $songid['Band']['id'] ), array( 'class' => 'btn btn-small', 'icon' => 'remove', 'title' => 'Borrar')); ?>
          </div>
        </div>
      <?php endif; ?>
    </div>
  </div>
</div>