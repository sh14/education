<?php
/**
 * Created by PhpStorm.
 * User: sh14ru
 * Date: 09.11.17
 * Time: 19:46
 */
?>
<div class="container registration">
    <div class="row">
        <div class="col-md-6">
            <button type="button" class="btn btn-success" data-toggle="modal" data-target="#modal-1">Регистрация\Вход</button>
        </div>
    </div>
</div>
<div class="modal registration_template" id="modal-1">
    <form method="post">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Регистрация</h4>
                    <button class="close" type="button" data-dismiss="modal">
                        <i class="fa fa-close"><?php registration(); ?></i>
                    </button>
                </div>
                <div class="modal-body">
                    <p>Введите ваше имя</p>
                    <input type="text" placeholder="first_name" required="required">
                    <br>
                    <p>Введите вашу фамилию</p>
                    <input type="text" placeholder="last_name" required="required">
                    <br>
                    <p>Введите ваше погоняло</p>
                    <input type="text" placeholder="nickname" required="required">
                    <br>
                    <p>Введите ваш email</p>
                    <input type="email" placeholder="email" required="required">
                    <br>
                    <p>Введите ваш пароль</p>
                    <input type="password" placeholder="password" required="required">
                    <input type="hidden" name="action" value="registration">
                </div>
                <div class="modal-footer">
                    <button class="btn btn-primary" type="button">Зарегистрироваться</button>
                    <button class="btn btn-danger" type="button" data-dismiss="modal">Пошло оно все нахер</button>
                </div>
            </div>
        </div>
    </form>
</div>
