<?php
header( 'Content-Type: text/html; charset=utf-8' );
setcookie('email','mail@mail.ru', time()+3600.);
setcookie('password',md5('russ'), time()+3600.);
require 'functions.php';
require 'installer.php';
init();
add_default_data();