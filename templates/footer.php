<div id="installer"></div>
<script type="text/javascript">
    $(document).ready(function() {
        /* Информирование пользователя о том, что пароли совпадают или не совпадают */
        $('.password, .confirm_password').on('keyup', function() {
            if ($('.password').val() === $('.confirm_password').val()) {
                if ($('.password, .confirm_password').val() != '') {
                    $('.message').html('Пароли совпадают').css('color', 'green');
                } else {
                    $('.message').html('').removeAttr('style');
                }
            } else {
                $('.message').html('Пароли не совпадают').css('color', 'red');
            }
        });
        /* Отправка формы добавления изображения при загрузке изображения */
        $('.file-avatar').on('change',function() {
            $('.form-avatar').submit();
        });
    });
</script>

<div class="massive">
	<div class="move_window">
		<div class="round">
			<img src="images/settings.png" alt="img" id="mex">
		</div>
		<div class="head_window">
			<p>Редактрования профиля</p>
		</div>
		<div class="photoUser_window">
			<p>Фото профиля</p>
		</div>
		<div class="head_btn">
			<button id="head_window-Btn">Загрузить фотографию</button>
		</div>
		<div class="form">
			<p>Личная информация:</p>
			<div class="inp">
				<label for="firstName">Имя:</label>
				<input type="text" name="firstName" id="name">
			</div>
			<div class="inp">
				<label for="lasName">Фамилия:</label>
				<input type="text" name="lastName" id="name">
			</div>
			<div class="inp">
				<label for="mail">Почта:</label>
				<input type="text" name="mail" id="mail">
			</div>
			<div class="inp">
				<label for="nickName">Никнэйм:</label>
				<input type="text" name="nickName" id="nickName">
			</div>
			<div class="inp">
				<label for="password">Пароль:</label>
				<input type="text" name="password" id="password">
			</div>
			<div class="inp">
				<label for="rePassword">Подтвердите пароль:</label>
				<input type="text" name="rePassword" id="rePassword">
			</div>
		</div>
		<div class="form_btn">
				<button id="form_window-Btn">Сохранить изменения</button>
		</div>
	</div>
</div>
<?php
do_action('footer');
?>
</body>
</html>
