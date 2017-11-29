<?php
/**
 * Created by PhpStorm.
 * User: Anton
 * Date: 10.11.2017
 * Time: 11:42
 */
$message = 'Внесите данные, которые хотите изменить.';
if ( ! empty( $_GET['event'] ) ) {
	if ( $_GET['event'] == 'error' ) {
		$message = 'Необходимо ввести все данные.';
	}

	if ( $_GET['event'] == 'success' ) {
		$message = 'Запись добавлена.';
	}
}

$user = get_user_info();
?>
<div class="profile">

	<!-- Колонка аватара -->
	<form class="form-avatar" method="post" enctype="multipart/form-data">
		<div class="row">
			<div class="text-center col-md-3">
				<div class="avatar col-md-12 " alt="avatar"></div>
				<label class="file_upload col-md-12 col-xs-12">
					<span class="button">Загрузить фотографию</span>
					<input class="file-avatar" type="file" name="file_to_upload" id="file_to_upload" accept="image/*">
				</label>
				<input type="hidden" name="action" value="upload">
			</div>
		</div>
	</form>

	<!-- Колонка профиля -->
	<h3>Личная информация</h3>
	<form method="post" class="form-horizontal">
		<div class="form-group">
			<label class="col-lg-3 control-label">Имя:</label>
			<div class="col-lg-8">
				<input class="form-control" type="text" name="first_name" value="<?php echo $user['first_name']; ?>">
			</div>
		</div>
		<div class="form-group">
			<label class="col-lg-3 control-label">Фамилия:</label>
			<div class="col-lg-8">
				<input class="form-control" type="text" name="last_name" value="<?php echo $user['last_name']; ?>">
			</div>
		</div>
		<div class="form-group">
			<label class="col-lg-3 control-label">Почта:</label>
			<div class="col-lg-8">
				<input class="form-control" type="email" name="email" value="<?php echo $user['email']; ?>">
			</div>
		</div>
		<div class="form-group">
			<label class="col-md-3 control-label">Никнейм:</label>
			<div class="col-md-8">
				<input class="form-control" type="text" name="nickname" value="<?php echo $user['nickname']; ?>">
			</div>
		</div>
		<div class="form-group">
			<label class="col-md-3 control-label">Пароль:</label>
			<div class="col-md-8">
				<input class="form-control password" type="password" name="password">
			</div>
		</div>
		<div class="form-group">
			<label class="col-md-3 control-label">Подтвердите пароль:</label>
			<div class="col-md-8">
				<input class="form-control confirm_password" type="password" name="confirm_password">
			</div>
			<div class="message col-md-8 col-md-offset-3"></div>
		</div>
		<input type="hidden" name="action" value="edit_user_info">
		<div class="form-group">
			<label class="col-md-3 control-label"></label>
			<div class="col-md-8">
				<input type="submit" class="btn btn-primary" value="Сохранить изменения">
			</div>
		</div>
	</form>
</div>
