<?php /* @var $this View */ ?>
<div class="row-fluid">
  <div class="span12">
    <ul class="nav nav-tabs">

      <li>
        <?php echo $this->BootstrapHtml->link( 'Lista', array( 'action' => 'index' ), array( 'icon' => 'list' ) ); ?>
      </li>

      <li class="<?php echo $this->view == 'admin_view' ? 'active' : '' ?>">
        <?php echo $this->BootstrapHtml->link( 'Ver', array( 'action' => 'view', $id ), array( 'icon' => 'eye-open' ) ); ?>
      </li>

      <?php
      if ( isset( $relatedChildren ) ):
        $listLinks = array();
        $addLinks = array();
        $pluralNames = array();
        $singularNames = array();
        foreach ( $relatedChildren as $i => $child ):
          $assoc = Hash::check( $model->hasOne, $child ) ? $model->hasOne[ $child ] : $model->hasMany[ $child ];
          $controller = Inflector::tableize( Inflector::pluralize( $assoc[ 'className' ] ) );
          $parent = Inflector::tableize( $this->name );
          $listLinks[ $i ] = Router::url( array( 'controller' => $controller, 'action' => 'filter', 'parent' => $parent, 'id' => $id ) );
          $addLinks[ $i ] = Router::url( array( 'controller' => $controller, 'action' => 'add_related', 'parent' => $parent, 'id' => $id ) );
          $pluralNames[ $i ] = $model->{$assoc[ 'className' ]}->pluralName;
          $singularNames[ $i ] = $model->{$assoc[ 'className' ]}->singularName;
        endforeach;
        ?>
        <li class="dropdown pull-right">
          <a class="dropdown-toggle" data-toggle="dropdown" href="#">Agregar Relacionados <b class="caret"></b></a>
          <ul class="dropdown-menu" style="left:auto; right:0;">
            <?php foreach ( $relatedChildren as $i => $child ): ?>
              <li><a href="<?php echo $addLinks[ $i ] ?>"><?php echo $singularNames[ $i ] ?></a></li>
            <?php endforeach; ?>
          </ul>
        </li>
        <li class="dropdown pull-right">
          <a class="dropdown-toggle" data-toggle="dropdown" href="#">Listar Relacionados <b class="caret"></b></a>
          <ul class="dropdown-menu" style="left:auto; right:0;">
            <?php foreach ( $relatedChildren as $i => $child ): ?>
              <li><a href="<?php echo $listLinks[ $i ] ?>"><?php echo $pluralNames[ $i ] ?></a></li>
            <?php endforeach; ?>
          </ul>
        </li>
      <?php endif; ?>
    </ul>
  </div>
</div>