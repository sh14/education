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
			<div class="text-center col-md-5">

				<div id="avatar" class="avatar"<?php echo display_avatar(); ?>>
					<div id="avatar__preview" class="js-preview avatar__preview"></div>
				</div>

				<div class="js-fileapi-wrapper">
					<label class="js-browse file_upload col-md-12 col-xs-12">
						<span class="button">Загрузить фотографию</span>
						<input class="file-avatar" type="file" name="file_to_upload" id="file_to_upload" accept="image/*">
					</label>
					<div class="js-upload" style="display: none;">
						<div class="progress progress-success"><div class="js-progress bar"></div></div>
						<span class="btn-txt">Загрузка фотографии</span>
					</div>
				</div>

				<!-- Модальное окно -->
				<div id="img-preview" class="modal fade img-modal" role="dialog">
					<div class="modal-dialog">

						<div class="modal-content modal-preview" style="left: 250px;">
							<div class="modal-header">
								<button type="button" class="close" data-dismiss="modal">&times;</button>
								<h4 class="modal-title">Ваша фотография</h4>
							</div>
							<div id="loading" class="loader" style="display: none; position: absolute; left: 45%; top: 30%"></div>
							<div id="image_container" class="modal-body">
								<p>Some text in the modal.</p>
							</div>
							<div class="modal-footer">
								<button type="submit" class="save_avatar btn btn-default">Сохранить</button>
							</div>
						</div>

					</div>
				</div>

			</div>
		</div>

		<input type="hidden" name="image" value="upload">
		<input type="hidden" name="action" value="upload">
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
