<?php
/**
 * Created by PhpStorm.
 * User: sh14ru
 * Date: 09.11.17
 * Time: 21:39
 */

function add_default_data() {
	$sql_check   = [];
	$sql_check[] = "SELECT * FROM `message`";
	$sql_check[] = "SELECT * FROM `users`";

	$sql   = [];
	$sql[] = "INSERT INTO `message`( `id_user`, `datatime`, `title`, `content`, `photo`) 
	VALUES (1,'2017-11-12 12:00:00','Привет, мир!','Это тестовая публикация!','../images/character-designer.png')";
	$sql[] = "INSERT INTO `users`( `login`, `email`, `password`, `first_name`, `last_name`) 
	VALUES ('admin','test@tes.ru','123','Админ','Админов')";

	$sql_reset_id   = [];
	$sql_reset_id[] = "SET @reset = 0";
	$sql_reset_id[] = "UPDATE `message` SET id = @reset:= @reset + 1";
	$sql_reset_id[] = "SET @reset = 0";
	$sql_reset_id[] = "UPDATE `users` SET ID = @reset:= @reset + 1";

	foreach ( $sql_check as $key => $query ) {
		$result[ $key ] = do_query( $query );
		if ( $result[ $key ]->num_rows == 0 ) {
			do_query( $sql[ $key ] );
		}
	}

	foreach ( $sql_reset_id as $key => $query ) {
		do_query( $sql_reset_id[ $key ] );
	}
}