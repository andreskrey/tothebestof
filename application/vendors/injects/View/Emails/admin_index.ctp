<?php /* @var $this View */ ?>
<div class="row-fluid">
  <!-- admin/model_panel custom para email list -->
  <div class="row-fluid">
    <div class="span12">
      <ul class="nav nav-tabs">

        <?php if ( $this->view == 'admin_index' ): ?>
          <li class="active">
            <?php echo $this->BootstrapHtml->link( 'Lista', array( 'action' => 'index' ), array( 'icon' => 'list' ) ); ?>
          </li>
        <?php else: ?>
          <li>
            <?php echo $this->BootstrapHtml->link( 'Lista', array( 'action' => 'index' ), array( 'icon' => 'list' ) ); ?>
          </li>
        <?php endif; ?>

        <?php if ( $this->view == 'admin_search' ): ?>
          <li class="active">
            <?php echo $this->BootstrapHtml->link( 'Búsqueda', $this->passedArgs, array( 'icon' => 'search' ) ); ?>
          </li>
        <?php endif; ?>
        <li class="dropdown" style="float:right">
          <a class="dropdown-toggle" data-toggle="dropdown" href="#">Paginación<b class="caret"></b></a>
          <ul class="dropdown-menu" style="left:auto; right:0;">
            <?php $pagination = array( 10, 20, 50, 100, 200, 500, 1000 ); ?>
            <?php foreach ( $pagination as $number ): ?>
              <?php $current = Hash::get( $this->BootstrapPaginator->params(), 'limit' ) == $number ? ' class="current"' : ''; ?>
              <li<?php echo $current; ?>><?php echo $this->BootstrapHtml->link( "{$number} Elementos", array_merge( $this->passedArgs, array( 'limit' => $number ) ) ) ?></li>
            <?php endforeach; ?>
          </ul>
        </li>
      </ul>
    </div>
  </div>
  <!-- fin admin/model_panel custom para email list -->
  <?php echo $this->element( 'admin/panels/search_panel', array( 'model' => $model, 'data' => $emails, 'export' => FALSE ) ); ?>
  <div class="row-fluid">
    <div class="span12">
      <h2>Listar <?php echo __( 'Emails' ) ?></h2>

      <?php if ( $this->BootstrapPaginator->hasPage( NULL, 2 ) ): ?>
        <p>
          <?php echo $this->BootstrapPaginator->counter( array( 'format' => 'Página {:page} de {:pages}, mostrando {:current} registros de un total de {:count}, desde el registro {:start} hasta {:end}' ) ); ?>
        </p>
      <?php endif; ?>
      <?php if ( count( $emails ) ): ?>
        <table class="table table-bordered table-striped table-hover auto-responsive">
          <thead>
            <tr>
              <th>id</th>
              <th>nombre</th>
              <th>nombre clave</th>
              <th>formato</th>
              <th>remitente</th>
              <th>email remitente</th>
              <th>asunto</th>
              <th class="actions"><span class="pull-right">Acciones</span></th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ( $emails as $email ): ?>
              <tr>
                <td><?php echo h( $email[ 'Email' ][ 'id' ] ); ?></td>
                <td><?php echo h( $email[ 'Email' ][ 'name' ] ); ?></td>
                <td><?php echo h( $email[ 'Email' ][ 'key' ] ); ?></td>
                <td><?php echo h( $email[ 'Email' ][ 'format' ] ); ?></td>
                <td><?php echo h( $email[ 'Email' ][ 'from_name' ] ); ?></td>
                <td><?php echo h( $email[ 'Email' ][ 'from_email' ] ); ?></td>
                <td><?php echo h( $email[ 'Email' ][ 'subject' ] ); ?></td>
                <td class="actions">
                  <div class="btn-group pull-right">
                    <?php echo $this->BootstrapHtml->link( 'Ver', array( 'action' => 'view', $email[ 'Email' ][ 'id' ] ), array( 'class' => 'btn', 'icon' => 'eye-open' ) ); ?>
                    <button class="btn dropdown-toggle" data-toggle="dropdown">
                      <span class="caret"></span>
                    </button>
                    <ul class="dropdown-menu pull-right">
                      <li><?php echo $this->BootstrapHtml->link( 'Enviar Prueba', array( 'action' => 'test', $email[ 'Email' ][ 'id' ] ), array( 'icon' => 'envelope' ) ); ?></li>
                      <li><?php echo $this->BootstrapHtml->link( 'Previsualizar', array( 'action' => 'preview', $email[ 'Email' ][ 'id' ] ), array( 'icon' => 'search' ) ); ?></li>
                    </ul>
                  </div>
                </td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      <?php else: ?>
        <div class="alert alert-info">
          <strong>Sin Resultados!</strong> No se encontraron resultados para tu búsqueda
        </div>
      <?php endif; ?>

      <?php echo $this->BootstrapPaginator->pagination( array( 'div' => 'pagination' ) ); ?>
      <?php if ( $this->BootstrapPaginator->hasNext() || $this->BootstrapPaginator->hasPrev() ): ?>
        <p>
          <a class="btn" href="<?php echo Router::url( array_merge( $this->passedArgs, array( 'limit' => '1000000000', 'page' => 1 ) ) ); ?>" rel="tooltip" title="Se listará un total de <?php echo Hash::get( $this->BootstrapPaginator->params(), 'count' ); ?> registros">Mostrar todos los registros</a>
        </p>
      <?php endif; ?>
    </div>
  </div>
</div>