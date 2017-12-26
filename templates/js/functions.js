// Simple JavaScript Templating
// John Resig - http://ejohn.org/ - MIT Licensed

(function ( $ ) {
	"use strict";

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
		$( '.chat__messages-box' ).height( (height - $( '.chat__form' ).height()) );
	}

	/**
	 * Функция отправляет введенные пользователем данные и если передача прошла успешно, выводит сообщение в чат
	 *
	 * @param obj
	 */
	function message_add( obj ) {

		let form = $( obj ).closest( 'form' );

		let data = form.serialize();

		$.post( shlo[ 'ajax_url' ], data + '&action=message_add' )
		 .done( function ( result ) {

			 result = JSON.parse( result );

			 if ( result[ 'error' ] === undefined ) {
				 console.log( result );
				 data         = form.serializeArray();
				 let new_data = {
					 'image' : shlo[ 'image' ],
					 'name' : shlo[ 'name' ],
					 'title' : '',
					 'content' : '',
					 'datetime' : '',
					 'class_name' : '',
					 'id_user' : '',
					 'id_message' : '',
				 };
				 $.each( data, function ( index, el ) {
					 if ( !el[ 'value' ].empty ) {
						 new_data[ el[ 'name' ] ] = el[ 'value' ];
					 }

				 } );

				 new_data[ 'id_user' ]  = result[ 'id_user' ];
				 new_data[ 'datetime' ] = format_date( result[ 'datetime' ] );
				 new_data[ 'image' ]    = ' style="background-image:url(' + new_data[ 'image' ] + ');"';

				 // вставка значений в шаблон сообщения
				 let message = tmpl( 'message_template', new_data );


				 // добавление сформированного сообщения в окно чата
				 $( '.chat__messages-box' ).append( message );

				 scroll_to_last_message();

				 // очистка полей формы
				 $( '[name=title]' ).val( '' );
				 $( '[name=content]' ).val( '' );
				 $( '[name=id_message]' ).val( '' );
			 } else {
				 console.log( result[ 'error' ] );
			 }
		 } )
		 .fail( function () {

		 } )
		 .always( function () {

		 } );
	}

	/**
	 * скрол чата к последнему элементу
	 */
	function scroll_to_last_message() {
		let box  = $( '.chat__messages-box' );
		let item = box.find( '.message:last-child' );
		if ( item.length > 0 ) {
			// скрол к последнему элементу
			box.scrollTop( box.scrollTop() + item.position().top );
		}
	}

	/**
	 * Функция перевода timestamp в формат даты yyyy-mm-dd
	 *
	 * @param date
	 * @returns {string}
	 */
	function format_date( date ) {

		if ( date === undefined ) {
			date = new Date();
		} else {
			date = new Date( date );
		}

		return ('0' + date.getHours()).slice( -2 ) + ':' + ('0' + date.getMinutes()).slice( -2 ) + ':' + date.getSeconds()
			+ ', ' + ('0' + date.getDate()).slice( -2 ) + '.' + ('0' + (date.getMonth() + 1)).slice( -2 ) + '.' + date.getFullYear();
	}

	/**
	 * Функция отображения поля ввода заголовка при вводе сообщения больше указанной длинны
	 */
	function show_title( obj, limit ) {
		let form   = $( obj ).closest( 'form' );
		let length = $( obj ).val().length;
		console.log( length );
		if ( length > limit ) {
			form.find( '[name="title"]' ).removeClass( 'hidden' );
		} else {
			form.find( '[name="title"]' ).addClass( 'hidden' );
		}
	}

	// авто размер чата
	chat_auto_height();

	// скрол к последнему сообщению
	scroll_to_last_message();

	/**
	 * Изменение размера окна чата при изменении размера окна с задержкой
	 */
	$( window ).on( 'resize', debounce( function () {
		chat_auto_height();
	}, 250 ) );

	/**
	 * Отслеживание ввода сообщения
	 */
	$( '[name="content"]' ).on( 'input', function ( event ) {
		show_title( this, 10 );
	} );
	/**
	 * отслеживание отправки сообщения
	 */
	$( '[name="content"]' ).on( 'keypress', function ( event ) {

		if ( event.which === 13 && event.altKey ) {
			console.log( 'sad' );
			message_add( this );
		}
	} );

	/**
	 * Отправка сообщения по клику на кнопке
	 */
	$( '.chat__submit' ).on( 'click', function ( event ) {
		event.preventDefault();
		message_add( this );
	} );


	/*Код жулинского для всплывающего окна*/


	function Show() {
		$( '.massive' ).addClass( "left" );
		$( '#mex' ).addClass( "active" );
	}

	function Hide() {
		$( '.massive' ).removeClass( "left" );
		$( '#mex' ).removeClass( "active" );
	}

	$( '#mex' ).on( 'click', function () {
		if ( $( '.massive' ).hasClass( "left" ) ) {
			Hide();
		} else {
			Show();
		}
	});


	/*Конец кода Жулинского*/

})( jQuery );
