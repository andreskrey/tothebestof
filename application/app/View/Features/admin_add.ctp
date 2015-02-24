<?php /* @var $this View */ ?>
<?php echo $this->element( 'admin/panels/model_panel' ); ?>
<div class="row-fluid">
	<div class="span12">
    <h2>Agregar <em>Feature</em></h2>
		<?php echo $this->BootstrapForm->create( 'Feature', array( 'class' => 'form-horizontal' ) ); ?>
		<fieldset>
			<?php
			echo $this->BootstrapForm->input( 'Feature.description', array(
        'label' => 'Descripcion',
				'class' => 'span8',
				'autofocus' => true,
        //'helpInline' => '',
        //'helpBlock' => '',
			) );
			echo $this->BootstrapForm->input( 'Feature.by', array(
        'label' => 'By',
				'class' => 'span8',
        //'helpInline' => '',
        //'helpBlock' => '',
			) );
			echo $this->BootstrapForm->input( 'Feature.votes', array(
        'label' => 'Votos',
				'class' => 'span8',
        //'helpInline' => '',
        //'helpBlock' => '',
			) );
			echo $this->BootstrapForm->input( 'Feature.enabled', array(
        'label' => 'Habilitado',
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