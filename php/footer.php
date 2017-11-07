</div>
<script type="text/javascript">
    $(document).ready(function() {
        var button = $('button[type="button"]');
        var exit_edit = $('.publishing .exit-edit');
        $(button).on('click', function() {
            $(exit_edit).removeClass("hidden");
            var content = $(this).val();
            $('input:hidden').val(content);
            $("html, body").animate({ scrollTop: 0 }, "slow");
            var title = $('h2');
            var date = $('.meta');
            var message_header = $(this).closest('article').find(title).text();
            var message_date = $(this).closest('article').find(date).text();
            $('#post .notification').text('Редактирование сообщения с заголовком "' + message_header +
                '", написанным в ' + message_date);
            return false;
        });
        $(exit_edit).on('click', function() {
            $('input:hidden').val("1");
            $(exit_edit).addClass("hidden");
            $('#post .notification').text('Отправка сообщений');
        });
    });
</script>
</body>
</html>