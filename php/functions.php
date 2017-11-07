<?php
/**
 * Created by PhpStorm.
 * User: Student
 * Date: 03.10.2017
 * Time: 19:43
 */

// объявление глобальной переменной
global $link;

// если $link - пуста
if ( empty( $link ) ) {
	$link = mysqli_connect( 'localhost', 'root', '', 'step_blog2' );
}

/**
 * Функция инициализации, в ней находится обработчик запрашиваемых страниц и определяется какие файлы
 * следует подключать
 */
function init() {
	$page = get_page_name();

	// функция обработки данных передаваемых через форму
	save_post();

	log_out();

	get_header();

	if ( $page == 'main' ) {
		include 'front-page.php';
	} else {

		if ( get_page() == false ) {
			include '404.php';
		}

	}

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

/**
 * Функция определения имени запрашиваемой страницы
 *
 * @return string
 */
function get_page_name() {
	if ( ! empty( $_GET['p'] ) ) {
		$page = $_GET['p'];
	} else {
		$page = 'main';
	}

	return $page;
}

/**
 * Функция вывода регулярных записей, типа post
 */
function get_posts() {
	$posts = do_query( "SELECT * FROM posts WHERE post_type = 'post' ORDER BY post_date DESC, ID DESC" );

	while ( $row = $posts->fetch_assoc() ) {

		//'2017-10-03 14:00:37';

		// способ переформатирования времени с помощью массивов
		/*		list( $date, $time ) = explode( ' ', $row['post_date'] );
				$date = explode( '-', $date );
				$date = $time . ', ' . implode( '.', array(
						$date[2],
						$date[1],
						$date[0],
					) );
		*/

		// способ переформатирования времени с помощью функций для работы с датой
		$date = date( 'H:i, d.m.Y', strtotime( $row['post_date'] ) );

		$row['post_date'] = $date;
		// подключение контента страницы
		include 'content.php';
	}
}

/**
 * Функция вывода раздела сайта
 *
 * @return bool
 */

function get_page() {

	$page = get_page_name();

	$posts = do_query( "SELECT * FROM posts WHERE post_type = 'page' AND post_name='{$page}' LIMIT 1" );

	while ( $row = $posts->fetch_assoc() ) {
		include 'page.php';

		return true;
	}

	return false;
}

/**
 * Функция возвращающая меню - записи типа page
 *
 * @return string
 */
function get_menu() {
	$posts = do_query( "SELECT * FROM posts WHERE post_type = 'page'" );
	$out   = '';
	$out   .= '<ul>';
	while ( $row = $posts->fetch_assoc() ) {
		$out .= '<li><a href="?p=' . $row['post_name'] . '">' . $row['post_title'] . '</a></li>';
	}
	$out .= '<li><a href="?p=log_out">Выйти</a></li>';
	$out .= '</ul>';

	return $out;
}

function log_out() {
	list( $url ) = explode( '?', $_SERVER['REQUEST_URI'] );
	if ( isset( $_GET['p'] ) && $_GET['p'] == 'log_out' ) {
		header( "location: " . $url );
		setcookie( 'credentials[name]', null, 1 );
		setcookie( 'credentials[password]', null, 1 );
		exit;
	}
}

/**
 * Функция сохранения данных, передаваемых через форму
 */
function save_post() {
	list( $url ) = explode( '?', $_SERVER['REQUEST_URI'] );
	$event = '';
	if ( ! empty( $_GET['event'] ) && ! empty( $_POST ) && ! empty( $_POST['ID'] ) && $_POST['ID'] == 1 ) {
		switch ( $_GET['event'] ) {
			case 'save_post':
				$vars_string = 'post_title,post_name,post_date,post_content,post_type';
				break;
			case 'save_registration':
				$vars_string = 'user_name,user_password,first_name,last_name';
				break;
			case 'save_sing_in':
				$vars_string = 'name,password';
				break;
		}
		$vars        = array_map( 'trim', explode( ',', $vars_string ) );
		$values      = [];
		$allow_query = 1;

		foreach ( $vars as $var ) {
			// если столбец post_type
			if ( $var == 'post_type' ) {
				// указывается тип - post
				$values[] = "'post'";
			} else {
				if ( empty( $_POST[ $var ] ) ) {
					$allow_query = 0;
					break;
				}
				$values[] = "'$_POST[$var]'";
			}
		}

		if ( $allow_query == 1 ) {
			$event  = 'success';
			$vars   = implode( ',', $vars );
			$values = implode( ',', $values );
			switch ( $_GET['event'] ) {
				case 'save_post':
					$table_name = 'posts';
					break;
				case 'save_registration':
					$table_name = 'users';
					break;
				case 'save_sing_in':
					$table_name = 'authorized_users';
					break;
			}
			$query = "INSERT INTO $table_name ($vars) VALUES ($values)";
			do_query( $query );
		} else {
			$event = 'error';
		}

	} elseif ( ! empty( $_GET['event'] ) && $_GET['event'] == 'save_post' && ! empty( $_POST['ID'] ) && $_POST['ID'] > 1 ) {
		$vars_string       = 'post_title,post_name,post_date,post_content';
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
				if ( $empty_input_count == 3 ) {
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
	if ( ! empty( $event ) ) {
		$event = '?event=' . $event;
		header( 'location: ' . $url . $event );
	}
}

function set_cookie() {
	if ( isset( $_POST['name'] ) && isset( $_POST['password'] ) ) {
		$name     = $_POST['name'];
		$password = $_POST['password'];
		$users    = do_query( "SELECT * FROM users WHERE user_name = '{$name}' AND user_password ='{$password}'" );
		if ( $users->num_rows > 0 ) {
			while ( $row = $users->fetch_assoc() ) {
				setcookie( 'credentials[name]', $row['user_name'], time() + 3600 );
				setcookie( 'credentials[password]', $row['user_password'], time() + 3600 );
				echo 'Добро пожаловать, ' . $row['first_name'] . ' ' . $row['last_name'] . '!' . '<br>';
			}

			return true;
		} else {
			echo 'Такого пользователя не существует.';

			return false;
		}
	}
}

function authorization() {
	if ( isset( $_COOKIE['credentials'] ) ) {
		return true;
	} else {
		return false;
	}
}

function delete_message() {
	if ( isset( $_POST['delete_button'] ) ) {
		$ID = (int) $_POST['delete_button'];
		do_query( "DELETE FROM posts WHERE ID = $ID AND post_type = 'post' LIMIT 1" );
	}
}

function redirect() {
	list( $url ) = explode( '?', $_SERVER['REQUEST_URI'] );
	if ( isset( $_GET['message'] ) ) {
		if ( $_GET['message'] == 'delete' ) {
			header( "refresh:5;url=" . $url );
			echo 'Сообщение удалено';
		}
	}
	if ( isset( $_GET['event'] ) && $_GET['event'] == 'save_sing_in' ) {
			header( "refresh:5;url=" . $url );
	}
}