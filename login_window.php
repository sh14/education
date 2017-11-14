<?php
/**
 * Created by PhpStorm.
 * User: Student
 * Date: 07.11.2017
 * Time: 20:55
 */ ?>
<div class="container">
    <div class="row">
        <div class="login_window col-md-6">
            <form class="login form-horizontal" action="autorization.php" method="post">
                <div class="col-md-3">
                    <div class="form-group">
                        <input class="login_input form-control" id="inputEmail3" name="login" type="text" placeholder="Логин"/>
                    </div>
                    <div class="form-group">
                        <input type="password" class="password_input form-control" id="inputPassword3" name="password"
                               placeholder="Пароль"/>
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-sm-10">
                        <button class="submit btn btn-default" type="submit">Вход</button>
                        <button class="registration btn btn-default">Регистрация</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>