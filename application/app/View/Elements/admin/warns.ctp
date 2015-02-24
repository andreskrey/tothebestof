<?php /* @var $this View */ ?>
<?php if ( Configure::check( 'Warn' ) ): ?>
  <?php $warns = Configure::read( 'Warn' ) ?>
  <div class="row-fluid">
    <div class="span12">
      <?php foreach ( $warns as $id => $warn ): ?>
        <div class="alert">
          <strong>Cuidado!</strong> <?php echo $warn ?>
          <?php echo $this->BootstrapForm->postLink( '(limpiar)', array( 'controller' => 'settings', 'action' => 'delete', $id ) ) ?>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
<?php endif; ?>