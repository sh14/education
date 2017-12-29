<?php
/**
 * Created by PhpStorm.
 * User: Anton
 * Date: 10.11.2017
 * Time: 11:42
 */
if ( is_user_logged_in() ) {
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

		<div class="profile__avatar">
		<div class="upload-image js-upload-image" data-maxwidth="300" data-maxheight="300" data-width="200" data-height="200">
			<div class="upload-image__preview js-preview"<?php echo display_avatar(); ?>></div>
			<div class="upload-image__wrapper js-fileapi-wrapper">
				<label class="upload-image__browse js-browse">
					<div class="upload-image__sign js-edit-button"></div>
					<input class="upload-image__file" name="filedata" type="file">
				</label>
				<div class="upload-image__caption js-edit-button">Выбирите файл</div>
				<div class="upload-image__upload js-upload" style="display: none;">
					<div class="upload-image__progress progress progress-success">
						<div class="js-progress bar upload-image__progress_bar "></div>
					</div>
					<span class="upload-image__progress_caption btn-txt">Загрузка...</span>
				</div>
			</div>
		</div>
	</div>


		<!-- Колонка профиля -->
		<form method="post" class="profile__form">
			<div class="form-group">
				<label for="first_name" class="control-label">Имя:</label>
				<input class="form-control" type="text" id="first_name" name="first_name"
				       value="<?php echo $user['first_name']; ?>">
			</div>
			<div class="form-group">
				<label for="last_name" class="control-label">Фамилия:</label>

				<input class="form-control" type="text" id="last_name" name="last_name"
				       value="<?php echo $user['last_name']; ?>">

			</div>
			<div class="form-group">
				<label for="email" class="control-label">Почта:</label>

				<input class="form-control" type="email" id="email" name="email" value="<?php echo $user['email']; ?>">

			</div>
			<div class="form-group">
				<label for="password" class=" control-label">Пароль:</label>
				<input class="form-control password" type="password" id="password" name="password">
			</div>
			<div class="form-group">
				<label for="confirm_password" class=" control-label">Подтвердите пароль:</label>
				<input class="form-control confirm_password" type="password" id="confirm_password"
				       name="confirm_password">
			</div>
			<div class="profile__message text-danger bg-danger form-group"></div>
			<input type="hidden" id="action" name="action" value="edit_user_info">
			<button type="submit" class="btn btn-primary btn-block">Сохранить изменения</button>
		</form>
	</div>
	<?php
}

// eof
