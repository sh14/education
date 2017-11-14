<!doctype html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <script src="<?php echo get_stylesheet_directory(); ?>/js/jquery-3.2.1.min.js"></script>
	<link rel="stylesheet" href="<?php echo get_stylesheet_directory(); ?>/bootstrap/css/bootstrap.min.css">
	<link rel="stylesheet" href="<?php echo get_stylesheet_directory(); ?>/style.css">
    <title>Blog</title>
</head>
<body>

<div class="menu">
    <ul>
        <li>
            <a href="?p=index">Главная страница</a>
        </li>
        <li>
            <a href="?p=profile_edit">Профиль</a>
        </li>
    </ul>
</div>
<?php get_template_part('profile_edit'); ?>
