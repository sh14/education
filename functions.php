<?php

include 'config.php';
include 'includes/variables.php';
include 'includes/hooks.php';
include 'includes/installer.php';
include 'includes/user.php';
include 'includes/formatting.php';


function init() {
	do_action( 'init' );
	get_template_part( 'header' );
	get_template_part( 'index' );
	get_template_part( 'footer' );
}

/**
 * Функция определения запрошенной страницы
 *
 * @return string
 */
function get_page() {
	$page = '';
	if ( ! empty( $_GET['p'] ) ) {
		$page = $_GET['p'];
	}

	return $page;
}


function pr( $data, $debug_backtrace = false ) {

	if ( $debug_backtrace == true ) {
		echo '<pre><code>';
		var_dump( debug_backtrace() );
		echo '</pre></code><br/>';
	}
	switch ( $data ) {
		case is_bool( $data ):
			if ( $data === true ) {
				$data = 'true';
			} else {
				$data = 'false';
			}
			break;
		case ( is_array( $data ) || is_object( $data ) ):
			ob_start();
			print_r( (array) $data );
			$data = ob_get_contents();
			ob_clean();
			break;
	}

	echo '<pre><code>' . htmlspecialchars( $data ) . '</pre></code><br/>';
}

/**
 * Функция, осуществляющая запрос в БД и возвращающая результат запроса
 *
 * @param $query
 *
 * @return bool|mysqli_result|string
 */
function do_query( $query ) {
	global $link;
	if ( $link ) {
		mysqli_set_charset( $link, 'utf8' );

		$result = mysqli_query( $link, $query );
		if ( ! $result ) {
			return mysqli_error( $link );
		}

		return $result;
	}

	return false;
}

/**
 * Функция подключения шапки сайта
 */
function get_header() {
	get_template_part( 'header' );
}

/**
 * Функция, выполняемая внутри тега head HTML документа
 */
function head() {
	do_action( 'head' );
}

/**
 * Функция подключения подвала
 */
function get_footer() {
	do_action( 'footer' );
	get_template_part( 'footer' );
}

/**
 * Функция подключения шаблона или его возврата в виде строки
 *
 * @param       $name
 * @param array $atts
 *
 * @return bool|string
 */
function get_template_part( $name, $atts = [] ) {

	$template_path   = [];
	$template_path[] = get_root_path() . '/templates/' . $name . '.php';
	foreach ( $template_path as $path ) {
		if ( file_exists( $path ) ) {
			if ( ! empty( $atts ) ) {

				// если массив не ассоциативный
				if ( array_keys( $atts ) === range( 0, sizeof( $atts ) - 1 ) ) {
					$new_atts = [];
					foreach ( $atts as $key ) {
						$new_atts[ $key ] = '<%=' . $key . '%>';
					}
					$atts = $new_atts;
					unset( $new_atts );
				}
				ob_start();
				include $path;
				$content = ob_get_contents();
				ob_end_clean();
				$content = implode( '', array_map( 'trim', explode( "\n", $content ) ) );

				return $content;
			} else {
				include $path;

				return true;
			}

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

	$port = ! empty( $_SERVER['SERVER_PORT'] ) && $_SERVER['SERVER_PORT'] != 80 ? ':' . $_SERVER['SERVER_PORT'] : '';

	$url = $protocol . $_SERVER["SERVER_NAME"] . $port . dirname( $_SERVER["SCRIPT_NAME"] );

	// удаление последнего слэша в строке
	$url = preg_replace( '{/$}', '', $url );

	return $url;
}

/**
 * Функция получения директории папки со стилями шаблона
 *
 * @return string
 */
function get_stylesheet_directory() {
	$url = get_root_url() . '/templates';

	return $url;
}

/**
 * Функция шифрования пароля
 *
 * @param $password
 *
 * @return string
 */
function encript_password( $password ) {
	$password = md5( md5( trim( $password ) ) );

	return $password;
}

/**
 * Регистрация скрипта для последующего вывода этого скрипта
 *
 * @param       $handle
 * @param       $src
 * @param array $deps
 * @param bool  $ver
 * @param bool  $in_footer
 *
 * @return array
 */
function register_script( $handle, $src, $deps = array(), $ver = false, $in_footer = false ) {
	global $scripts;

	if ( ! empty( $ver ) ) {
		$ver = '?ver=' . $ver;
	} else {
		$ver = '';
	}
	$scripts[ $handle ] = [
		'in_footer' => $in_footer
	];

	// если $src является массивом, значит необходимо дописать $src перед регистрируемым скриптом
	if ( is_array( $src ) ) {
		$scripts[ $handle ]['src'] = implode( '', $src );
	} else {
		$scripts[ $handle ]['src'] = $src . $ver;
	}

	$reordered                 = [];
	$i                         = 0;
	$current_script_position   = 0;
	$dependent_script_position = 0;
	foreach ( $scripts as $key => $data ) {
		if ( $handle == $key ) {
			$current_script_position = $i;
		}
		if ( in_array( $key, $deps ) ) {
			$dependent_script_position = $i;
		}
		if ( $dependent_script_position > $current_script_position ) {
			unset( $reordered[ $key ] );
		}
		$reordered[ $key ] = $scripts[ $key ];
		$i ++;
	}

	$scripts = $reordered;

	return $scripts;
}

/**
 * Вывод скрипта в нужном месте
 *
 * @param $handle
 */
function enqueue_script( $handle, $object_name = '' ) {
	global $scripts;

	if ( ! empty( $scripts[ $handle ] ) ) {


		if ( $scripts[ $handle ]['in_footer'] === true ) {
			$action = 'footer';
		} else {
			$action = 'head';
		}

		// если не указан $object_name, необходимо подключить скрипт
		if ( empty( $object_name ) ) {
			$out = '<script src="' . $scripts[ $handle ]['src'] . '"></script>';
		} else {
			$out = '<script>' . $scripts[ $handle ]['src'] . '</script>';
		}

		add_action( $action, function () use ( $out ) {
			echo $out . "\n";
		} );

	}
}

/**
 * Вывод переменной
 *
 * @param $handle
 * @param $object_name
 * @param $l10n
 */
function wp_localize_script( $handle, $object_name, $l10n ) {
	$out   = [];
	$out[] = 'var ' . $object_name . '= {';

	foreach ( $l10n as $key => $value ) {
		if ( is_string( $value ) ) {
			$value = "'{$value}'";
		}
		$out[] = "'$key':{$value},";
	}
	$out[] = '};';

	$out = implode( "\n", $out );
	register_script( $handle . $object_name, [ $out ] );
	enqueue_script( $handle . $object_name, $object_name );
}


/**
 * Вывод сообщений на дисплей
 *
 */
function display_message() {
	if ( is_user_logged_in() ) {
		$sql    = "SELECT * FROM message m LEFT JOIN users u ON u.ID = m.id_user ORDER BY datetime ASC LIMIT 30";
		$result = do_query( $sql );
		$count  = mysqli_num_rows( $result );
		if ( $count > 0 ) {

			$template        = get_template_part( 'message', [
				'image',
				'name',
				'title',
				'content',
				'datetime',
				'class_name',
				'id_user',
				'id_message',
			] );
			$current_user_id = get_current_user_id();
			while ( $row = mysqli_fetch_array( $result, MYSQLI_ASSOC ) ) {

				$image = '';
				if ( ! empty( $row['image'] ) ) {
					$image = ' style="background-image:url(' . get_root_url() . '/images/users/' . $row['image'] . ');"';
				}
				$datetime = '';
				if ( ! empty( $row['datetime'] ) ) {

					$datetime = date( 'H:i:s, d.m.Y', strtotime( $row['datetime'] ) );
				}
				$name = '';
				if ( ! empty( $row['first_name'] ) || ! empty( $row['last_name'] ) ) {
					$name = $row['first_name'] . ' ' . $row['last_name'];
				}

				$class = '';
				if ( $current_user_id != $row['id_user'] ) {
					$class = ' message_alien';
				}
				$message = get_template_string( $template, [
					'image'      => $image,
					'name'       => $name,
					'title'      => ! empty( $row['title'] ) ? $row['title'] : '',
					'content'    => ! empty( $row['content'] ) ? $row['content'] : '',
					'datetime'   => $datetime,
					'class_name' => $class,
					'id_user'    => $row['id_user'],
					'id_message' => $row ['id_message']
				] );
				echo $message;
			}
		} else {
			echo '<h3>Вы будете первым, кто оставит тут запись!</h3>';
		}
	}
}

function get_template_string( $content, $atts ) {
	foreach ( $atts as $key => $value ) {
		$content = str_replace( '<%=' . $key . '%>', $value, $content );
	}

	return $content;
}

/**
 * Регистрация скриптов и их вывод
 */
function enqueue_scripts() {
	register_script( 'jquery', get_stylesheet_directory() . '/js/jquery-3.2.1.min.js' );
	enqueue_script( 'jquery' );

/*	register_script( 'jquery.mobile', get_stylesheet_directory() . '/js/jquery.mobile.custom.min.js' );
	enqueue_script( 'jquery.mobile' );*/

	register_script( 'jquery.modal', get_stylesheet_directory() . '/js/jquery.modal.js' );
	enqueue_script( 'jquery.modal' );

	register_script( 'bootstrap', get_stylesheet_directory() . '/bootstrap/js/bootstrap.min.js', [ 'jquery' ], '', true );
	//enqueue_script( 'bootstrap' );

	register_script( 'fileapi', get_stylesheet_directory() . '/js/FileAPI/FileAPI.min.js', [],'', true );
	enqueue_script( 'fileapi' );

	register_script( 'jquery.fileapi', get_stylesheet_directory() . '/js/FileAPI/jquery.fileapi.min.js', ['fileapi'],'', true );
	enqueue_script( 'jquery.fileapi' );

	register_script( 'jcrop', get_stylesheet_directory() . '/js/jcrop/js/jquery.Jcrop.min.js', [],'', true );
	enqueue_script( 'jcrop' );

	register_script( 'fileapi_functions', get_stylesheet_directory() . '/js/fileapi_functions.js', [],'', true );
	enqueue_script( 'fileapi_functions' );

	register_script( 'microtemplating', get_stylesheet_directory() . '/js/microtemplating.js', [], '', true );
	enqueue_script( 'microtemplating' );

	register_script( 'functions', get_stylesheet_directory() . '/js/functions.js', [ 'jquery' ], '', true );
	enqueue_script( 'functions' );

	$shlo = get_user_info();
	if ( ! empty( $shlo ) ) {
		$shlo = array_merge( [], $shlo );

		$shlo['ajax_url'] = get_root_url() . '/ajax.php';
		$shlo['name']     = $shlo['first_name'] . ' ' . $shlo['last_name'];
		$shlo['user_id']     = $shlo['ID'];
		$shlo['image']    = get_root_url() . '/images/users/' . $shlo['image'];

		wp_localize_script( 'functions', 'shlo', $shlo );
	}
}

add_action( 'init', 'enqueue_scripts' );


/*
 * это недоработанная функция сохраниня пользователя
 * function save_profile() {
	list( $url ) = explode( '?', $_SERVER['REQUEST_URI'] ); вычденияем из url строки елементы
	$event = ''; здесь создали переменную для опреления положения в url строке

	if(!empty($_GET['event']) && $_GET['event'] == 'save' && !empty($_POST)) {    создаем условие в котором прописываем занесение в базу данных
																				  информации из инпутов при удачном случае и изменении значения url строки
		$vars_string = array_map('trim', explode(',', 'first_name, last_name, nickname, Email, Password'));
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
			$event = 'success';
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

/**
 * Функция валидации email
 */

//$email = 'Почтовый ящик';

function emailValidation( $email ) {
	if ( $email ) {
		if ( preg_match( "/[0-9a-z_\.\-]+@[0-9a-z_\.\-]+\.[a-z]{2,4}/i", $email ) ) {
			$message = 'Корректный Email';
		} else {
			$message = 'Некорректный Email';
		}
	} else {
		$message = 'Email не указан';
	}

	return $message;
}

//$message = emailValidation( $email );
//echo emailValidation($email);


/**
 * Функция добавления или редактирования  сообщения в БД
 */
function message_add() {
	global $link;
	if ( ! empty( $_POST['action'] ) && $_POST['action'] == 'message_add' ) {

		$data    = $_POST;
		$user_id = get_current_user_id();
		$errors  = [];

		if ( is_user_logged_in() && ! empty( $data['content'] ) ) {

			// указан id сообщения6 которое необходимо обновить
			if ( ! empty( $data['id_message'] ) ) {

				$sql = "UPDATE `message` SET `content` = '{$data['content']}' " .
				       "WHERE `id_message` = {$data['id_message']} " .
				       "AND `id_user` = {$user_id}";
			} else {

				$datetime = date( 'Y-m-d H:i:s' );
				$sql      = "INSERT INTO `message` " .
				            "( `id_user`, `datetime`, `title`, `content` ) " .
				            "VALUES ({$user_id}, '{$datetime}', '{$data['title']}', '{$data['content']}' )";
			}
		} else {
			$errors[] = 'Сообщение не указано';
		}


		if ( ! do_query( $sql ) ) {
			$errors[] = 'Что-то пошло не так';
		} else {
			$data               = [];
			$data['id_message'] = mysqli_insert_id( $link );
			$data['datetime']   = $datetime;
			$data['id_user']    = $user_id;
		}

		if ( ! empty( $errors ) ) {
			$data['errors'] = $errors;
		}

		if ( ! empty( $_POST['action'] ) ) {
			echo json_encode( $data );
			die();
		} else {
			header( 'location: index.php' );
		}
	}
}

// Функция получения последних n сообщений и конвертация их в формат json
function get_last_messages() {
	if ( ! empty( $_POST['last_message_id'] ) ) {
		$last_message_id = $_POST['last_message_id'];

		$sql    = "SELECT * FROM `message` ORDER BY `id_message` DESC LIMIT 3";
		$result = do_query( $sql );

		while ( $rows = mysqli_fetch_array( $result, MYSQLI_ASSOC ) ) {
			$messages[] = $rows;

		}
		echo json_encode( $messages );
		die();
	}
}

add_action( 'init', 'get_last_messages' );

function redirect_configuration_page() {
	global $redirect;
	if ( $redirect === true ) {
		return true;
	} else {
		return false;
	}
}

add_action( 'init', 'redirect_configuration_page' );

//function proverka() {

/*	//Получение данных из $_POST (от Влада)
//	$_POST['content'] = 'ne jfuy';
//	$_POST['event'] = 'message_update';
//	$_POST['id_message'] = 4;
//	$_POST['id_user'] = 2;
	// Проверка на наличие и значение атрибута 'event'
	if ( ! empty( $_POST['event'] && $_POST['event'] == 'message_update' ) ) {
		$sql    = "SELECT COUNT(*) FROM `message` WHERE `id_message` = {$_POST['id_message']} AND  `id_user` = {$_POST['id_user']}";
		$result = do_query( $sql );
		$row    = $result->fetch_row();
	//	pr( $row );
	//  Замена старого сообщения в дб на новое, при прохождении проверки
		if ( $row = 1 ) {
			$new_message = $_POST['content'];
			$update      = "UPDATE `message` SET `content` = '{$new_message}' WHERE `id_message` = {$_POST['id_message']}";
			do_query( $update );
		}
	}
}

add_action( 'init', 'proverka' );
*/
