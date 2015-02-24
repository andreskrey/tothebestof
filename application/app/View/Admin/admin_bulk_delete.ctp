<?php /* @var $this View */ ?>
<?php echo $this->element( 'admin/panels/model_panel' ); ?>
<div class="row-fluid">
  <div class="span12">
    <h2><?php echo sprintf( '¿Está seguro que desea borrar de la colección <em>%s</em> ', $model->name ) . __dn( 'admin', 'el siguiente registro', 'los siguientes registros', count( $records ) ) . '?' ?></h2>

    <?php echo $this->BootstrapForm->create( 'Delete', array( 'url' => array( 'controller' => $controller, 'action' => 'bulk_delete' ) ) ); ?>
    <?php echo $this->BootstrapForm->hidden( 'process' ); ?>
    <?php echo $this->BootstrapForm->hidden( 'conditions' ); ?>
    <ul>
      <?php foreach ( $records as $record ): ?>
        <li><?php echo h( $record[ $model->name ][ $model->displayField ] ) ?></li>
      <?php endforeach; ?>
    </ul>
    <div class="form-actions">
      <?php echo $this->BootstrapForm->submit( 'Estoy seguro', array( 'div' => FALSE, 'class' => 'btn btn-danger', 'icon' => 'ok white' ) ); ?>
      <?php echo $this->BootstrapHtml->link( 'Mejor no', array( 'action' => 'index' ), array( 'class' => 'btn' ) ); ?>
    </div>
    <?php echo $this->BootstrapForm->end(); ?>
  </div>
</div>