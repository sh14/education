<?php
/**
 * Created by PhpStorm.
 * User: Student
 * Date: 07.11.2017
 * Time: 21:52
 */?>
<div class="main_container container">
    <div class="row">
        <div class="registrate col-sm-12">
            <div class="registration_buttons">
                <button type="button" class="btn btn_main btn-success main_registration" data-toggle="modal" data-target="#modal-1" >Регистрация</button>
                <button type="button" class="btn btn_main btn-success main_login" data-toggle="modal" data-target="#modal-2">Вход</button>
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
                                <label>Введите Ваше имя</label>
                                <div class="form-group"><input class="form-control" type="text" placeholder="First name" required="required" ></div>
                                <label>Введите Вашу фамилию</label>
                                <div class="form-group"><input class="form-control" type="text" placeholder="Last name" required="required" ></div>
                                <label>Введите Ваш ник</label>
                                <div class="form-group"><input class="form-control" type="text" placeholder="Nickname" required="required" ></div>
                                <label>Введите Ваш email</label>
                                <div class="form-group"><input class="form-control" type="email" placeholder="Email" required="required" ></div>
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
                                <form class="login">
                                    <div class="form-group"><input class="form-control" class="login_input" type="email" placeholder="Email" required="required"/ ></div>
                                    <div class="form-group"><input class="form-control" type="password" class="password_input" placeholder="Пароль" required="required"/ ></div>
                                    <button class="login btn btn-success" type="submit">Вход</button>
                                    <button type="button" class="btn btn_main btn-success main_registration" data-toggle="modal" data-target="#modal-1" data-dismiss="modal">Регистрация</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
