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
	$link = mysqli_connect( HOST, LOGIN, PASSWORD, DATABASE );
}

global $global_actions;
if ( empty( $global_actions ) ) {
	$global_actions = [];
}
