<?php
/**
 * Created by PhpStorm.
 * User: Ruslan
 * Date: 12.11.2017
 * Time: 14:47
 */

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
				<div class="chat">
					<div class="chat__messages">
						<?php echo display_message(); ?>


					</div>
					<form action="" method="post" class="chat__form">
						<input type="text" class="form-control chat__title" placeholder="Тема сообщения"
						       name="title"
						       id="title" value="">
						<textarea class="form-control chat__message" rows="1"
						          placeholder="Текст сообщения"
						          name="content"></textarea>
						<button class="btn btn-success chat__submit" type="submit"
						        name="">Отправить
						</button>
						<input type="hidden" name="action" value="message_add">
					</form>
				</div>
			</div>

			<div class="col-md-3 col-sm-3 col-xs-3">
				<div class="avatar">
					<div class="avatar__image"></div>
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
