<?php
/**
 * Created by PhpStorm.
 * User: Student
 * Date: 28.11.2017
 * Time: 20:35
 */

/**
 * Функция добавления данных по умолчанию в базу данных
 */
function add_default_data() {
	global $link;
	$sql_check_database = "SHOW TABLES FROM " . DATABASE;
	$result_db          = do_query( $sql_check_database );
	if ( $result_db ) {
		$rows = $result_db->num_rows;
		if ( $rows == 0 ) {
			insert_tables();
		} else {
			return true;
		}
	}

	$password = encript_password( 123 );

	$sql_check_tables   = [];
	$sql_check_tables[] = "SELECT * FROM `message`";
	$sql_check_tables[] = "SELECT * FROM `users`";

	$sql   = [];
	$sql[] = "INSERT INTO `message`( `id_user`, `datetime`, `title`, `content`) 
	VALUES (1,'2017-11-12 12:00:00','Привет, мир!','Это тестовая публикация!')";
	$sql[] = "INSERT INTO `users`( `email`, `password`, `first_name`, `last_name`, `image`)
	VALUES ('test@tes.ru','{$password}','Админ','Админов','character-designer.png')";

	$sql_set_id     = [];
	$sql_reset_id   = [];
	$sql_set_id[]   = "SET @reset = 0";
	$sql_set_id[]   = "SET @reset = 0";
	$sql_reset_id[] = "UPDATE `message` SET id_message = @reset:= @reset + 1";
	$sql_reset_id[] = "UPDATE `users` SET ID = @reset:= @reset + 1";
	if ( $link ) {
		foreach ( $sql_check_tables as $key => $query ) {
			$result[ $key ] = do_query( $query );
			if ( $result[ $key ]->num_rows == 0 ) {
				do_query( $sql[ $key ] );
				do_query( $sql_set_id[ $key ] );
				do_query( $sql_reset_id[ $key ] );
			}
		}
	}
	//header( "location:" . get_root_url() );
}

add_action( 'init', 'add_default_data' );

function drop_database() {
	if(!empty($_POST['drop_database'])/*$_POST['drop_database']=='on'*/) {
		$sql = "DROP TABLE `message`,`users`";
		do_query( $sql );
		//header( "location:" . get_root_url() );
	}
}

//add_action( 'init', 'drop_database' );

/**
 * Функция проверки существования базы данных
 */

function check_database() {
	$sql_check_database = "SHOW TABLES FROM " . DATABASE;
	$result_db          = do_query( $sql_check_database );
	$rows               = $result_db->num_rows;

	if ( $rows == 0 ) {
		return false;
	} else {
		return true;
	}
}

/**
 * Функция добавления таблицы
 */

function insert_tables() {
	global $link;
	if ( $link ) {
		$filename = 'shlo.sql';
		$templine = '';
		$lines    = file( $filename );
		foreach ( $lines as $line ) {
			if ( substr( $line, 0, 2 ) == '--' || $line == '' ) {
				continue;
			}
			$templine .= $line;
			if ( substr( trim( $line ), - 1, 1 ) == ';' ) {
				do_query( $templine ) or print( 'Ошибка при осуществлении запроса \'<strong>' . $templine . '\': ' . mysqli_error( $link ) . '<br /><br />' );
				$templine = '';
			}
		}
		echo "Таблицы успешно импортированы";
	}

}

function get_configuration_data() {
	if ( ! empty( $_POST['action'] ) && $_POST['action'] == 'configuration' ) {
		if ( ! empty( $_POST['host'] ) && ! empty( $_POST['login'] ) && ! empty( $_POST['database'] ) ) {
			$vars_string       = 'host,login,password,database';
			$vars              = array_map( 'trim', explode( ',', $vars_string ) );
			$values            = [];
			$empty_input_count = 0;
			foreach ( $vars as $var_key => $var_value ) {
				if ( ! empty( $_POST[ $var_value ] ) ) {
					$values[] = "$_POST[$var_value]";
				} else {
					unset( $vars[ $var_key ] );
					++ $empty_input_count;
				}
			}
			if ( $empty_input_count == 1 ) {
				set_configuration_data( $values[0], $values[1], '', $values[2] );
			} else {
				set_configuration_data( $values[0], $values[1], $values[2], $values[3] );
			}
		} elseif ( empty( $_POST['host'] ) ) {
			echo 'Поле "Хост" не заполнено';
		} elseif ( empty( $_POST['login'] ) ) {
			echo 'Поле "Логин" не заполнено';
		} elseif ( empty( $_POST['database'] ) ) {
			echo 'Поле "Название базы данных" не заполнено';
		}
	}
}

add_action( 'init', 'get_configuration_data' );

function set_configuration_data( $host, $login, $password, $database ) {
	$variables     = [ $host, $login, $password, $database ];
	$constant_name = [ 'HOST', 'LOGIN', 'PASSWORD', 'DATABASE' ];
	$file_exists   = file_exists( 'config.php' );
	if ( $file_exists === true ) {
		$file_array = file( 'config.php' );
	}
	$fp = fopen( 'config.php', 'w' );
	fwrite( $fp, "<?php\r\n" );
	if ( $file_exists === true ) {
		foreach ( $constant_name as $constant_key => $constant_value ) {
			foreach ( $file_array as $file_value ) {
				if ( strstr( $file_value, $constant_value ) ) {
					$position = strpos( $file_value, $constant_value );
					$position += strlen( $constant_value ) + 3;
					$string   = substr( $file_value, $position );
					$string   = explode( "'", $string );
					$string   = $string[0];
					if ( $string == '' ) {
						$replace = "define('$constant_value','$variables[$constant_key]');\r\n";
					} else {
						$replace = str_replace( $string, $variables[ $constant_key ], $file_value );
					}
					fwrite( $fp, $replace );
				}
			}
		}
	} else {
		foreach ( $constant_name as $constant_key => $constant_value ) {
			$replace = "define('$constant_value','$variables[$constant_key]');\r\n";
			fwrite( $fp, $replace );
		}
	}
	fclose( $fp );
}
