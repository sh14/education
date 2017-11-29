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

<form method="POST" id="message" action="?123">
    <label for="your_massage">Your Message</label>
    <input type="text" name="your_massage" id="your_massage" placeholder="Напишите сообщение...">
    <input value="Send Message" type="submit" data-event="btn_message">
</form>

<script id="message-template" type="text/ejs">
		<div class="message">
			<img src="#" class="photo-user" name="<%=p%>"><p class="head-message"></p>
			<p class="text-message"><%=message%></p>
		</div>
</script>

<div id="results_message"><!-- текст сообщений --></div>




<!--получить данные из конкретного инпута
определить переменную темплате и вызвать функцию.
и в массив этой функции вставить данные полученные из инпута
и вставить данные переменной темплате в блок в которой находятся все сообщдения-->
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
    }


    //ниже ajax запрос на сообщения из сервера
    function see_message() {
        if(your_message.value != "") {
            var mes = $('#message').serialize();
            $.ajax({
                type: 'POST',
                url: 'mes.php',
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



//запрос ajax

    var btn_auth = document.querySelector('[data-event="auth"]');

    btn_auth.addEventListener("click", auth);
    //btn_message.addEventListener("click", see_message);

    function auth(event) {
        event.preventDefault();
        var form   = $('#auth').serialize();
            $.ajax({
            type: 'POST',
            url: 'res.php',
            data: form,
            success: function(data) {
                $('#results_form').html(data);
            },
            error:  function(xhr, str){
                alert('Возникла ошибка: ' + xhr.responseCode);
            }
        });
    }


    function control_auth() {
        var base = setInterval(function(){
            $.ajax({
                url: 'res.php',
                success: function(url) {
                    $('#results_form').html(data);
                },
                error:  function(xhr, str){
                    alert('Возникла ошибка: ' + xhr.responseCode);
                }
            });
        }, 100);
        clearInterval (base);
    }

    function control_message() {
        var base = setInterval(function(){
            $.ajax({
                url: 'mes.php',
                success: function(url) {
                    $('#results_message').html(data);
                },
                error:  function(xhr, str){
                    alert('Возникла ошибка: ' + xhr.responseCode);
                }
            });
        }, 100);
        clearInterval (base);
    }
    </script>