<?php
/**
 * Created by PhpStorm.
 * User: Ruslan
 * Date: 11.11.2017
 * Time: 23:39
 */
//Проверяем  на наличие куки
if ( isset( $_COOKIE['RestrictedArea'] ) ) {
	$data_array = explode( ':', $_COOKIE['RestrictedArea'] );

	/*Проверяем на корректность введенных данных и берем строку из БД, если она существует
	делаем проверку на совпадение данных из БД и КУКИ*/

	if ( preg_match( "/^[a-zA-Z0-9]{3,30}$/", $data_array[0] ) ) {
		$user = mysqli_query( $link, "SELECT * FROM users WHERE login='" . $data_array[0] . "'" );
		$rows = mysqli_num_rows( $user );
		if ( $rows == 1 ) {
			$cookies_hash  = $data_array[1];
			$user_data     = $user->fetch_array();
			$evaluate_hash =  $user_data['password'];
			if ( $cookies_hash == $evaluate_hash ) {
				$access = true;
			}
		}
	} else {
		$access = FALSE;
	}
}
/*Если данные совпадают подключаем стр с чатом*/
if (isset($access) and $access = TRUE) {
	include "window_chat.php";
}
/*Если КУКА отсутствует то выводим окно авторизации*/
else {
	include ($_SERVER["DOCUMENT_ROOT"]."/education/login_window.php");
	exit();
}