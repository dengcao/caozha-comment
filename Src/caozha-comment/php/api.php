<?php
/**
 * 源码名：caozha-comment（功能强大的原生PHP评论系统）
 * Copyright © 2020 草札 （草札官网：http://caozha.com）
 * 基于木兰宽松许可证 2.0（Mulan PSL v2）免费开源，您可以自由复制、修改、分发或用于商业用途，但需保留作者版权等声明。详见开源协议：http://license.coscl.org.cn/MulanPSL2
 * caozha-comment (Software Name) is licensed under Mulan PSL v2. Please refer to: http://license.coscl.org.cn/MulanPSL2
 * Github：https://github.com/cao-zha/caozha-comment   or   Gitee：https://gitee.com/caozha/caozha-comment
 */

include_once "common.php";
$action=filter_sql($_GET["action"]);

if($action){
    $caozha=new caozha_comment();
    $caozha->$action();
}