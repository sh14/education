<div id="installer"></div>

<?php
if ( is_user_logged_in() ) {
	?>

	<div class="modal modal-avatar" role="dialog">
		<div class="modal-dialog">

			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal">&times;</button>
					<h4 class="modal-title">Ваша фотография</h4>
				</div>
				<div id="image_container" class="modal-body">
					<div class="js-img"></div>
				</div>
				<div class="modal-footer">
					<button type="submit" class="js-upload btn btn_browse btn_browse_small btn btn-default">Сохранить
					</button>
				</div>
			</div>

		</div>
	</div>

	<!-- Свайп окна профиля -->
	<div class="swipe swipe-hidden">
		<div class="swipe__container">
			<div class="swipe__button">
				<div class="swipe__sign">Профиль</div>
			</div>
			<div class="swipe__content">
				<?php get_template_part( 'profile_edit' ); ?>
			</div>
		</div>
	</div>
	<!-- END: Свайп окна профиля -->
	<?php
}
?>


<?php
do_action( 'footer' );
?>
</body>
</html>
