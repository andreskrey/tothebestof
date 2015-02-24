<div role="main" class="content playlist clearfix">
    <h2>Edit items in your playlist</h2>
    <table class="table table-bordered table-striped table-hover auto-responsive">
        <thead>
        <tr>
            <!--            <th>Select</th>-->
            <th>Band</th>
            <th>Song name</th>
            <th>Order</th>
            <th>Delete</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($data as $k => $i) { ?>
            <tr>
                <!--                <td>-->
                <?php //echo $this->Form->checkbox($k, array('label' => false)); ?><!--</td>-->
                <td><?php echo h($i['band']) ?></td>
                <td><?php echo h($i['name']) ?></td>
                <td>
                    <a class="glyphicon glyphicon-plus"
                       href="<?php echo Router::url(array('action' => 'edit', 'sessionId' => $this->request->params['sessionId'], 'sort', $k, 1), true) ?>"></a>
                    <a class="glyphicon glyphicon-minus"
                       href="<?php echo Router::url(array('action' => 'edit', 'sessionId' => $this->request->params['sessionId'], 'sort', $k, -1), true) ?>"></a>
                </td>
                <td>
                    <a class="btn btn-danger"
                       href="<?php echo Router::url(array('action' => 'edit', 'sessionId' => $this->request->params['sessionId'], 'remove', $k), true) ?>">Remove song</a>
                </td>
            </tr>
        <?php } ?>
        </tbody>
    </table>
    <?php if ($ddbb) { ?>
        <a class="btn btn-success" href="<?php echo Router::url(array('action' => 'save', 'sessionId' => $this->request->params['sessionId']), true) ?>">Save changes</a>
    <?php } else { ?>
        <a class="btn btn-success" href="<?php echo Router::url(array('action' => 'add', 'sessionId' => $this->request->params['sessionId']), true) ?>">Done</a>
    <?php } ?>
    <hr>
    <?php if ($ddbb) { ?>
        <?php echo $this->Form->create('Playlist', array('url' => array('controller' => 'playlists', 'action' => 'edit', 'sessionId' => $this->request->params['sessionId'], 'name'))); ?>
        <div>
            <?php echo $this->Form->input('name', array('label' => false, 'div' => false, 'value' => $ddbb['name'], 'placeholder' => 'Name your playlist')); ?>
            <?php echo $this->Form->error('name'); ?></div>
        <?php echo $this->Form->end(array('label' => 'Save name', 'class' => 'btn btn-success')); ?>
    <?php } ?>
    <a class="btn btn-danger"
       href="<?php echo Router::url(array('action' => 'del', 'sessionId' => $this->request->params['sessionId']), true) ?>">Delete playlist</a>
</div>