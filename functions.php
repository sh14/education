<?php

include 'config.php';

// объявление глобальной переменной
global $link;

// если $link - пуста
if ( empty( $link ) ) {
	$link = mysqli_connect( HOST, LOGIN, PASSWORD, DATABASE );
}

function init() {
	get_header();
	get_footer();
}


/**
 * Функция, осуществляющая запрос в БД и возвращающая результат запроса
 *
 * @param $query
 *
 * @return bool|mysqli_result
 */
function do_query( $query ) {
	global $link;
	mysqli_set_charset( $link, 'utf8' );
	$result = mysqli_query( $link, $query );

	return $result;
}

/**
 * Функция подключения шапки сайта
 */
function get_header() {
	include 'header.php';
}

/**
 * Функция подключения подвала
 */
function get_footer() {
	include 'footer.php';
}
