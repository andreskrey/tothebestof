<?php /* @var $this View */ ?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <?php echo $this->Html->charset(); ?>
    <title>
      <?php echo $title_for_layout; ?>
    </title>

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <meta name="description" content="">
    <meta name="author" content="">

    <?php
    echo $this->Html->meta( 'icon' );

    echo $this->Html->css( 'base/cake', NULL, array( 'block' => 'css' ) );
    echo $this->Html->css( 'bootstrap/bootstrap', NULL, array( 'block' => 'css' ) );
    echo $this->Html->css( 'bootstrap/responsive', NULL, array( 'block' => 'css' ) );

    echo $this->fetch( 'meta' );
    echo $this->fetch( 'css' );
    echo $this->fetch( 'script' );
    echo $this->fetch( 'cssBottom' );
    ?>
    <style type="text/css">
      .hero-error { text-align : center; margin-top : 20px; }
    </style>
  </head>
  <body>
    <div class="container-fluid">
      <?php echo $this->fetch( 'content' ); ?>

      <?php if ( Configure::read( 'debug' ) > 0 ): ?>
        <div class="row-fluid">
          <div class="well">
            <?php echo $this->element( 'exception_stack_trace' ); ?>
          </div>
        </div>
      <?php endif; ?>
    </div>
    <?php echo $this->fetch( 'scriptBottom' );; ?>
  </body>
</html>