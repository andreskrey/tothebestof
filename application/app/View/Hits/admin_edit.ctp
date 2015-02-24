<?php /* @var $this View */ ?>
<?php echo $this->element( 'admin/panels/record_panel', array( 'id' => $this->BootstrapForm->value( 'Hit.id' ) ) ); ?>
<div class="row-fluid">
	<div class="span12">
    <h2>Editar <em>Hit</em>: <em><?php echo $this->BootstrapForm->value('Hit.name') ?></em></h2>
		<?php echo $this->BootstrapForm->create( 'Hit', array( 'class' => 'form-horizontal' ) ); ?>
		<fieldset>
			<?php
			echo $this->BootstrapForm->input( 'Hit.id', array(
        'label' => 'id',
				'class' => 'span8',
        //'helpInline' => '',
        //'helpBlock' => '',
			) );
			echo $this->BootstrapForm->input( 'Hit.band_id', array(
        'label' => 'band_id',
				'class' => 'span8',
        //'helpInline' => '',
        //'helpBlock' => '',
			) );
			echo $this->BootstrapForm->input( 'Hit.name', array(
        'label' => 'nombre',
				'class' => 'span8',
				'autofocus' => true,
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