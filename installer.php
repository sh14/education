<?php
/**
 * Created by PhpStorm.
 * User: sh14ru
 * Date: 09.11.17
 * Time: 21:39
 */

function add_default_data() {
	$sql   = [];
	$sql[] = "INSERT INTO `message`( `id_user`,  `title`, `content`) VALUES (1,'Привет, мир!','Это тестовая публикация!')";
	$sql[] = "INSERT INTO `users`( `login`, `email`, `password`, `first_name`, `last_name`) VALUES ('admin','test@tes.ru','123','Админ','Админов')";
}
