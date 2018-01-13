<?php
global $link;
global $redirect;

if ( redirect_configuration_page() === true ) {

	get_template_part( 'configuration_page' );
} else {

	// если пользователь авторизован
	if ( is_user_logged_in() ) {

		get_template_part( 'chat' );
	}else{
		get_template_part( 'login' );
	}
}
