<?php /* @var $this View */ ?>
<?php echo $this->element( 'admin/panels/record_panel', array( 'id' => $favorite['Favorite']['id'] ) ); ?>
<div class="row-fluid">
	<div class="span12">
		<h2><?php echo sprintf( '¿Está seguro que desea borrar de la colección <em>%s</em> el registro <em>%s</em>?', 'Favorita', $favorite['Favorite']['user_id'] ) ?></h2>
		<!-- if si hay relacionados de tipo hasOne y hasMany -->

		<?php if ( count( $favorite ) > 1 ): ?>
			<h5>Quedarán huérfanos o se borrarán los siguientes registros asociados</h5>
			<ul>
				<!-- loopear por cada hasOne -->
				<!-- end loop -->
				<!-- loopear por cada hasMany -->
			</ul>
		<?php endif; ?>
		<!-- end if -->
		<div class="form-actions">
			<?php echo $this->BootstrapForm->postLink( 'Estoy seguro', array( 'action' => 'delete', $favorite['Favorite']['id'] ), array( 'class' => 'btn btn-danger', 'icon' => 'ok white' ) ); ?>
			<?php echo $this->BootstrapHtml->link( 'Mejor no', array( 'action' => 'index' ), array( 'class' => 'btn' ) ); ?>
		</div>
	</div>
</div>
