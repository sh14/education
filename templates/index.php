<?php
global $link;

// если пользователь авторизован
if ( is_user_logged_in() ) {

	switch ( get_page() ) {
		case 'profile_edit':
			get_template_part( 'profile_edit' );
			break;
		default:
			get_template_part( 'window_chat' );
	}
} else {
	// пользователь НЕ авторизован

	switch ( get_page() ) {
		case 'error_login':
			echo '<div class="error_password">Вы ввели неверный пароль!</div>';
			break;
		case 'profile_edit':
			get_template_part( 'main' );
			break;
		default:
			get_template_part( 'main' );
	}




}
