<div role="main" class="content default clearfix">
    <h2>Loaded playlists:</h2><br/>

    <div class="center">
        <?php foreach ($data as $k => $i) { ?>
            <p>
                <a href="<?php echo Router::url(array('action' => 'add', 'sessionId' => $k)); ?>">Session <?php echo $k ?></a><br/>
                <?php if (empty($i['bands'])) { ?>
                    Empty<br/>
                <?php } else { ?>
                    Contains: <?php echo $this->Text->tolist($i['bands']) ?><br/>
                <?php } ?>
                <?php if (isset($i['ddbb'])) { ?>
                    <?php if ($i['ddbb']['name']) { ?>
                        Name: <?php echo h($i['ddbb']['name']) ?><br/>
                    <?php } ?>
                    UUID: <a href="<?php echo Router::url(array('action' => 'view', $i['ddbb']['uuid'])); ?>"><?php echo $i['ddbb']['uuid'] ?></a><br/>
                <?php } else { ?>
                    Not saved<br/>
                <?php } ?>
            </p>
            <hr/>
        <?php } ?>
    </div>
</div>