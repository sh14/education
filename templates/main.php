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
                                <p>Введите Ваше имя</p>
                                <input type="text" placeholder="First name" required="required">
                                <br>
                                <br>
                                <p>Введите Вашу фамилию</p>
                                <input type="text" placeholder="Last name" required="required">
                                <br>
                                <br>
                                <p>Введите Ваш ник</p>
                                <input type="text" placeholder="Nickname" required="required">
                                <br>
                                <br>
                                <p>Введите Ваш email</p>
                                <input type="email" placeholder="Email" required="required">

                                <p>Введите Ваш пароль</p>
                                <input type="password" placeholder="Password" required="required">
                                <div class="modal-footer">
                                    <button class="btn btn-primary" type="submit">Зарегистрироваться</button>
                                    <button class="btn btn-danger" type="button" data-dismiss="modal">Да пошло оно все</button>
                                </div>
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
                                    <input class="login_input" type="email" placeholder="Email" required="required"/>
                                    <input type="password" class="password_input" placeholder="Пароль" required="required"/>
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