<?php /* @var $this View */ ?>
<div class="formsUser clearfix">
    <a href="#" class="closePopup">
        <span class="glyphicon glyphicon-remove"></span>
    </a>

    <?php if (!$this->Session->read('user.logged')) { ?>

        <!-- LOG IN -->
        <?php echo $this->Form->create('User', array('id' => 'formUserLogin', 'class' => 'formUser', 'autocomplete' => 'on', 'url' => array('controller' => 'users', 'action' => 'login'))); ?>

        <h3>Log in</h3>

        <div class="subtitle">or <a class="fireForm" href="#formUserRegister">Join the community!</a></div>
        <div>
            <?php echo $this->Form->text('username', array('placeholder' => 'username')); ?>
            <?php echo $this->Form->error('username'); ?>
            <span class="glyphicon glyphicon-user"></span>
        </div>
        <div>
            <?php echo $this->Form->text('password', array('placeholder' => 'password', 'type' => 'password')); ?>
            <?php echo $this->Form->error('password'); ?>
            <span class="glyphicon glyphicon-asterisk"></span>
            <a class="noteText fireForm" href="#formUserPassword">pst.. forgot your
                password?</a>
        </div>
        <div>
            <?php echo $this->Form->checkbox('rememberMe') ?>
            <?php echo $this->Form->label('rememberMe', 'Keep me logged in') ?>
        </div>
        <?php echo $this->Form->end(array('label' => 'Go!', 'class' => 'btn  btn-success')); ?>


        <!-- REGISTER -->

        <?php echo $this->Form->create('User', array('id' => 'formUserRegister', 'class' => 'formUser', 'autocomplete' => 'on', 'url' => array('controller' => 'users', 'action' => 'add'))); ?>

        <h3>Register</h3>

        <div class="subtitle">or <a class="fireForm" href="#formUserLogin">log in!</a></div>

        <span class="noteText">Registering allows you to favorite bands, save playlists and gives you access to a secret society that may or may not exist.</span>

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
        <span class="noteText">Email is optional &mdash;
            will be used only if you plan to forget <br/>your password in the future.</span>
        </div>
        <?php echo $this->Form->end(array('label' => 'Go!', 'class' => 'btn btn-success')); ?>


        <!-- RECOVER PASSWORD -->

        <?php echo $this->Form->create('User', array('id' => 'formUserPassword', 'class' => 'formUser', 'autocomplete' => 'on', 'url' => array('controller' => 'users', 'action' => 'recover'))); ?>
        <h3>Forgot your password?</h3>

        <div class="subtitle">Don't worry, happens to the best of us!</div>

        <div>
            <?php echo $this->Form->text('email', array('placeholder' => 'email')); ?>
            <span class="noteText">Remember &mdash; it's the one you used to register</span>
            <?php echo $this->Form->error('email'); ?>
        </div>
        <?php echo $this->Form->end(array('label' => 'Go!', 'class' => 'btn btn-success')); ?>

    <?php } else { ?>

        <!-- DELETE ACCOUNT -->

        <div id="formUserRemove" class="formUser">
            <h3>Delete my<br/> Account</h3>

            <div class="subtitle">Are you hyper mega sure?</div>
            <?php echo $this->Form->postButton('Yeah, fuck this place', array('controller' => 'users', 'action' => 'remove'), array('data' => array('delete' => true), 'class' => 'btn btn-danger')); ?>
            <button class="btn btn-success"
                    href="<?php echo Router::url(array('controller' => 'users', 'action' => 'edit'), true) ?>">No way, I
                clicked by
                mistake!
            </button>

        </div>

        <!-- EDIT MY ACCOUNT -->
        <div id="formUserEdit" class="formUser">
            <?php echo $this->Form->create('User', array('autocomplete' => 'on', 'url' => array('controller' => 'users', 'action' => 'edit'))); ?>
            <?php echo $this->Form->text('id', array('value' => $this->Session->read('user.id'), 'type' => 'hidden')) ?>
            <h3>Edit my account</h3>

            <div class="subtitle">as many times as you want!</div>


            <div>
                <?php echo $this->Form->text('username', array('disabled' => 'disabled', 'value' => $this->Session->read('user.username'))); ?>
                <?php echo $this->Form->error('username'); ?>
                <span class="glyphicon glyphicon-user"></span>
            </div>
            <div>
                <?php echo $this->Form->text('password', array('placeholder' => 'password', 'type' => 'password')); ?>
                <?php echo $this->Form->error('password'); ?>
                <span class="glyphicon glyphicon-asterisk"></span>
            </div>
            <div>
                <?php echo $this->Form->text('email', array('value' => $this->Session->read('user.email'))); ?>
                <?php echo $this->Form->error('email'); ?>
                <span class="glyphicon glyphicon-envelope"></span>
        <span class="noteText">Optional &mdash;
            only used if you plan to forget your <br/>password in the future</span>
            </div>

            <?php echo $this->Form->end(array('label' => 'Edit!', 'class' => 'btn btn-success')); ?>
            <a class="btn btn-danger" href="<?php echo Router::url(array('controller' => 'users', 'action' => 'remove'), true) ?>">Delete account</a>
        </div>
    <?php } ?>
</div>