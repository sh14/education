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
<style>
    .chat_window_wall, .chat_window_avatar {
        border: 1px solid black;
        height: 100%;
        background: snow;
    }
    .chat_window_wall {
        overflow: auto;
        border-right: none;
    }
    .chat_window_avatar_img {
        height: 70%;
        background-image: url("https://skin-challenges.com/wp-content/uploads/2017/08/profile_unknown.png");
	    background-repeat: no-repeat;
	    background-size: contain;
	    background-position: center;
    }
    .chat_window_avatar_signature {
        height: 20%;
        text-align: center;
        font-size: 30px;
	    overflow: hidden;
    }
    .chat_window {
        height: 300px;
    }
	.profile_button {
		text-align: center;
	}
</style>
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
                            <form class="form-avatar" method="post" enctype="multipart/form-data">
                                <div class="row">
                                    <div class="text-center col-md-5">
                                        <div class="avatar col-md-12"></div>
                                        <label class="file_upload col-md-12 col-xs-12">
                                            <span class="button">Загрузить фотографию</span>
                                            <input class="file-avatar" type="file" name="file_to_upload" id="file_to_upload" accept="image/*">
                                        </label>
                                        <input type="hidden" name="action" value="upload">
                                    </div>
                                </div>
                            </form>
                            <!-- Колонка профиля -->
                            <div class="row">
                                <div class="col-md-12 personal-info">
                                    <h3>Личная информация</h3>
                                    <form method="post" class="form-horizontal">
                                        <div class="form-group">
                                            <label for="first_name" class="col-lg-3 control-label">Имя:</label>
                                            <div class="col-lg-8">
                                                <input class="form-control" type="text" name="first_name" id="first_name">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="last_name" class="col-lg-3 control-label">Фамилия:</label>
                                            <div class="col-lg-8">
                                                <input class="form-control" type="text" name="last_name" id="last_name">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="email" class="col-lg-3 control-label">Почта:</label>
                                            <div class="col-lg-8">
                                                <input class="form-control" type="email" name="email" id="email">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="nickname" class="col-md-3 control-label">Никнейм:</label>
                                            <div class="col-md-8">
                                                <input class="form-control" type="text" name="nickname" id="nickname">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="password" class="col-md-3 control-label">Пароль:</label>
                                            <div class="col-md-8">
                                                <input class="form-control password" type="password" name="password" id="password">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="confirm_password" class="col-md-3 control-label">Подтвердите пароль:</label>
                                            <div class="col-md-8">
                                                <input class="form-control confirm_password" type="password" name="confirm_password" id="confirm_password">
                                            </div>
                                            <div class="message col-md-8 col-md-offset-3"></div>
                                        </div>
                                        <input type="hidden" name="action" value="edit_user_info">
                                        <div class="form-group profile_button">
                                            <div class="col-md-12">
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
            <div class="row">
                <div class="col-md-12 col-sm-12 col-xs-12 chat_window">
                    <div class="col-md-9 col-sm-9 col-xs-9 chat_window_wall">
                        </div>
                        <div class="col-md-3 col-sm-3 col-xs-3 chat_window_avatar">
                            <div class="chat_window_avatar_img">
                            </div>
                            <div class="chat_window_avatar_signature">
                                Антон и Ко
                            </div>
                        </div>
                </div>
            </div>
            <div class="row row-centered post_form">
                <div class="col-md-6 col-sm-12">
                    <form action="" method="post">
                        <input type="text" class="form-control" placeholder="Тема сообщения" name="title" id="title">
                        <textarea class="form-control" rows="5" placeholder="Текст сообщения" name="message_input" id="message_input" ></textarea>
                        <input class="btn btn-info" type="button" value="Отправить">
                    </form>
                </div>
            </div>
</div>
