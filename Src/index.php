<?php
/**
 * 源码名：caozha-comment（功能强大的原生PHP评论系统）
 * Copyright © 2020 草札 （草札官网：http://caozha.com）
 * 基于木兰宽松许可证 2.0（Mulan PSL v2）免费开源，您可以自由复制、修改、分发或用于商业用途，但需保留作者版权等声明。详见开源协议：http://license.coscl.org.cn/MulanPSL2
 * caozha-comment (Software Name) is licensed under Mulan PSL v2. Please refer to: http://license.coscl.org.cn/MulanPSL2
 * Github：https://github.com/cao-zha/caozha-comment   or   Gitee：https://gitee.com/caozha/caozha-comment
 */

include_once "caozha-comment/php/common.php";
?><!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0,user-scalable=no">
    <title>评论 - <?=get_cz_name()?> <?=get_cz_version()?></title>
</head>
<body style="text-align: center">

<div style="max-width: 1110px;margin:8px auto 0px auto;text-align: left;padding: 0px;">
    <!-- 评论 开始 -->
    <script>
        cz_cmt_template="<?=$caozha_static?>php/api.php?action=template";//模板页接口
        cz_cmt_list="<?=$caozha_static?>php/api.php?action=get_list";//读取评论列表接口
        cz_cmt_doaction="<?=$caozha_static?>php/api.php?action=doaction";//发布评论接口
        cz_cmt_dolike="<?=$caozha_static?>php/api.php?action=dolike";//点赞或踩接口
        cz_cmt_userinfo="<?=$caozha_static?>php/api.php?action=userinfo";//获取会员信息
    </script>
    <div class="pinglun">
        <div class="pl-520am" data-cmtid="act_1" data-catid="0" data-pagesize="<?=$cmt_config["cmt_pagesize"]?>" data-scrollload="<?=$cmt_config["is_scroll_load"]?>" data-scrollbottom="<?=$cmt_config["bottom_scroll_load"]?>" data-showhot="<?=$cmt_config["cmt_hot"]?>" data-hotpagesize="<?=$cmt_config["cmt_hot"]?>"></div>
        <script type="text/javascript" src="<?=$caozha_static?>api.js"></script>
    </div>
    <!-- 评论 结束 -->
</div>

</body>
</html>