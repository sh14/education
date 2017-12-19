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
						<div class="avatar__image"<?php //echo display_avatar(); ?>></div>
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
