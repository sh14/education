<?php
/**
 * Created by PhpStorm.
 * User: Ruslan
 * Date: 12.11.2017
 * Time: 14:47
 */

date_default_timezone_set( 'Europe/Moscow' );

$name = '';
if ( is_user_logged_in() ) {
	$user = get_user_info();
	$name = $user['first_name'] . ' ' . $user['last_name'];
}

?>

<div class="page container">
	<div class="row">
		<div class="col-md-12 col-sm-12 col-xs-12">
			<div class="col-md-9 col-sm-9 col-xs-9">
				<div class="chat js-chat">
					<div class="chat__messages">
						<div class="chat__messages-box">
							<?php echo display_message(); ?>
						</div>
					</div>
					<form action="" method="post" class="chat__form">
						<input type="text" class="form-control chat__title hidden" placeholder="Тема сообщения"
						       name="title">
						<textarea class="form-control chat__message" rows="1" placeholder="Для отправки сообщения нажмите Alt + Enter"
						          name="content" autofocus></textarea>
						<input type="hidden" name="action" value="message_add">
						<input type="hidden" name="id_message" value="">
						<button class="btn btn-success chat__submit" type="submit">Отправить</button>
					</form>
				</div>
			</div>

				<div class="col-md-3 col-sm-3 col-xs-3">
					<div class="avatar">
						<div class="avatar__image"<?php echo display_avatar(); ?>></div>
						<h3 class="avatar__name"><?php echo $name; ?></h3>
						<div class="avatar__buttons">
							<button type="button" class="btn btn-primary btn-block" data-toggle="modal"
							        data-target="#modal-1">Мой профиль
							</button>
							<a href="<?php echo get_root_url() . '?p=logout'; ?>"
							   class="btn btn-link btn-block">Выход</a>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>

<script id="message_template" type="text/html"><?php
	echo get_template_part( 'message', [
		'image',
		'name',
		'title',
		'content',
		'datetime',
		'class_name',
		'id_user',
		'id_message',
	] );
	?></script>

<?php
if ( is_user_logged_in() ) {
	?>
	<div class="modal profile" id="modal-1">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<h4 class="modal-title">Редактирование профиля</h4>
					<button class="close" type="button" data-dismiss="modal">
						<i class="fa fa-close"></i>
					</button>
				</div>
				<div class="modal-body">
					<?php
					get_template_part( 'profile_edit' );
					?>
				</div>
			</div>
		</div>
	</div>
	<?php
}

?>

<script>
	/*var your_message = document.getElementById("your_massage");
	var btn_message = document.querySelector('[data-event="btn_message"]');
	var results_message = document.getElementById("results_message");
	var p = document.createElement('p');
	var flag = 0;
	var now = new Date;

	btn_message.addEventListener("click", cl);


	//ниже функция нажатия на клавишу и вызова функций
	function cl(event) {
		event.preventDefault();
		if (flag == 0) {
			flag = 1;
			show_message();
		}
		return flag = 0;
	}

	//ниже функция отправки и получения сообщения в поле
	function show_message() {
		if (flag == 1 && your_message.value != "") {
			var vall = document.getElementById("your_massage").value;
			var template = tmpl( jQuery( '#message-template').html(), {
				message : vall
			} );

			jQuery('#results_message').append(template);
		}
		your_message.value = "";


		var mes = $('#message').serialize();
		$.ajax({
			type: 'POST',
			url: 'index.php',
			data: mes && "event=mes_complited",
			success: function (data) {
			   // $('#results_message').append(data);
				console.log(1);
			},
			error: function (xhr, str) {
				alert('Возникла ошибка: ' + xhr.responseCode);
			}
		});
	}

	(function () {
		var cache = {};

		this.tmpl = function tmpl( str, data ) {
			// Figure out if we're getting a template, or if we need to
			// load the template - and be sure to cache the result.
			var fn = !/\W/.test( str ) ?
				cache[ str ] = cache[ str ] ||
					tmpl( document.getElementById( str ).innerHTML ) :

				// Generate a reusable function that will serve as a template
				// generator (and which will be cached).
				new Function( "obj",
					"var p=[],print=function(){p.push.apply(p,arguments);};" +

					// Introduce the data as local variables using with(){}
					"with(obj){p.push('" +

					// Convert the template into pure JavaScript
					str
						.replace( /[\r\t\n]/g, " " )
						.split( "<%" ).join( "\t" )
						.replace( /((^|%>)[^\t]*)'/g, "$1\r" )
						.replace( /\t=(.*?)%>/g, "',$1,'" )
						.split( "\t" ).join( "');" )
						.split( "%>" ).join( "p.push('" )
						.split( "\r" ).join( "\\'" )
					+ "');}return p.join('');" );
			// Provide some basic currying to the user
			return data ? fn( data ) : fn;
		};
	})();*/

	/* //ниже ajax запрос на сообщения из сервера
		 function see_message() {
			 if(your_message.value != "") {
				 var mes = $('#message').serialize();
				 $.ajax({
					 type: 'POST',
					 url: 'index.php',
					 data: mes,
					 success: function (data) {
						 $('#results_message').append(data);
					 },
					 error: function (xhr, str) {
						 alert('Возникла ошибка: ' + xhr.responseCode);
					 }
				 });
			 }
		 }
	 */
</script>
<style></style>
