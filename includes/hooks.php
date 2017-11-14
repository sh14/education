<?php
/**
 * Created by PhpStorm.
 * User: sh14ru
 * Date: 14.11.17
 * Time: 19:45
 */

function add_action( $action, $function ) {
	global $global_actions;

	if(!empty($action)&&!empty($function)){
		if(empty($global_actions[$action])){
			$global_actions[$action] = [];
		}

		$global_actions[$action][] = $function;
	}
}

function do_action($action) {
	global $global_actions;

	if(!empty($action)&&!empty($global_actions[$action])){
		foreach ($global_actions[$action] as $name){
			$name();
		}
	}
}

