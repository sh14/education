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
?>
<div class="container profile">
    <h1>Редактирование профиля</h1>
    <hr>

    <!-- Колонка аватара -->
    <div class="row">
        <div class="text-center col-md-3">
            <div class="avatar col-md-12 " alt="avatar"></div>
            <div class="file_upload col-md-12 col-xs-12">
                <button type="button">Загрузить фотографию</button>
                <input type="file" accept="image/*">
            </div>
        </div>
    </div>

    <!-- Колонка профиля -->
    <div class="row">
        <div class="col-md-9 personal-info">
            <div class="alert alert-info alert-dismissable">
                <a class="panel-close close" data-dismiss="alert">×</a>
                <i class="fa fa-coffee"><?php echo $message ?></i>
            </div>
            <h3>Личная информация</h3>

            <form method="post" class="form-horizontal" role="form" action="?event=edit_user_info">
                <div class="form-group">
                    <label class="col-lg-3 control-label">Имя:</label>
                    <div class="col-lg-8">
                        <input class="form-control" type="text" name="first_name">
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-lg-3 control-label">Фамилия:</label>
                    <div class="col-lg-8">
                        <input class="form-control" type="text" name="last_name">
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-lg-3 control-label">Email:</label>
                    <div class="col-lg-8">
                        <input class="form-control" type="email" name="email">
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-3 control-label">Имя пользователя:</label>
                    <div class="col-md-8">
                        <input class="form-control" type="text" name="login">
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
                        <input class="form-control confirm_password" type="password">
                    </div>
                    <div class="message col-md-8 col-md-offset-3"></div>
                </div>
                <input type="hidden" name="access" value="permitted">
                <input type="hidden" name="ID" value="1">
                <div class="form-group">
                    <label class="col-md-3 control-label"></label>
                    <div class="col-md-8">
                        <input type="submit" class="btn btn-primary" value="Сохранить изменения">
                        <span></span>
                        <input type="reset" class="btn btn-default" value="Очистить данные формы">
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
