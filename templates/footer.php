<div id="installer"></div>
<script type="text/javascript">
    $(document).ready(function() {
        /* Валидация формы */
        $('.password, .confirm_password').on('keyup', function() {
            if ($('.password').val() === $('.confirm_password').val()) {
                if ($('.password, .confirm_password').val() != '') {
                    $('.message').html('Пароли совпадают').css('color', 'green');
                } else {
                    $('.message').html('').removeAttr('style');
                }
            } else {
                $('.message').html('Пароли не совпадают').css('color', 'red');
            }
        });
        /* Отправка формы добавления изображения при загрузке изображения */
        $('.file-avatar').on('change',function() {
            $('.form-avatar').submit();
        });
    });
</script>
<script type="text/javascript" src="<?php echo get_stylesheet_directory(); ?>/bootstrap/js/bootstrap.min.js"></script>
</body>
</html>
