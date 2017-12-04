<?php
/**
 * Created by PhpStorm.
 * User: Антон
 * Date: 01.12.2017
 * Time: 23:04
 */
?>
<form method="post">
	<div class="form-group" class="form-horizontal">
		<label class="col-lg-3 control-label">Введите имя хоста:*</label>
		<div class="col-lg-8">
			<input class="form-control" type="text" name="host" value="">
		</div>
	</div>
	<div class="form-group">
		<label class="col-lg-3 control-label">Введите логин:*</label>
		<div class="col-lg-8">
			<input class="form-control" type="text" name="login" value="">
		</div>
	</div>
	<div class="form-group">
		<label class="col-lg-3 control-label">Введите пароль:</label>
		<div class="col-lg-8">
			<input class="form-control" type="text" name="password" value="">
		</div>
	</div>
	<div class="form-group">
		<label class="col-lg-3 control-label">Введите название базы данных:*</label>
		<div class="col-lg-8">
			<input class="form-control" type="text" name="database" value="">
		</div>
	</div>
	<input type="hidden" name="action" value="configuration">
	<div class="col-md-8">
		<input type="submit" class="btn btn-primary" value="Сохранить изменения">
	</div>
</form>