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
	$sql_check_database = "SHOW TABLES FROM " . DATABASE;
	$result_db          = do_query( $sql_check_database );
	$rows               = $result_db->num_rows;
	if ( $rows == 0 ) {
		insert_tables();
	}

	$sql_check_tables   = [];
	$sql_check_tables[] = "SELECT * FROM `message`";
	$sql_check_tables[] = "SELECT * FROM `users`";

	$sql   = [];
	$sql[] = "INSERT INTO `message`( `id_user`, `datatime`, `title`, `content`, `photo`) 
	VALUES (1,'2017-11-12 12:00:00','Привет, мир!','Это тестовая публикация!','../images/character-designer.png')";
	$sql[] = "INSERT INTO `users`( `nickname`, `email`, `password`, `first_name`, `last_name`)
	VALUES ('admin','test@tes.ru','123','Админ','Админов')";

	$sql_set_id     = [];
	$sql_reset_id   = [];
	$sql_set_id[]   = "SET @reset = 0";
	$sql_set_id[]   = "SET @reset = 0";
	$sql_reset_id[] = "UPDATE `message` SET id = @reset:= @reset + 1";
	$sql_reset_id[] = "UPDATE `users` SET ID = @reset:= @reset + 1";

	foreach ( $sql_check_tables as $key => $query ) {
		$result[ $key ] = do_query( $query );
		if ( $result[ $key ]->num_rows == 0 ) {
			do_query( $sql[ $key ] );
			do_query( $sql_set_id[ $key ] );
			do_query( $sql_reset_id[ $key ] );
		}
	}
}

add_action( 'init', 'add_default_data' );

/**
 * Функция проверки таблиц
 */

function check_database() {
	$sql    = "SHOW TABLES FROM " . DATABASE;
	$result = do_query( $sql );
	pr( $result );
	if ( ! $result ) {
		echo "Ошибка базы данных, невозможно вывести таблицы\n";
		echo 'Ошибка MySQL: ' . mysqli_error();
		exit;
	}

	while ( $row = mysqli_fetch_row( $result ) ) {
		echo "Таблица: {$row[0]}\n";
	}

	mysqli_free_result( $result );
}

//add_action( 'init', 'check_database' );

/**
 * Функция добавления таблицы
 */

function insert_tables() {
	global $link;
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