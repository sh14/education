<div id="installer"></div>
<script type="text/javascript">
    $(document).ready(function () {
        /* Валидация формы */

        $('.password, .confirm_password').on('keyup', function () {
            if ($('.password').val() == $('.confirm_password').val()) {
                if ($('.password, .confirm_password').val() != '') {
                    $('.message').html('Пароли совпадают').css('color', 'green');
                    $('input[name="access"]').val('permitted');
                } else {
                    $('.message').html('').removeAttr('style');
                    $('input[name="access"]').val('permitted');
                }
            } else {
                $('.message').html('Пароли не совпадают').css('color', 'red');
                $('input[name="access"]').val('denied');
            }
        });
    });
    /*Кнопка выхода*/
    function preparePage() {
      document.getElementById('enter').onclick = function () {
        if (document.getElementById('enter')) {
            document.getElementById('exit').style.display = "block";
            document.getElementById('reg').style.display = "none";
            document.getElementById('enter').style.display = "none";
      } else {
        document.getElementById('exit').style.display = "none";
      }
    };
    document.getElementById('exit').style.display = "none";
  }
  window.onload = function() {
    preparePage();
  }
</script>
<script type="text/javascript" src="<?php echo get_stylesheet_directory(); ?>/bootstrap/js/bootstrap.min.js"></script>
</body>
</html>
