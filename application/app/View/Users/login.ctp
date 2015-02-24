<div role="main" class="content default clearfix">
    <h2>Welcome back!
        <span>What was your name again?</span>
    </h2>

    <?php echo $this->Form->create('User'); ?>
    <div>
        <?php echo $this->Form->text('username', array('placeholder' => 'name pretty please')); ?>
        <?php echo $this->Form->error('username'); ?>
        <span class="glyphicon glyphicon-user"></span>
    </div>
    <div>
        <?php echo $this->Form->text('password', array('placeholder' => 'password', 'type' => 'password')); ?>
        <?php echo $this->Form->error('password'); ?>
        <span class="glyphicon glyphicon-asterisk"></span>
    </div>
    <div>
        <?php echo $this->Form->checkbox('rememberMe') ?>
        <?php echo $this->Form->label('rememberMe', 'Keep me logged in') ?>
    </div>
    <?php echo $this->Form->end(array('label' => 'Login', 'class' => 'btn btn-success')); ?>
</div>