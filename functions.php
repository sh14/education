<?php

include 'config.php';
include 'includes/variables.php';
include 'includes/hooks.php';
include 'includes/installer.php';
include 'includes/formatting.php';

// объявление глобальной переменной
global $link;

// если $link - пуста
if ( empty( $link ) ) {
	$link = mysqli_connect( HOST, LOGIN, PASSWORD, DATABASE )
	or die( 'Ошибка при подключении к серверу MySQL: ' . mysqli_error() );
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
 *  Функция редактирования профиля пользователя
 */

function profile_edit() {
	list( $url ) = explode( '?', $_SERVER['REQUEST_URI'] );
	$event = '';
	if ( ! empty( $_POST['action'] ) && $_POST['action'] == 'edit_user_info' && $_POST['password'] === $_POST['confirm_password'] ) {
		$vars_string       = 'nickname,email,password,first_name,last_name';
		$vars              = array_map( 'trim', explode( ',', $vars_string ) );
		$values            = [];
		$empty_input_count = 0;
		$allow_query       = 1;
		foreach ( $vars as $var_key => $var_value ) {
			if ( ! empty( $_POST[ $var_value ] ) ) {
				if ($var_value == 'password') {
					$password = md5( md5( trim( $_POST['password'] ) ) );
					$values[] = "'$password'";
				} else {
					$values[] = "'$_POST[$var_value]'";
				}
			} else {
				unset( $vars[ $var_key ] );
				++ $empty_input_count;
				if ( $empty_input_count == 5 ) {
					$allow_query = 0;
					break;
				}
			}
		}

		$vars = array_combine( array_keys( $values ), array_values( $vars ) );
		for ( $i = 0; $i < count( $values ); $i ++ ) {
			$values[ $i ] = $vars[ $i ] . '=' . $values[ $i ];
		}
		$user_info = get_user_info();
		$ID        = $user_info['ID'];

		if ( $allow_query == 1 ) {
			$event = 'success';

			$values = implode( ',', $values );
			$ID     = "WHERE ID = $ID";
			$query  = "UPDATE users SET $values $ID";

			do_query( $query );
		} else {
			$event = 'error';
		}
	}
	if ( ! empty( $event ) ) {
		$event = '?event=' . $event;
		header( 'location: ' . $url . $event );
	}
}

add_action( 'init', 'profile_edit' );

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
 * Функция проверки - авторизирован ли пользователь
 *
 */
function is_user_logged_in() {

	if ( ! empty( $_COOKIE['shlo_chat'] ) ) {

		list( $email, $password ) = explode( ';', esc_sql( $_COOKIE['shlo_chat'] ) );

		if ( ! empty( $email ) && ! empty( $password ) ) {
			$sql    = "SELECT COUNT(*) FROM users WHERE email='{$email}' AND password='{$password}'";
			$result = do_query( $sql );
			$rows   = $result->fetch_row();

			if ( $rows[0] == 1 ) {
				return true;
			}
		}
	}

	return false;
}

/**
 * Функция авторизации пользователя
 *
 */
function autorization_user() {
	if ( isset( $_POST['email_login'] ) && isset( $_POST['password_login'] ) ) {

		$email    = $_POST['email_login'];
		$password = md5( md5( trim( $_POST['password_login'] ) ) );
		$sql    = "SELECT COUNT(*) FROM users WHERE email='{$email}' AND password='{$password}'";
		$result = do_query( $sql );
		$rows   = $result->fetch_row();

		if ( $rows[0] == 1 ) {
			setcookie( 'shlo_chat', implode( ';', [ $email, $password ] ), time() + 60 * 60 * 24 );
			$url = get_root_url();
		} else {
			$url = 'p=error_login';
			user_logout( $url );
		}
		header( "Location: " . $url );
		die();
	}
}

add_action( 'init', 'autorization_user' );

/**
 * Функция логаута
 *
 * @param $args
 */
function user_logout( $args = '' ) {
	setcookie( 'shlo_chat', '', time() - 60 * 60 * 24 );
	if ( ! empty( $args ) && is_array( $args ) ) {
		$args = '?' . implode( '&', $args );
	}
	$url = get_root_url() . $args;

	header( "Location: " . $url );
	die();
}

/**
 * Функция разлогинивания
 */
function logout() {
	if ( get_page() == 'logout' ) {
		user_logout();
	}
}

add_action( 'init', 'logout' );

/**
 * Функция регистрации пользователя
 */
function registration() {
	if ( ! empty( $_POST['email'] ) && ! empty( $_POST['password'] ) && ! empty( $_POST['action'] == 'registration' ) ) {
		$err = [];

		if ( strlen( $_POST['email'] ) < 7 or strlen( $_POST['email'] ) > 255 ) {
			$err[] = "Email не должен быть меньше 7 символов и не больше 255";
		}

		if ( ! preg_match( "/[0-9a-z_\.\-]+@[0-9a-z_\.\-]+\.[a-z]{2,4}/i", $_POST['email'] ) ) {
			$err[] = "Некорректный Email";
		}

		if ( strlen( $_POST['password'] ) < 6 or strlen( $_POST['password'] ) > 255 ) {
			$err[] = "Password не должен быть меньше 6 символов и не больше 255";
		}

		if ( count( $err ) == 0 ) {

			$email = $_POST['email'];

			$password = md5( md5( trim( $_POST['password'] ) ) );

			do_query( "INSERT INTO users SET email='" . $email . "', password='" . $password . "'" );
			$query = do_query( "SELECT count(*) FROM users WHERE email='{$_POST['email']}'" );

			if ( mysqli_num_rows( $query ) > 0 ) {
				$err[] = "Пользователь с таким email существует";
			}
			header( "location:" . get_root_url() );
		} else {
			echo "<strong>При регистрации произошли следующие ошибки:</strong><br>";
			foreach ( $err as $error ) {
				echo $error . "<br>";
			}
		}
	}
}


add_action( 'init', 'registration' );
/**
 * Регистрация скрипта для последующего вывода этого скрипта
 *
 * @param       $handle
 * @param       $src
 * @param array $deps
 * @param bool $ver
 * @param bool $in_footer
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
	$sql = "SELECT * FROM `message`";
	$result = do_query($sql);
	$message_data = $result->fetch_array( MYSQLI_ASSOC );
	print_r($message_data);
}
add_action( 'init', 'display_message' );

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

$email = 'Почтовый ящик';

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

$message = emailValidation( $email );
//echo emailValidation($email);

//Функция вытягивания и преобразования в асс массив данных из БД

function get_user_info() {
	global $current_user;
	if ( ! empty( $_COOKIE['shlo_chat'] ) && empty( $current_user ) && is_user_logged_in() ) {

		list( $email, $password ) = explode( ';', esc_sql( $_COOKIE['shlo_chat'] ) );

		if ( ! empty( $email ) && ! empty( $password ) ) {
			$sql          = "SELECT * FROM users WHERE email='{$email}' AND password='{$password}'";
			$result       = do_query( $sql );
			$current_user = $result->fetch_array( MYSQLI_ASSOC );

		}
	}

	return $current_user;
}

add_action( 'init', 'get_user_info' );