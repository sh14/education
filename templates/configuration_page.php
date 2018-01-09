<?php
/**
 * Created by PhpStorm.
 * User: Антон
 * Date: 01.12.2017
 * Time: 23:04
 */
?>
<div class="container">
	<h3>Заполните все поля помеченные звёздочкой</h3>
	<form method="post">
		<div class="form-group">
			<label for="host" class="control-label">Введите имя хоста:*</label>

			<input class="form-control" type="text" id="host" name="host" value="localhost">

		</div>
		<div class="form-group">
			<label for="login" class="control-label">Введите логин:*</label>

			<input class="form-control" type="text" id="login" name="login" value="">
		</div>
		<div class="form-group">
			<label for="password" class="control-label">Введите пароль:</label>

			<input class="form-control" type="text" id="password" name="password" value="">
		</div>
		<div class="form-group">
			<label for="database" class="control-label">Введите название базы данных:*</label>

			<input class="form-control" type="text" id="database" name="database" value="">
		</div>
		<input type="hidden" name="action" value="configuration">
		<button type="submit" class="btn btn-primary">Сохранить изменения</button>
		<label for="drop_database" class="form-check-label">Удалить таблицы</label>
		<input id="drop_database" class="form-check-input" type="checkbox" name="drop_database">
	</form>
</div>
