<?php /* @var $this View */ ?>
<?php echo $this->element( 'admin/panels/record_panel', array( 'id' => $this->BootstrapForm->value( 'User.id' ) ) ); ?>
<div class="row-fluid">
	<div class="span12">
    <h2>Editar <em>Usuario</em>: <em><?php echo $this->BootstrapForm->value('User.id') ?></em></h2>
		<?php echo $this->BootstrapForm->create( 'User', array( 'class' => 'form-horizontal' ) ); ?>
		<fieldset>
			<?php
			echo $this->BootstrapForm->input( 'User.id', array(
        'label' => 'id',
				'class' => 'span8',
        //'helpInline' => '',
        //'helpBlock' => '',
			) );
			echo $this->BootstrapForm->input( 'User.username', array(
        'label' => 'Usuario',
				'class' => 'span8',
				'autofocus' => true,
        //'helpInline' => '',
        //'helpBlock' => '',
			) );
			echo $this->BootstrapForm->input( 'User.password', array(
        'label' => 'Password',
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