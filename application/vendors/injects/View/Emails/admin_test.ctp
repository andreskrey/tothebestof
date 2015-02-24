<?php /* @var $this View */ ?>
<!-- record panel custom para emails -->
<div class="row-fluid">
  <div class="span12">
    <ul class="nav nav-tabs">
      <li>
        <?php echo $this->BootstrapHtml->link( 'Lista', array( 'action' => 'index' ), array( 'icon' => 'list' ) ); ?>
      </li>

      <li>
        <?php echo $this->BootstrapHtml->link( 'Ver', array( 'action' => 'view', $email[ 'Email' ][ 'id' ] ), array( 'icon' => 'eye-open' ) ); ?>
      </li>

      <li class="active">
        <?php echo $this->BootstrapHtml->link( 'Enviar Prueba', array( 'action' => 'test', $email[ 'Email' ][ 'id' ] ), array( 'icon' => 'envelope' ) ); ?>
      </li>
      <li>
        <?php echo $this->BootstrapHtml->link( 'Previsualizar', array( 'action' => 'preview', $email[ 'Email' ][ 'id' ] ), array( 'icon' => 'search' ) ); ?>
      </li>
    </ul>
  </div>
</div>
<!-- fin record panel custom para emails -->
<div class="row-fluid">
  <div class="span12">
    <h2>Enviar Prueba: <?php echo $email[ 'Email' ][ 'name' ] ?></h2>
    <?php echo $this->BootstrapForm->create( 'Email', array( 'class' => 'form-horizontal' ) ); ?>
    <fieldset>
      <legend>Ingresá el destinatario del Email de prueba</legend>
      <?php
      echo $this->BootstrapForm->hidden( 'key', array( 'value' => $email[ 'Email' ][ 'key' ] ) );
      echo $this->BootstrapForm->input( 'Email.to_name', array(
        'label'     => 'Nombre',
        'autofocus' => TRUE,
        'required'  => TRUE,
        'class'     => 'span8',
        //'helpInline' => '',
        //'helpBlock' => '',
      ) );
      echo $this->BootstrapForm->input( 'Email.to_email', array(
        'label'    => 'Email',
        'required' => TRUE,
        'class'    => 'span8',
        //'helpInline' => '',
        //'helpBlock' => '',
      ) );
      ?>
      <legend>Completá las variables para personalizar el Email</legend>
      <?php
      foreach ( $email[ 'Email' ][ 'vars' ] as $key => $val )
      {
        echo $this->BootstrapForm->input( "Email.vars.{$val}", array(
          'required' => TRUE,
          'class'    => 'span8',
          //'helpInline' => '',
          //'helpBlock' => '',
        ) );
      }
      ?>
      <div class="form-actions">
        <?php echo $this->BootstrapForm->button( 'Enviar', array( 'class' => 'btn btn-primary' ) ) ?>
        <?php echo $this->BootstrapHtml->link( 'Volver', array( 'action' => 'index' ), array( 'class' => 'btn' ) ); ?>
      </div>
    </fieldset>
    <?php echo $this->BootstrapForm->end(); ?>
  </div>
</div>