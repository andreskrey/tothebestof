<div role="main" class="content default clearfix">
    <h2>Have something to say?
        <span>I'm happy to hear it!</span>
    </h2>



    <?php echo $this->Form->create('Contact', array('class' => 'contactArtur clearfix')); ?>
    <div>
        <?php echo $this->Form->input('namemail', array('div' => false, 'label' => false, 'placeholder' => "Name and email (optional, but I like to know who I'm talking to, required if you want a reply)")); ?>
    </div>
    <div>
        <?php echo $this->Form->textarea('message', array('div' => false, 'label' => false, 'placeholder' => "Message")); ?>
        <?php echo $this->Form->error('message'); ?></div>

    <?php echo $this->Form->end(array('label' => 'Send!', 'class' => 'btn btn-success')); ?>


    <p>Alternatively, you can send me an email to <b>andy at tothebestof dot com</b>. <br/>
        If you want an answer make sure you input a <b>real and valid email address</b>! Fill the first field with it.</p>
</div>
