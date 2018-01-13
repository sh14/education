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
		<div class="row">
			<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
				<h2 class="text-center">Регистрация</h2>
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
					<?php do_action( 'reg_error' ); ?>
				</form>
			</div>
			<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
				<h2 class="text-center">Вход</h2>
				<form class="login" method="post">
					<div class="form-group">
						<label for="email_login">Введите Ваш email</label>
						<input class="form-control login_input" type="email" name="email"
						       id="email_login" placeholder="Email" required="required"/>
					</div>
					<div class="form-group">
						<label for="password_login">Введите Ваш пароль</label>
						<input class="form-control password_login" type="password" placeholder="Пароль"
						       id="password_login" name="password" required="required"/>
					</div>
					<input type="hidden" name="action" value="authorization"/>
					<button class="login btn btn-success" type="submit" name="login_send">Вход</button>
					<?php do_action( 'login_error' ); ?>
				</form>
			</div>
		</div>
	</div>
</div>
