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
 * @return bool|mysqli_result
 */
function do_query( $query ) {
	global $link;
	if ( $link ) {
		mysqli_set_charset( $link, 'utf8' );

		$result = mysqli_query( $link, $query );
		if ( ! $result ) {
			die( 'Неверный запрос: ' . mysqli_error( $link ) );
		}

		return $result;
	}
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

//add_action( 'init', 'upload_image' );


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
			echo $out . "\n";
		} );

	}
}

/**
 * Вывод сообщений на дисплей
 *
 */
function display_message() {
	if ( is_user_logged_in() ) {
		$sql    = "SELECT * FROM message m LEFT JOIN users u ON u.ID = m.id_user ORDER BY datetime DESC LIMIT 3";
		$result = do_query( $sql );
		$count  = mysqli_num_rows( $result );
		if ( $count > 0 ) {

			$template        = get_template_part( 'message', [
				'image',
				'name',
				'title',
				'content',
				'datetime',
				'class',
				'ID',
				'id_message',
			] );
			$current_user_id = get_current_user_id();
			while ( $row = mysqli_fetch_array( $result, MYSQLI_ASSOC ) ) {
//pr($row);
				$image = '';
				if ( ! empty( $row['photo'] ) ) {
					$image = ' style="background-image:url(' . get_root_url() . '/images/' . $row['photo'] . ');"';
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
				if ( $current_user_id != $row['ID'] ) {
					$class = ' message_alien';
				}
				$message = get_template_string( $template, [
					'image'      => $image,
					'name'       => $name,
					'title'      => ! empty( $row['title'] ) ? $row['title'] : '',
					'content'    => ! empty( $row['content'] ) ? $row['content'] : '',
					'datetime'   => $datetime,
					'class'      => $class,
					'ID'         => $row['ID'],
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

	register_script( 'bootstrap', get_stylesheet_directory() . '/bootstrap/js/bootstrap.min.js', [ 'jquery' ], '', true );
	enqueue_script( 'bootstrap' );

	register_script( 'functions', get_stylesheet_directory() . '/js/functions.js', [ 'jquery' ], '', true );
	enqueue_script( 'functions' );

	register_script( 'fileapi', get_stylesheet_directory() . '/js/FileAPI/dist/FileAPI.min.js' );
	enqueue_script( 'fileapi' );

	register_script( 'fileapi.exif', get_stylesheet_directory() . '/js/FileAPI/plugins/FileAPI.exif.js' );
	enqueue_script( 'fileapi.exif' );

	register_script( 'jquery.fileapi', get_stylesheet_directory() . '/js/FileAPI/jquery.fileapi.js' );
	enqueue_script( 'jquery.fileapi' );

	register_script( 'jcrop', get_stylesheet_directory() . '/js/jcrop/js/jquery.Jcrop.min.js' );
	enqueue_script( 'jcrop' );

	register_script( 'jquery.modal', get_stylesheet_directory() . '/js/FileAPI/statics/jquery.modal.js' );
	enqueue_script( 'jquery.modal' );
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
 * Функция добавления сообщений в БД
 */

function message_add() {
	if ( ! empty( $_POST['action'] ) && $_POST['action'] == 'message_add' ) {
		if ( is_user_logged_in() && ! empty( $_POST['content'] ) ) {
			$user_id = get_current_user_id();
			do_query( "INSERT INTO `message` ( `id_user`, `datetime`, `title`, `content` ) 
			VALUES ({$user_id}, '{$_POST['datetime']}', '{$_POST['title']}', '{$_POST['content']}' )" );
			header( 'location: index.php' );
		}
	}
}

add_action( 'init', 'message_add' );

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

function proverka() {

	//Получение данных из $_POST (от Влада)
//	$_POST['content'] = 'ne jfuy';
//	$_POST['event'] = 'message_update';
//	$_POST['id_message'] = 4;
//	$_POST['id_user'] = 2;
	// Проверка на наличие и значение атрибута 'event'
	if ( ! empty($_POST['event'] && $_POST['event']=='message_update')) {
		$sql    = "SELECT COUNT(*) FROM `message` WHERE `id_message` = {$_POST['id_message']} AND  `id_user` = {$_POST['id_user']}";
		$result = do_query( $sql );
		$row    = $result->fetch_row();
	//	pr( $row );
	//  Замена старого сообщения в дб на новое, при прохождении проверки
		if ( $row = 1 ) {
			$new_message = $_POST['content'];
			$update = "UPDATE `message` SET content = '{$new_message}' WHERE `id_message` = {$_POST['id_message']}";
			do_query($update);
		}

	}
}
add_action( 'init', 'proverka' );

