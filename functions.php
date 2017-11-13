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

/**
 *  Функция редактирования профиля пользователя
 */

function profile_edit() {
	list( $url ) = explode( '?', $_SERVER['REQUEST_URI'] );
	$event = '';
	if ( ! empty( $_GET['event'] ) && $_GET['event'] == 'edit_user_info' ) {
		$vars_string       = 'login,email,first_name,last_name';
		$vars              = array_map( 'trim', explode( ',', $vars_string ) );
		$values            = [];
		$empty_input_count = 0;
		$allow_query       = 1;
		foreach ( $vars as $var_key => $var_value ) {
			if ( ! empty( $_POST[ $var_value ] ) ) {
				$values[] = "'$_POST[$var_value]'";
			} else {
				unset( $vars[ $var_key ] );
				++ $empty_input_count;
				if ( $empty_input_count == 4 ) {
					$allow_query = 0;
					break;
				}
			}
		}

		$vars = array_combine( array_keys( $values ), array_values( $vars ) );
		for ( $i = 0; $i < count( $values ); $i ++ ) {
			$values[ $i ] = $vars[ $i ] . '=' . $values[ $i ];
		}
		$ID = $_POST['ID'];

		if ( $allow_query == 1 ) {
			$event = 'success';

			$values = implode( ',', $values );
			$ID     = "WHERE ID = $ID";
			$query  = "UPDATE posts SET $values $ID";

			do_query( $query );
		} else {
			$event = 'error';
		}
	}

	/*if ( ! empty( $_GET['event'] ) && $_GET['event'] == 'edit_user_password' && ! empty( $_POST ) ) {

	}*/

	if ( ! empty( $event ) ) {
		$event = '?event=' . $event;
		header( 'location: ' . $url . $event );
	}
}


/*
 * это недоработанная функция сохраниня пользователя
 * function save_profile() {
    list( $url ) = explode( '?', $_SERVER['REQUEST_URI'] ); вычденияем из url строки елементы
    $event = ''; здесь создали переменную для опреления положения в url строке

    if(!empty($_GET['event']) && $_GET['event'] == 'save' && !empty($_POST)) {    создаем условие в котором прописываем занесение в базу данных
                                                                                  информации из инпутов при удачном случае и изменении значения url строки
        $vars_string = array_map('trim', explode(',', 'first_name, last_name, login, Email, Password'));
        $values = [];
        $allow_query       = 1;
        foreach ($vars_string as $var) {
            if(empty($_POST[$var])){
                $allow_query = 0;
                break;
            }
            $values[] = "'$_POST[$var]'";
        }
        if ($allow_query == 1) {
            $event = 'succes';
            $vars_string = implode(',' , $vars_string);
            $values = implode(',' , $values);
            $query = "INSERT INTO users ($vars_string) VALUES ($values)";

            do_query($query);
        } else {
            $event = "error";
        }
    }

    if ( ! empty( $event ) ) {
        $event = '?event=' . $event;
        header( 'location: ' . $url . $event );
    }
}*/