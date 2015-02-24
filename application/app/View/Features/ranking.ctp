<div role="main" class="content default clearfix">
    <h2>These are all ideas submitted by users.
        <span>Vote the ones you like!</span>
    </h2>

    <?php if (!empty($data['review'])) { ?>
        <h3>To review</h3>
        <table class="table table-bordered table-striped table-hover auto-responsive" style="font-weight: 100;">
            <thead>
            <tr>
                <th>Feature</th>
                <th>Response by admin</th>
                <th>Votes</th>
                <th>Actions</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($data['review'] as $i) { ?>
                <tr>
                    <td><?php echo h($i['description']) ?></td>
                    <td><?php echo h($i['response']) ?></td>
                    <td><?php echo $i['votes'] ?></td>
                    <td><?php echo $this->BootstrapForm->postLink('<i class="glyphicon glyphicon-plus"></i>', array('action' => 'ranking_sort', $i['id'], 1), array('escape' => FALSE, 'data-title' => 'Vote', 'rel' => 'tooltip')) ?>
                        / <?php echo $this->BootstrapForm->postLink('<i class="glyphicon glyphicon-minus"></i>', array('action' => 'ranking_sort', $i['id'], -1), array('escape' => FALSE, 'data-title' => 'Vote', 'rel' => 'tooltip')) ?>
                    </td>
                </tr>
            <?php } ?>
            </tbody>
        </table>
        <hr/>
    <?php } ?>

    <?php if (!empty($data['progress'])) { ?>
        <h3>In progress</h3>
        <table class="table table-bordered table-striped table-hover auto-responsive" style="font-weight: 100;">
            <thead>
            <tr>
                <th>Feature</th>
                <th>Response by admin</th>
                <th>Votes</th>
                <th>Actions</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($data['progress'] as $i) { ?>
                <tr>
                    <td><?php echo h($i['description']) ?></td>
                    <td><?php echo h($i['response']) ?></td>
                    <td><?php echo $i['votes'] ?></td>
                    <td><?php echo $this->BootstrapForm->postLink('<i class="glyphicon glyphicon-plus"></i>', array('action' => 'ranking_sort', $i['id'], 1), array('escape' => FALSE, 'data-title' => 'Vote', 'rel' => 'tooltip')) ?>
                        / <?php echo $this->BootstrapForm->postLink('<i class="glyphicon glyphicon-minus"></i>', array('action' => 'ranking_sort', $i['id'], -1), array('escape' => FALSE, 'data-title' => 'Vote', 'rel' => 'tooltip')) ?>
                    </td>
                </tr>
            <?php } ?>
            </tbody>
        </table>
        <hr/>
    <?php } ?>

    <?php if (!empty($data['done'])) { ?>
        <h3>Done</h3>
        <table class="table table-bordered table-striped table-hover auto-responsive" style="font-weight: 100;">
            <thead>
            <tr>
                <th>Feature</th>
                <th>Response by admin</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($data['done'] as $i) { ?>
                <tr>
                    <td><?php echo h($i['description']) ?></td>
                    <td><?php echo h($i['response']) ?></td>
                </tr>
            <?php } ?>
            </tbody>
        </table>
        <hr/>
    <?php } ?>

    <?php if (!empty($data['rejected'])) { ?>
        <h3>Rejected</h3>
        <table class="table table-bordered table-striped table-hover auto-responsive" style="font-weight: 100;">
            <thead>
            <tr>
                <th>Feature</th>
                <th>Response by admin</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($data['rejected'] as $i) { ?>
                <tr>
                    <td><?php echo h($i['description']) ?></td>
                    <td><?php echo h($i['response']) ?></td>
                </tr>
            <?php } ?>
            </tbody>
        </table>
    <?php } ?>
</div>