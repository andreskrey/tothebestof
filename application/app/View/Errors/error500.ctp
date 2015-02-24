<?php /* @var $this View */ ?>
<?php $this->layout = 'error'; ?>
<?php
$msgs = array(
    500 => 'Internal Server Error',
    501 => 'Not Implemented',
);
?>
<?php $home = Hash::get($this->request->params, 'prefix') == 'admin' ? '/admin/administration/dashboard' : '/'; ?>
<div class="row-fluid">
    <div class="hero-unit offset2 span8 hero-error">
        <h1><?php echo $error->getCode() . ' ' . Hash::get($msgs, $error->getCode()) ?></h1>
        <br/>
        <?php if (Configure::read('debug') > 0): ?>
            <p><?php echo $error->getMessage(); ?></p>
        <?php endif; ?>
        <h1>WHAT?</h1>
        <br/>

        <p>Something super horrible happened with the server while gathering info about that awesome band. Try again. I hope this time
            you don't break anything.</p>
    </div>
</div>