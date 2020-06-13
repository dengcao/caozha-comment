<?php
/**
 * 源码名：caozha-comment（功能强大的原生PHP评论系统）
 * Copyright © 2020 草札 （草札官网：http://caozha.com）
 * 基于木兰宽松许可证 2.0（Mulan PSL v2）免费开源，您可以自由复制、修改、分发或用于商业用途，但需保留作者版权等声明。详见开源协议：http://license.coscl.org.cn/MulanPSL2
 * caozha-comment (Software Name) is licensed under Mulan PSL v2. Please refer to: http://license.coscl.org.cn/MulanPSL2
 * Github：https://github.com/cao-zha/caozha-comment   or   Gitee：https://gitee.com/caozha/caozha-comment
 */


//评论配置
$cmt_config = array(
    "cache_time" => 0,//评论列表缓存时间，0为不启用缓存，缓存单位：秒，比如缓存1小时可设置：3600
    "cmt_pagesize" => 5,//每页显示评论数量
    "cmt_hot" => 3,//显示热门评论数量，为0时不显示热门评论
    "is_captcha" => true,//评论是否启用验证码
    "is_check" => false,//评论是否需要审核
    "is_face" => true,//评论是否启用聊天表情
    "is_img" => true,//评论是否允许发图片
    "is_br" => true,//评论内容是否转换换行符
    "is_like" => true,//评论是否可以点赞
    "is_bad" => true,//评论是否可以踩
    "is_reply" => true,//评论是否可以回复
    "is_scroll_load" => 1,//是否滚动加载评论，1为滚动加载，0为不滚动加载
    "bottom_scroll_load" => 50,//底部触发距离，默认是：50
    "bad_word" => "操|你妈|吃屎|你全家|fuck|艹|垃圾|傻逼",//要屏蔽的违规关键词，包含此词将不允许提交。多个词中间用|分隔
    "default_cmtname"=>"匿名游客",//设置默认用户名，当评论者为游客时启用
);


//MySQL配置
$mysql_config=array(
    "username" => "root",//数据库用户名
    "password" => "root",//数据库密码
    "dbname" => "caozha_comment",//数据库名称
    "host" => "localhost",//主机
    "port" => 3306,//端口
    "prefix" => "cz_",//表前缀
);


//评论的表情包
$cmt_faces = array(
    array("微笑", "wx.gif"),
    array("鄙视", "bs.gif"),
    array("闭嘴", "bz.gif"),
    array("吃惊", "cj.gif"),
    array("酷", "cool.gif"),
    array("呲牙", "cy.gif"),
    array("鼓掌", "gz.gif"),
    array("流汗", "han.gif"),
    array("哈欠", "hq.gif"),
    array("害羞", "hx.gif"),
    array("可爱", "ka.gif"),
    array("泪", "lei.gif"),
    array("难过", "ng.gif"),
    //array("高手", "q.gif"),
    array("示爱", "sa.gif"),
    array("衰", "shuai.gif"),
    array("憨笑", "hanx.gif"),
    array("吐血", "tux.gif"),
    array("偷笑", "tx.gif"),
    array("斜眼笑", "xyx.gif"),
    array("笑哭", "xk.gif"),
    array("色", "se.gif"),
    array("晕", "y.gif"),
    array("折磨", "zm.gif"),
    array("坏笑", "67.gif"),
    array("撇嘴", "2.gif"),
    array("睡", "8.gif"),
    array("尴尬", "10.gif"),
    array("发怒", "11.gif"),
    array("调皮", "12.gif"),
    array("吐", "18.gif"),
    array("白眼", "21.gif"),
    array("困", "24.gif"),
    array("惊恐", "25.gif"),
    array("大兵", "28.gif"),
    array("奋斗", "29.gif"),
    array("疑问", "30.gif"),
    array("嘘", "31.gif"),
    array("敲打", "35.gif"),
    array("再见", "36.gif"),
    array("猪头", "40.gif"),
    array("抱抱", "41.gif"),
    array("蛋糕", "42.gif"),
    //array("闪电", "43.gif"),
    array("炸弹", "44.gif"),
    //array("刀", "45.gif"),
    array("便便", "47.gif"),
    array("咖啡", "48.gif"),
    array("饭", "49.gif"),
    array("玫瑰", "50.gif"),
    array("凋谢", "51.gif"),
    array("爱心", "52.gif"),
    array("心碎", "53.gif"),
    //array("太阳", "55.gif"),
    //array("月亮", "56.gif"),
    array("强", "57.gif"),
    array("弱", "58.gif"),
    array("握手", "59.gif"),
    array("抠鼻", "64.gif"),
    array("委屈", "72.gif"),
    array("阴险", "74.gif"),
    array("亲亲", "75.gif"),
    array("可怜", "77.gif"),
    array("菜刀", "78.gif"),
    array("啤酒", "79.gif"),
    array("抱拳", "84.gif"),
    array("勾引", "85.gif"),
    //array("OK", "90.gif"),
    array("蜡烛", "102.gif"),
    array("鞭炮", "126.gif"),
    array("红包", "105.gif"),
    array("福", "125.gif"),
);


$caozha_static = get_cz_path()."caozha-comment/"; //评论安装目录，一般无需修改