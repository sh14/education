<?php

include 'config.php';

// объявление глобальной переменной
global $link;

// если $link - пуста
if ( empty( $link ) ) {
	$link = mysqli_connect( HOST, LOGIN, PASSWORD, DATABASE );
}


function init() {

	if ( ! empty( $_GET['p'] ) ) {
		$page = $_GET['p'];
	}
	if ( empty( $page ) ) {
		$page = 'index';
	}

	get_template_part( $page );
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
	get_template_part( 'header' );
}

/**
 * Функция подключения подвала
 */
function get_footer() {
	get_template_part( 'footer' );
}

/**
 * Функция подключения шаблона
 *
 * @param $name
 *
 * @return bool
 */
function get_template_part( $name ) {

	$template_path   = [];
	$template_path[] = get_root_path() . '/templates/' . $name . '.php';
	foreach ( $template_path as $path ) {
		if ( file_exists( $path ) ) {
			include $path;

			return true;
		}
	}

	return false;
}

/**
 * Функция получения директории сайта
 *
 * @return string
 */
function get_root_path() {
	$path = dirname( __FILE__ );

	return $path;
}

/**
 * Функция получения url'а сайта
 *
 * @return string
 */
function get_root_url() {
	$protocol = stripos( $_SERVER['SERVER_PROTOCOL'], 'https' ) === true ? 'https://' : 'http://';
	$port     = ! empty( $_SERVER['SERVER_PORT'] ) ? ':' . $_SERVER['SERVER_PORT'] : '';

	return $protocol . $_SERVER["SERVER_NAME"] . $port . dirname( $_SERVER["SCRIPT_NAME"] );
}

/**
 * Функция получения директории папки со стилями шаблона
 *
 * @return string
 */
function get_stylesheet_directory() {
	return get_root_url() . '/templates';
}
