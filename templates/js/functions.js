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

	$('.chat__submit').on('click',function(){

		let form = $(this).closest('form');

		let data = {
			'title':form.find('[name="title"]'),
			'content':form.find('[name="content"]'),
			'id_user':form.find('[name="id_user"]'),
		};

		$.post()

		let message = tmpl( 'message_template', {
			//'image' : '1',
			//'name' : '2',
			'title' : '3',
			'content' : '4',
			//'datetime' : '5',
			//'class_name' : '6',
			'id_user' : '7',
			//'id_message' : '8',
		} );



	});


})( jQuery );
