
-- phpMyAdmin SQL Dump
-- version 4.2.5
-- http://www.phpmyadmin.net
--
-- Хост: localhost
-- Время создания: Авг 14 2018 г., 00:23
-- Версия сервера: 5.7.23-0ubuntu0.16.04.1
-- Версия PHP: 7.0.30-0ubuntu0.16.04.1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- База данных: `mokriy_nos`
--
CREATE DATABASE IF NOT EXISTS `mokriy_nos` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;
USE `mokriy_nos`;

GRANT ALL PRIVILEGES ON `mokriy_nos`.* TO `mokriy_nos`@`localhost` IDENTIFIED BY 'mokriy_nos';

-- --------------------------------------------------------

--
-- Структура таблицы `category`
--

DROP TABLE IF EXISTS `category`;
CREATE TABLE IF NOT EXISTS `category` (
`id` int(11) NOT NULL COMMENT 'ID категории',
  `parent_id` int(11) DEFAULT NULL COMMENT 'ID родительской категории',
  `name` varchar(255) NOT NULL COMMENT 'Название категории'
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=12 ;


--
-- Структура таблицы `comment`
--

DROP TABLE IF EXISTS `comment`;
CREATE TABLE IF NOT EXISTS `comment` (
`id` int(11) NOT NULL COMMENT 'ID комментария',
  `news_id` int(11) NOT NULL COMMENT 'ID новости',
  `name` varchar(255) NOT NULL COMMENT 'Имя пользователя',
  `text` text NOT NULL COMMENT 'Текст комментария',
  `date` datetime DEFAULT CURRENT_TIMESTAMP COMMENT 'Дата создания комментария'
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=654 ;


--
-- Структура таблицы `news`
--

DROP TABLE IF EXISTS `news`;
CREATE TABLE IF NOT EXISTS `news` (
`id` int(11) NOT NULL COMMENT 'ID',
  `category_id` int(11) NOT NULL COMMENT 'ID категории',
  `title` varchar(255) NOT NULL COMMENT 'Заголовок статьи',
  `short_text` varchar(511) NOT NULL COMMENT 'Краткий анонс статьи',
  `text` text NOT NULL COMMENT 'Полный текст статьи',
  `is_active` tinyint(4) NOT NULL COMMENT 'Флаг активной статьи',
  `alias` varchar(255) NOT NULL COMMENT 'URL адрес страницы',
  `date` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Дата и время добавления статьи'
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=777 ;


--
-- Структура таблицы `user`
--

DROP TABLE IF EXISTS `user`;
CREATE TABLE IF NOT EXISTS `user` (
`id` int(11) NOT NULL COMMENT 'ID пользователя',
  `username` varchar(255) NOT NULL COMMENT 'Имя пользователя',
  `password` varchar(255) NOT NULL COMMENT 'Пароль пользователя',
  `auth_key` varchar(255) DEFAULT NULL COMMENT 'Кука для автолоина'
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- Дамп данных таблицы `user`
--

INSERT INTO `user` (`id`, `username`, `password`, `auth_key`) VALUES
(1, 'login', '$2y$13$iAShV3YDz6UouR282gSOTe2DrKAzAoTbWNKO8TKGb1FIUfBAxcUly', NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `category`
--
ALTER TABLE `category`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `comment`
--
ALTER TABLE `comment`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `news`
--
ALTER TABLE `news`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
 ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `category`
--
ALTER TABLE `category`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID категории',AUTO_INCREMENT=12;
--
-- AUTO_INCREMENT for table `comment`
--
ALTER TABLE `comment`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID комментария',AUTO_INCREMENT=654;
--
-- AUTO_INCREMENT for table `news`
--
ALTER TABLE `news`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID',AUTO_INCREMENT=777;
--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID пользователя',AUTO_INCREMENT=2;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
