<?php /* @var $this View */ ?>
<?php echo $this->element( 'admin/panels/model_panel' ); ?>
<div class="row-fluid">
	<div class="span12">
    <h2>Agregar <em>Playlist</em></h2>
		<?php echo $this->BootstrapForm->create( 'Playlist', array( 'class' => 'form-horizontal' ) ); ?>
		<fieldset>
			<?php
			echo $this->BootstrapForm->input( 'Playlist.playlist_uuid', array(
        'label' => 'UUID',
				'class' => 'span8',
				'autofocus' => true,
        //'helpInline' => '',
        //'helpBlock' => '',
			) );
			echo $this->BootstrapForm->input( 'Playlist.songids', array(
        'label' => 'Songids',
				'class' => 'span8',
        //'helpInline' => '',
        //'helpBlock' => '',
			) );
      ?>
			<div class="form-actions">
				<?php echo $this->BootstrapForm->button( 'Guardar', array( 'class' => 'btn btn-primary' ) ) ?>
				<?php echo $this->BootstrapForm->button( 'Guardar y crear otro', array( 'name' => '_addAnother', 'value' => TRUE, 'class' => 'btn btn-info btn-primary' ) ) ?>
				<?php echo $this->BootstrapHtml->link( 'Volver', $this->Admin->getPreviousUrl(), array( 'class' => 'btn' ) ); ?>
			</div>
		</fieldset>
		<?php echo $this->BootstrapForm->end(); ?>
	</div>
</div>