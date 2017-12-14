<div class="message<?php echo $atts['class_name']; ?>">
	<div class="message__box">
		<div class="message__user-avatar">
			<a href="javascript:" class="message__user-image"<?php echo $atts['image']; ?>></a>
		</div>
		<div class="message__data">
			<div class="message__data-box">
				<a href="javascript:" class="message__user-name"><?php echo $atts['name']; ?></a>
				<div class="message__title"><?php echo $atts['title']; ?></div>
				<div class="message__text" data-id_user="<?php echo $atts['ID']; ?>" data-id_message="<?php echo $atts['id_message']; ?>"><?php echo $atts['content']; ?></div>
			</div>
		</div>
	</div>
	<div class="message__date"><?php echo $atts['datetime']; ?></div>
</div>
