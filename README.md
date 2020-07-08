# caozha-comment（原生PHP评论系统）

caozha-comment，一个功能强大的评论系统，采用原生PHP编写，不依赖任何框架，特点：易上手，零门槛，界面清爽极简，极便于二次开发。

可以自动适配电脑、平板和手机等不同客户端。

### 演示地址

[http://caozha.com/git/demo/caozha-admin/public/index/comment/index](http://caozha.com/git/demo/caozha-admin/public/index/comment/index)


### 其他版本

1、此为原生PHP编写的评论系统，如需要thinkphp版本的评论系统，请移步：[caozha-tp-comment](http://gitee.com/caozha/caozha-tp-comment)。

2、此源码不含后台，如需要后台，请参考：[caozha-admin](http://gitee.com/caozha/caozha-admin)，内有评论的完整后台示例。


### 安装使用

**快速安装**

1、PHP版本必须5.0及以上。（已在PHP5和PHP7上测试通过）

2、上传目录/Src/内所有源码到服务器。

3、将/Database/目录里的.sql文件导入到MYSQL数据库。

4、修改评论配置文件\Src\caozha-comment\php\config.php：

（a）参数$mysql_config为配置您的数据库信息。

（b）参数$cmt_config为评论系统基础配置参数，可以设置评论每页的数量、验证码、缓存、是否需要审核、是否允许发图片、滚动自动加载、屏蔽词等等。

（c）参数$cmt_faces为表情包配置，可以把不需要的表情注释掉即可。


**评论引入设置：**

**参考示例文件：\Src\index.php** 

打开此文件，参照，可以为每篇文章或者需要评论的模块添加唯一ID：

<div class="pl-520am" data-cmtid="act_1" data-catid="0" ></div>

上面的data-cmtid是评论标识符ID，data-catid是评论标识符分类ID，这两个参数是用来区分文章等评论的，一般情况下使用data-cmtid就足够了。


### 更新方法

**1.0.0升级到1.0.1的方法：**

1、执行下面MYSQL命令：

ALTER TABLE `cz_comment` CHANGE `addtime` `addtime` DATETIME NULL DEFAULT NULL COMMENT '评论时间';


2、将1.0.1版/SRC/目录的源文件覆盖旧版本，注意修改数据库配置。


### 更新说明

**版本1.0.1，主要更新：**

兼容了MySQL5.6及以下数据库，在MySQL5.5/5.6上测试，可以正常导入和使用。但为了获取更高的性能，依然建议您使用更高版本的MySQL数据库。


### 特别鸣谢

caozha-comment使用了以下开源代码：lgyPl，特别致谢！


### 赞助支持：

支持本程序，请到Gitee和GitHub给我们点Star！

Gitee：https://gitee.com/caozha/caozha-comment

GitHub：https://github.com/cao-zha/caozha-comment

### 关于开发者

开发：草札 www.caozha.com

鸣谢：品络 www.pinluo.com  &ensp;  穷店 www.qiongdian.com


### 界面预览


**评论界面（PC）：**

![输入图片说明](https://images.gitee.com/uploads/images/2020/0611/145140_3e613b5d_7397417.png "16.png")

![输入图片说明](https://images.gitee.com/uploads/images/2020/0611/135914_73eb0310_7397417.png "19.png")

  
  

**评论界面（手机）：**

![输入图片说明](https://images.gitee.com/uploads/images/2020/0612/152711_77208177_7397417.jpeg "5.jpg")

 
![输入图片说明](https://images.gitee.com/uploads/images/2020/0612/152720_633821db_7397417.jpeg "6.jpg")
 

**评论可设置项**

![输入图片说明](https://images.gitee.com/uploads/images/2020/0613/215106_529a1e40_7397417.png "7.png")
