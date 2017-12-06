<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 09.11.2017
 * Time: 19:06
 */?>
<script src="js/jquery-3.2.1.min.js"></script>

<form method="POST" id="auth" action="?123">
    <label for="name">Name:</label><input id="name" name="name" value="" type="text">
    <label for="email">Email:</label><input id="email" name="email" value="" type="text">
    <input value="Send Auth" type="submit" data-event="auth">
</form>

<div id="results_form"></div>

<hr>

<!--<form method="POST" id="message" action="?123">
    <label for="your_massage">Your Message</label>
    <input type="text" name="your_massage" id="your_massage" placeholder="Напишите сообщение...">
    <input value="Send Message" type="submit" data-event="btn_message">
</form>-->

<!--<script id="message-template" type="text/ejs">
		<div class="message">
			<img src="#" class="photo-user" name="<%=p%>"><p class="head-message"></p>
			<p class="text-message"><%=message%></p>
		</div>
</script>-->

<form action="" method="post" class="chat__form" id="message">
    <input type="text" class="form-control chat__title" placeholder="Тема сообщения"
           name="title"
           id="title" value="">
    <textarea class="form-control chat__message" rows="1"
              placeholder="Текст сообщения"
              name="content" id="your_massage"></textarea>
    <button class="btn btn-success chat__submit" type="submit"
            name="" data-event="btn_message">Отправить
    </button>
    <input type="hidden" name="action" value="message_add">
</form>

<script id="message-template" type="text/ejs">
    <div class="message<?php echo $atts['class']; ?>">
        <div class="message__box">
            <div class="message__user-avatar">
                <a href="javascript:" class="message__user-image"<?php echo $atts['image']; ?>></a>
            </div>
            <div class="message__data">
                <div class="message__data-box">
                    <a href="javascript:" class="message__user-name"><?php echo $atts['name']; ?></a>
                    <div class="message__title"><?php echo $atts['title']; ?></div>
                    <div class="message__text"><%=message%></div>
                </div>
            </div>
        </div>
        <div class="message__date"><?php echo $atts['datetime']; ?></div>
    </div>
</script>

<div id="results_message"><!-- текст сообщений --></div>



<script>
    var your_message = document.getElementById("your_massage");
    var btn_message = document.querySelector('[data-event="btn_message"]');
    var results_message = document.getElementById("results_message");
    var p = document.createElement('p');
    var flag = 0;
    var now = new Date;

    btn_message.addEventListener("click", cl);


    //ниже функция нажатия на клавишу и вызова функций
    function cl(event) {
        event.preventDefault();
        if (flag == 0) {
            flag = 1;
            //see_message();
            show_message();
        }
        return flag = 0;
    }

    //ниже функция отправки и получения сообщения в поле
    function show_message() {
        if (flag == 1 && your_message.value != "") {
            var vall = document.getElementById("your_massage").value;
            var template = tmpl( jQuery( '#message-template').html(), {
                message : vall
            } );

            jQuery('#results_message').append(template);
        }
        your_message.value = "";


        var mes = $('#message').serialize();
        $.ajax({
            type: 'POST',
            url: 'index.php',
            data: mes,
            success: function (data) {
                $('#results_message').append(data);
            },
            error: function (xhr, str) {
                alert('Возникла ошибка: ' + xhr.responseCode);
            }
        });
    }

   /* //ниже ajax запрос на сообщения из сервера
    function see_message() {
        if(your_message.value != "") {
            var mes = $('#message').serialize();
            $.ajax({
                type: 'POST',
                url: 'index.php',
                data: mes,
                success: function (data) {
                    $('#results_message').append(data);
                },
                error: function (xhr, str) {
                    alert('Возникла ошибка: ' + xhr.responseCode);
                }
            });
        }
    }
*/
    (function () {
        var cache = {};

        this.tmpl = function tmpl( str, data ) {
            // Figure out if we're getting a template, or if we need to
            // load the template - and be sure to cache the result.
            var fn = !/\W/.test( str ) ?
                cache[ str ] = cache[ str ] ||
                    tmpl( document.getElementById( str ).innerHTML ) :

                // Generate a reusable function that will serve as a template
                // generator (and which will be cached).
                new Function( "obj",
                    "var p=[],print=function(){p.push.apply(p,arguments);};" +

                    // Introduce the data as local variables using with(){}
                    "with(obj){p.push('" +

                    // Convert the template into pure JavaScript
                    str
                        .replace( /[\r\t\n]/g, " " )
                        .split( "<%" ).join( "\t" )
                        .replace( /((^|%>)[^\t]*)'/g, "$1\r" )
                        .replace( /\t=(.*?)%>/g, "',$1,'" )
                        .split( "\t" ).join( "');" )
                        .split( "%>" ).join( "p.push('" )
                        .split( "\r" ).join( "\\'" )
                    + "');}return p.join('');" );
            // Provide some basic currying to the user
            return data ? fn( data ) : fn;
        };
    })();

    //ниже функция шаблона отображения времени отправки сообщения
    /*function curent(){
        var H = now.getHours();
        var M = now.getMinutes();
        var S = now.getSeconds();
        if(H < 10){H = "0" + H;} if(M < 10){M = "0" + M;}  if(S < 10){S = "0" + S;}
        var time = H + ":" + M + ":" + S ;
        return time;
    }*/

    </script>