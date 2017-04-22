-- phpMyAdmin SQL Dump
-- version 4.5.2
-- http://www.phpmyadmin.net
--
-- Хост: localhost
-- Время создания: Апр 19 2017 г., 16:26
-- Версия сервера: 10.1.13-MariaDB
-- Версия PHP: 5.6.23

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- База данных: `game`
--

-- --------------------------------------------------------

--
-- Структура таблицы `ban`
--

CREATE TABLE `ban` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `moderator_id` int(11) NOT NULL,
  `time_ban` int(11) NOT NULL,
  `text` varchar(250) NOT NULL,
  `time` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `blacklist`
--

CREATE TABLE `blacklist` (
  `id` int(11) NOT NULL,
  `ua` varchar(250) NOT NULL,
  `ip` varchar(250) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `chat`
--

CREATE TABLE `chat` (
  `id` int(11) NOT NULL,
  `id_user` int(11) NOT NULL,
  `id_kont` int(11) NOT NULL,
  `time` int(11) NOT NULL,
  `text` varchar(1000) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `mail`
--

CREATE TABLE `mail` (
  `id` int(11) NOT NULL,
  `sender` int(11) NOT NULL,
  `given` int(11) NOT NULL,
  `text` varchar(1000) NOT NULL,
  `time` int(11) NOT NULL,
  `read` enum('0','1') NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `mail_kont`
--

CREATE TABLE `mail_kont` (
  `id` int(11) NOT NULL,
  `id_user` int(11) NOT NULL,
  `id_kont` int(11) NOT NULL,
  `time` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `settings`
--

CREATE TABLE `settings` (
  `id` int(1) NOT NULL,
  `site_name` varchar(100) DEFAULT 'Игра',
  `registration` int(1) NOT NULL DEFAULT '1',
  `money_name` varchar(10) NOT NULL DEFAULT 'Золото',
  `money_img` varchar(10) NOT NULL DEFAULT '0',
  `registration_money` int(11) NOT NULL DEFAULT '0',
  `mail_cost` int(11) NOT NULL DEFAULT '1',
  `mail_lvl` int(11) NOT NULL DEFAULT '1',
  `mail_max` int(11) NOT NULL DEFAULT '1000',
  `chat_max` int(11) NOT NULL DEFAULT '1000',
  `password_cost` int(11) NOT NULL DEFAULT '10',
  `login_cost` int(11) NOT NULL DEFAULT '100',
  `admin_help` int(1) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `settings`
--

INSERT INTO `settings` (`id`, `site_name`, `registration`, `money_name`, `money_img`, `registration_money`, `mail_cost`, `mail_lvl`, `mail_max`, `chat_max`, `password_cost`, `login_cost`, `admin_help`) VALUES
(1, 'Game-CMS', 1, 'Золото', 'gold.png', 100, 1, 1, 1000, 1000, 26, 100, 0);

-- --------------------------------------------------------

--
-- Структура таблицы `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL COMMENT 'Идентификатор',
  `login` varchar(20) NOT NULL COMMENT 'Логин',
  `password` varchar(250) NOT NULL COMMENT 'Пароль',
  `access` enum('0','1','2') NOT NULL DEFAULT '0',
  `where` varchar(100) NOT NULL,
  `where_link` varchar(100) NOT NULL,
  `browser` varchar(100) NOT NULL,
  `ip` varchar(50) NOT NULL,
  `last_time` int(11) NOT NULL,
  `online_time` int(11) NOT NULL DEFAULT '0',
  `money` int(11) NOT NULL DEFAULT '0',
  `lvl` int(2) NOT NULL DEFAULT '1',
  `strike` int(11) NOT NULL DEFAULT '0',
  `health` int(11) NOT NULL DEFAULT '0',
  `defend` int(11) NOT NULL DEFAULT '0',
  `design` set('touch','lite') NOT NULL DEFAULT 'touch'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Пользователи';

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `ban`
--
ALTER TABLE `ban`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id` (`id`);

--
-- Индексы таблицы `blacklist`
--
ALTER TABLE `blacklist`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id` (`id`);

--
-- Индексы таблицы `chat`
--
ALTER TABLE `chat`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `mail`
--
ALTER TABLE `mail`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `mail_kont`
--
ALTER TABLE `mail_kont`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `settings`
--
ALTER TABLE `settings`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `ban`
--
ALTER TABLE `ban`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT для таблицы `blacklist`
--
ALTER TABLE `blacklist`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT для таблицы `chat`
--
ALTER TABLE `chat`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT для таблицы `mail`
--
ALTER TABLE `mail`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT для таблицы `mail_kont`
--
ALTER TABLE `mail_kont`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT для таблицы `settings`
--
ALTER TABLE `settings`
  MODIFY `id` int(1) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT для таблицы `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Идентификатор';
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
