$().ready( function ()
{
  $( 'textarea.richtext' ).each( function ( i, item )
  {
    $( 'textarea#' + $( item ).attr( 'id' ) ).wysihtml5( {
      stylesheets: false,
      html       : true,
      locale     : "es-AR"
    } );
  } );
} );