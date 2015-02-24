<?php /* @var $this View */ ?>
<?php echo $this->Html->css( 'admin/login', NULL, array( 'block' => 'cssBottom' ) ) ?>
<div class="container">
  <?php echo $this->Form->create( 'Administrator', array( 'url' => array('controller'  => 'administration', 'action'  => 'login'), 'class' => 'form-signin' ) ); ?>
  <h2 class="form-signin-heading">Ingresa tus credenciales</h2>
  <?php echo $this->Form->text( 'username', array( 'class' => 'input-block-level', 'placeholder' => 'Usuario', 'autofocus' => true ) ) ?>
  <?php echo $this->Form->password( 'password', array( 'class' => 'input-block-level', 'placeholder' => 'Password' ) ) ?>
  <label class="checkbox">
    <?php echo $this->Form->checkbox( 'remember_me' ) ?> Recordarme por hoy
  </label>
  <button class="btn btn-large btn-primary" type="submit">Ingresar</button>
  <?php echo $this->Form->end(); ?>
</div>