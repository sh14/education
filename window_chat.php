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
<h1>Окно чата</h1>
<form action="<?php echo $_SERVER["PHP_SELF"];?>" method="post">
	<input type="submit" name="exit" value="Выйти"/>
</form>
