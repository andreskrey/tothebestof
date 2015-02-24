<?php /* @var $this View */ ?>
<?php echo $this->element( 'admin/panels/record_panel', array( 'id' => $this->BootstrapForm->value( 'Favorite.id' ) ) ); ?>
<div class="row-fluid">
	<div class="span12">
    <h2>Editar <em>Favorita</em>: <em><?php echo $this->BootstrapForm->value('Favorite.user_id') ?></em></h2>
		<?php echo $this->BootstrapForm->create( 'Favorite', array( 'class' => 'form-horizontal' ) ); ?>
		<fieldset>
			<?php
			echo $this->BootstrapForm->input( 'Favorite.id', array(
        'label' => 'id',
				'class' => 'span8',
        //'helpInline' => '',
        //'helpBlock' => '',
			) );
			echo $this->BootstrapForm->input( 'Favorite.user_id', array(
        'label' => 'user_id',
				'class' => 'span8',
        //'helpInline' => '',
        //'helpBlock' => '',
			) );
			echo $this->BootstrapForm->input( 'Favorite.band', array(
        'label' => 'Banda',
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