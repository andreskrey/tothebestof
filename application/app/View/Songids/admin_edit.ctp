<?php /* @var $this View */ ?>
<?php echo $this->element( 'admin/panels/record_panel', array( 'id' => $this->BootstrapForm->value( 'Songid.id' ) ) ); ?>
<div class="row-fluid">
	<div class="span12">
    <h2>Editar <em>Songid</em>: <em><?php echo $this->BootstrapForm->value('Songid.name') ?></em></h2>
		<?php echo $this->BootstrapForm->create( 'Songid', array( 'class' => 'form-horizontal' ) ); ?>
		<fieldset>
			<?php
			echo $this->BootstrapForm->input( 'Songid.id', array(
        'label' => 'id',
				'class' => 'span8',
        //'helpInline' => '',
        //'helpBlock' => '',
			) );
			echo $this->BootstrapForm->input( 'Songid.band_id', array(
        'label' => 'band_id',
				'class' => 'span8',
        //'helpInline' => '',
        //'helpBlock' => '',
			) );
			echo $this->BootstrapForm->input( 'Songid.name', array(
        'label' => 'nombre',
				'class' => 'span8',
				'autofocus' => true,
        //'helpInline' => '',
        //'helpBlock' => '',
			) );
			echo $this->BootstrapForm->input( 'Songid.songid', array(
        'label' => 'songid',
				'class' => 'span8',
        //'helpInline' => '',
        //'helpBlock' => '',
			) );
      ?>
			<div class="form-actions">
				<?php echo $this->BootstrapForm->button( 'Guardar', array( 'class' => 'btn btn-primary' ) ) ?>
				<?php echo $this->BootstrapHtml->link( 'Volver', $this->Admin->getPreviousUrl(), array( 'class' => 'btn' ) ); ?>
			</div>
		</fieldset>
		<?php echo $this->BootstrapForm->end(); ?>
	</div>
</div>