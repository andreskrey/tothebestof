<?php /* @var $this View */ ?>
<div class="row-fluid">
  <div class="span12">
    <?php echo $this->BootstrapForm->create( 'Export', array( 'class' => 'form-horizontal' ) ); ?>
    <h2>Exportar Reporte: <em><?php echo $report[ 'name' ] ?></em></h2>
    <fieldset>
      <?php if ( count( $report[ 'parameters' ] ) ): ?>
        <legend>Completá los valores</legend>
        <?php foreach ( $report[ 'parameters' ] as $key => $parameter ):
          $options = array_merge( array( 'required' => 'required' ), $parameter, $typeMap[ $parameter[ 'type' ] ] );
          echo $this->BootstrapForm->input( $key, $options );
        endforeach; ?>
      <?php endif; ?>
      <legend>Elejí el formato</legend>
      <?php echo $this->BootstrapForm->input( 'noheader', array(
        'label'     => 'Sin cabecera',
        'type'      => 'checkbox',
        'helpBlock' => 'Marcar para <em>no</em> incluir una primera fila con los nombres de las columnas'
      ) ); ?>
      <?php echo $this->BootstrapForm->input( 'separator', array(
        'label'   => 'Separador de columnas',
        'type'    => 'select',
          'options' => array( 'colon' => 'Coma (Excel 2003)', 'semicolon' => 'Punto y coma (Excel 2007+)', 'tab' => 'Tabs' ),
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