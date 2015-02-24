<?php /* @var $this View */ ?>
<?php $export = isset( $export ) ? $export : TRUE; ?>
<div class="row-fluid">
  <div class="well span12 clearfix">
    <hr style="display: none;">
    <?php echo $this->BootstrapForm->create( $model->name, array( 'id' => 'admin_search_form', 'url' => array( 'action' => 'search' ), 'class' => 'form-horizontal pull-left span7', 'style' => 'margin-bottom: 0px' ) ) ?>

    <?php foreach ( $model->filterArgs as $key => $value ): ?>
      <?php if ( in_array( $key, array( '*' ) ) ) continue; ?>
      <div class="control-group" data-id="<?php echo $key ?>" style="display: none">
        <?php echo $this->BootstrapForm->label( $key, "Filtro por {$model->fieldNames[$key]}", array( 'class' => 'control-label' ) ) ?>
        <div class="controls">

          <div class="input-append">
            <?php echo $this->BootstrapForm->text( $key, array( 'class' => 'span8', 'novalidate' => TRUE, 'required' => FALSE ) ) ?>
            <button class="btn search_filter_remove" data-id="<?php echo $key ?>" type="button">Eliminar filtro</button>
          </div>
        </div>
      </div>
    <?php endforeach; ?>

    <div class="control-group" data-id="*" style="margin-bottom: 0px">
      <?php echo $this->BootstrapForm->label( '*', 'Búsqueda libre', array( 'class' => 'control-label' ) ) ?>
      <div class="controls">
        <?php echo $this->BootstrapForm->text( '*', array( 'class' => 'span5', 'autofocus' ) ) ?>
        <span class="help-inline"><small>busca en todas las columnas de los registros</small></span>
      </div>
    </div>
    <input type="submit" style="position:absolute; left: -9999px; height: 1px; width: 1px;">
    <?php echo $this->BootstrapForm->end(); ?>

    <div class="btn-toolbar pull-right" style="margin-top: 0px; margin-bottom: 0px">

      <div class="btn-group">
        <button class="btn btn-inverse dropdown-toggle" data-toggle="dropdown">
          Agregar filtros <span class="caret"></span>
        </button>
        <ul class="dropdown-menu pull-right">
          <?php foreach ( $model->filterArgs as $key => $field ): ?>
            <li>
              <?php if ( in_array( $key, array( '*' ) ) ) continue; ?>
              <a class="field_filters" id="field_<?php echo $key ?>" data-id="<?php echo $key ?>" data-name="<?php echo __( $key ) ?>">
                Agregar filtro por <?php echo $model->fieldNames[ $key ] ?></a>
            </li>
          <?php endforeach; ?>
        </ul>
      </div>

      <button class="btn btn-inverse" id="admin_search_apply">Buscar</button>

      <?php if ( $export && $this->view == 'admin_search' && count( $data ) ): ?>
        <a class="btn btn-info" id="bulk_export">Exportar búsqueda</a>
      <?php endif; ?>

    </div>
  </div>
</div>
<script type="text/javascript">
  var currentFilters = <?php echo json_encode( array_keys( $this->passedArgs ) ) ?>;
</script>