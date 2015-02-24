<?php /* @var $this View */ ?>
<!doctype html>
<!--[if IE 7 ]>
<html lang="es" class="no-js ie7"> <![endif]-->
<!--[if IE 8 ]>
<html lang="es" class="no-js ie8"> <![endif]-->
<!--[if IE 9 ]>
<html lang="es" class="no-js ie9"> <![endif]-->
<!--[if (gt IE 9)|!(IE)]><!-->
<html lang="es" class="no-js"> <!--<![endif]-->
<head>
    <?php echo $this->Html->charset(); ?>

    <title><?php if (isset($data['title_for_layout'])) {
            echo mb_strtoupper($data['title_for_layout']);
        } else {
            echo 'I JUST WANT TO LISTEN TO THE BEST OF';
        } ?></title>
    <meta name="description" content="Listen to the very top ten tracks of <?php if (isset($data['artist_name'])) {
        echo $data['artist_name'];
    } else {
        echo 'any band';
    } ?>, quick and easy"/>
    <meta name="keywords"
          content="music, top ten, favorite, mp3, wav, cd, band, song, theme, list, listing, grooveshark, last.fm"/>
    <meta property="og:description"
          content="Listen to the very top ten tracks of <?php if (isset($data['artist_name'])) {
              echo $data['artist_name'];
          } else {
              echo 'any band';
          } ?>, quick and easy"/>
    <meta name="viewport" content="user-scalable=no, initial-scale=1.0, maximum-scale=1.0, width=device-width">
    <meta name="apple-mobile-web-app-capable" content="yes"/>

    <link href='http://fonts.googleapis.com/css?family=Ubuntu:300,400,500,700' rel='stylesheet' type='text/css'>
    <?php
    echo $this->Html->meta('icon');

    echo $this->AssetCompress->css('site.css', array('block' => 'css', 'raw' => Configure::read('AssetCompress.raw')));
    echo $this->AssetCompress->includeCss();
    echo $this->AssetCompress->script('site.js', array('block' => 'scriptMiddle', 'raw' => Configure::read('AssetCompress.raw')));

    echo $this->fetch('meta');
    echo $this->fetch('css');
    echo $this->fetch('script');
    echo $this->fetch('cssBottom');
    ?>

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->

</head>
<body>

<div class="cover"></div>
<div class="container">
    <header>
        <div class="menuUser clearfix">
            <ul>
                <?php if (!$this->Session->read('user.logged')) { ?>
                    <li><a class="fireForm" href="#formUserLogin">Login</a></li>
                    <li>|</li>
                    <li><a class="fireForm" href="#formUserRegister">Register</a></li>
                <?php } else { ?>
                    <li>
                        <a href="<?php echo Router::url(array('controller' => 'users', 'action' => 'edit')); ?>">Hi <?php echo $this->Session->read('user.username') ?>
                            !</a><?php echo $this->element('site/profileHeader') ?></li>
                    <li>|</li>
                    <li><a href="<?php echo Router::url(array('controller' => 'users', 'action' => 'info')); ?>">Your
                            music</a></li>
                <?php } ?>
                <li>|</li>
                <li>
                    <a href="<?php echo Router::url(array('controller' => 'playlists', 'action' => 'select')); ?>">Playlists</a>
                </li>
                <li>|</li>
                <li>
                    <a href="#">History</a>

                    <div class="menuLists searchHistory">
                        <?php if (!$this->Session->read('user.logged')) { ?>
                            <?php if (!empty($history['default']['bands'])) { ?>
                                <ol>
                                    <?php foreach (array_reverse($history['default']['bands']) as $k => $i) { ?>
                                        <li>
                                            <a href="<?php echo Router::url(array('controller' => 'bands', 'action' => 'topten', $i)); ?>"><?php echo $i ?></a>
                                        </li>
                                    <?php } ?>
                                </ol>
                                <a class="btn  btn-danger btn-sm clearCookie"
                                   href="<?php echo Router::url(array('controller' => 'bands', 'action' => 'clearCookie')); ?>">Clear</a>
                            <?php } else { ?>
                                No History
                            <?php } ?>
                        <?php } else { ?>
                            <?php if (!empty($history[$this->Session->read('user.username')]['bands'])) { ?>
                                <ol>
                                    <?php foreach (array_reverse($history[$this->Session->read('user.username')]['bands']) as $k => $i) { ?>
                                        <li>
                                            <a href="<?php echo Router::url(array('controller' => 'bands', 'action' => 'topten', $i)); ?>"><?php echo $i ?></a>
                                        </li>
                                    <?php } ?>
                                </ol>
                                <a class="btn btn-danger btn-sm clearCookie"
                                   href="<?php echo Router::url(array('controller' => 'bands', 'action' => 'clearCookie')); ?>">Clear
                                    history</a>
                            <?php } else { ?>
                                No History
                            <?php } ?>
                        <?php } ?>
                    </div>
                </li>
                <?php if ($this->Session->read('user.logged')) { ?>
                    <li>|</li>
                    <li class="favs">
                        <a href="#">Favorites</a>

                        <div class="menuLists">
                            <?php if ($this->Session->read('user.favs')) { ?>
                                <ol class="favList">
                                    <?php $c = 1;
                                    foreach ($this->Session->read('user.favs') as $k => $i) {
                                        if ($c >= 11) {
                                            ?>
                                            <a href="<?php echo Router::url(array('controller' => 'users', 'action' => 'info')); ?>">More...</a>
                                            <?php break; ?>
                                        <?php } else { ?>
                                            <li>
                                                <a href="<?php echo Router::url(array('controller' => 'bands', 'action' => 'topten', $i)); ?>"><?php echo $i ?></a>
                                            </li>
                                        <?php } ?>
                                        <?php $c++; ?>
                                    <?php } ?>
                                </ol>
                            <?php } else { ?>
                                No favorites
                            <?php } ?>
                        </div>
                    </li>
                    <li>|</li>
                    <li>
                        <a href="<?php echo Router::url(array('controller' => 'users', 'action' => 'logout')); ?>">Logout</a>
                    </li>
                <?php } ?>
                <li>|</li>
                <li>
                    <a href="<?php echo Router::url(array('controller' => 'pages', 'action' => 'halp')); ?>">Make me happy</a>
                </li>
            </ul>
        </div>

        <?php echo $this->element('site/formsUser'); ?>
        <h1><a href="<?php echo Router::url(array('controller' => 'pages', 'action' => 'home'), true) ?>">STOP ASKING FOR A MOBILE APP. THANKS</a></h1>

        <div class="search clearfix">
            <form action="#" method="get" class="clearfix">
                <select class="selectBox">
                    <option value="suggestBand">Band</option>
                    <option value="suggestGenre">Genre</option>
                    <option value="playlist">Playlist</option>
                </select>

                <div class="inputAhead">
                    <input type="text" id="searchinput"/>
                    <span class="glyphicon glyphicon-search"></span>
                    <?php if (isset($homeBands)) { ?>
                        <span class="tooltip">Can't think of any band?<br/>
                    Try with <a
                                href="<?php echo Router::url(array('controller' => 'bands', 'action' => 'topten', $homeBands[0])); ?>"><?php echo $homeBands[0] ?></a>,
                    <a href="<?php echo Router::url(array('controller' => 'bands', 'action' => 'topten', $homeBands[1])); ?>"><?php echo $homeBands[1] ?></a>,
                    <a href="<?php echo Router::url(array('controller' => 'bands', 'action' => 'topten', $homeBands[2])); ?>"><?php echo $homeBands[2] ?></a>, <a
                                href="<?php echo Router::url(array('controller' => 'bands', 'action' => 'topten', $homeBands[3])); ?>"><?php echo $homeBands[3] ?></a> or a <a
                                href="<?php echo Router::url(array('controller' => 'bands', 'action' => 'random')); ?>">random</a> one.
                </span>
                    <?php } ?>
                    <div class="noteText">&mdash; press 'enter' to search</div>
                </div>
                <a href="<?php echo Router::url(array('controller' => 'bands', 'action' => 'random')); ?>"><span class="glyphicon glyphicon-random"></span></a>

            </form>

        </div>
    </header>

    <?php
    echo $this->Session->flash();
    echo $this->fetch('content');
    ?>
</div>

<footer class="container">

    <div class="row">
        <div class="logoGrooveshark col-md-6 col-lg-3">
            <a href="http://grooveshark.com/" target="_blank" title="">Music streaming<br/> courtesy of
                Grooveshark</a>
        </div>
        <div class="logoLastfm col-md-6 col-lg-3">
            <a href="http://last.fm/" target="_blank" title="">Top ten tracks listing<br/> provided by
                Last FM</a>
        </div>
        <div class="logoArturo col-md-6 col-lg-3">
            <a href="<?php echo Router::url(array('controller' => 'pages', 'action' => 'about'), true) ?>" target="_blank" title="">Made
                with love by<br/>
                Andres Rey</a>
        </div>
        <div class="logoDonate col-md-6 col-lg-3">
            <a href="<?php echo Router::url(array('controller' => 'pages', 'action' => 'halp'), true) ?>" target="_blank" title="">Feeling
                generous?<br/>
                Help me to keep this alive
            </a>
        </div>
    </div>
    <div class="footerLinks">
        <ul>
            <li><a href="<?php echo Router::url(array('controller' => 'pages', 'action' => 'faq'), true) ?>" target="_blank">FAQ</a>
            </li>
            <li> |</li>
            <li><a href="<?php echo Router::url(array('controller' => 'pages', 'action' => 'contact'), true) ?>" target="_blank">Contact
                    me</a></li>
            <li> |</li>
            <li><a href="<?php echo Router::url(array('controller' => 'pages', 'action' => 'privacy'), true) ?>" target="_blank">Privacy
                    Policy</a></li>
            <li> |</li>
            <li><a href="<?php echo Router::url(array('controller' => 'pages', 'action' => 'terms'), true) ?>" target="_blank">Terms &
                    conditions</a></li>
            <li> |</li>
            <li><a href="<?php echo Router::url(array('controller' => 'features', 'action' => 'index'), true) ?>" target="_blank">Request
                    a new feature</a></li>
            <li> |</li>
            <li><a href="<?php echo Router::url(array('controller' => 'hits', 'action' => 'index'), true) ?>" target="_blank">Statistics</a></li>
            <li> |</li>
            <li><a href="<?php echo Router::url(array('controller' => 'pages', 'action' => 'halp'), true) ?>" target="_blank">Make me happy</a></li>
        </ul>
    </div>
</footer>
<script type="text/javascript">
    rootUrl = "<?php echo Router::url('/', true) ?>";
    controller = "<?php echo $this->name ?>";
</script>
<?php
echo $this->fetch('scriptMiddle');
echo $this->fetch('scriptBottom');?>
<?php echo $this->AssetCompress->includeJs();
?>
</body>
</html>