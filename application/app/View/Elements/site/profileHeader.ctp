<?php /* @var $this View */ ?>


<div class="donated">
    <?php if ($this->Session->read('user.donated')) { ?>
        <a href="#"><span class="glyphicon glyphicon-certificate"></span></a>
        <span class="tooltip">You're one of the heroes that donated. Mega thank you!</span>
    <?php } else { ?>
        <a href="<?php echo Router::url(array('controller' => 'pages', 'action' => 'halp'), true) ?>"><span class="glyphicon glyphicon-comment"></span></a>
        <span class="tooltip">Donate today and get a bitchin' badge instead of this annoying comment icon. Chicks and/or dudes, according to your preference, will totally dig it.</span>
    <?php } ?>
</div>

