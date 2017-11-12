<?php
/**
 * Created by PhpStorm.
 * User: Ruslan
 * Date: 11.11.2017
 * Time: 21:46
 */
include 'functions.php';
//Проверяем не пуста ли форма отправки и если нет то сравнив данные с БД записываем их в COOCKIE
if ( isset( $_POST['login'] ) && isset( $_POST['password'] ) && $_POST['login'] !== "" && $_POST['password'] !== "" ) {
	if ( preg_match( "/^[a-zA-Z0-9]{3,30}$/", $_POST['login'] ) ) {
		global $link;
		$user = do_query( "SELECT * FROM `users` WHERE `login` = '" . $_POST['login'] . "'" );
		$rows = mysqli_num_rows( $user );
		if ( $rows == 1 ) {
			$user_data    = $user->fetch_array();
			$pasword_hash = md5( $_POST['password'] );
			if ( $pasword_hash == $user_data['password'] ) {
				$curr_date = time();
				setcookie( 'RestrictedArea', $_POST['login'] . ":" . $pasword_hash . ":" . md5( $_SERVER['REMOTE_ADDR'] . ":" . $curr_date ), time() + 60 * 60 * 24 );
				header( "Location: " . "index.php" );
			} else {
				echo "<div class='error'><span>Введенный пароль не верный.</span></div>";
			}
		} else {
			echo "<div class='error'><span>Пользователь с таким логином не найден.</span></div>";
		}
	} else {
		echo "<div class='error''><span>Вы ввели некорректный логин.</span></div>";
	}
}else{
	echo "<div class='error''><span>Введите все данные</span></div>";
}
