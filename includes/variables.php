<?php
/**
 * Created by PhpStorm.
 * User: sh14ru
 * Date: 14.11.17
 * Time: 19:57
 */

// объявление глобальной переменной
global $link;

// если $link - пуста
if ( empty( $link ) ) {
	$link = mysqli_connect( HOST, LOGIN, PASSWORD, DATABASE )
	or die('Ошибка при подключении к серверу MySQL: ' . mysqli_error($link));
}

global $global_actions;
if ( empty( $global_actions ) ) {
	$global_actions = [];
}

global $scripts;
if ( empty( $scripts ) ) {
	$scripts = [];
}

global $current_user;
if ( empty( $current_user ) ) {
    $current_user = [];
}
