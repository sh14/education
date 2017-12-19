<?php
/**
 * Created by PhpStorm.
 * User: sh14ru
 * Date: 15.12.17
 * Time: 2:51
 */
require 'functions.php';

function do_ajax() {

	if ( ! empty( $_POST['action'] ) ) {
		$function_name = $_POST['action'];
		$function_name();
	}
}

// определение текущего пользователя по передаваемым вместе с ajax запросом кукам
get_user_info();

// вызов указанной в запросе функции
do_ajax();
