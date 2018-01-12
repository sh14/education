<?php
/**
 * Created by PhpStorm.
 * User: Ruslan
 * Date: 12.11.2017
 * Time: 14:47
 */
/*
$last_message_id = 17;
$sql    = "SELECT * FROM message m LEFT JOIN users u ON u.ID = m.id_user WHERE m.id_message > {$last_message_id} ORDER BY datetime DESC LIMIT 30";

//$sql    = "SELECT * FROM `message` WHERE id_message > {$last_message_id} ORDER BY `id_message` ASC";
$result = do_query( $sql );
if ( $result->num_rows > 0 ) {
	while ( $rows = mysqli_fetch_array( $result, MYSQLI_ASSOC ) ) {
		pr( $rows );
	}
}else{
	pr( $result );
}*/
?>

<div class="page container">
	<div class="row">
		<div class="col-md-12 col-sm-12 col-xs-12">
			<div class="chat js-chat">
				<div class="chat__messages">
					<div class="chat__messages-box">
						<?php echo display_message(); ?>
					</div>
				</div>
				<div class="chat__form">
					<form action="" method="post" class="chat__form-tag">

						<div class="chat__table">
							<input type="text" class="form-control chat__title hidden" placeholder="Тема сообщения"
							       name="title">
							<div class="chat__message">
								<textarea class="form-control chat__message-field" rows="1"
								          placeholder="Для отправки сообщения нажмите Alt + Enter"
								          name="content" autofocus></textarea>
							</div>
							<div class="chat__buttons">
								<input type="hidden" name="action" value="message_add">
								<input type="hidden" name="id_message" value="">
								<button class="btn btn-danger chat__cancel hidden" type="button">Отменить</button>
								<button class="btn btn-success chat__submit" type="submit">Отправить</button>
							</div>
						</div>
					</form>
				</div>
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
		'edit',
	] );
	?></script>
