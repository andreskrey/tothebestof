<div role="main" class="content default clearfix">
    <h2>Hello stranger!
        <span>How u doin'?</span>
    </h2>



    <?php echo $this->Form->create('User'); ?>
    <div>
        <?php echo $this->Form->text('username', array('placeholder' => 'username')); ?>
        <?php echo $this->Form->error('username'); ?>
        <span class="glyphicon glyphicon-user"></span>
    </div>
    <div>
        <?php echo $this->Form->text('password', array('placeholder' => 'password', 'type' => 'password')); ?>
        <?php echo $this->Form->error('password'); ?>
        <span class="glyphicon glyphicon-asterisk"></span>
    </div>
    <div>
        <?php echo $this->Form->text('email', array('placeholder' => 'email')); ?>
        <?php echo $this->Form->error('email'); ?>
        <span class="glyphicon glyphicon-envelope"></span>
        <span class="noteText">Optional &mdash;
            only used if you plan to forget your password in the future.</span>
    </div>

    <?php echo $this->Form->end(array('label' => 'Register', 'class' => 'btn btn-success')); ?>
</div>