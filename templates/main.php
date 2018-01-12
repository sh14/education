<?php
/**
 * Created by PhpStorm.
 * User: Student
 * Date: 07.11.2017
 * Time: 21:52
 */
?>
<div class="container">
	<div class="forms">
		<h2 class="text-center">Регистрация / Вход</h2>
		<div class="row">
			<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">

				<form method="post">
					<div class="form-group">
						<label for="email">Укажите Ваш действующий email</label>
						<input class="form-control" type="email" name="email" id="email"
						       placeholder="Email" required="required">
					</div>
					<div class="form-group">
						<label for="password">Придумайте пароль</label>
						<input class="form-control" type="password" placeholder="Password" id="password"
						       name="password" required="required">
					</div>

					<button class="btn btn-primary" type="submit">Зарегистрироваться</button>
					<input type="hidden" name="action" value="registration">
					<?php
					if ( get_page() == 'error_register' ) {
						$msg = json_decode( $_GET['msg'] );
						if ( ! empty( $msg ) ) {
							foreach ( $msg as $message ) {
								echo '<p class="bg-danger text-danger error-message">' . $message . '</p>';
							}
						}
					}
					?>
				</form>
			</div>
			<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
				<form class="login" method="post">
					<div class="form-group">
						<label for="email_login">Введите Ваш email</label>
						<input class="form-control login_input" type="email" name="email_login"
						       id="email_login" placeholder="Email" required="required"/>
					</div>
					<div class="form-group">
						<label for="password_login">Введите Ваш пароль</label>
						<input class="form-control password_login" type="password" placeholder="Пароль"
						       id="password_login" name="password_login" required="required"/>
					</div>

					<button class="login btn btn-success" type="submit" name="login_send">Вход</button>
					<input type="hidden" name="action" value="authorization">
					<?php
					if ( get_page() == 'error_login' ) {
						echo '<p class="bg-danger text-danger error-message fade">Данные не верны</p>';
					}
					?>
				</form>

			</div>
		</div>
	</div>
</div>
