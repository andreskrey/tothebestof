<div role="main" class="content default profile clearfix">
    <div class="profileHeader clearfix">

        <h2><?php echo $this->Session->read('user.username') ?>'s music</h2>
        <?php echo $this->element('site/profileHeader') ?>

        <div class="buttonMenu">
            <a class="btn btn-default fireList" href="#profileFavorites">Favorites</a>
            <a class="btn btn-default fireList" href="#profilePlaylists">Playlists</a>
            <a class="btn btn-default fireList" href="#profileHistory">History</a>
            <a class="btn btn-default" href="<?php echo Router::url(array('action' => 'edit'), true) ?>">Profile</a>

        </div>
    </div>
    <div id="profileFavorites" class="profileLists">
        <?php if (!empty($data['Favorite'])) { ?>
            <h3>Your favorites (<a href="<?php echo Router::url(array('controller' => 'favorites', 'action' => 'edit')); ?>">Edit</a>)</h3>
            <ol>
                <?php foreach ($data['Favorite'] as $i) { ?>
                    <li class="favItem">
                        <a class="favIteminfo" href="<?php echo Router::url(array('controller' => 'bands', 'action' => 'topten', $i['Band']['band'])); ?>"><?php echo $i['Band']['band'] ?></a>
                        <span class="tooltip"><?php echo $i['Band']['bio'] ?></span>
                        <a class="directUnfav" href="#" data-band="<?php echo $i['band_id'] ?>"><span class="glyphicon glyphicon-remove"></span></a>
                    </li>

                <?php } ?>
            </ol>

        <?php } else { ?>
            <h3>No favorites</h3>
        <?php } ?>
    </div>
    <div id="profilePlaylists" class="profileLists">
        <?php if (!empty($data['Playlist'])) { ?>

            <h3>Your playlists</h3>
            <ol>
                <?php foreach ($data['Playlist'] as $i) { ?>
                    <li>
                        UUID: <a
                            href="<?php echo Router::url(array('controller' => 'playlists', 'action' => 'view', $i['playlist_uuid'])); ?>"><?php echo $i['playlist_uuid'] ?></a><br/>
                        <?php if ($i['name']) { ?>Name: <?php echo h($i['name']) ?><br/><?php } ?>
                        Contains: <?php echo $this->Text->toList($i['bands']) ?><br/><br/>
                    </li>
                <?php } ?>
            </ol>

        <?php } else { ?>
            <h3>No playlists</h3>
        <?php } ?>
        <a href="<?php echo Router::url(array('controller' => 'playlists', 'action' => 'add')); ?>"
           class="btn btn-success">Create a playlist</a>
    </div>
    <div id="profileHistory" class="profileLists">
        <?php if (!empty($history[$this->Session->read('user.username')]['bands'])) { ?>

            <h3>Your history</h3>
            <ol class="searchHistory">
                <?php foreach (array_reverse($history[$this->Session->read('user.username')]['bands']) as $k => $i) { ?>
                    <li>
                        <a href="<?php echo Router::url(array('controller' => 'bands', 'action' => 'topten', $i)); ?>"><?php echo $i ?></a>
                    </li>
                <?php } ?>
            </ol>
            <a class="btn btn-danger clearCookie"
               href="<?php echo Router::url(array('controller' => 'bands', 'action' => 'clearCookie')); ?>">Clear
                history</a>
        <?php } else { ?>
            <h3>No history</h3>
        <?php } ?>
    </div>
    <h3>Donated?</h3>
    <?php if ($data['User']['donation'] === TRUE) { ?>
        <b>Yes</b>. Thank you!
    <?php } else { ?>
        <b>Not yet.</b> <a href="<?php echo Router::url(array('controller' => 'pages', 'action' => 'halp')); ?>">Want
            to donate now?</a>
    <?php } ?>

</div>