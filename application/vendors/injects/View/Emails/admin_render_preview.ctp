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
    <h4>Asunto: <?php echo $email[ 'Email' ][ 'subject' ] ?></h4>
  </div>
</div>
<?php $email = Router::url( array( 'admin' => FALSE, 'action' => 'view', $uuid, $format, '?' => $query ), TRUE ) ?>
<div class="row-fluid">
  <iframe src="<?php echo $email ?>" class="span12 well" height="600px" frameborder="0"></iframe>
</div>
