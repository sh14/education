<?php
/**
 * Created by PhpStorm.
 * User: sh14ru
 * Date: 14.11.17
 * Time: 19:45
 */

/**
 * Функция добавления экшена к указанному хуку
 *
 * @param $action
 * @param $function
 */
function add_action( $action, $function ) {
	global $global_actions;

	if(!empty($action)&&!empty($function)){
		if(empty($global_actions[$action])){
			$global_actions[$action] = [];
		}

		// добавление названия функции в глобальную переменную
		$global_actions[$action][] = $function;
	}
}

/**
 * Вызов функций по которые запускаются по указанному хуку
 *
 * @param $action
 */
function do_action($action) {
	global $global_actions;

	if(!empty($action)&&!empty($global_actions[$action])){
		foreach ($global_actions[$action] as $name){

			// вызов функции, название которой содержится в переменной $name
			$name();
		}
	}
}

