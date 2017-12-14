// Simple JavaScript Templating
// John Resig - http://ejohn.org/ - MIT Licensed


(function ( $ ) {
	//"use strict";

	/**
	 * Функция задержки, помогает не "положить" браузер под нагрузкой пересчетов размера окна
	 *
	 * @param func
	 * @param wait
	 * @param immediate
	 * @returns {Function}
	 */
	function debounce( func, wait, immediate ) {
		let timeout;
		return function () {
			let context = this, args = arguments;
			let later   = function () {
				timeout = null;
				if ( !immediate ) func.apply( context, args );
			};
			let callNow = immediate && !timeout;
			clearTimeout( timeout );
			timeout = setTimeout( later, wait );
			if ( callNow ) func.apply( context, args );
		};
	}

	/**
	 * Функция подстройки высоты окна чата под окно браузера
	 */
	function chat_auto_height() {
		let height = parseInt( $( window ).height() ) - 20;
		console.log( height );
		$( '.js-chat' ).height( height );
	}

	chat_auto_height();

	/**
	 * Изменение размера окна чата при изменении размера окна с задержкой
	 */
	$( window ).on( 'resize', debounce( function () {
		chat_auto_height();
	}, 250 ) );

	$( '.chat__submit' ).on( 'click', function ( event ) {
		event.preventDefault();

		let form = $( this ).closest( 'form' );

		let data = form.serialize();
		console.log( data );
		console.log( form.serializeArray() );
		//return '';
		let jqxhr = $.post( "index.php", data )
		             .done( function ( result ) {
			             console.log( result );

			             result = JSON.parse( result );
			             console.log( result );
			             if ( result[ 'result' ] === 'success' ) {
				             data         = form.serializeArray();
				             let new_data = {
					             'image' : '',
					             'name' : '',
					             'title' : '',
					             'content' : '',
					             'datetime' : '',
					             'class_name' : '',
					             'ID' : '',
					             'id_message' : '',
				             };
				             $.each( data, function ( index, el ) {
					             new_data[ el[ 'name' ] ] = el[ 'value' ];
				             } );
				             console.log( new_data );

				             let message = tmpl( 'message_template', new_data );
				             $( '.chat__messages' ).append( message );
			             }
			             /**/
		             } )
		             .fail( function () {

		             } )
		             .always( function () {

		             } );


	} );


})( jQuery );
