<?php
/**
 * 源码名：caozha-comment（功能强大的原生PHP评论系统）
 * Copyright © 2020 草札 （草札官网：http://caozha.com）
 * 基于木兰宽松许可证 2.0（Mulan PSL v2）免费开源，您可以自由复制、修改、分发或用于商业用途，但需保留作者版权等声明。详见开源协议：http://license.coscl.org.cn/MulanPSL2
 * caozha-comment (Software Name) is licensed under Mulan PSL v2. Please refer to: http://license.coscl.org.cn/MulanPSL2
 * Github：https://github.com/cao-zha/caozha-comment   or   Gitee：https://gitee.com/caozha/caozha-comment
 */

class caozha_comment
{
    protected $cmt_config;
    protected $cmt_faces = array();
    protected $cmt_url;//评论插件根URL
    protected $conn;

    function __construct()
    {
        global $cmt_config,$cmt_faces;
        $this->cmt_config=$cmt_config;
        $this->cmt_url=str_ireplace("/php/","/",get_cz_path());
        $this->cmt_faces = $cmt_faces;
    }

    function __destruct() {}

    /**
     * 建立mysqli数据库连接
     */
    public function mysqli_conn(){
        global $mysql_config;
        $this->conn = mysqli_connect($mysql_config["host"],$mysql_config["username"],$mysql_config["password"],$mysql_config["dbname"],$mysql_config["port"]) or die("数据库连接错误");
        mysqli_set_charset($this->conn,'utf8');
    }

    /**
     * 关闭连接
     */
    public function mysqli_close(){
        mysqli_close($this->conn);
    }

    public function template()
    {//评论模板
        include "template.php";
    }

    public function captcha()
    {
        include "captcha.php";
    }


    public function get_list(){//评论列表，JSON

        global $mysql_config;
        $action = filter_sql_arr($_POST);//过滤注入

        if ($this->cmt_config['cache_time'] > 0) {//开启缓存
            $cache_name = md5(implode("-", $action));
            include "cache.php";
            $cache=new FileCache("cache");
            $list_cache = $cache->get($cache_name);//优先从缓存读取
            if ($list_cache) {
                return json($list_cache);
            }
        }

        $this->mysqli_conn();

        $page = isset($action["pageIndex"]) ? $action["pageIndex"] : 1;
        if (!is_numeric($page)) {
            $page = 1;
        }
        $limit = $action["pageSize"];
        if (!is_numeric($limit)) {
            $limit = $this->cmt_config['cmt_pagesize'];//默认显示数目
        }
        $query = $action["query"];//all,hot
        $cmtid = $action["cmtid"];
        $catid = $action["catid"];

        $limit_sql=" limit ".($limit*($page-1)).",".$limit;
        $list_data = array();

        if ($query == "hot") {//获取热门评论
            $sql="select * from `".$mysql_config["prefix"]."comment` where cmtid='".$cmtid."' and catid='".$catid."' and ischeck=1 and ishot=1 order by addtime desc ";
            $sql_query=mysqli_query($this->conn,$sql.$limit_sql);
            while($comment_list=mysqli_fetch_array($sql_query)){
                $list_data[] = $comment_list;
            }
        } else {
            $sql="select * from `".$mysql_config["prefix"]."comment` where cmtid='".$cmtid."' and catid='".$catid."' and ischeck=1 order by addtime desc ";
            $sql_query=mysqli_query($this->conn,$sql.$limit_sql);
            while($comment_list=mysqli_fetch_array($sql_query)){
                $list_data[] = $comment_list;
            }
        }

        $total = mysqli_result(mysqli_query($this->conn,str_ireplace("*"," count(*) as total ",$sql)),0,'total'); //查询出结果的数量
        $like_num = mysqli_result(mysqli_query($this->conn,"SELECT SUM( `like_num`) as total FROM `".$mysql_config["prefix"]."comment` where cmtid='".$cmtid."' and catid='".$catid."' and ischeck=1"),0,'total'); //获取点赞的总数
        $bad_num = mysqli_result(mysqli_query($this->conn,"SELECT SUM( `bad_num`) as total FROM `".$mysql_config["prefix"]."comment` where cmtid='".$cmtid."' and catid='".$catid."' and ischeck=1"),0,'total'); //获取踩的总数
        $join_num = $like_num + $bad_num;


        $comments = array();

        if (!empty($list_data)) {
            foreach ($list_data as $r) {

                $saytext = trim($r['cmtcontent']);
                if ($this->cmt_config['is_br']) {
                    $saytext = nl2br($saytext);
                }
                $arrparentid = trim($r['arrparentid']);

                $parent_context = "";//初始化，获取父评论的内容
                if ($arrparentid) {
                    $parentid_arr = explode(",", $arrparentid);
                    foreach ($parentid_arr as $p_value) {

                        $p_sql="select * from `".$mysql_config["prefix"]."comment` where id=".$p_value;
                        $p_query=mysqli_query($this->conn,$p_sql);
                        $p_comment=array();
                        while($p_c=mysqli_fetch_array($p_query)){
                            $p_comment=$p_c;
                        }

                        if (empty($p_comment)) {
                            $parent_context = '<div class="ecomment"><span class="ecommentauthor">引用 @：</span><div class="ecommenttext">' . $parent_context . '该评论已被删除</div></div>';
                        } else {
                            $parent_cmtname=$p_comment["cmtname"];
                            if(!$parent_cmtname){$parent_cmtname=$this->cmt_config['default_cmtname'];}
                            if ($p_comment["ischeck"] == 1) {
                                $parent_context = '<div class="ecomment"><span class="ecommentauthor">引用 @' . $parent_cmtname . '：</span><div class="ecommenttext">' . $parent_context . $p_comment["cmtcontent"] . '</div></div>';
                            } else {
                                $parent_context = '<div class="ecomment"><span class="ecommentauthor">引用 @' . $parent_cmtname . '：</span><div class="ecommenttext">' . $parent_context . '该评论正在审核中</div></div>';
                            }

                        }
                    }
                }

                $saytext = $this->face_replace($parent_context . $saytext);//解析表情
                $saytext = $this->img_replace($saytext);//解析图片

                if ($r['userid'] > 0) {//当评论者是网站会员时
                    //自己写逻辑，下面是示例代码
//                    $member = MemberModel::where("userid", "=", $r['userid'])->findOrEmpty();
//                    if ($member->isEmpty()) {
//                        $is_member = 0;
//                    } else {
//                        $is_member = 1;
//                        $userpic = $member->avatar;
//                        //$plusername = $member->nickname;
//                    }
                } else {
                    $is_member = 0;
                }

                if ($is_member != 1) {
                    $userpic = $this->cmt_url . 'assets/userpic/' . $r['userpic'] . '.gif';
                }

                $plusername = $r['cmtname'];
                if (!$plusername) {
                    $plusername = $this->cmt_config['default_cmtname'];
                }

                $comments[] = array(
                    "plid" => $r['id'],
                    "userpic" => $userpic,
                    "plusername" => $plusername,
                    "formattime" => $this->lgy_tranTime(strtotime($r['addtime'])),
                    "saytext" => $saytext,
                    "zcnum" => $r['like_num'],
                    "fdnum" => $r['bad_num'],
                );
            }
        }

        $pageTotal = ceil($total / $limit);  //总页数
        if ($page < $pageTotal) {//判断是否有下一页
            $hasmore = 1;
        } else {
            $hasmore = 0;
        }


        $list_arr = array(
            "err_msg" => "success",
            "data" => $comments,
            "total" => $total,//总记录数
            "pageTotal" => $pageTotal,  //总页数
            "pageSize" => $limit,
            "pageIndex" => $page,
            "cmtid" => $cmtid,
            "catid" => $catid,
            "onclick" => ($join_num + $total),//评论参与人数
            "hasmore" => $hasmore,//是否有下一页
            "info" => "读取信息评论成功！",
        );

        if ($this->cmt_config['cache_time'] > 0) {//开启缓存
            $cache->set($cache_name, $list_arr, $this->cmt_config['cache_time']);//缓存评论列表
        }
        $this->mysqli_close();
        return json($list_arr);
    }

    public function face_replace($saytext)
    {//替换表情标签
        if ($saytext) {
            preg_match_all("/\[\/(.*?)\]/isu", $saytext, $matches, PREG_PATTERN_ORDER);
            foreach ($matches[1] as $faces) {
                $view_face = $this->view_face($faces);
                if (count($view_face) > 0) {
                    $face_tag = "[/" . $view_face[0] . "]";
                    $saytext = str_ireplace($face_tag, "<img src=\"" . $this->cmt_url . "face/" . $view_face[1] . "\" border=\"0\">", $saytext);
                }
            }
        }
        return $saytext;
    }

    //解释图片标签
    public function img_replace($text)
    {
        $preg_str = "/\[img\](.+?)\[\/img\]/isu";
        $text = preg_replace($preg_str, "<img src=$1 class='pic' onclick=window.open('$1')>", $text);
        return $text;
    }

    public function lgy_tranTime($time)
    {//格式化时间
        $minute = date("H:i", $time);
        $hour = date("H:i", $time);
        $alltime = date("Y年m月d日 H:i", $time);
        $time = time() - $time;
        if ($time < 60) {
            $str = ' 刚刚 ';
        } elseif ($time < 60 * 60) {
            $min = floor($time / 60);
            $str = $min . '分钟前 ';
        } elseif ($time < 60 * 60 * 24) {
            $h = floor($time / (60 * 60));
            $str = $h . '小时前 ';
        } elseif ($time < 60 * 60 * 24 * 3) {
            $d = floor($time / (60 * 60 * 24));
            if ($d = 1)
                $str = '昨天 ' . $minute;
            else
                $str = $alltime;
        } else {
            $str = $alltime;
        }
        return $str;
    }

    public function view_face($str)
    {//获取对应的表情数组
        $cmt_faces = $this->cmt_faces;
        foreach ($cmt_faces as $faces) {
            if (trim($faces[0]) == trim($str)) {
                return $faces;
            }
        }
        return array();
    }

    public function dolike()//点赞或踩
    {
        global $mysql_config;

        $action_data = filter_sql_arr($_POST);//过滤注入
        $action_data["catid"] = isset($action_data["catid"]) ? $action_data["catid"] : 0;
        $action_data["cmtid"] = isset($action_data["cmtid"]) ? $action_data["cmtid"] : "";//评论的标识符ID
        $action_data["plid"] = isset($action_data["plid"]) ? $action_data["plid"] : 0;//评论的标识符ID
        $action_data["dopl"] = isset($action_data["dopl"]) ? trim($action_data["dopl"]) : 1;//点赞操作，1=点赞，0=踩
        $result = "?catid=" . $action_data["catid"] . "&cmtid=" . $action_data["cmtid"];

        if (!$action_data["cmtid"]) {
            return json(array("err_msg" => "fail", "result" => $result, "info" => "标识符cmtid不能为空"));
        }
        if (empty($action_data["catid"]) && $action_data["catid"] != 0) {
            return json(array("err_msg" => "fail", "result" => $result, "info" => "标识符catid不能为空"));
        }
        if (!is_numeric($action_data["plid"])) {
            return json(array("err_msg" => "fail", "result" => $result, "info" => "评论ID不能为空"));
        }

        $dolike = $_COOKIE["dolike"];
        if ($dolike) {
            $like_arr = explode(",", $dolike);
            if (in_array($action_data["plid"], $like_arr)) {
                return json(array("err_msg" => "fail", "result" => $result, "info" => "请不要重复操作"));
            } else {
                setcookie("dolike", $dolike . "," . $action_data["plid"], time()+intval(60 * 60 * 24 * 30));//30天内不允许重复点赞
            }
        } else {
            setcookie("dolike", $action_data["plid"], time()+intval(60 * 60 * 24 * 30));//30天内不允许重复点赞
        }

        $this->mysqli_conn();

        $list_data = array();
        $sql="select * from `".$mysql_config["prefix"]."comment` where id='".$action_data["plid"]."' and cmtid='".$action_data["cmtid"]."' and catid='".$action_data["catid"]."' and ischeck=1 order by addtime desc ";
        $sql_query=mysqli_query($this->conn,$sql);
        while($comment_list=mysqli_fetch_array($sql_query)){
            $list_data[] = $comment_list;
        }

        if (empty($list_data)) {
            return json(array("err_msg" => "fail", "result" => $result, "info" => "评论不存在或未审核"));
        } else {
            if ($action_data["dopl"] == 1) {
                mysqli_query($this->conn,"update `".$mysql_config["prefix"]."comment` set `like_num`=`like_num`+1 WHERE id=".$action_data["plid"]);
                $this->mysqli_close();
                return json(array("err_msg" => "success", "result" => $result, "info" => "点赞成功"));
            } elseif ($action_data["dopl"] == 0) {
                mysqli_query($this->conn,"update `".$mysql_config["prefix"]."comment` set `bad_num`=`bad_num`+1 WHERE id=".$action_data["plid"]);
                $this->mysqli_close();
                return json(array("err_msg" => "success", "result" => $result, "info" => "踩成功"));
            } else {
                return json(array("err_msg" => "fail", "result" => $result, "info" => "未定义操作"));
            }

        }

    }

    public function doaction()//发布评论
    {
        session_start();

        global $mysql_config;
        $action_data = filter_sql_arr($_POST);//过滤注入
        $action_data["catid"] = isset($action_data["catid"]) ? $action_data["catid"] : 0;
        $action_data["cmtid"] = isset($action_data["cmtid"]) ? $action_data["cmtid"] : "";//评论的标识符cmtid
        $action_data["saytext"] = isset($action_data["saytext"]) ? strip_tags($action_data["saytext"]) : "";//评论内容
        $action_data["username"] = isset($action_data["username"]) ? $action_data["username"] : "";//用户名
        $action_data["repid"] = isset($action_data["repid"]) ? $action_data["repid"] : 0;//回复ID
        $result = "?catid=" . $action_data["catid"] . "&cmtid=" . $action_data["cmtid"];

        if (!$action_data["cmtid"]) {
            return json(array("err_msg" => "fail", "result" => $result, "info" => "标识符cmtid不能为空"));
        }
        if (empty($action_data["catid"]) && $action_data["catid"] != 0) {
            return json(array("err_msg" => "fail", "result" => $result, "info" => "标识符catid不能为空"));
        }
        if (mb_strlen($action_data["username"]) > 12) {
            return json(array("err_msg" => "fail", "result" => $result, "info" => "昵称不能超过12个字"));
        }
        if (!$action_data["saytext"]) {
            return json(array("err_msg" => "fail", "result" => $result, "info" => "评论内容不能为空"));
        }else{
            if($this->check_bad_word($action_data["saytext"])){
                return json(array("err_msg" => "fail", "result" => $result, "info" => "评论内容含有违规词，请修改后再提交"));
            }
        }

        // 检测输入的验证码是否正确
        if ($this->cmt_config['is_captcha']) {
            $captcha = $action_data["key"];
            if (!$captcha) {
                return json(array("err_msg" => "fail", "result" => $result, "info" => "请输入验证码。"));
            } elseif (!captcha_check($captcha)) {
                // 验证失败
                return json(array("err_msg" => "fail", "result" => $result, "info" => "验证码错误，请刷新验证码后重新输入。"));
            }
        }

        $this->mysqli_conn();

        $userid = 0;//会员ID，可以自己做判断是否登录，此处略
        if ($userid > 0) {//当为会员时
            //自己写逻辑
            $cmtname = "";
        } else {
            $cmtname = $action_data["username"];
        }

        $parentid = $action_data["repid"];//回复的评论ID
        if ($parentid > 0) {

            $list_data = array();
            $sql="select * from `".$mysql_config["prefix"]."comment` where id=".$parentid;
            $sql_query=mysqli_query($this->conn,$sql);
            while($comment_list=mysqli_fetch_array($sql_query)){
                $list_data = $comment_list;
            }

            if (empty($list_data)) {
                $parentid = 0;
                $arrparentid = "";
            } else {
                $parentid = $list_data["id"];
                if (trim($list_data["parentid"]) != "0") {
                    $arrparentid = $list_data["arrparentid"] . "," . $parentid;
                } else {
                    $arrparentid = $parentid;
                }
            }
        } else {
            $parentid = 0;
            $arrparentid = "";
        }

        if ($this->cmt_config['is_check']) {//评论需要审核
            $ischeck = 0;
            $info = "提交成功，但需要审核通过后才能正常显示。";
        } else {
            $ischeck = 1;
            $info = "提交成功";
        }

        $insert_data = array(
            "cmtname" => $cmtname,
            "userid" => $userid,
            "userpic" => mt_rand(1, 50),
            "cmtip" => getip(),
            "cmtid" => $action_data["cmtid"],
            "catid" => $action_data["catid"],
            "parentid" => $parentid,
            "arrparentid" => $arrparentid,
            "cmtcontent" => $action_data["saytext"],
            "ischeck" => $ischeck,
            "addtime"=>date("Y-m-d H:i:s",time()),
        );

        $insert_keys = "`".implode("`,`", array_keys($insert_data))."`";
        $insert_value = "'".implode("','",array_values($insert_data))."'";
        $this->conn->query("insert into `".$mysql_config["prefix"]."comment` (".$insert_keys.") values(".$insert_value.")");
        $comment_id=$this->conn->insert_id;
        if ($comment_id > 0) {
            include "cache.php";
            $cache=new FileCache("cache");
            $cache->flush();//清除缓存
            $list = array("err_msg" => "success", "result" => $result, "info" => $info);
        } else {
            $list = array("err_msg" => "fail", "result" => $result, "info" => $info);
        }

        $this->mysqli_close();

        return json($list);
    }


    /**
     * 检测是否包含违规词
     */
    public function check_bad_word($str)
    {
        $bad_word_arr=explode("|",$this->cmt_config["bad_word"]);
        foreach ($bad_word_arr as $value){
            if(strpos($str,$value) !== false){
                return true;
            }
        }
        return false;
    }

}