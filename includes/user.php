<?php
/**
 * Created by PhpStorm.
 * User: sh14ru
 * Date: 29.11.17
 * Time: 21:21
 */

/**
 * Получение текущего ID пользователя
 *
 * @return int
 */
function get_current_user_id() {
	global $current_user;

	if ( ! empty( $current_user['ID'] ) ) {
		return intval( $current_user['ID'] );
	}

	return 0;
}

/**
 * Функция получения данных текущего пользователя
 *
 * @return mixed
 */
function get_user_info() {
	global $current_user;

	$user = $current_user;

	if ( is_user_logged_in() ) {

		if ( empty( $current_user ) ) {
			list( $email, $password ) = explode( ';', esc_sql( $_COOKIE['shlo_chat'] ) );

			if ( ! empty( $email ) && ! empty( $password ) ) {
				$sql          = "SELECT * FROM users WHERE email='{$email}' AND password='{$password}'";
				$result       = do_query( $sql );
				$user         = $result->fetch_array( MYSQLI_ASSOC );
				$current_user = $user;
			}
		}
	}

	return $user;
}

add_action( 'init', 'get_user_info' );


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

			$password = encript_password( $_POST['password'] );

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
 * Функция разлогинивания
 */
function logout() {
	if ( get_page() == 'logout' ) {
		user_logout();
	}
}

add_action( 'init', 'logout' );

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
 * Функция авторизации пользователя
 *
 */
function autorization_user() {
	if ( isset( $_POST['email_login'] ) && isset( $_POST['password_login'] ) ) {

		$email    = $_POST['email_login'];
		$password = encript_password( $_POST['password_login'] );
		$sql      = "SELECT COUNT(*) FROM users WHERE email='{$email}' AND password='{$password}'";
		$result   = do_query( $sql );
		$rows     = $result->fetch_row();
		$password = md5( md5( trim( $_POST['password_login'] ) ) );
		$sql      = "SELECT COUNT(*) FROM users WHERE email='{$email}' AND password='{$password}'";
		$result   = do_query( $sql );
		$rows     = $result->fetch_row();

		if ( $rows[0] == 1 ) {
			setcookie( 'shlo_chat', implode( ';', [ $email, $password ] ), time() + 60 * 60 * 24 );
			$url = get_root_url();
		} else {
			$url = '?p=error_login';
			user_logout( $url );
		}
		header( "Location: " . $url );
		die();
	}
}

add_action( 'init', 'autorization_user' );

/**
 * Функция проверки - авторизирован ли пользователь
 *
 */
function is_user_logged_in() {
	global $link;
	if ( $link ) {
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
 * Функция загрузки изображения canvas
 */

function image_resize() {
	$str = 'Строка в кодировке UTF-8';
	echo base64_encode($str);
	print_r($_FILES);
	//echo get_root_path();
	if ( ! empty( $_POST['action'] ) && $_POST['action'] == 'upload' ) {
		echo get_root_path();
		echo basename( $_FILES['file_to_upload']['name'] );
		$target_dir      = '/images/users/';
		$target_file     = get_root_path() . $target_dir . basename( $_FILES['file_to_upload']['name'] );
		$img = $_POST['image'];
		$img = str_replace('data:image/png;base64,', '', $img);
		$img = str_replace(' ', '+', $img);
		$data = base64_decode($img);
		$success = file_put_contents($target_file, $data);
		print $success ? $target_file : 'Unable to save the file.';

		echo $target_dir;

		//header('Location: '.$_POST['return_url']);

		/*
		$targ_w = $targ_h = 200;
		$jpeg_quality = 100;

		$src = 'images/avatar.jpg';
		$img_r = imagecreatefromjpeg($src);
		$dst_r = ImageCreateTrueColor( $targ_w, $targ_h );

		imagecopyresampled($dst_r,$img_r,0,0,$_POST['x'],$_POST['y'],
			$targ_w,$targ_h,$_POST['w'],$_POST['h']);

		header('Content-type: image/jpeg');
		imagejpeg($dst_r, null, $jpeg_quality);
		*/
	}
}

add_action( 'init', 'image_resize' );

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
				if ( $var_value == 'password' ) {
					$password = encript_password( $_POST['password'] );
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

		$vars       = array_combine( array_keys( $values ), array_values( $vars ) );
		$num_values = count( $values );
		for ( $i = 0; $i < $num_values; $i ++ ) {
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
