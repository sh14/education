<?php
/**
 * Created by PhpStorm.
 * User: Ruslan
 * Date: 12.11.2017
 * Time: 14:47
 */

/*Обработка кнопки ВЫЙТИ*/
if ( @$_POST['exit'] ) {
	setcookie( 'RestrictedArea', '', time() - 60 * 60 * 24 );
	header( "Location: " . $_SERVER['PHP_SELF'] );
	exit();
}
$message = 'Внесите данные, которые хотите изменить.';
if ( ! empty( $_GET['event'] ) ) {
	if ( $_GET['event'] == 'error' ) {
		$message = 'Необходимо ввести все данные.';
	}

	if ( $_GET['event'] == 'success' ) {
		$message = 'Запись добавлена.';
	}
}
?>

	<div class="chat_container container">
		<div class="row">
			<div class="registrate col-sm-12">
				<div class="chat_profile_buttons">
					<form action="<?php echo $_SERVER["PHP_SELF"]; ?>" method="post">
						<button type="button" class="btn btn_chat btn-success btn_chat_profile" data-toggle="modal"
						        data-target="#modal-1">Мой профиль
						</button>
						<a href="<?php echo get_root_url() . '?p=logout'; ?>"
						   class="btn btn-danger btn_chat btn-success chat_logout">Выход</a>
					</form>
				</div>
				<br>
				<br>

				<h1>Окно чата</h1>
				<div class="row">
					<div class="col-md-12 col-sm-12 col-xs-12 chat_window">
						<div class="col-md-9 col-sm-9 col-xs-9 chat_window_wall">
						</div>
						<div class="col-md-3 col-sm-3 col-xs-3 chat_window_avatar">
							<div class="chat_window_avatar_img"></div>
							<div class="chat_window_avatar_signature">
								<?php
								if ( is_user_logged_in() ) {
									$user = get_user_info();
									$name = $user['first_name'] . ' ' . $user['last_name'];
								}
								?>
							</div>
						</div>
					</div>
				</div>
				<div class="row row-centered post_form">
					<div class="col-md-6 col-sm-12">
						<form action="" method="post">
							<input type="text" class="form-control" placeholder="Тема сообщения" name="title"
							       id="title">
							<textarea class="form-control" rows="5" placeholder="Текст сообщения" name="message_input"
							          id="message_input"></textarea>
							<input class="btn btn-info" type="button" value="Отправить">
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>

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
