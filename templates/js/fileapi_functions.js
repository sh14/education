(function ( $ ) {
	//"use strict";

	function set_fileapi_image( selector ) {
		
		let image_width    = $( selector ).attr( 'data-width' );
		let image_height   = $( selector ).attr( 'data-height' );
		let maxSize_width  = $( selector ).attr( 'data-maxwidth' );
		let maxSize_height = $( selector ).attr( 'data-maxheight' );

		$( selector ).fileapi(
			{
				url : shlo[ 'ajax_url' ],
				accept : 'image/*',
				imageSize : { minWidth : image_width, minHeight : image_height },
				data : {
					action : 'upload_file',
					/*data : {
						width : image_width,
						height : image_height,
						user_id : shlo.user_id,
					}*/
				},
				//debug: true,
				media : false,
				elements : {
					active : { show : '.js-upload', hide : '.js-browse' },
					progress : '.js-progress'
				},
				onSelect : function ( evt, ui ) {

					let file = ui.files[ 0 ];
					if ( file === undefined ) {
						window.swal( {
							type : 'error',
							title : 'Файл не подходит',
							text : "Выбранный файл не подходит! Вероятно он слишком мал.",
							showCancelButton : false
						} )
					}

					/*
					 FileAPI.filterFiles( file, function ( file1, info ) {
					 if (info.width<600){
					 console.log( info );
					 return false;
					 }
					 } );
					 */

					if ( !FileAPI.support.transform ) {
						alert( 'В вашем браузере не установлен Flash :(' );
					} else if ( file ) {
						$( '.modal-avatar' ).modal( {
							closeOnEsc : true,
							closeOnOverlayClick : false,
							onOpen : function ( overlay ) {
								// overlay - это модальное окно с картинкой для кропера

								// при клике на ОК
								$( overlay ).on( 'click', '.js-upload', function () {

									// закрывается окно кропера
									$.modal().close();

									$( this ).remove();

									// файл загружается
									$( selector ).fileapi( 'upload' );

								} );

								maxSize_width  = $( window ).width() - 10;
								maxSize_height = $( window ).height() - 10;

								let minSize = image_width;
								if ( image_height > image_width ) {
									minSize = image_height;
								}

								$( '.js-img', overlay ).cropper( {
									file : file,
									bgColor : '#fff',
									setSelect : [ 0, 0, 300, 300 ],
									maxSize : [ maxSize_width, maxSize_height ],
									minSize : [ minSize, minSize ],
									aspectRatio : image_width / image_height,
									selection : '100%',
									onSelect : function ( coords ) {

										coords.w = Math.ceil( coords.w );
										coords.h = Math.ceil( coords.h );

										$( selector ).fileapi( 'crop', file, coords );

										FileAPI.Image( file ).crop( coords.x, coords.y, coords.w, coords.h ).get( function ( err, file ) {

											FileAPI.Image( file ).preview( coords.w, coords.h ).get( function ( err, file ) {

												let dataURL = file.toDataURL();

												$( selector ).find( '.js-preview' ).css( { backgroundImage : 'url(' + dataURL + ')' } );
											} );
										} );
									}
								} );

							}
						} ).open();
					}
				}
			}
		);
	}

	$( '.js-upload-image' ).on( 'click', function ( event ) {
		set_fileapi_image( this );
	} );

})( jQuery );
