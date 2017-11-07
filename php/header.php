<!doctype html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="../css/style.css">
    <script src="../js/jquery-3.2.1.min.js"></script>
    <title>Blog</title>
</head>
<body>

<div class="menu">
	<?php echo get_menu(); ?>
</div>
<?php
switch (get_page_name()) {
    case 'main':
	    include 'publishing-form.php';
        break;
    case 'registration':
	    include 'registration-form.php';
	    break;
    case 'sing_in':
	    include 'sing-in-form.php';
	    break;
}
?>
<div class="container">