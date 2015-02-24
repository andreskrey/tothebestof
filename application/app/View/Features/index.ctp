<div role="main" class="content default clearfix">
    <h2>Have an idea?
        <span>Awesome! happy to hear it. <br/><strong>Don't bother asking for a mobile app. It's not going to happen.</strong></span>
    </h2>

    <?php echo $this->Form->create('Feature'); ?>
    <div>
        <?php echo $this->Form->textarea('description', array('label' => false, 'div' => false, 'placeholder' => 'Tell me your idea')); ?>
        <?php echo $this->Form->error('description'); ?></div>
    <div>
        <?php echo $this->Form->text('by', array('label' => false, 'div' => false, 'placeholder' => "Name and email")); ?>
        <?php echo $this->Form->error('by'); ?>
        <span class="noteText">Optional&mdash; only if you want me to contact you. <em>Will not be published.</em></span>
    </div>

    <?php echo $this->Form->end(array('label' => 'Submit', 'class' => 'btn btn-success')); ?>

    <p>Check out the <a href="<?php echo Router::url(array('action' => 'ranking')); ?>">ranking</a> and vote for ideas
        submitted by other users.</p>
</div>