<?php
/**
 * Created by PhpStorm.
 * User: Student
 * Date: 07.11.2017
 * Time: 21:52
 */
?>
<div class="main_container container">
    <div class="row">
        <div class="registrate col-sm-12">
            <div class="registration_buttons">
                <button type="button" class="btn btn-success main_registration" data-toggle="modal" data-target="#modal-1" >Регистрация</button>
                <button type="button"  class="btn btn-success main_login" data-toggle="modal" data-target="#modal-2">Вход</button>                
            </div>
            <br>
            <br>
            <div class="modal" id="modal-1">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title">Регистрация</h4>
                            <button class="close" type="button" data-dismiss="modal">
                                <i class="fa fa-close"></i>
                            </button>
                        </div>
                        <div class="modal-body">
	                        <form method="post">
		                            <label for="first_name">Введите Ваше имя</label>
		                            <div class="form-group"><input class="form-control" type="text" name="first_name" id="first_name" placeholder="Ваше имя" required="required" ></div>
		                            <label for="last_name">Введите Вашу фамилию</label>
		                            <div class="form-group"><input class="form-control" type="text" name="last_name" id="last_name" placeholder="Ваша фамилия" required="required" ></div>
	                            <label for="email">Введите Ваш email</label>
                                <div class="form-group"><input class="form-control" type="email" name="email" id="email" placeholder="Email" required="required" ></div>
                                <label for="password">Введите Ваш пароль</label>
                                <div class="form-group"><input class="form-control" type="password" placeholder="Password" id="password" name="password" required="required" ></div>
                                <div class="modal-footer">
                                    <button class="btn btn-primary" type="submit">Зарегистрироваться</button>
                                    <button class="btn btn-danger" type="button" data-dismiss="modal">Да пошло оно все</button>
                                </div>
	                            <input type="hidden" name="action" value="registration">
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal" id="modal-2">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title">Вход</h4>
                            <button class="close" type="button" data-dismiss="modal">
                                <i class="fa fa-close"></i>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="login_window">
                                <form class="login" method="post">
                                    <label for="email_login">Введите Ваш email</label>
                                    <div class="form-group"><input class="form-control login_input" type="email" name="email_login" id="email_login" placeholder="Email" required="required"/ ></div>
                                    <label for="password_login">Введите Ваш пароль</label>
                                    <div class="form-group"><input class="form-control password_login" type="password" placeholder="Пароль" id="password_login" name="password_login" required="required"/ ></div>
                                    <input class="login btn btn-success" type="submit" name="login_send" value="Вход">
                                    <button type="button" class="btn btn-success main_registration" data-toggle="modal" data-target="#modal-1" data-dismiss="modal">Регистрация</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
