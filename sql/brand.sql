-- phpMyAdmin SQL Dump
-- version 4.8.5
-- https://www.phpmyadmin.net/
--
-- 主机： localhost
-- 生成日期： 2024-05-23 23:04:37
-- 服务器版本： 5.7.26
-- PHP 版本： 7.0.9

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- 数据库： `www2`
--

-- --------------------------------------------------------

--
-- 表的结构 `blog`
--

CREATE TABLE `blog` (
  `id` int(11) NOT NULL,
  `author` int(11) DEFAULT NULL,
  `type` int(11) DEFAULT NULL,
  `posted_date` date DEFAULT NULL,
  `posted_time` time DEFAULT NULL,
  `title` varchar(255) DEFAULT NULL,
  `content` text
) ENGINE=MyISAM DEFAULT CHARSET=gbk;

--
-- 转存表中的数据 `blog`
--

INSERT INTO `blog` (`id`, `author`, `type`, `posted_date`, `posted_time`, `title`, `content`) VALUES
(23, 1, 8, '2009-02-22', '15:57:00', '广州嘻唰唰儿童用品有限公司', '</pictures/shop29.gif|http://www.muyingjie.com/item-29.html>'),
(22, 1, 0, '2009-02-22', '00:36:00', '内蒙古蒙牛乳业股份有限公司', '</pictures/shop1.gif|http://www.muyingjie.com/item-1.html>'),
(24, 1, 0, '2009-02-24', '00:23:00', '澳优乳品(湖南)有限公司', '</pictures/shop8.gif|http://www.muyingjie.com/item-8.html>'),
(25, 1, 0, '2009-03-22', '03:37:00', '多美滋(中国)婴幼儿食品有限公司', '</pictures/shop14.gif|http://www.muyingjie.com/item-14.html>'),
(31, 1, 1, '2011-08-13', '04:46:00', '广州妈咪爱宝宝贸易有限公司', '</pictures/shop140.gif|http://www.mami2baobao.com/>'),
(28, 1, 3, '2009-04-18', '23:58:00', '香港咿咿呀呀国际集团有限公司', '</pictures/shop51.gif|http://www.muyingjie.com/item-51.html>'),
(29, 1, 3, '2009-05-24', '02:00:00', '金佰利(中国)有限公司(好奇品牌)', '</pictures/shop50.gif|http://www.muyingjie.com/item-50.html>'),
(34, 1, 4, '2011-08-20', '09:43:00', '上海齐悦家居用品有限公司', '</pictures/shop121.gif|http://www.muyingjie.com/item-121.html>'),
(33, 1, 1, '2011-08-20', '09:18:00', '上海博士蛙国际控股有限公司', '</pictures/shop355.gif|http://www.muyingjie.com/item-355.html>'),
(35, 1, 0, '2011-08-26', '08:08:00', '上海纽贝滋生物科技有限公司', '</pictures/shop440.gif|http://www.muyingjie.com/item-440.html>'),
(36, 1, 8, '2011-08-31', '17:31:00', '北京华恩投资有限公司', '</pictures/shop418.gif|http://www.muyingjie.com/item-418.html>'),
(37, 1, 0, '2011-09-05', '12:48:00', '天津市亚亿实业有限公司', '</pictures/shop248.gif|http://www.muyingjie.com/item-248.html>'),
(38, 1, 3, '2011-09-06', '07:25:00', '帮宝适(中国)股份有限公司', '</pictures/shop9.gif|http://www.muyingjie.com/item-9.html>'),
(39, 1, 6, '2011-09-14', '18:20:00', '北京歪歪兔教育科技有限公司', '</pictures/shop131.gif|http://www.muyingjie.com/item-131.html>'),
(40, 1, 1, '2012-04-21', '06:18:00', '广州丹尼熊儿童服饰有限公司', '</pictures/shop88.gif|http://www.muyingjie.com/item-88.html>');

-- --------------------------------------------------------

--
-- 表的结构 `blog_setup`
--

CREATE TABLE `blog_setup` (
  `id` int(11) NOT NULL DEFAULT '0',
  `name` text,
  `color_light` varchar(255) DEFAULT NULL,
  `color_dark` varchar(255) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=gbk;

--
-- 转存表中的数据 `blog_setup`
--

INSERT INTO `blog_setup` (`id`, `name`, `color_light`, `color_dark`) VALUES
(0, '营养健康', '#8DAFD5', '#5B85AF'),
(1, '服装服饰', '#A5C8B1', '#749882'),
(2, '婴童玩乐', '#ECBEB9', '#C5887B'),
(3, '洗护喂养', '#C3AACB', '#9480A5'),
(4, '寝居家具', '#F1CF80', '#D39F6B'),
(5, '安全出行', '#D99300', '#996600'),
(6, '电子商务', '#C3AACB', '#9480A5'),
(7, '孕婴机构', '#3AAF3A', '#2D882D'),
(8, '投资创业', '#7373D0', '#333399');

-- --------------------------------------------------------

--
-- 表的结构 `blog_users`
--

CREATE TABLE `blog_users` (
  `id` int(11) NOT NULL,
  `email` varchar(60) CHARACTER SET utf8 NOT NULL,
  `login` varchar(24) CHARACTER SET utf8 NOT NULL,
  `password` varchar(32) CHARACTER SET utf8 NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=gbk;

--
-- 转存表中的数据 `blog_users`
--

INSERT INTO `blog_users` (`id`, `email`, `login`, `password`) VALUES
(1, '@', '', '5866c4ab258a8ef25e809153cc4faf25');

--
-- 转储表的索引
--

--
-- 表的索引 `blog`
--
ALTER TABLE `blog`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id` (`id`),
  ADD KEY `id_2` (`id`);

--
-- 表的索引 `blog_setup`
--
ALTER TABLE `blog_setup`
  ADD PRIMARY KEY (`id`);

--
-- 表的索引 `blog_users`
--
ALTER TABLE `blog_users`
  ADD PRIMARY KEY (`id`);

--
-- 在导出的表使用AUTO_INCREMENT
--

--
-- 使用表AUTO_INCREMENT `blog`
--
ALTER TABLE `blog`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=41;

--
-- 使用表AUTO_INCREMENT `blog_users`
--
ALTER TABLE `blog_users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
