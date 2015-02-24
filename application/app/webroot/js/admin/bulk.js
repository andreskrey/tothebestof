/**
 * Selector multiple
 */
$( '#bulk_checkbox' ).click( function ()
{
	//revisa si tiene que checkear (si es falso que todos están chekeados)
	var check = !_.every( $( 'input.bulk_checkbox' ), function ( e ) { return $( e ).prop( 'checked' ) == true;} );

	//solo checkea si el propio selector está chekeado
	if ( ($( this ).is( ':checked' ) && check) )
	{
		$( 'input.bulk_checkbox' ).prop( 'checked', check );
		$( 'input.bulk_checkbox' ).each( function ( i, item )
		{
			$( item ).parents().eq( 1 ).addClass( 'warning' );
		} )
	}
	//y solo deschekea si el propio selector está deschekeado
	else if ( (!$( this ).is( ':checked' ) && !check) )
	{
		$( 'input.bulk_checkbox' ).prop( 'checked', check );
		$( 'input.bulk_checkbox' ).each( function ( i, item )
		{
			$( item ).parents().eq( 1 ).removeClass( 'warning' );
		} )
	}

	updateBulkForms();
} );

/**
 * por cada checkbox de fila, agrega o quita warning si está o no deschekeado, y chekea o deschekea el selector general si están todos los checkboxes chekeados o deschekeados.
 */
$( 'input.bulk_checkbox' ).change( function ()
{
	if ( $( this ).is( ':checked' ) )
	{
		$( this ).parents().eq( 1 ).addClass( 'warning' );
		var check = _.every( $( 'input.bulk_checkbox' ), function ( e ) { return $( e ).prop( 'checked' ) == true} );
		if ( check ) $( '#bulk_checkbox' ).prop( 'checked', true );
	}
	else
	{
		$( this ).parents().eq( 1 ).removeClass( 'warning' );
		var uncheck = _.any( $( 'input.bulk_checkbox' ), function ( e ) { return $( e ).prop( 'checked' ) == false} );
		if ( uncheck ) $( '#bulk_checkbox' ).prop( 'checked', false );
	}

	updateBulkForms();
} );

/**
 * actualiza formularios de bulk_export y bulk_delete con los IDs seleccionados
 */
function updateBulkForms()
{
	var ids = _.map( $( 'input.bulk_checkbox:checked' ),function ( e )
	{
		return $( e ).val();
	} ).join( ',' );

	$( '#bulk_form_export, #bulk_form_delete' ).find( 'input.bulk_form_value' ).val( ids );
}

