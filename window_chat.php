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
?>
<div class="container">
    <h1>Окно чата</h1>
    <div class="row row-centered post_form">
        <div class="col-md-6">
            <form action="" method="post">
                <input type="text" class="form-control" placeholder="Тема сообщения">
                <textarea class="form-control" rows="5" placeholder="Текст сообщения"></textarea>
                <input class="btn btn-info" type="button" value="Отправить">
            </form>
        </div>
    </div>
    <form action="<?php echo $_SERVER["PHP_SELF"]; ?>" method="post">
        <input type="submit" class="btn btn-danger" name="exit" value="Выйти"/>
    </form>
</div>