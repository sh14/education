<?php

include 'config.php';
include 'includes/variables.php';
include 'includes/hooks.php';
include 'includes/user.php';
include 'includes/installer.php';
include 'includes/formatting.php';

// объявление глобальной переменной
global $link;

// если $link - пуста
if ( empty( $link ) ) {
	$link = mysqli_connect( HOST, LOGIN, PASSWORD, DATABASE )
	or die( 'Ошибка при подключении к серверу MySQL: ' . mysqli_connect_error() );
}

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
 * @return bool|mysqli_result
 */
function do_query( $query ) {
	global $link;

	mysqli_set_charset( $link, 'utf8' );

	$result = mysqli_query( $link, $query );
	if ( ! $result ) {
		die( 'Неверный запрос: ' . mysqli_error( $link ) );
	}

	return $result;
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
 * Функция загрузки фотографии пользователя
 */
function upload_image() {
	if ( ! empty( $_POST['action'] ) && $_POST['action'] == 'upload' ) {
		$target_dir      = '/images/';
		$target_file     = get_root_path() . $target_dir . basename( $_FILES['file_to_upload']['name'] );
		$upload_ok       = 1;
		$image_file_type = pathinfo( $target_file, PATHINFO_EXTENSION );
		if ( isset( $_POST['submit'] ) ) {
			$check = getimagesize( $_FILES['file_to_upload']['tmp_name'] );
			if ( $check !== false ) {
				echo 'Файл ' . $check['mime'] . ' является изображением.';
				$upload_ok = 1;
			} else {
				echo 'Файл не является изображением.';
				$upload_ok = 0;
			}
		}

		if ( file_exists( $target_file ) ) {
			echo 'Файл уже существует.';
			$upload_ok = 0;
		}
		if ( $_FILES['file_to_upload']['size'] > 500000 ) {
			echo 'Файл слишком большой.';
			$upload_ok = 0;
		}
		if ( $image_file_type != "jpg" && $image_file_type != "png" && $image_file_type != "jpeg"
		     && $image_file_type != "gif" ) {
			echo 'Можно загружать только файлы JPG, JPEG, PNG & GIF.';
			$upload_ok = 0;
		}
		if ( $upload_ok == 0 ) {
			echo 'Файл не загружен.';
		} else {
			if ( move_uploaded_file( $_FILES['file_to_upload']['tmp_name'], $target_file ) ) {
				echo 'Файл ' . basename( $_FILES['file_to_upload']['name'] ) . ' успешно загружен.';
			} else {
				echo 'При загрузке файла произошла ошибка.';
			}
		}
	}
}

add_action( 'init', 'upload_image' );


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
		'src'       => $src . $ver,
		'in_footer' => $in_footer
	];

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
 * Вывод скрипта в нужно месте
 *
 * @param $handle
 */
function enqueue_script( $handle ) {
	global $scripts;

	if ( ! empty( $scripts[ $handle ] ) ) {
		$out = '<script src="' . $scripts[ $handle ]['src'] . '"></script>';

		if ( $scripts[ $handle ]['in_footer'] === true ) {
			$action = 'footer';
		} else {
			$action = 'head';
		}
		add_action( $action, function () use ( $out ) {
			echo $out;
		} );

	}
}

/**
 * Вывод сообщений на дисплей
 *
 */
function display_message() {
	if ( is_user_logged_in() ) {
		$sql    = "SELECT `title`, `content`, `datatime` FROM `message` ORDER BY `id` DESC limit 30";
		$result = do_query( $sql );
		$count = mysqli_num_rows($result);
		if ($count != 0) {
			while ( $rows = mysqli_fetch_array( $result, MYSQLI_ASSOC ) ) {
				include 'templates/message.php';
			}
		}else{
			echo 'Вы будете первым, кто оставил тут запись';
		}
	}
}


/**
 * Регистрация скриптов и их вывод
 */
function enqueue_scripts() {
	register_script( 'jquery', get_stylesheet_directory() . '/js/jquery-3.2.1.min.js' );
	enqueue_script( 'jquery' );
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


//Функция добавления сообщений в БД

function message_add() {
	if ( is_user_logged_in() && ! empty( $_POST['content'] ) ) {
		$user_id = get_current_user_id();
		do_query( "INSERT INTO `message` ( `title`, `id_user`, `content` ) VALUES ('{$_POST['title']}',{$user_id}, '{$_POST['content']}' )" );
		header('location: index.php');
	}
}

add_action( 'init', 'message_add' );


