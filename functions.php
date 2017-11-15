<?php

include 'config.php';
include 'includes/variables.php';
include 'includes/hooks.php';

// объявление глобальной переменной
global $link;

// если $link - пуста
if ( empty( $link ) ) {
	$link = mysqli_connect( HOST, LOGIN, PASSWORD, DATABASE );
}

function init() {
	do_action( 'init' );
	if ( ! empty( $_GET['p'] ) ) {
		$page = $_GET['p'];
	}
	if ( empty( $page ) ) {
		$page = 'index';
	}
	get_template_part( $page );

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
	if ( $_POST['action'] == 'edit_user_info' ) {
		$vars_string       = 'login,email,password,first_name,last_name';
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
		$ID = $_POST['ID'];
		if ( $_POST['access'] == 'denied' ) {
			$allow_query = 0;
		}

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

//add_action( 'init', 'profile_edit' );

/**
 * Функция загрузки фотографии пользователя
 */
function upload_image() {
	if ( $_POST['action'] == 'upload' ) {
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
 * Функция верификации пользователя
 *
 */
function verification_user() {
	global $link;
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
				$evaluate_hash = $user_data['password'];
				if ( $cookies_hash == $evaluate_hash ) {
					$access = true;
				}
			}
		} else {
			$access = false;
		}
	}
	/*Если данные совпадают подключаем стр с чатом*/
	if ( isset( $access ) and $access = true ) {
		include "window_chat.php";
	} /*Если КУКА отсутствует то выводим окно авторизации*/
	else {
		include( $_SERVER["DOCUMENT_ROOT"] . "/education/login_window.php" );
		exit();
	}
}

/**
 * Функция авторизации пользователя
 *
 */
function autorization_user() {

//Проверяем не пуста ли форма отправки и если нет то сравнив данные с БД записываем их в COOCKIE
	if ( isset( $_POST['login'] ) && isset( $_POST['password'] ) && $_POST['login'] !== "" && $_POST['password'] !== "" ) {
		if ( preg_match( "/^[a-zA-Z0-9]{3,30}$/", $_POST['login'] ) ) {
			global $link;
			$user = do_query( "SELECT * FROM `users` WHERE `login` = '" . $_POST['login'] . "'" );
			$rows = mysqli_num_rows( $user );
			if ( $rows == 1 ) {
				$user_data    = $user->fetch_array();
				$pasword_hash = md5( $_POST['password'] );
				if ( $pasword_hash == $user_data['password'] ) {
					$curr_date = time();
					setcookie( 'RestrictedArea', $_POST['login'] . ":" . $pasword_hash . ":" . md5( $_SERVER['REMOTE_ADDR'] . ":" . $curr_date ), time() + 60 * 60 * 24 );
					header( "Location: " . "index.php" );
				} else {
					echo "<div class='error'><span>Введенный пароль не верный.</span></div>";
				}
			} else {
				echo "<div class='error'><span>Пользователь с таким логином не найден.</span></div>";
			}
		} else {
			echo "<div class='error''><span>Вы ввели некорректный логин.</span></div>";
		}
	} else {
		echo "<div class='error''><span>Введите все данные</span></div>";
	}

}

/**
 * Функция регистрации пользователя
 */
function registration() {
	if ( ! empty($_POST) && $_POST['action'] == 'registration' ) {
		$err = [];
		if ( ! preg_match( "/^[a-zA-Z0-9]+$/", $_POST['email'] ) ) {
			$err = "Email может состоять только и букв английского языка";
		}

		if ( strlen( $_POST['email'] ) < 7 or strlen( $_POST['email'] ) > 255 ) {
			$err = "Email не должен быть меньше 7 символов и не больше 255";
		}

		$query = do_query( "SELECT count(*) FROM users WHERE email='{$_POST['email']}'" );

		if ( mysqli_num_rows( $query ) > 0 ) {

			$err[] = "Пользователь с таким email существует";
		}

		if ( count( $err ) == 0 ) {

			$email = $_POST['email'];

			$password = md5( md5( trim( $_POST['password'] ) ) );

			do_query( "INSERT INTO users SET email='" . $email . "', password='" . $password . "'" );
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
