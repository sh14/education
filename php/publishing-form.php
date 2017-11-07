<?php
/**
 * Created by PhpStorm.
 * User: sh14ru
 * Date: 05.10.17
 * Time: 23:10
 */

?>
<div class="container alert"><?php
	set_cookie();
    redirect();
    ?></div>
<?php if (authorization() == true) { ?>
<div class="container">
    <form id="post" action="?event=save_post" method="post" class="form">
		<div class="form-group">
			<label class="hidden" for="post_title">Заголовок</label>
			<input placeholder="Заголовок" type="text" name="post_title" class="form-control"></div>
		<div class="form-group">
			<label class="hidden" for="post_name">Якорь</label>
			<input placeholder="Имя публикации латинскими буквами"
			       type="text" name="post_name"
			       class="form-control">
		</div>
		<div class="form-group">
			<label class="hidden" for="post_date">Дата публикации</label>
			<input placeholder="Дата публикации"
			       type="datetime-local" name="post_date"
			       class="form-control"
			       value="<?php
			       echo date( 'Y-m-d' ) . 'T' . date( 'H:i' );
			       ?>">
		</div>
		<div class="form-group">
			<label class="hidden" for="post_content">Текст публикации</label>
			<textarea
					placeholder="Введите текст публикации" name="post_content" class="form-control"></textarea></div>
        <input type="hidden" name="ID" value="1">
        <div class="notification"></div>
        <div><?php
            echo $_SERVER['REQUEST_URI'];
	        list( $url ) = explode( '?', $_SERVER['REQUEST_URI'] );
	        echo "<br>$url";
            ?></div>
        <div class="publishing">
            <button type="submit" class="btn btn-primary pull-right">Опубликовать</button>
            <button type="button" class="btn btn-primary pull-right margin-right hidden exit-edit">&#10006</button>
        </div>
		<?php
		if ( ! empty( $_GET['event'] ) ) {
			if ( $_GET['event'] == 'error' ) {
				$message = 'Необходимо ввести все данные.';
			}

			if ( $_GET['event'] == 'success' ) {
				$message = 'Запись добавлена.';
			}
			if (! empty($message)) {
				$message = '<div class="fade-out pull-left bg bg-block bg-' . $_GET['event'] . '">' . $message . '</div>';
				echo $message;
			}
		}
		?>
	</form>
</div>
<?php } ?>
<?php if (authorization() == false) { ?>
<div class="container">
    <form action="?event=save_registration" method="post" class="form">
        <div class="form-group">
            <label class="hidden" for="user_name">Логин</label>
            <input placeholder="Логин латинскими буквами"
                   type="text" name="user_name"
                   class="form-control">
        </div>
        <div class="form-group">
            <label class="hidden" for="user_password">Пароль</label>
            <input placeholder="Пароль латинскими буквами"
                   type="text" name="user_password"
                   class="form-control">
        </div>
        <div class="form-group">
            <label class="hidden" for="first_name">Имя пользователя</label>
            <input placeholder="Имя пользователя"
                   type="text" name="first_name"
                   class="form-control">
        </div>
        <div class="form-group">
            <label class="hidden" for="last_name">Фамилия пользователя</label>
            <input placeholder="Фамилия пользователя"
                   type="text" name="last_name"
                   class="form-control">
        </div>
        <input type="hidden" name="ID" value="1">
        <div><?php
			echo $_SERVER['REQUEST_URI'];
			list( $url ) = explode( '?', $_SERVER['REQUEST_URI'] );
			echo "<br>$url";
			?></div>
        <button type="submit" class="btn btn-primary pull-right">Зарегистрироваться</button>
		<?php
		if ( ! empty( $_GET['event'] ) ) {
			if ( $_GET['event'] == 'error' ) {
				$message = 'Необходимо ввести все данные.';
			}

			if ( $_GET['event'] == 'success' ) {
				$message = 'Пользователь зарегистрирован.';
			}
			if (! empty($message)) {
				$message = '<div class="fade-out pull-left bg bg-block bg-' . $_GET['event'] . '">' . $message . '</div>';
				echo $message;
            }
		}
		?>
    </form>
</div>
<?php } ?>
<?php if (authorization() == false) { ?>
<div class="container">
    <form action="?event=save_sing_in" method="post" class="form">
        <div class="form-group">
            <label class="hidden" for="name">Логин</label>
            <input placeholder="Логин латинскими буквами"
                   type="text" name="name"
                   class="form-control">
        </div>
        <div class="form-group">
            <label class="hidden" for="password">Пароль</label>
            <input placeholder="Пароль латинскими буквами"
                   type="text" name="password"
                   class="form-control">
        </div>
        <button type="submit" class="btn btn-primary pull-right">Войти</button>
        <input type="hidden" name="ID" value="1">
		<?php
		if ( ! empty( $_GET['event'] ) ) {
			if ( $_GET['event'] == 'error' ) {
				$message = 'Необходимо ввести все данные.';
			}

			if ( $_GET['event'] == 'success' ) {
				$message = 'Пользователь авторизован.';
			}
			if (! empty($message)) {
				$message = '<div class="fade-out pull-left bg bg-block bg-' . $_GET['event'] . '">' . $message . '</div>';
				echo $message;
			}
		}
		?>
    </form>
</div>
<?php } ?>