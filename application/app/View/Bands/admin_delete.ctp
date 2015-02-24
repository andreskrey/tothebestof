<?php /* @var $this View */ ?>
<?php echo $this->element( 'admin/panels/record_panel', array( 'id' => $band['Band']['id'] ) ); ?>
<div class="row-fluid">
	<div class="span12">
		<h2><?php echo sprintf( '¿Está seguro que desea borrar de la colección <em>%s</em> el registro <em>%s</em>?', 'Banda', $band['Band']['id'] ) ?></h2>
		<!-- if si hay relacionados de tipo hasOne y hasMany -->

		<?php if ( count( $band ) > 1 ): ?>
			<h5>Quedarán huérfanos o se borrarán los siguientes registros asociados</h5>
			<ul>
				<!-- loopear por cada hasOne -->
				<!-- end loop -->
				<!-- loopear por cada hasMany -->
        <?php if ( count( $band[ 'Songid' ] ) ): ?>
          <?php foreach ( $band[ 'Songid' ] as $related ): ?>
            <li><?php echo $this->BootstrapHtml->link( $related[ 'name' ], array( 'controller' => 'songids', 'action' => 'view', $related[ 'id' ] ) ) ?></li>
          <?php endforeach; ?>
        <?php endif; ?>
			</ul>
		<?php endif; ?>
		<!-- end if -->
		<div class="form-actions">
			<?php echo $this->BootstrapForm->postLink( 'Estoy seguro', array( 'action' => 'delete', $band['Band']['id'] ), array( 'class' => 'btn btn-danger', 'icon' => 'ok white' ) ); ?>
			<?php echo $this->BootstrapHtml->link( 'Mejor no', array( 'action' => 'index' ), array( 'class' => 'btn' ) ); ?>
		</div>
	</div>
</div>
