<?php /* @var $this View */ ?>
<!-- admin/record_panel custom para settings -->
<div class="row-fluid">
  <div class="span12">
    <ul class="nav nav-tabs">
      <li>
        <?php echo $this->BootstrapHtml->link( 'Lista', array( 'action' => 'index' ), array( 'icon' => 'list' ) ); ?>
      </li>
      <li>
        <?php echo $this->BootstrapHtml->link( 'Ver', array( 'action' => 'view', $this->BootstrapForm->value( 'Setting.id' ) ), array( 'icon' => 'eye-open' ) ); ?>
      </li>
      <li class="active">
        <?php echo $this->BootstrapHtml->link( 'Editar', array( 'action' => 'edit', $this->BootstrapForm->value( 'Setting.id' ) ), array( 'icon' => 'pencil' ) ); ?>
      </li>
      <?php if ( $this->BootstrapForm->value( 'Setting.overwritten' ) ): ?>
        <li>
          <?php echo $this->BootstrapForm->postLink( 'Limpiar', array( 'action' => 'delete', $this->BootstrapForm->value( 'Setting.id' ) ), array( 'icon' => 'trash' ) ); ?>
        </li>
      <?php endif; ?>
    </ul>
  </div>
</div>
<!-- fin admin/record_panel custom para settings -->
<div class="row-fluid">
  <div class="span12">
    <h2>Editar <em>Configuración</em>: <em><?php echo $this->BootstrapForm->value( 'Setting.name' ) ?></em></h2>
    <?php echo $this->BootstrapForm->create( 'Setting', array( 'class' => 'form-horizontal' ) ); ?>
    <fieldset>
      <?php
      echo $this->BootstrapForm->hidden( 'Setting.id' );
      echo $this->BootstrapForm->input( 'Setting.name', array(
        'label'      => 'nombre',
        'helpInline' => '<span class="badge badge-info">no editable</span>',
        'class'      => 'span8',
        'readonly'   => TRUE,
        //'helpBlock' => ''
      ) );
      echo $this->BootstrapForm->input( 'Setting.key', array(
        'label'      => 'clave',
        'helpInline' => '<span class="badge badge-info">no editable</span>',
        'class'      => 'span8',
        'readonly'   => TRUE,
        //'helpBlock' => ''
      ) );
      $config = array(
        'label'     => 'valor',
        'class'     => 'span8',
        'autofocus' => TRUE,
        'helpBlock' => $this->BootstrapForm->value( 'Setting.description' )
      );
      $config = array_merge( $config, $type );
      echo $this->BootstrapForm->input( 'Setting.value', $config );
      ?>
      <div class="form-actions">
        <?php echo $this->BootstrapForm->button( 'Guardar', array( 'class' => 'btn btn-primary' ) ) ?>
        <?php echo $this->BootstrapHtml->link( 'Volver', array( 'action' => 'index' ), array( 'class' => 'btn' ) ); ?>
      </div>
    </fieldset>
    <?php echo $this->BootstrapForm->end(); ?>
  </div>
</div>