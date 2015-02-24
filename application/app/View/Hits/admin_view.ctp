<?php /* @var $this View */ ?>
<?php echo $this->element( 'admin/panels/record_panel', array( 'id' => $hit['Hit']['id'] ) ); ?>
<div class="row-fluid">
  <div class="span8">
    <h2><em>Hit: <?php echo $hit['Hit']['name'] ?></em></h2>
    <dl class="dl-horizontal well">
      <dt>id</dt>
      <dd><?php echo h($hit['Hit']['id']); ?>&nbsp;</dd>
      <dt>nombre</dt>
      <dd><?php echo h($hit['Hit']['name']); ?>&nbsp;</dd>
      <dt>creado</dt>
      <dd><?php echo h($hit['Hit']['created']); ?>&nbsp;</dd>
    </dl>
  </div>
  <div class="span4">
    <h3>Relacionados</h3>
    <div class="well">
      <p>Pertenece a:</p>
      <?php if ( $hit[ 'Band' ][ 'id' ] ): ?>
        <div class="clearfix">
          <p class="pull-left"><strong><?php echo $hit['Band']['id']; ?></strong></p>

          <div class="btn-group pull-right">
            <?php echo $this->BootstrapHtml->link('', array( 'controller' => 'bands', 'action' => 'view', $hit['Band']['id'] ), array( 'class' => 'btn btn-small', 'icon' => 'eye-open', 'title' => 'Ver')); ?>
            <?php echo $this->BootstrapHtml->link('', array( 'controller' => 'bands', 'action' => 'edit', $hit['Band']['id'] ), array( 'class' => 'btn btn-small', 'icon' => 'pencil', 'title' => 'Editar')); ?>
            <?php echo $this->BootstrapHtml->link('', array( 'controller' => 'bands', 'action' => 'delete', $hit['Band']['id'] ), array( 'class' => 'btn btn-small', 'icon' => 'remove', 'title' => 'Borrar')); ?>
          </div>
        </div>
      <?php endif; ?>
    </div>
  </div>
</div>