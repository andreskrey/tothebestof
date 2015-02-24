<div role="main" class="content default clearfix">
    <h2 class="noFloat">Are you super mega sure?
        <span>I will delete <em>everything</em> and act like <em>I don't know you</em> next time you're around here.</span></h2>

    <?php echo $this->Form->postButton('Yeah, fuck this place', array('action' => 'remove'), array('data' => array('delete' => true), 'class' => 'btn btn-danger ')); ?>


    <a class="btn btn-success marginTop" href="<?php echo Router::url(array('action' => 'edit'), true) ?>">No way, I clicked by mistake!</a>
</div>