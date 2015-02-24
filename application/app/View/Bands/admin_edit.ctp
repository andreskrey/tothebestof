<?php /* @var $this View */ ?>
<?php echo $this->element( 'admin/panels/record_panel', array( 'id' => $this->BootstrapForm->value( 'Band.id' ) ) ); ?>
<div class="row-fluid">
	<div class="span12">
    <h2>Editar <em>Banda</em>: <em><?php echo $this->BootstrapForm->value('Band.id') ?></em></h2>
		<?php echo $this->BootstrapForm->create( 'Band', array( 'class' => 'form-horizontal' ) ); ?>
		<fieldset>
			<?php
			echo $this->BootstrapForm->input( 'Band.id', array(
        'label' => 'id',
				'class' => 'span8',
        //'helpInline' => '',
        //'helpBlock' => '',
			) );
			echo $this->BootstrapForm->input( 'Band.band', array(
        'label' => 'Banda',
				'class' => 'span8',
				'autofocus' => true,
        //'helpInline' => '',
        //'helpBlock' => '',
			) );
			echo $this->BootstrapForm->input( 'Band.bio', array(
        'label' => 'Bio',
				'class' => 'span8',
        //'helpInline' => '',
        //'helpBlock' => '',
			) );
			echo $this->BootstrapForm->input( 'Band.pic', array(
        'label' => 'Imagen',
				'class' => 'span8',
        //'helpInline' => '',
        //'helpBlock' => '',
			) );
            echo $this->BootstrapForm->input( 'Band.topten', array(
        'label' => 'Top ten',
				'class' => 'span8',
        //'helpInline' => '',
        //'helpBlock' => '',
			) );
			echo $this->BootstrapForm->input( 'Band.hits', array(
        'label' => 'Hits',
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