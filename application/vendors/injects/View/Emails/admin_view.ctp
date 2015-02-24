<?php /* @var $this View */ ?>
<!-- record panel custom para emails -->
<div class="row-fluid">
  <div class="span12">
    <ul class="nav nav-tabs">
      <li>
        <?php echo $this->BootstrapHtml->link( 'Lista', array( 'action' => 'index' ), array( 'icon' => 'list' ) ); ?>
      </li>

      <li class="active">
        <?php echo $this->BootstrapHtml->link( 'Ver', array( 'action' => 'view', $email[ 'Email' ][ 'id' ] ), array( 'icon' => 'eye-open' ) ); ?>
      </li>

      <li>
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
    <h2><em><?php echo __( 'Email' ) ?>: <?php echo $email[ 'Email' ][ 'name' ] ?></em></h2>
    <dl class="dl-horizontal well">
      <dt>Id</dt>
      <dd><?php echo h( $email[ 'Email' ][ 'id' ] ); ?></dd>
      <dt>UUID</dt>
      <dd><?php echo h( $email[ 'Email' ][ 'uuid' ] ); ?></dd>
      <dt>Nombre</dt>
      <dd><?php echo h( $email[ 'Email' ][ 'name' ] ); ?></dd>

      <?php if ( isset( $email[ 'Email' ][ 'description' ] ) ): ?>
        <dt>Descripción</dt>
        <dd><?php echo h( $email[ 'Email' ][ 'description' ] ); ?></dd>
      <?php endif; ?>

      <dt>Nombre Clave</dt>
      <dd><?php echo h( $email[ 'Email' ][ 'key' ] ); ?></dd>
      <dt>Formato</dt>
      <dd><?php echo h( $email[ 'Email' ][ 'format' ] ); ?></dd>
      <dt>Remitente</dt>
      <dd><?php echo h( $email[ 'Email' ][ 'from_name' ] ); ?></dd>
      <dt>Email de Remitente</dt>
      <dd><?php echo h( $email[ 'Email' ][ 'from_email' ] ); ?></dd>
      <dt>Asunto</dt>
      <dd><?php echo h( $email[ 'Email' ][ 'subject' ] ); ?></dd>
      <dt>Email</dt>
      <dd>
        <a href="<?php echo Router::url( array( 'action' => 'admin_preview', $email[ 'Email' ][ 'id' ] ) ); ?>">Previsualizar Email</a>
      </dd>
    </dl>
  </div>
</div>