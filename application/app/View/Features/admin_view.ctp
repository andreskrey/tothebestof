<?php /* @var $this View */ ?>
<?php echo $this->element('admin/panels/record_panel', array('id' => $feature['Feature']['id'])); ?>
<div class="row-fluid">
    <div class="span12">
        <h2><em>Feature: <?php echo $feature['Feature']['id'] ?></em></h2>
        <dl class="dl-horizontal well">
            <dt>id</dt>
            <dd><?php echo h($feature['Feature']['id']); ?>&nbsp;</dd>
            <dt>Descripcion</dt>
            <dd><?php echo h($feature['Feature']['description']); ?>&nbsp;</dd>
            <dt>By</dt>
            <dd><?php echo h($feature['Feature']['by']); ?>&nbsp;</dd>
            <dt>Votos</dt>
            <dd><?php echo h($feature['Feature']['votes']); ?>&nbsp;</dd>
            <dt>Estado</dt>
            <dd><?php echo h($feature['Feature']['status']); ?>&nbsp;</dd>
            <dt>Respuesta</dt>
            <dd><?php echo h($feature['Feature']['response']); ?>&nbsp;</dd>
            <dt>Habilitado</dt>
            <dd><?php echo h($feature['Feature']['enabled']); ?>&nbsp;</dd>
            <dt>Creado</dt>
            <dd><?php echo h($feature['Feature']['created']); ?>&nbsp;</dd>
        </dl>
    </div>
</div>