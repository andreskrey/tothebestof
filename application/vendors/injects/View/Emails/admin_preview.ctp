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

      <li>
        <?php echo $this->BootstrapHtml->link( 'Enviar Prueba', array( 'action' => 'test', $email[ 'Email' ][ 'id' ] ), array( 'icon' => 'envelope' ) ); ?>
      </li>
      <li class="active">
        <?php echo $this->BootstrapHtml->link( 'Previsualizar', array( 'action' => 'preview', $email[ 'Email' ][ 'id' ] ), array( 'icon' => 'search' ) ); ?>
      </li>
    </ul>
  </div>
</div>
<!-- fin record panel custom para emails -->
<div class="row-fluid">
  <div class="span12">
    <h2>Previsualizar: <?php echo $email[ 'Email' ][ 'name' ] ?></h2>
    <?php echo $this->BootstrapForm->create( 'Email', array( 'class' => 'form-horizontal' ) ); ?>
    <fieldset>
      <legend>Completá las variables para previsualizar el Email</legend>
      <?php
      echo $this->BootstrapForm->hidden( 'uuid', array( 'value' => $email[ 'Email' ][ 'uuid' ] ) );
      if ( $email[ 'Email' ][ 'format' ] == 'both' )
      {
        echo $this->BootstrapForm->input( "Email.format", array(
          'type'     => 'select',
          'options'  => array( 'html' => 'HTML', 'text' => 'Texto Plano' ),
          'required' => TRUE,
          'class'    => 'span8',
          //'helpInline' => '',
          //'helpBlock' => '',
        ) );
      }
      $autofocus = TRUE;
      foreach ( $email[ 'Email' ][ 'vars' ] as $key => $val )
      {
        echo $this->BootstrapForm->input( "Email.query.{$val}", array(
          'value'     => 'XXXXXX',
          'autofocus' => $autofocus,
          'required'  => TRUE,
          'class'     => 'span8',
          //'helpInline' => '',
          //'helpBlock' => '',
        ) );
        $autofocus = FALSE;
      }
      ?>
      <div class="form-actions">
        <?php echo $this->BootstrapForm->button( 'Previsualizar', array( 'class' => 'btn btn-primary' ) ) ?>
        <?php echo $this->BootstrapHtml->link( 'Volver', array( 'action' => 'index' ), array( 'class' => 'btn' ) ); ?>
      </div>
    </fieldset>
    <?php echo $this->BootstrapForm->end(); ?>
  </div>
</div>