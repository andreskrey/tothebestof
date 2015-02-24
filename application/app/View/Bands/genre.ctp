<div role="main" class="bands content clearfix">
    <div class="infoBand clearfix">
        <h2><?php echo $data['genre'] ?></h2>
        <p><?php if ($data['success'] === FALSE) { ?>
                I couldn't find any songs under that genre :(
            <?php } else { ?>
                I've found the following bands under the <?php echo strtolower($data['genre']) ?>
                genre: <?php echo $this->Text->toList($data['bands']) ?>.
            <?php } ?>
        </p>
        <div class="imageBand">
            <img src="<?php echo $data['pic'] ?>" width="300">
        </div>
    </div>
    <div class="musicBand clearfix">
        <?php if (isset($data['idsplayer'])) { ?>
            <div class="groovesharkBand">
                <object width="600" height="380" classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000"
                        id="gsManySongs281408533391532117" name="gsManySongs281408533391532117">
                    <param name="movie" value="http://grooveshark.com/widget.swf"/>
                    <param name="wmode" value="opaque"/>
                    <param name="allowScriptAccess" value="always"/>
                    <param name="flashvars"
                           value="hostname=cowbell.grooveshark.com&songIDs=<?php echo implode(',', $data['idsplayer']) ?>&bbg=000000&bth=000000&pfg=000000&lfg=000000&bt=FFFFFF&pbg=FFFFFF&pfgh=FFFFFF&si=FFFFFF&lbg=FFFFFF&lfgh=FFFFFF&sb=FFFFFF&bfg=666666&pbgh=666666&lbgh=666666&sbh=666666&p=0"/>
                    <object type="application/x-shockwave-flash" data="http://grooveshark.com/widget.swf" width="600"
                            height="380">
                        <param name="wmode" value="opaque"/>
                        <param name="allowScriptAccess"
                               value="always"/>
                        <param
                            name="flashvars"
                            value="hostname=cowbell.grooveshark.com&songIDs=<?php echo implode(',', $data['idsplayer']) ?>&bbg=000000&bth=000000&pfg=000000&lfg=000000&bt=FFFFFF&pbg=FFFFFF&pfgh=FFFFFF&si=FFFFFF&lbg=FFFFFF&lfgh=FFFFFF&sb=FFFFFF&bfg=666666&pbgh=666666&lbgh=666666&sbh=666666&p=0"/>
                    </object>
                </object>
            </div>
        <?php } ?>
    </div>
</div>