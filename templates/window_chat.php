<?php
/**
 * Created by PhpStorm.
 * User: Ruslan
 * Date: 12.11.2017
 * Time: 14:47
 */

/*Обработка кнопки ВЫЙТИ*/
if ( @$_POST['exit'] ) {
    setcookie( 'RestrictedArea', '', time() - 60 * 60 * 24 );
    header( "Location: " . $_SERVER['PHP_SELF'] );
    exit();
}
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
<div class="chat_container container">
    <div class="row">
        <div class="registrate col-sm-12">
            <div class="chat_profile_buttons">
                <form action="<?php echo $_SERVER["PHP_SELF"]; ?>" method="post">
                    <button type="button" class="btn btn_chat btn-success btn_chat_profile" data-toggle="modal" data-target="#modal-1" >Мой профиль</button>
	                <a href="<?php echo get_root_url().'?p=logout'; ?>" class="btn btn-danger btn_chat btn-success chat_logout" >Выход</a>
                </form>
            </div>
            <br>
            <br>
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
                            <!-- Колонка аватара -->
                            <form method="post" enctype="multipart/form-data">
                                <div class="row">
                                    <div class="text-center col-md-6">
                                        <div class="avatar col-md-12 " alt="avatar"></div>
                                        <div class="file_upload col-md-12 col-xs-12">
                                            <button type="button">Загрузить фотографию</button>
                                            <input type="file" name="file_to_upload" id="file_to_upload" accept="image/*">
                                        </div>
                                        <input type="hidden" name="action" value="upload">
                                        <button type="submit" value="upload_image" name="submit" class="btn btn-success">Отправить</button>
                                    </div>
                                </div>
                            </form>
                            <!-- Колонка профиля -->
                            <div class="row">
                                <div class="col-md-9 personal-info">

                                    <h3>Личная информация</h3>

                                    <form method="post" class="form-horizontal">
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
                                        <input type="hidden" name="action" value="edit_user_info">
                                        <div class="alert alert-info alert-dismissable">
                                            <a class="panel-close close" data-dismiss="alert">×</a>
                                            <i class="fa fa-coffee"><?php echo $message; ?></i>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-md-3 control-label"></label>
                                            <div class="col-md-8">
                                                <input type="submit" class="btn btn-primary" value="Сохранить изменения">
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <h1>Окно чата</h1>
            <div class="row row-centered post_form">
                <div class="col-md-6">
                    <form action="" method="post">
                        <input type="text" class="form-control" placeholder="Тема сообщения" name="title" id="title">
                        <textarea class="form-control" rows="5" placeholder="Текст сообщения" name="message_input" id="message_input" ></textarea>
                        <input class="btn btn-info" type="button" value="Отправить">
                    </form>
                </div>
            </div>
</div>
