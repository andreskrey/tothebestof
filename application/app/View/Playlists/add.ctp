<div role="main" class="content default playlist clearfix">
    <?php if (empty($data)) { ?>
        <h2>Start creating your playlist</h2>
    <?php } else { ?>
        <h2><?php if ($ddbb['name']) { ?>
                <?php echo h($ddbb['name']) ?>
            <?php } else { ?>
                Your playlist
            <?php } ?>
        </h2>
    <?php } ?>

    <?php if ($data) { ?>
        <div class="buttonMenu">
            <a class="btn btn-default"
               href="<?php echo Router::url(array('action' => 'edit', 'sessionId' => $this->request->params['sessionId']), true) ?>">Edit</a>
            <a class="btn btn-default"
               href="<?php echo Router::url(array('action' => 'edit', 'sessionId' => $this->request->params['sessionId'], 'shuffle'), true) ?>">Shuffle</a>
            <a class="btn btn-default"
               href="<?php echo Router::url(array('action' => 'edit', 'sessionId' => $this->request->params['sessionId'], 'reorder'), true) ?>">Reorder</a>
            <?php if (count($this->Session->read('playlist')) >= 2) { ?>
                <a class="btn btn-default"
                   href="<?php echo Router::url(array('action' => 'select'), true) ?>">Loaded playlists</a>
            <?php } ?>
            <?php if (!$ddbb) { ?>
                <a class="btn btn-default" href="<?php echo Router::url(array('action' => 'save', 'sessionId' => $this->request->params['sessionId']), true) ?>">
                    Save & get link</a>
            <?php } ?>
            <a class="btn btn-default" href="<?php echo Router::url(array('action' => 'destroy'), true) ?>">Create a new playlist</a>
        </div>
    <?php } ?>

    <?php echo $this->Form->create('Playlist'); ?>
    <?php echo $this->Form->input('band', array('div' => false, 'label' => false, 'Placeholder' => 'Add Bands')); ?>
    <span class="glyphicon glyphicon-search"></span>
    </form>
    <?php if ($data) { ?>
    <div class="center">
        <object width="600" height="380" classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000"
                id="gsManySongs281408533391532117" name="gsManySongs281408533391532117">
            <param name="movie" value="http://grooveshark.com/widget.swf"/>
            <param name="wmode" value="opaque"/>
            <param name="allowScriptAccess" value="always"/>
            <param name="flashvars"
                   value="hostname=cowbell.grooveshark.com&songIDs=<?php echo implode(',', $data) ?>&bbg=000000&bth=000000&pfg=000000&lfg=000000&bt=FFFFFF&pbg=FFFFFF&pfgh=FFFFFF&si=FFFFFF&lbg=FFFFFF&lfgh=FFFFFF&sb=FFFFFF&bfg=666666&pbgh=666666&lbgh=666666&sbh=666666&p=0"/>
            <object type="application/x-shockwave-flash" data="http://grooveshark.com/widget.swf" width="600"
                    height="380">
                <param name="wmode" value="opaque"/>
                <param name="allowScriptAccess"
                       value="always"/>
                <param
                    name="flashvars"
                    value="hostname=cowbell.grooveshark.com&songIDs=<?php echo implode(',', $data) ?>&bbg=000000&bth=000000&pfg=000000&lfg=000000&bt=FFFFFF&pbg=FFFFFF&pfgh=FFFFFF&si=FFFFFF&lbg=FFFFFF&lfgh=FFFFFF&sb=FFFFFF&bfg=666666&pbgh=666666&lbgh=666666&sbh=666666&p=0"/>
            </object>
        </object>
        <?php if ($ddbb) { ?>
            <h3>Share your playlist &mdash; <a href="#" id="copyUrl"
                                               data-clipboard-text="<?php echo Router::url(array('action' => 'view', $ddbb['uuid']), true) ?>">Copy
                    to Clipboard</a></h3>
            <input type="text" id="link" class="text" readOnly="true" onclick="this.focus();this.select();"
                   value="<?php echo Router::url(array('action' => 'view', $ddbb['uuid']), true) ?>"/>
        <?php } ?>

        <?php } ?>
    </div>
</div>