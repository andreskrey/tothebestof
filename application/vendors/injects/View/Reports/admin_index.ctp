<?php /* @var $this View */ ?>
<div class="row-fluid">
  <div class="span12">
    <h2>Generar Reportes</h2>
  </div>
  <?php $index = 0; ?>
  <?php foreach ( $reports as $key => $report ): ?>
    <?php if ( $index % 3 == 0 ): ?>
      <div class="row-fluid show-grid">
    <?php endif; ?>
    <li class="span4">
      <h4><?php echo $report[ 'name' ] ?></h4>
      <p><?php echo $report[ 'description' ] ?></p>
      <p>
        <a class="btn" href="<?php echo Router::url( array( 'action' => 'export', $key ) ) ?>"><i class="icon-download-alt"></i> Generar</a>
      </p>
    </li>
    <?php if ( $index++ % 3 == 2 ): ?>
      </div>
    <?php endif; ?>
  <?php endforeach; ?>
</div>
