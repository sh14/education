<div id="installer"></div>

<script type="text/javascript">
	$( document ).ready( function () {

		$( '.password, .confirm_password' ).on( 'keyup', function () {
			if ( $( '.password' ).val() === $( '.confirm_password' ).val() ) {
				if ( $( '.password, .confirm_password' ).val() != '' ) {
					$( '.message' ).html( 'Пароли совпадают' ).css( 'color', 'green' );
				} else {
					$( '.message' ).html( '' ).removeAttr( 'style' );
				}
			} else {
				$( '.message' ).html( 'Пароли не совпадают' ).css( 'color', 'red' );
			}
		} );

		$( '.save_avatar' ).on( 'click', function () {
			var canvas = $( '.avatar__preview canvas' );
			$( '.form-avatar input[name=image]' ).val( canvas[ 0 ].toDataURL() );
		} );


		$( '#img-preview' ).modal( { show : false } );


		var processing = false;

		var class_number = 0;

		if ( !(FileAPI.support.html5 || FileAPI.support.flash) ) {
			alert( 'Ваш браузер не поддерживает Flash и HTML5' );
		}

		function image( file, width, height, type ) {
			var image = FileAPI.Image( file ), callback;

			if ( type ) {
				image.resize( width, height, type );
			} else if ( width ) {
				image.preview( width, height );
			}

			image.get( function ( err, img ) {
				if ( err ) {
					alert( width + 'x' + height + ' — ошибка' );
				}
				else {
					var el       = document.createElement( 'div' );
					el.className = 'image_' + ++class_number;
					if ( el.className == 'image_1' ) {
						el.className += ' jcrop-preview';
						el.setAttribute( 'alt', 'предпросмотр' );
						el.appendChild( img );
						avatar__preview.appendChild( el );
					}
					else if ( el.className == 'image_2' ) {
						el.setAttribute( 'id', 'target' );
						el.setAttribute( 'alt', 'ваша фотография' );
						el.appendChild( img );
						image_container.appendChild( el );
					}
				}

				callback && callback();
			} );

			return function ( then ) {
				callback = then;
			};
		}


		FileAPI.event.on( file_to_upload, 'change', function ( evt ) {
			$( '#img-preview' ).modal( 'show' );
			var file = FileAPI.getFiles( evt )[ 0 ];

			!processing && FileAPI.getInfo( file, function ( err, info ) {
				if ( info.width >= 200 && info.height >= 200 ) {
					aspectRatio               = info.height / info.width;
					processing                = true;
					image_container.innerHTML = '';
					loading.style.display     = '';

					$( '#img-preview' ).modal( 'show' );
					$( '.profile .avatar' ).css( 'background-image', 'none' );

					image( file, 200, 200 )( function () {

						image( file, 300, 300 * aspectRatio );

						processing            = false;
						loading.style.display = 'none';
					} );

				}
				else {
					alert( 'Размер изображения должен быть больше: ' + info.width + 'x' + info.height );
				}
			} );
		} );


		function updatePreview( c ) {
			if ( parseInt( c.w ) > 0 ) {

				var imageObj = $( '.image_2 canvas' )[ 0 ];
				var canvas   = $( '.image_1 canvas' )[ 0 ];
				var context  = canvas.getContext( "2d" );
				context.clearRect( 0, 0, canvas.width, canvas.height );

				if ( imageObj != null && c.x != 0 && c.y != 0 && c.w != 0 && c.h != 0 ) {
					context.drawImage( imageObj, c.x, c.y, c.w, c.h, 0, 0, canvas.width, canvas.height );
				}
			}
		}


		$( '#img-preview' ).on( 'click', '#target canvas', function () {
			$( this ).Jcrop( {
				keySupport : false,
				bgColor : '#fff',
				onChange : updatePreview,
				onSelect : updatePreview,
				allowSelect : true,
				allowMove : true,
				allowResize : true,
				aspectRatio : 1
			} );

		} );

	} );


</script>


<!--Свайп окна профиля-->
        <div class="massive">
            <div class="round">
                <img src="images/settings.png" alt="img" id="mex">
            </div>
            <div class="move_window">
                <div class="modal-header">
                    <h4 class="modal-title">Редактирование профиля</h4>
                    <button class="close" type="button" data-dismiss="modal">
                        <i class="fa fa-close"></i>
                    </button>
                </div>
                <div class="modal-body">
                    <?php get_template_part( 'profile_edit' ); ?>
                </div>
            </div>
        </div>
<!--Свайп окна профиля-->


<?php
do_action( 'footer' );
?>
</body>
</html>
