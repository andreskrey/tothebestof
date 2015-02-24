<div role="main" class="content default profile clearfix">
    <div class="profileHeader clearfix">
        <h2>Edit your profile</h2>
        <?php echo $this->element('site/profileHeader') ?>

        <div class="buttonMenu">
            <a class="btn btn-default" href="<?php echo Router::url(array('action' => 'info'), true) ?>">Music</a>

        </div>

    </div>
    <?php echo $this->Form->create('User', array('novalidate'=>true)); ?>
    <?php echo $this->Form->input('id', array('value' => $data['User']['id'], 'type' => 'hidden')) ?>
    <div>
        <?php echo $this->Form->text('username', array('placeholder' => 'email', 'disabled' => 'disabled', 'value' => $data['User']['username'])); ?>
        <?php echo $this->Form->error('username'); ?>
    </div>
    <div>
        <?php echo $this->Form->text('password', array('placeholder' => 'password', 'type' => 'password' )); ?>
        <?php echo $this->Form->error('password'); ?>
        <span class="glyphicon glyphicon-asterisk"></span>
    </div>
    <div>
        <?php echo $this->Form->text('email', array('placeholder' => 'email', 'value' => $data['User']['email'])); ?>
        <?php echo $this->Form->error('email'); ?>
        <span class="glyphicon glyphicon-envelope"></span>
        <span class="noteText">Optional &mdash; only used if you plan to forget your password in the future.</span>
    </div>
    <?php echo $this->Form->end(array('label' => 'Submit', 'class' => 'btn btn-success')); ?>
    <a class="btn btn-danger" href="<?php echo Router::url(array('action' => 'remove'), true) ?>">Delete account</a>
</div>



