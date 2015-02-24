<?php /* @var $this View */ ?>
<?php echo h( $value ) ?>
 <?php echo $this->BootstrapForm->postLink( '<i class="icon-minus-sign"></i>', array( 'action' => 'sort', $id, $field, -1 ), array( 'escape' => FALSE, 'data-title' => 'Disminuir', 'rel' => 'tooltip' ) ) ?>
 <?php echo $this->BootstrapForm->postLink( '<i class="icon-plus-sign"></i>', array( 'action' => 'sort', $id, $field, 1 ), array( 'escape' => FALSE, 'data-title' => 'Aumentar', 'rel' => 'tooltip' ) ) ?>
