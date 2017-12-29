<?php
error_reporting(E_ALL);
ini_set("display_errors", 1);
header( 'Content-Type: text/html; charset=utf-8' );
date_default_timezone_set( 'Europe/Moscow' );
require 'functions.php';
init();
get_last_messages();
