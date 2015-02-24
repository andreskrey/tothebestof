<div role="main" class="content default clearfix">
    <h2 class="formTrigger">Most listened bands. <a href="#">(Filter results)</a></h2>


    <div class="statsForm">
        <?php echo $this->Form->create('Hit'); ?>
        <div class="input-append date">
            <?php echo $this->Form->text('dateFrom', array('placeholder' => 'Date from')); ?>
            <span class="add-on"></span>
        </div>

        <div class="input-append date">
            <?php echo $this->Form->text('dateTo', array('placeholder' => 'Date to')); ?>
            <span class="add-on"><i class="icon-th"></i></span>
        </div>
        <?php echo $this->Form->text('band', array('placeholder' => 'Bands (separated by comma, optional)')); ?>
        <?php echo $this->Form->end(array('label' => 'Filter', 'class' => 'btn btn-success')); ?>
    </div>

    <h3>From <?php echo $dates['from'] ?> to <?php echo $dates['to'] ?></h3>

    <table class="table table-bordered table-striped table-hover auto-responsive" style=";">
        <thead>
        <tr>
            <th>Position</th>
            <th>Band</th>
            <th>Hits (approximate count)</th>
        </tr>
        </thead>
        <tbody>
        <?php if (!empty($data)) { ?>
            <?php foreach ($data as $pos => $i) { ?>
                <tr>
                    <td><?php echo $pos + 1 ?></td>
                    <td>
                        <b><a href="<?php echo Router::url(array('controller' => 'bands', 'action' => 'topten', $i['name'])); ?>"><?php echo h($i['name']) ?></a></b>
                    </td>
                    <td>~<?php echo $i['count'] * 100 ?></td>
                </tr>
            <?php } ?>
            <tr>
                <td></td>
                <td>All the other bands</td>
                <td><?php echo $total ?></td>
            </tr>
        <?php } else { ?>

            <tr>
                <td></td>
                <td>No results</td>
                <td></td>
            </tr>
        <?php } ?>
        </tbody>
    </table>
</div>