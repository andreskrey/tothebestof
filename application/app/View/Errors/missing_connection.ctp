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
        <h1>WHAT?</h1>
        <br/>

        <p>Something super horrible happened with the database while gathering info about that awesome band. Try again. I hope this time you don't break anything.</p>


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