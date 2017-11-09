-- phpMyAdmin SQL Dump
-- version 4.7.4
-- https://www.phpmyadmin.net/
--
-- Хост: 127.0.0.1:3306
-- Время создания: Окт 30 2017 г., 18:32
-- Версия сервера: 5.7.19
-- Версия PHP: 5.6.31

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- База данных: `step_blog2`
--

-- --------------------------------------------------------

--
-- Структура таблицы `posts`
--

DROP TABLE IF EXISTS `posts`;
CREATE TABLE IF NOT EXISTS `posts` (
  `ID` int(21) UNSIGNED NOT NULL AUTO_INCREMENT,
  `post_date` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `post_title` text NOT NULL,
  `post_name` text NOT NULL,
  `post_content` text NOT NULL,
  `post_type` varchar(255) NOT NULL,
  UNIQUE KEY `ID` (`ID`)
) ENGINE=InnoDB AUTO_INCREMENT=77 DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `posts`
--

INSERT INTO `posts` (`ID`, `post_date`, `post_title`, `post_name`, `post_content`, `post_type`) VALUES
(1, '2017-09-26 21:16:24', 'Привет, мир!', 'main', 'Привет, это текст моей первой публикации! Я молодец!', 'post'),
(2, '2017-09-26 21:18:55', 'Second super cool post title', 'about', 'Text of second post.', 'post'),
(3, '2017-10-10 18:07:00', 'Новая новость', 'new_news', 'Какой-то текст', 'post'),
(4, '2017-10-10 18:43:00', 'Главная', 'main', '', 'page'),
(5, '2017-10-10 18:45:00', 'Контакты', 'contacts', '+7 903 123 45 67, звоните!', 'page'),
(6, '2017-10-10 19:19:00', 'Задача', 'task', 'Сделать с нуля:\r\n<ul>\r\n<li>Создать меню с двумя пунктами;</li>\r\n<li>При клике по первому пункту должна выводиться страница со всеми записями из таблицы posts;</li>\r\n<li>При клике по второму пункту, должна выводиться только одна запись из таблицы;</li>\r\n<li>Какую именно запись выводить - определяет параметр в адресной строке;</li>\r\n<li>Если записи, соответствующей указанному параметру нет, выводить содержимое файла 404.php. </li>\r\n</ul>', 'post'),
(7, '2017-10-10 20:32:00', 'Задача до 28-го октября', 'task2', '<ul>\r\n<li>Создать таблицу users;</li>\r\n<li>В таблицу добавить следующие столбцы:\r\n<ul>\r\n<li>user_id;</li>\r\n<li>user_name;</li>\r\n<li>user_password;</li>\r\n<li>first_name;</li>\r\n<li>last_name;</li>\r\n</ul>\r\n<li>\r\n<li>Добавить пользователя admin;</li>\r\n<li>Сделать проверку - залогинен ли пользователь(проверка существования cookies с данными о логине и пароле);</li>\r\n<li>Выводить форму публикации только зарегистрированным пользователям;</li>\r\n<li>Если пользователь не авторизован(нет куки, соответствующей записи в бд), выводить форму авторизации;</li>\r\n<li>Сделать возможность удалить запись;</li>\r\n<li>Сделать возможность редактировать запись - при редактировании данные для изменения должы отображаться в той же форме, которая используется для создания записи;</li>\r\n</ul>', 'post');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
