$( window ).unload( function ()
{
  $( "#loading" ).show();
} )

$().ready( function ()
{
  /**
   * Tooltip
   */
  $( '[rel=tooltip]' ).tooltip();

  /**
   * Selects setup
   */
  $( "select" ).select2( { width: 'off' } );

  /**
   * Date Picker cero config
   */
  $( '.date' ).datetimepicker( {
    autoclose     : true,
    weekStart     : 1,
    format        : 'yyyy-mm-dd',
    minView       : 2,
    forceParse    : true,
    todayHighlight: true,
    language      : 'es'
  } );
  if ( $( '.date' ).is( ':focus' ) ) $( '.date' ).datetimepicker( 'show' );

  /**
   * DateTime Picker cero config
   */
  $( '.datetime' ).datetimepicker( {
    autoclose     : true,
    weekStart     : 1,
    format        : 'yyyy-mm-dd hh:ii:ss',
    minView       : 0,
    forceParse    : true,
    todayHighlight: true,
    language      : 'es'
  } );
  if ( $( '.datetime' ).is( ':focus' ) )$( '.datetime' ).datetimepicker( 'show' );

  /**
   * Config de Search Element
   */
  var filters = {};
  $( '#admin_search_form' ).children( 'div.control-group' ).each( function ()
  {
    if ( $( this ).attr( 'data-id' ) == '*' ) return;

    filters[$( this ).attr( 'data-id' )] = $( this );
    $( this ).data( 'visible', 1 );

    $( this ).find( '.search_filter_remove' ).click( function ()
    {
      filters[$( this ).attr( 'data-id' )].find( 'input' ).val( '' );
      filters[$( this ).attr( 'data-id' )].data( 'visible', 0 );
      filters[$( this ).attr( 'data-id' )].hide();
    } )

    $( this ).removeAttr( 'style' );
    if ( !_.include( currentFilters, $( this ).attr( 'data-id' ) ) )
    {
      $( this ).data( 'visible', 0 );
      $( this ).hide();
    }
  } )

  /**
   * Search Submit
   */
  $( '#admin_search_apply' ).click( function ()
  {
    $( '#admin_search_form' ).submit();
  } );

  $( '.field_filters' ).click( function ()
  {
    var key = $( this ).attr( 'data-id' );
    if ( filters[key].data( 'visible' ) ) return;
    filters[key].data( 'visible', 1 )
    filters[key].show();
  } )

  $( '#bulk_export' ).click( function ()
  {
    $( '#bulk_export_type' ).val( 'search' );
    $( '#bulk_form_export' ).submit();
  } );

  /**
   * Table columns aligns
   */
  _.each( ['center', 'left', 'right'], function ( type )
  {
    $( 'th.' + type ).each( function ( i, head )
    {
      var index = $( head ).index();
      $( head ).parents().eq( 2 ).find( 'tr>td' ).parent().each( function ( i, elem )
      {
        $( elem ).children( 'td' ).eq( index ).addClass( type );
      } )
    } )

  } );

  /**
   * automatic responsive tables
   * simplemente deja visibles 3 o 6 columnas en phones y tablets respectivamente
   * además siempre deja visible la ultima columna
   *
   * para diseñar tablas responsive a medida, simplemente sacar la clase auto-responsive de las tablas
   */
  $( 'table.auto-responsive > thead > tr, table.auto-responsive > tbody > tr' ).each( function ( i, elem )
  {
    var cols = $( elem ).children( 'td, th' );
    cols.splice( cols.length - 1, 1 );
    cols.slice( 3 ).addClass( 'hidden-phone' );
    cols.slice( 6 ).addClass( 'hidden-tablet' );
  } )


} );