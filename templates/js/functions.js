// Simple JavaScript Templating
// John Resig - http://ejohn.org/ - MIT Licensed

(function ( $ ) {
	"use strict";

	/**
	 * Функция постановки задач в очередь. Функции выполняются по очереди, это сделано для сокращения кол-ва таймеров
	 * до одного, с целью предотвращения зависания.
	 */
	let Scheduler = (function () {
		let tasks      = [];
		let minimum    = 10;
		let timeoutvar = null;
		let output     = {
			add : function ( func, context, timer, once ) {
				let iTimer = parseInt( timer );
				context    = context && typeof context === 'object' ? context : null;
				if ( typeof func === 'function' && !isNaN( iTimer ) && iTimer > 0 ) {
					tasks.push( [ func, context, iTimer, iTimer * minimum, once ] );
				}
			},
			remove : function ( func, context ) {
				for ( let i = 0, l = tasks.length; i < l; i++ ) {
					if ( tasks[ i ][ 0 ] === func && (tasks[ i ][ 1 ] === context || tasks[ i ][ 1 ] == null) ) {
						tasks.splice( i, 1 );
						return;
					}
				}
			},
			halt : function () {
				if ( timeoutvar ) {
					clearInterval( timeoutvar );
				}
			}
		};
		let schedule   = function () {
			for ( let i = 0, l = tasks.length; i < l; i++ ) {
				if ( tasks[ i ] instanceof Array ) {
					tasks[ i ][ 3 ] -= minimum;
					if ( tasks[ i ][ 3 ] < 0 ) {
						tasks[ i ][ 3 ] = tasks[ i ][ 2 ] * minimum;
						tasks[ i ][ 0 ].apply( tasks[ i ][ 1 ] );
						if ( tasks[ i ][ 4 ] ) {
							tasks.splice( i, 1 );
						}
					}
				}
			}
		};
		timeoutvar     = setInterval( schedule, minimum );
		return output;
	})();

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

	/**
	 * Функция подстройки высоты окна чата под окно браузера
	 */
	function chat_auto_height() {
		let height = parseInt( $( window ).height() ) - 20;

		$( '.js-chat' ).height( height );
		$( '.chat__messages-box' ).height( (height - $( '.chat__form' ).height()) );
	}

	/**
	 * Функция обработки полученных от сервера данных - добавление или исправление сообщений в чате
	 *
	 * @param result - массив в формате json, содержащий сообщения
	 */
	function message_push( result ) {
		if ( result !== null ) {

			// перевод полученной строки в массив
			result = JSON.parse( result );

			// если массив что-то содержит
			if ( result.length > 0 ) {

				// осуществляется перебор сообщений из массива
				$.each( result, function ( i, message_data ) {

					// если ошибок в сообщении нет
					if ( message_data[ 'error' ] === undefined ) {

						// объединение данных сообщения с шаблонным массивом данных
						message_data = $.extend( {
							'image' : shlo[ 'image' ],
							'name' : shlo[ 'name' ],
							'title' : '',
							'content' : '',
							'datetime' : '',
							'class_name' : '',
							'id_user' : '',
							'id_message' : '',
							'edit' : '',
						}, message_data );

						message_data[ 'content' ] = message_data[ 'content' ].replace( /<\/?[^>]+>/gi, '' );
						message_data[ 'content' ] = links_encode( message_data[ 'content' ] );

						// определение шаблона для сообщения
						let message_box = $( '.chat__messages-box' );

						// флаг в положении "редактирование сообщения"
						let action = 'edit';

						// поиск указанного сообщения в окне чата
						let current_message = message_box.find( '[data-id_message="' + message_data[ 'id_message' ] + '"]' );

						// если id_message не указан, флаг в положение "добавление нового сообщения"
						// и сообщения с указанным id еще нет в чате
						if ( current_message.length === 0 ) {
							action = 'add';
						}

						// определение необходимости давать возможность редактировать сообщение
						if ( message_data[ 'edit' ] === 1 ) {
							message_data[ 'edit' ] = '<span class="message__edit"></span>';
						} else {
							message_data[ 'edit' ] = '';
						}

						// обозначение своего/чужого сообщения
						if ( message_data[ 'class_name' ] === 0 ) {
							message_data[ 'class_name' ] = ' message_alien';
						} else {
							message_data[ 'class_name' ] = '';
						}

						// указание пути к файлу аватара
						if ( message_data[ 'image' ] !== '' ) {
							message_data[ 'image' ] = ' style="background-image:url(' + shlo[ 'image_url' ] + message_data[ 'image' ] + ');"';
						}

						//message_data[ 'datetime' ]   = format_date( message_data[ 'datetime' ] );

						// вставка значений в шаблон сообщения
						let message = tmpl( 'message_template', message_data );

						// если нужно добавить сообщение
						if ( action === 'add' ) {

							// добавление сформированного сообщения в окно чата
							message_box.append( message );
						} else {

							// замена указанного сообщения на обновленное
							current_message.replaceWith( message );
						}

						// скрол к последнему сообщению
						scroll_to_last_message();
					} else {
						//console.log( message[ 'error' ] );
					}
				} );
			}
		}
	}


	/**
	 * Функция отправляет введенные пользователем данные
	 *
	 * @param obj
	 */
	function message_add( obj ) {

		let data = $( obj ).closest( 'form' ).serialize();

		$.post( shlo[ 'ajax_url' ], data + '&action=message_add' )
		 .done( function () {
			 let id_message = $( '[name="id_message"]' ).val();
			 if ( id_message !== '' ) {

				 // отправка запроса на получение конкретного сообщения
				 send_display_request( id_message );
			 } else {
				 // отправка запроса на получение последних сообщений
				 send_display_request();
			 }
			 // если данные отпралены успешно, происходит очистка формы
			 clear_message_form();
		 } );
	}

	/**
	 * Функция отправляет запрос для получения еще не полученных сообщений, либо на измененное сообщение.
	 */
	function send_display_request( id ) {
		if ( shlo[ 'user_id' ] > 0 ) {

			let query = 'action=get_last_messages&';

			if ( id === undefined ) {
				// определение id последнего полученного сообщения
				id = $( '.chat__messages-box' ).find( '.message' ).last().attr( 'data-id_message' );

				// составление запроса
				query += 'last_message_id=' + id;
			} else {
				// составление запроса
				query += 'message_id=' + id;
			}

			$.post( shlo[ 'ajax_url' ], query ).done( function ( result ) {
				console.log( new Date() );
				// обработка ответа сервера
				message_push( result );
			} );
		}
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
		if ( length > limit ) {
			form.find( '[name="title"]' ).removeClass( 'hidden' );
		} else {
			form.find( '[name="title"]' ).addClass( 'hidden' );
		}
	}

	/**
	 * Перевод url'а в html ссылку
	 *
	 * @param text
	 * @returns {*}
	 */
	function links_encode( text ) {
		let exp   = /(\b(https?|ftp|file):\/\/[-A-Z0-9+&@#\/%?=~_|!:,.;]*[-A-Z0-9+&@#\/%=~_|])/ig;
		let text1 = text.replace( exp, "<a target=\"_blank\" href='$1'>$1</a>" );
		let exp2  = /(^|[^\/])(www\.[\S]+(\b|$))/gim;
		text      = text1.replace( exp2, '$1<a target="_blank" href="http://$2">$2</a>' );
		return text;
	}

	/**
	 * Перевод html ссылки в url
	 *
	 * @param text
	 * @returns {string | * | void}
	 */
	function links_decode( text ) {
		let exp = /<a(.*?)href="(.*?)">(.*?)<\/a>/ig;
		text    = text.replace( exp, '$3' );
		return text;
	}

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

	// авто размер чата
	chat_auto_height();

	// скрол к последнему сообщению
	scroll_to_last_message();

	/**
	 * Функция редактирования сообщения
	 */
	$( '.chat__messages-box' ).on( 'click', '.message__edit', function () {
		let message    = $( this ).closest( '.message' );
		let title      = message.find( '.message__title' ).text();
		let id_message = message.attr( 'data-id_message' );
		let text       = message.find( '.message__text' ).html();

		// преобразование html ссылок в url'ы
		text = links_decode( text );

		$( '.chat__cancel' ).removeClass( 'hidden' );
		let content = $( '.chat__message-field' );
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
	 * Функция отмены редактирования сообщения
	 */
	$( '.chat__cancel' ).on( 'click', function () {
		clear_message_form();
	} );

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


	Scheduler.add( send_display_request, null, 1000, false );
	//setInterval( send_display_request, 1500 );

	/*

		const events = [ 'click', 'mousemove', 'resize', 'scroll', 'touchstart', 'touchmove' ];

		for ( let i = 0; i < events.length; i++ ) {
			$( window ).on( events[ i ], debounce( function ( event ) {
				let msg = "Handler for " + events[ i ] + " called at ";
				msg += event.pageX + ", " + event.pageY;
				console.log( msg );
			}, 500 ) );
		}
	*/


})( jQuery );
