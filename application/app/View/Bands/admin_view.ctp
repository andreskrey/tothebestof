<?php /* @var $this View */ ?>
<?php echo $this->element('admin/panels/record_panel', array('id' => $band['Band']['id'])); ?>
<div class="row-fluid">
    <div class="span8">
        <h2><em>Banda: <?php echo $band['Band']['id'] ?></em></h2>
        <dl class="dl-horizontal well">
            <dt>id</dt>
            <dd><?php echo h($band['Band']['id']); ?>&nbsp;</dd>
            <dt>Banda</dt>
            <dd><?php echo h($band['Band']['band']); ?>&nbsp;</dd>
            <dt>Bio</dt>
            <dd><?php echo h($band['Band']['bio']); ?>&nbsp;</dd>
            <dt>Imagen</dt>
            <dd><?php echo h($band['Band']['pic']); ?>&nbsp;</dd>
            <dt>Top ten</dt>
            <dd><?php echo h($band['Band']['topten']); ?>&nbsp;</dd>
            <dt>Relacionados</dt>
            <dd><?php echo h($band['Band']['related']); ?>&nbsp;</dd>
            <dt>Hits</dt>
            <dd><?php echo h($band['Band']['hits']); ?>&nbsp;</dd>
            <dt>creado</dt>
            <dd><?php echo h($band['Band']['created']); ?>&nbsp;</dd>
        </dl>
    </div>
    <div class="span4">
        <h3>Relacionados</h3>
        <?php if (count($band['Songid'])): ?>
            <div class="well">
                <p>Tiene muchos:</p>
                <ul class="unstyled">
                    <?php foreach ($band['Songid'] as $related): ?>
                        <li>
                            <div class="clearfix">
                                <p class="pull-left"><strong><?php echo $related['name']; ?></strong></p>

                                <div class="btn-group pull-right">
                                    <?php echo $this->BootstrapHtml->link('', array('controller' => 'songids', 'action' => 'view', $related['id']), array('class' => 'btn btn-small', 'icon' => 'eye-open', 'title' => 'Ver')); ?>
                                    <?php echo $this->BootstrapHtml->link('', array('controller' => 'songids', 'action' => 'edit', $related['id']), array('class' => 'btn btn-small', 'icon' => 'pencil', 'title' => 'Editar')); ?>
                                    <?php echo $this->BootstrapHtml->link('', array('controller' => 'songids', 'action' => 'delete', $related['id']), array('class' => 'btn btn-small', 'icon' => 'remove', 'title' => 'Borrar')); ?>
                                </div>
                            </div>
                        </li>
                    <?php endforeach; ?>
                </ul>
                <div class="clearfix"></div>
            </div>
        <?php endif; ?>
    </div>
</div>