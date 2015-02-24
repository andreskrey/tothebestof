<div role="main" class="bands content clearfix">
    <div class="infoBand clearfix">

        <h2><?php echo $data['artist_name'] ?></h2>

        <p><?php echo $data['bio'] ?></p>

        <div class="imageBand">
            <img src="<?php echo $data['pic'] ?>" width="300">
        </div>
    </div>

    <div class="musicBand clearfix">

        <?php if (isset($data['idsplayer'])) { ?>
            <div class="groovesharkBand">
                <object width="600" height="380" classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" id="gsManySongs281408533391532117" name="gsManySongs281408533391532117">
                    <param name="movie" value="http://grooveshark.com/widget.swf"/>
                    <param name="wmode" value="opaque"/>
                    <param name="allowScriptAccess" value="always"/>
                    <param name="flashvars" value="hostname=cowbell.grooveshark.com&songIDs=<?php echo $data['idsplayer']
                    ?>&bbg=000000&bth=000000&pfg=000000&lfg=000000&bt=FFFFFF&pbg=FFFFFF&pfgh=FFFFFF&si=FFFFFF&lbg=FFFFFF&lfgh=FFFFFF&sb=FFFFFF&bfg=666666&pbgh=666666&lbgh=666666&sbh=666666&p=0"/>
                    <object type="application/x-shockwave-flash" data="http://grooveshark.com/widget.swf" width="600" height="380">
                        <param name="wmode" value="opaque"/>
                        <param name="allowScriptAccess" value="always"/>
                        <param name="flashvars" value="hostname=cowbell.grooveshark.com&songIDs=<?php echo $data['idsplayer']
                        ?>&bbg=000000&bth=000000&pfg=000000&lfg=000000&bt=FFFFFF&pbg=FFFFFF&pfgh=FFFFFF&si=FFFFFF&lbg=FFFFFF&lfgh=FFFFFF&sb=FFFFFF&bfg=666666&pbgh=666666&lbgh=666666&sbh=666666&p=0"/>
                    </object>
                </object>
                <div class="relatedBands">
                    <?php if ($data['related'] != NULL) { ?>
                        Check out similar artists like
                        <?php foreach ($data['related'] as $i => $value) { ?>
                            <?php if ($i < 2) { ?>
                                <a href="<?php echo Router::url(array('controller' => 'band', 'action' => htmlspecialchars($value->band)), true) ?>"><?php echo $value->band; ?></a>,
                            <?php } else { ?>
                                or <a
                                    href="<?php echo Router::url(array('controller' => 'band', 'action' => htmlspecialchars($value->band)), true) ?>"><?php echo $value->band; ?></a>.
                                <?php break; ?>
                            <?php } ?>
                        <?php } ?>
                    <?php } ?>
                </div>
            </div>
        <?php } ?>
        <div class="optionsBandUser">
            <?php if ($this->Session->read('user.logged')) { ?>
                <?php if (isset($data['id'])) { ?>
                    <a class="<?php if ($data['favorite']) { ?>unfav<?php } else { ?>fav<?php } ?>" data-band="<?php echo $data['id'] ?>" data-name="<?php echo $data['artist_name'] ?>" href="#">
                        <span class="glyphicon glyphicon-heart"></span>
                            <span class="toFav tooltip">
                                <?php if ($data['favorite']) { ?>Already favorited<?php } else { ?> Add to favorites<?php } ?>
                            </span>
                        <span class="favoriteMessage tooltip">Please wait...</span>
                    </a>
                <?php } ?>
            <?php } else { ?>
                <a href="#">
                    <span class="glyphicon glyphicon-heart"></span>
                    <span class="tooltip">
                      Login to favorite
                    </span>
                </a>
            <?php } ?>
            <a href="<?php
            $params = array('controller' => 'bands', 'action' => 'topten', $data['artist_name'], '!shuffle');
            if (strpos($this->request->params['pass'][0], '/!more')) $params[] = '!more';
            echo Router::url($params);
            ?>">
                <span class="glyphicon glyphicon-random"></span><span class="tooltip">Shuffle all songs</span></a>
            <?php if ($data['count'] >= 15 && !strpos($this->request->params['pass'][0], '/!more')) { ?>
                <a href="<?php echo Router::url(array('controller' => 'bands', 'action' => 'topten', $data['artist_name'], '!more')); ?>">
                    <span class="glyphicon glyphicon-plus"></span><span class="tooltip">Show more songs</span></a>
            <?php } ?>
            <?php echo $this->Form->postLink('<span class="glyphicon glyphicon-list"></span><span class="tooltip">Create a playlist with this band</span>',
                Router::url(array('controller' => 'playlists', 'action' => 'add'), true),
                array(
                    'data' => array('Playlist' => array('band' => $data['artist_name'])),
                    'escape' => false,
                )) ?>
        </div>

    </div>
</div>