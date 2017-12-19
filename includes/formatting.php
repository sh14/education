<?php
/**
 * Created by PhpStorm.
 * User: sh14ru
 * Date: 16.11.17
 * Time: 20:38
 */

/**
 * Экранирует специальные символы в строке для использования в SQL выражении, используя текущий набор символов соединения
 *
 * @param $string
 *
 * @return string
 */
function esc_sql( $string ) {
	global $link;
	if ( $link ) {
		return mysqli_real_escape_string( $link, $string );
	}
}
