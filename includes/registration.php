<?php
/**
 * Created by PhpStorm.
 * User: Jamay
 * Date: 14.11.2017
 * Time: 19:43
 */
function registration()
{
    if (isset($_POST['email'])&&isset($_POST['password'])&&isset($_POST['action'] =='registration')) {
        $err = [];

        if (!preg_match("/^[a-zA-Z0-9]+$/", $_POST['email'])) {
            $err = "Email может состоять только и букв английского языка";
        }

        if (strlen($_POST['email']) < 7 or strlen($_POST['email']) > 255) {
            $err = "Email не должен быть меньше 7 символов и не больше 255";
        }

        $query = do_query("SELECT count(*) FROM users WHERE email='{$_POST['email']}'");

        if (mysqli_num_rows($query) > 0) {

            $err[] = "Пользователь с таким email существует";
        }

        if (count($err) == 0) {

            $email = $_POST['email'];

            $password = md5(md5(trim($_POST['password'])));

            do_query("INSERT INTO users SET email='" . $email . "', password='" . $password . "'");
            header("location:" . get_root_url());
        } else {
            print "<strong>При регистрации произошли следующие ошибки:</strong><br>";
            foreach ($err as $error) {
                print $error . "<br>";
            }
        }
    }
}
add_action('init', 'registration');