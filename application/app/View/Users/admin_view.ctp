<?php /* @var $this View */ ?>
<?php echo $this->element( 'admin/panels/record_panel', array( 'id' => $user['User']['id'] ) ); ?>
<div class="row-fluid">
  <div class="span12">
    <h2><em>Usuario: <?php echo $user['User']['id'] ?></em></h2>
    <dl class="dl-horizontal well">
      <dt>id</dt>
      <dd><?php echo h($user['User']['id']); ?>&nbsp;</dd>
      <dt>Usuario</dt>
      <dd><?php echo h($user['User']['username']); ?>&nbsp;</dd>
      <dt>Password</dt>
      <dd><?php echo h($user['User']['password']); ?>&nbsp;</dd>
      <dt>Creado</dt>
      <dd><?php echo h($user['User']['created']); ?>&nbsp;</dd>
      <dt>Modificado</dt>
      <dd><?php echo h($user['User']['modified']); ?>&nbsp;</dd>
    </dl>
  </div>
</div>