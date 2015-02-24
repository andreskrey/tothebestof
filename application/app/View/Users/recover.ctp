<div role="main" class="content default clearfix">
    <h2>Forgot your password?
        <span>Psss, no problem.</span>
    </h2>



    <?php echo $this->Form->create('User'); ?>
    <div>
        <?php echo $this->Form->text('email', array('placeholder' => 'Tell me the email you used to register')); ?>
        <?php echo $this->Form->error('email'); ?>
        <span class="glyphicon glyphicon-envelope"></span>
    </div>
    <?php echo $this->Form->end(array('label' => 'Go!', 'class' => 'btn btn-success')); ?>
</div>