<?php
/**
 * Created by PhpStorm.
 * User: Student
 * Date: 07.11.2017
 * Time: 21:11
 */?>
<div class="profile">
	<div class="avatar"></div>
	<form class="user_info" action="?event=edit_user_info">
        <div class="form-group">
            <label for="first_name">Введите ваше имя</label>
            <input class="first_name"/>
        </div>
        <div class="form-group">
            <label for="last_name">Введите вашу фамилию</label>
            <input class="last_name"/>
        </div>
        <div class="form-group">
            <label for="login">Введите ваш логин</label>
            <input class="login"/>
        </div>
        <div class="form-group">
            <label for="email">Введите ваш email</label>
            <input class="email"/>
        </div>
        <div class="form-group">
            <button type="submit" class="change_user_info">Изменить данные пользователя</button>
        </div>
	</form>
    <?php echo $_SERVER['REQUEST_URI']; ?>
	<form class="user_password" action="?event=edit_user_password">
        <div class="form-group">
            <label for="old_password">Введите старый пароль</label>
            <input type="password" name="old_password" class="old_password" />
        </div>
        <div class="form-group">
            <label for="new_password">Введите новый пароль</label>
            <input type="password" name="new_password" class="new_password"/>
        </div>
        <div class="form-group">
            <label for="confirm">Введите новый пароль ещё раз</label>
            <input type="password" name="confirm" class="confirm"/>
        </div>
        <div class="form-group">
		    <button type="submit" class="change_password">Сменить пароль</button>
        </div>
	</form>
</div>

