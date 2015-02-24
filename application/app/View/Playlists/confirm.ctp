<div role="main" class="content default clearfix">
    <h2>Which songs would you like to add?</h2>
    <?php echo $this->Form->create('Playlist'); ?>
    <table class="table table-bordered table-striped table-hover auto-responsive">
        <thead>
        <tr>
            <th><?php echo $this->Form->checkbox('selectAll', array(
                    'label' => false,
                )); ?>
            <th>Song</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($data['Songid'] as $k => $i) { ?>
            <tr>
                <td><?php echo $this->Form->checkbox($k, array(
                        'label' => false,
                        'class' => 'selectAllAble'
                    )); ?></td>
                <td><label for="Playlist<?php echo $k; ?>"><?php echo h($i['name']) ?><label></td>
            </tr>
        <?php } ?>
        </tbody>
    </table>
    <?php echo $this->Form->end(array('label' => 'Add', 'class' => 'btn btn-success')); ?>
</div>