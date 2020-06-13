-- phpMyAdmin SQL Dump
-- version 4.8.5
-- https://www.phpmyadmin.net/
--
-- 主机： localhost
-- 生成日期： 2020-06-13 21:10:06
-- 服务器版本： 5.7.26
-- PHP 版本： 7.3.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- 数据库： `caozha_comment`
--

-- --------------------------------------------------------

--
-- 表的结构 `cz_comment`
--

CREATE TABLE `cz_comment` (
  `id` int(11) NOT NULL,
  `cmtname` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '评论者昵称',
  `userid` int(11) DEFAULT '0' COMMENT '用户ID,0为游客',
  `userpic` int(4) DEFAULT NULL COMMENT '游客用户的头像',
  `cmtip` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '评论IP',
  `addtime` datetime DEFAULT CURRENT_TIMESTAMP COMMENT '评论时间',
  `cmtid` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '评论ID，标识符，用于关联',
  `catid` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '评论分类ID，标识符，用于关联',
  `parentid` int(11) DEFAULT '0' COMMENT '父ID',
  `arrparentid` text COLLATE utf8mb4_unicode_ci COMMENT '所有父ID，多个中间按顺序用逗号,分隔',
  `cmtcontent` text COLLATE utf8mb4_unicode_ci COMMENT '评论内容',
  `like_num` int(11) DEFAULT '0' COMMENT '点赞次数',
  `bad_num` int(11) DEFAULT '0' COMMENT '被踩次数',
  `ischeck` tinyint(1) DEFAULT '0' COMMENT '是否审核通过，1=通过',
  `ishot` tinyint(1) DEFAULT '0' COMMENT '是否热门评论，1=热门'
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- 转存表中的数据 `cz_comment`
--

INSERT INTO `cz_comment` (`id`, `cmtname`, `userid`, `userpic`, `cmtip`, `addtime`, `cmtid`, `catid`, `parentid`, `arrparentid`, `cmtcontent`, `like_num`, `bad_num`, `ischeck`, `ishot`) VALUES
(1, '草札', 0, 24, '127.0.0.1', '2020-06-05 19:01:20', 'act_1', '0', 0, NULL, '这篇文章不错啊，很精彩！', 43, 8, 1, 1),
(2, '张峰', 0, 15, '127.0.0.1', '2020-06-05 19:01:20', 'act_1', '0', 0, NULL, '使用有什么诀窍吗？', 2, 0, 1, 1),
(3, '小林', 0, 16, '127.0.0.1', '2020-06-05 19:17:26', 'act_1', '0', 1, '1', '对啊，我也觉得还是不错的！[/呲牙]', 0, 1, 1, 0),
(4, '李露', 0, 22, '127.0.0.1', '2020-06-05 19:17:26', 'act_1', '0', 3, '1,3', '你懂个什么啊！[/憨笑]', 1, 0, 1, 0),
(5, '大黄', 0, 33, '127.0.0.1', '2020-06-09 02:29:54', 'act_1', '0', 0, '', '[/哈欠]有点困了', 2, 0, 1, 0),
(6, '测试', 0, 31, '127.0.0.1', '2020-06-09 02:31:13', 'act_1', '0', 5, '5', '[/斜眼笑]那可以去睡了', 0, 1, 1, 0),
(7, '阿达', 0, 17, '127.0.0.1', '2020-06-09 02:49:56', 'act_1', '0', 0, '', '[/酷]大家好', 2, 1, 1, 0),
(8, '', 0, 25, '127.0.0.1', '2020-06-09 11:33:57', 'act_1', '0', 6, '5,6', '睡得比狗晚，起得比鸡早[/偷笑]', 0, 1, 1, 0),
(9, '', 0, 10, '127.0.0.1', '2020-06-09 16:53:33', 'act_1', '0', 0, '', '[/斜眼笑]', 0, 1, 1, 0),
(10, '', 0, 3, '127.0.0.1', '2020-06-09 17:10:58', 'act_1', '0', 0, '', '你好啊[/酷]', 1, 0, 1, 0),
(11, '', 0, 41, '127.0.0.1', '2020-06-09 17:14:22', 'act_1', '0', 0, '', '[img]https://ss3.bdstatic.com/70cFv8Sh_Q1YnxGkpoWK1HF6hhy/it/u=1332800745,3820435792&fm=11&gp=0.jpg[/img]测试提交图片', 0, 1, 1, 0),
(12, '', 0, 28, '127.0.0.1', '2020-06-09 18:25:13', 'act_1', '0', 0, '', '[/呲牙]测试提交脚本。百度网链接\ndocument.write(\\\'\\\');\n', 1, 0, 1, 0),
(13, '大宝', 0, 39, '127.0.0.1', '2020-06-10 17:11:09', 'act_1', '0', 0, '', '[/哈欠]下午好！', 1, 0, 1, 0),
(14, '', 0, 14, '127.0.0.1', '2020-06-10 23:42:20', 'act_1', '0', 0, '', '测试换行。\r\n大家晚上好啊！\r\n[/调皮]', 1, 0, 1, 0),
(16, '菲尔丁', 0, 37, '127.0.0.1', '2020-06-11 12:01:07', 'act_1', '0', 0, '', '如果你把金钱当成上帝，它便会像魔鬼一样折磨你。', 1, 0, 1, 0),
(17, '', 0, 1, '127.0.0.1', '2020-06-11 14:33:22', 'act_1', '0', 0, '', '每个人心里都有一团火，路过的人只能看到烟。但是我觉得总会有一个人看到这团火，然后走过来，陪我一起。', 1, 0, 1, 0);

--
-- 转储表的索引
--

--
-- 表的索引 `cz_comment`
--
ALTER TABLE `cz_comment`
  ADD PRIMARY KEY (`id`);

--
-- 在导出的表使用AUTO_INCREMENT
--

--
-- 使用表AUTO_INCREMENT `cz_comment`
--
ALTER TABLE `cz_comment`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
