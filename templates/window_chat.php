<?php
/**
 * Created by PhpStorm.
 * User: Ruslan
 * Date: 12.11.2017
 * Time: 14:47
 */
?>

<div class="page container">
	<div class="row">
		<div class="col-md-12 col-sm-12 col-xs-12">
			<div class="chat js-chat">
				<div class="chat__messages">
					<div class="chat__messages-box"></div>
				</div>
				<form action="" method="post" class="chat__form">
					<input type="text" class="form-control chat__title hidden" placeholder="Тема сообщения"
					       name="title">
					<textarea class="form-control chat__message" rows="1"
					          placeholder="Для отправки сообщения нажмите Alt + Enter"
					          name="content" autofocus></textarea>
					<input type="hidden" name="action" value="message_add">
					<input type="hidden" name="id_message" value="">
					<button class="btn btn-success chat__submit" type="submit">Отправить</button>
				</form>
			</div>
		</div>
	</div>
</div>

<script id="message_template" type="text/html"><?php
	echo get_template_part( 'message', [
		'image',
		'name',
		'title',
		'content',
		'datetime',
		'class_name',
		'id_user',
		'id_message',
	] );
	?></script>
