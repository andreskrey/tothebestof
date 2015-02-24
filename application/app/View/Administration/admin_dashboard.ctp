<?php /* @var $this View */ ?>
<style type="text/css">
  .jumbotron {
    margin     : 80px 80px;
    text-align : center;
  }

  .jumbotron h1 {
    font-size   : 60px;
    line-height : 1;
  }

  .jumbotron .lead {
    font-size   : 20px;
    line-height : 1.25;
  }

  .jumbotron .btn {
    font-size : 21px;
    padding   : 14px 24px;
  }
</style>
<div class="jumbotron">
  <h1>Bienvenido al Administrador!</h1>
  <p class="lead">
    Este Dashboard está para tunear según cada proyecto, si lo amerita, claro.
    <br> Sino simplemente un cálido mensaje de bienvenida alcanza.
  </p>
  <a class="btn btn-large btn-inverse" href="<?php echo Router::url( array( 'controller' => 'settings' ) ); ?>">Configuración</a>
</div>