<?php /* @var $this View */ ?>
<?php echo $this->element( 'admin/panels/model_panel' ); ?>
<div class="row-fluid">
  <div class="span12">
    <?php echo $this->BootstrapForm->create( 'Export', array( 'url' => array( 'controller' => $controller, 'action' => $this->request->params[ 'action' ] ), 'class' => 'form-horizontal' ) ); ?>
    <h2><?php echo __dn( 'admin', 'Exportar un registro', 'Exportar %d registros', $count, $count ) ?> de la colección
      <em><?php echo __( $model->name ) ?></em></h2>
    <fieldset>

      <legend>Elija los campos a exportar</legend>
      <?php echo $this->BootstrapForm->hidden( 'process' ); ?>
      <?php echo $this->BootstrapForm->hidden( 'conditions' ); ?>
      <div class="control-group">
        <label class="control-label">Campos del modelo: House</label>
        <input type="hidden" value="" id="ExportFields_" name="data[Export][fields]">

        <div class="controls">
          <?php foreach ( $model->schema() as $key => $value ): ?>
            <label class="checkbox"><input type="checkbox" value="<?php echo "{$model->name}.$key" ?>" checked="checked" name="data[Export][fields][]">
              <?php echo $model->fieldNames[ $key ] ?>
            </label>
          <?php endforeach; ?>
          <?php $this->BootstrapForm->unlockField( 'fields' ); ?>
        </div>
      </div>
      <legend>Elija el formato</legend>
      <?php echo $this->BootstrapForm->input( 'noheader', array(
        'label'     => 'Sin cabecera',
        'type'      => 'checkbox',
        'helpBlock' => 'Marcar para <em>no</em> incluir una primera fila con los nombres de las columnas'
      ) ); ?>
      <?php echo $this->BootstrapForm->input( 'separator', array(
        'label'   => 'Separador de columnas',
        'type'    => 'select',
        'options' => array( 'colon' => 'Coma', 'semicolon' => 'Punto y coma', 'tab' => 'Tabs' ),
        'class'   => 'span2',
      ) ); ?>
      <div class="form-actions">
        <?php echo $this->BootstrapForm->button( 'Exportar', array( 'class' => 'btn btn-primary' ) ) ?>
        <?php echo $this->BootstrapHtml->link( 'Volver', array( 'action' => 'index' ), array( 'class' => 'btn' ) ); ?>
      </div>
    </fieldset>
    <?php echo $this->BootstrapForm->end(); ?>
  </div>
</div>