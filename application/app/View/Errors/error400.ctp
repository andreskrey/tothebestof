<?php /* @var $this View */ ?>
<?php $this->layout = 'error'; ?>
<?php
$msgs = array(
    400 => 'Bad Request',
    401 => 'Unauthorized',
    404 => 'Not Found',
    403 => 'Forbidden',
    405 => 'Method Not Allowed'
);
?>
<?php $home = Hash::get($this->request->params, 'prefix') == 'admin' ? '/admin/administration/dashboard' : '/'; ?>
<!--404 Tracking Google-->
<script type="text/javascript">// <![CDATA[
    _gaq.push(['_trackEvent', 'Error', '404', 'page: ' + document.location.pathname + document.location.search + ' ref: ' + document.referrer, , true]);
    // ]]></script>
<div class="row-fluid">
    <div class="hero-unit offset2 span8 hero-error">
        <h1><?php echo $error->getCode() . ' ' . $msgs[$error->getCode()] ?></h1>
        <br/>

        <?php if ($error->getCode() == '400') { ?>
            <p>You did something that our hosting didn't like. Try again. Or not.</p>
        <?php } else { ?>
            <p>Hey, I did a major refactor of the website, and now your have to add "/band/" before each artist. Go to
                the
                homepage and use the search bar if you don't want to do that. Sorry for the
                inconvenience!</p>
        <?php } ?>

    </div>
</div>
<script type="text/javascript">(function (i, s, o, g, r, a, m) {
        i['GoogleAnalyticsObject'] = r;
        i[r] = i[r] || function () {
            (i[r].q = i[r].q || []).push(arguments)
        }, i[r].l = 1 * new Date();
        a = s.createElement(o),
            m = s.getElementsByTagName(o)[0];
        a.async = 1;
        a.src = g;
        m.parentNode.insertBefore(a, m)
    })(window, document, 'script', '//www.google-analytics.com/analytics.js', 'ga');

    ga('create', 'UA-31948241-1', 'tothebestof.com');
    ga('require', 'displayfeatures');
    ga('send', 'pageview');</script>