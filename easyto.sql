-- phpMyAdmin SQL Dump
-- version 4.5.2
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: 2018-10-26 06:17:27
-- 服务器版本： 5.7.9
-- PHP Version: 5.6.16

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `easyto`
--

-- --------------------------------------------------------

--
-- 表的结构 `easyto_about`
--

DROP TABLE IF EXISTS `easyto_about`;
CREATE TABLE IF NOT EXISTS `easyto_about` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(200) NOT NULL,
  `content` varchar(255) NOT NULL,
  `create_time` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='关于表';

-- --------------------------------------------------------

--
-- 表的结构 `easyto_admin`
--

DROP TABLE IF EXISTS `easyto_admin`;
CREATE TABLE IF NOT EXISTS `easyto_admin` (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL,
  `password` varchar(32) NOT NULL,
  `email` varchar(255) NOT NULL,
  `permissions` tinyint(1) UNSIGNED NOT NULL DEFAULT '1',
  `status` tinyint(1) UNSIGNED NOT NULL DEFAULT '1',
  `create_time` int(11) DEFAULT NULL,
  `update_time` int(11) DEFAULT NULL,
  `delete_time` int(11) DEFAULT NULL,
  `login_time` int(11) UNSIGNED DEFAULT NULL,
  `login_count` int(11) NOT NULL DEFAULT '0',
  `is_delete` tinyint(1) UNSIGNED NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COMMENT='易起来管理员';

--
-- 转存表中的数据 `easyto_admin`
--

INSERT INTO `easyto_admin` (`id`, `username`, `password`, `email`, `permissions`, `status`, `create_time`, `update_time`, `delete_time`, `login_time`, `login_count`, `is_delete`) VALUES
(1, 'admin', 'f0b216163c9c722e150fe6aa30086488', 'admin@qq.com', 0, 1, 1540534517, 1540534517, NULL, NULL, 0, 0);

-- --------------------------------------------------------

--
-- 表的结构 `easyto_advice`
--

DROP TABLE IF EXISTS `easyto_advice`;
CREATE TABLE IF NOT EXISTS `easyto_advice` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `advice` varchar(255) NOT NULL,
  `create_time` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='反馈信息表';

-- --------------------------------------------------------

--
-- 表的结构 `easyto_collect`
--

DROP TABLE IF EXISTS `easyto_collect`;
CREATE TABLE IF NOT EXISTS `easyto_collect` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `goods_id` int(11) NOT NULL,
  `is_collect` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='用户收藏表';

-- --------------------------------------------------------

--
-- 表的结构 `easyto_goods`
--

DROP TABLE IF EXISTS `easyto_goods`;
CREATE TABLE IF NOT EXISTS `easyto_goods` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `goods_title` varchar(100) NOT NULL,
  `seller_id` int(11) NOT NULL,
  `goods_prom_price` varchar(100) NOT NULL,
  `goods_abstract` varchar(255) NOT NULL,
  `goods_img` varchar(200) DEFAULT NULL,
  `goods_img2` varchar(200) DEFAULT NULL,
  `goods_img3` varchar(200) DEFAULT NULL,
  `goods_category` tinyint(1) NOT NULL DEFAULT '0',
  `create_time` int(11) NOT NULL,
  `is_buy` tinyint(1) NOT NULL DEFAULT '0',
  `is_audit` tinyint(1) NOT NULL DEFAULT '0',
  `look` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='商品表';

-- --------------------------------------------------------

--
-- 表的结构 `easyto_lookandtalk`
--

DROP TABLE IF EXISTS `easyto_lookandtalk`;
CREATE TABLE IF NOT EXISTS `easyto_lookandtalk` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `goods_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `talk` varchar(255) DEFAULT NULL,
  `create_time` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='浏览和评论表';

-- --------------------------------------------------------

--
-- 表的结构 `easyto_praiseandtread`
--

DROP TABLE IF EXISTS `easyto_praiseandtread`;
CREATE TABLE IF NOT EXISTS `easyto_praiseandtread` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `say_id` int(11) NOT NULL,
  `is_praise` tinyint(1) NOT NULL DEFAULT '0',
  `is_tread` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='赞和踩表';

-- --------------------------------------------------------

--
-- 表的结构 `easyto_say`
--

DROP TABLE IF EXISTS `easyto_say`;
CREATE TABLE IF NOT EXISTS `easyto_say` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `say` varchar(255) NOT NULL,
  `create_time` int(11) DEFAULT NULL,
  `praise` int(11) NOT NULL DEFAULT '0',
  `tread` int(11) NOT NULL DEFAULT '0',
  `is_delete` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='书评表';

-- --------------------------------------------------------

--
-- 表的结构 `easyto_slide`
--

DROP TABLE IF EXISTS `easyto_slide`;
CREATE TABLE IF NOT EXISTS `easyto_slide` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `slide_title` varchar(50) NOT NULL,
  `slide_url` varchar(200) NOT NULL,
  `slide_order` int(2) NOT NULL,
  `is_show` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='幻灯片表';

-- --------------------------------------------------------

--
-- 表的结构 `easyto_user`
--

DROP TABLE IF EXISTS `easyto_user`;
CREATE TABLE IF NOT EXISTS `easyto_user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `compellation` varchar(20) CHARACTER SET utf8 DEFAULT NULL,
  `student_id` varchar(20) CHARACTER SET utf8 DEFAULT NULL,
  `nick_name` varchar(50) NOT NULL,
  `avatar_url` varchar(200) CHARACTER SET utf8 NOT NULL,
  `open_id` varchar(64) CHARACTER SET utf8 NOT NULL,
  `session_key` varchar(50) CHARACTER SET utf8 NOT NULL,
  `thr_session` varchar(32) CHARACTER SET utf8 NOT NULL,
  `linkman` varchar(11) CHARACTER SET utf8 DEFAULT NULL,
  `wechat_number` varchar(30) CHARACTER SET utf8 DEFAULT NULL,
  `phone_number` varchar(30) CHARACTER SET utf8 DEFAULT NULL,
  `is_approve` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COMMENT='用户表';

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
