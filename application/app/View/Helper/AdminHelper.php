<?php
/**
 * Created by IntelliJ IDEA.
 * User: ramiro
 * Date: 8/27/12
 * Time: 1:55 PM
 * To change this template use File | Settings | File Templates.
 */
App::uses( 'AppHelper', 'View/Helper' );
App::uses( 'PaginatorHelper', 'View/Helper' );
App::uses( 'HtmlHelper', 'View/Helper' );

/**
 * @property BootstrapHtmlHelper      $Html
 * @property BootstrapPaginatorHelper $Paginator
 */
class AdminHelper extends AppHelper
{
  public $helpers = array(
    'BootstrapPaginator' => array( 'className' => 'TwitterBootstrap.BootstrapPaginator' ),
    'BootstrapHtml'      => array( 'className' => 'TwitterBootstrap.BootstrapHtml' )
  );


  /**
   * Helper function para generar li>a links de navegacion, generalmente usados en el header, pero aplicables a cualquier lista
   * donde se necesite marcar como activo un item de la lista vinculado a la URL actual
   *
   * @param string $title
   * @param null $url
   * @param array $options
   *
   * @return string
   */
  public function navListLink( $title, $url = NULL, $options = array() )
  {
    $active = strpos( $this->request->here, Router::url( $url ) ) === 0 ? 'active' : '';
    $link = $this->BootstrapHtml->link( $title, $url, $options );

    return $this->BootstrapHtml->tag( 'li', $link, array( 'escape' => FALSE, 'class' => $active ) );
  }


  /**
   * Helper function para generar th>a de cabecera de tabla para vista de lista, para agregar fondo e ícono de dirección
   *
   * @param       $key
   * @param       $label
   * @param array $attributes
   *
   * @return string
   */
  public function sort( $key, $label, $attributes = array() )
  {
    $class = $this->BootstrapPaginator->sortKey() == $key ? 'sorted-column' : '';
    $sortIcon = '';
    if ( $class )
    {
      $dir = $this->BootstrapPaginator->sortDir() == 'desc' ? 'down' : 'up';
      $sortIcon = "<i class=\"icon-chevron-{$dir}\"></i>";
      $attributes[ 'class' ] = isset( $attributes[ 'class' ] ) ? $attributes[ 'class' ] . ' ' . 'sorted-column' : 'sorted-column';
    }

    return $this->BootstrapHtml->tag( 'th', $this->BootstrapPaginator->sort( $key, $label ) . ' ' . $sortIcon, $attributes );
  }


  /**
   * @return array
   */
  public function getPreviousUrl()
  {
    $navTree = $this->_View->viewVars[ 'navTree' ];

    return $navTree[ count( $navTree ) - 2 ][ 'route' ];
  }
}
