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
	 * Функция очистки полей формы, содержащих введенные пользователем данные
	 */
	function clear_message_form() {
		// очистка полей формы
		let title = $( '[name=title]' );
		title.val( '' );
		$( '[name=content]' ).val( '' );
		$( '[name=id_message]' ).val( '' );
		title.addClass( 'hidden' );
		$( '.chat__cancel' ).addClass( 'hidden' );
	}

	const events = [ 'click', 'mousemove', 'resize', 'scroll', 'touchstart', 'touchmove' ];

	for ( let i = 0; i < events.length; i++ ) {
		$( window ).on( events[ i ], debounce(function ( event ) {
			let msg = "Handler for " + events[ i ] + " called at ";
			msg += event.pageX + ", " + event.pageY;
			console.log( msg );
		}, 500) );
	}


	/**
	 * Функция подстройки высоты окна чата под окно браузера
	 */
	function chat_auto_height() {
		let height = parseInt( $( window ).height() ) - 20;

		$( '.js-chat' ).height( height );
		$( '.chat__messages-box' ).height( (height - $( '.chat__form' ).height()) );
	}

	function message_append( one_message, form ) {
		if ( one_message[ 'error' ] === undefined ) {

			if ( form === undefined ) {
				form = $( '.chat__form' );
			}

			let data     = form.serializeArray();
			let new_data = {
				'image' : shlo[ 'image' ],
				'name' : shlo[ 'name' ],
				'title' : '',
				'content' : '',
				'datetime' : '',
				'class_name' : '',
				'id_user' : '',
				'id_message' : '',
				'edit' : '',
			};

			// флаг в положении "редактирование сообщения"
			let action = 'edit';
			$.each( data, function ( index, el ) {

				// если форма содержит данные в указанном поле
				if ( el[ 'value' ] !== '' ) {

					// данные добавляются в массив из формы
					new_data[ el[ 'name' ] ] = el[ 'value' ];
				} else {
					// иначе данные добавляются в массив из результата запроса
					new_data[ el[ 'name' ] ] = one_message[ el[ 'name' ] ];
				}

				// если id_message не указан, флаг в положение "добавление нового сообщения"
				if ( el[ 'name' ] === 'id_message' && el[ 'value' ] === '' ) {
					action = 'add';
				}
			} );

			new_data[ 'id_user' ]    = one_message[ 'id_user' ];
			new_data[ 'id_message' ] = one_message[ 'id_message' ];
			new_data[ 'datetime' ]   = format_date( one_message[ 'datetime' ] );
			new_data[ 'image' ]      = ' style="background-image:url(' + new_data[ 'image' ] + ');"';
			new_data[ 'edit' ]       = '<span class="message__edit"></span>';

			// вставка значений в шаблон сообщения
			let message = tmpl( 'message_template', new_data );

			// если нужно добавить сообщение
			if ( action === 'add' ) {

				// добавление сформированного сообщения в окно чата
				$( '.chat__messages-box' ).append( message );
			} else {

				// редактирование указанного сообщения
				let current_message = $( document ).find( '[data-id_message="' + new_data[ 'id_message' ] + '"]' );
				current_message.find( '.message__title' ).text( new_data[ 'title' ] );
				current_message.find( '.message__text' ).html( new_data[ 'content' ] );
			}

			// скрол к последнему сообщению
			scroll_to_last_message();

			clear_message_form();
		} else {
			//console.log( message[ 'error' ] );
		}
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

			 $.each( result, function ( i, one_message ) {
				 message_append( one_message, form );
			 } );
		 } )
		 .fail( function () {

		 } )
		 .always( function () {

		 } );
	}

	/**
	 * Функция редактирования сообщения
	 */
	$( '.chat__messages-box' ).on( 'click', '.message__edit', function () {
		let message    = $( this ).closest( '.message' );
		let title      = message.find( '.message__title' ).text();
		let id_message = message.attr( 'data-id_message' );
		let text       = message.find( '.message__text' ).html();

		$( '.chat__cancel' ).removeClass( 'hidden' );
		let content = $( '.chat__message' );
		content.val( text );
		content.focus();
		if ( title.length > 0 ) {
			let message_title = $( '[name=title]' );
			message_title.removeClass( 'hidden' );
			message_title.val( title );
		}
		$( '[name="id_message"]' ).val( id_message );
	} );

	/**
	 * Функция отправляет запрос на отображении сообщений в чате
	 */
	function send_display_request() {
		if ( shlo[ 'user_id' ] > 0 ) {

			// определение id последнего полученного сообщения
			let last_message_id = $( '.chat__messages-box' ).find( '.message' ).last().attr( 'data-id_message' );

			// составление запроса
			let query = 'action=get_last_messages&last_message_id=' + last_message_id;

			$.post( shlo[ 'ajax_url' ], query ).done( function ( result ) {
				if ( result !== null ) {
					result = JSON.parse( result );

					$.each( result, function ( i, one_message ) {

						message_append( one_message );
					} );
				}

			} );
		}
	}


	send_display_request();
	setInterval( send_display_request, 1500 );

	/**
	 * Функция отмены редактирования сообщения
	 */
	$( '.chat__cancel' ).on( 'click', function () {
		clear_message_form();
	} );

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
	$( '[name="content"]' ).on( 'keydown', function ( event ) {

		if ( event.key === 'Enter' && event.altKey ) {
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


	function swipe_show() {
		$( '.swipe' ).removeClass( 'swipe-hidden' );
	}

	function swipe_hide() {
		$( '.swipe' ).addClass( 'swipe-hidden' );
	}

	$( '.swipe__button' ).on( 'click', function () {
		let parent = $( this ).closest( '.swipe' );
		if ( parent.hasClass( 'swipe-hidden' ) ) {
			swipe_show();
		} else {
			swipe_hide();
		}
	} );

	/**
	 * Проверка соответствия введенных паролей
	 */
	$( '.password, .confirm_password' ).on( 'keyup', function () {
		let parent  = $( this ).closest( 'form' );
		let message = parent.find( '.profile__message' );
		if ( $( '.password' ).val() !== $( '.confirm_password' ).val() && $( '.password, .confirm_password' ).val() !== '' ) {
			message.html( 'Пароли не совпадают' );
			parent.find( '[type="submit"]' ).prop( "disabled", true );
		} else {
			message.html( '' );
			parent.find( '[type="submit"]' ).prop( "disabled", false );
		}
	} );

	/*
		$( "body" ).on( 'swipeleft', swipe_show() );
		$( "body" ).on( 'swipeleft', swipe_hide() );
	*/


})( jQuery );
