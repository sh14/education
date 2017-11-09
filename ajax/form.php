<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 09.11.2017
 * Time: 19:06
 */?>

<form method="POST" id="auth" action="javascript:void(null);" onsubmit="call()">
    <legend>Test From</legend>
    <label for="name">Name:</label><input id="name" name="name" value="" type="text">
    <label for="email">Email:</label><input id="email" name="email" value="" type="text">
    <input value="Send_Auth" type="submit" data-event="auth">
</form>

<div id="results_form"></div>


<form method="POST" id="message" action="javascript:void(null);" onsubmit="call()">
    <legend>Test Message</legend>
    <label for="your_massage">Your Message</label><textarea name="your_massage" value="" id="your_massage"></textarea>
    <input value="Send_Message" type="submit" data-event="btn_message">
</form>

<div id="results_message"></div>

<script>
    var btn_auth = document.querySelector('[data-event="auth"]');
    var btn_message = document.querySelector('[data-event="btn_message"]');

    btn_auth.addEventListener("click", auth);
    btn_message.addEventListener("click", see_message);

    function auth() {
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

    function see_message() {
        var mes = $('#message').serialize();
        $.ajax({
            type: 'POST',
            url: 'mes.php',
            data: mes,
            success: function(data) {
                $('#results_message').html(data);
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