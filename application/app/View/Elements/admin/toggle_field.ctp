<?php /* @var $this View */ ?>
<?php echo $this->BootstrapForm->postLink(
	'<i class="icon-' . ( $value ? "ok" : "remove" ) . '"></i>',
	array( 'action' => 'toggle', $id, $field ),
	array( 'escape' => FALSE, 'data-title' => 'Click para cambiar', 'rel' => 'tooltip' )
) ?>