<?php
define("TOKEN", "zhangjun");
//用于回复用户消息
function responseMsg(){
    $postStr = $GLOBALS["HTTP_RAW_POST_DATA"];
    if (!empty($postStr)){
        $postObj = simplexml_load_string($postStr, 'SimpleXMLElement', LIBXML_NOCDATA);
        $fromUsername = $postObj->FromUserName;
        $toUsername = $postObj->ToUserName;
        $MsgT = $postObj->MsgType;
        $time = time();
        //如果用户发的text类型
        if($MsgT=="text"){
            $key = trim($postObj->Content);
            $fromUsername = $postObj->FromUserName;
            $textTpl = "<xml>
                        <ToUserName><![CDATA[%s]]></ToUserName>
                        <FromUserName><![CDATA[%s]]></FromUserName>
                        <CreateTime>%s</CreateTime>
                        <MsgType><![CDATA[%s]]></MsgType>
                        <Content><![CDATA[%s]]></Content>
                        </xml>"; 
            $msgType = "text";

            $appid="wx45f4798533f17360";//填写AppID　　
            $secret="8cb126d890d663870d76e333f2f00088";//填写Secret
            $url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid={$appid}&secret={$secret}";
            $ch = curl_init();//初始化curl函数
            curl_setopt($ch,CURLOPT_URL,$url);//GET方式抓取url
            curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);//以文件流的方式输出
            $a = curl_exec($ch);//将文件保存到变量$a
            $strjson=json_decode($a);//JSON解析
            $access_token = $strjson->access_token;//获取access_token

            $wxid=$fromUsername;
            $url="https://api.weixin.qq.com/cgi-bin/user/info?access_token={$access_token}&openid={$wxid}&lang=zh_CN";
            $ch = curl_init();//初始化curl函数
            curl_setopt($ch,CURLOPT_URL,$url);//GET方式抓取url
            curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);//以文件流的方式输出
            $a = curl_exec($ch);//将文件保存到变量$a
            $strjson=json_decode($a);//JSON解析
            $openid=$strjson->openid;
            $nickname = $strjson->nickname;//获取access_token
            $headimgurl=$strjson->headimgurl;

            $content=$postObj->Content;

            if($content=="签到"){
                //处理GET数据
                $servername = "localhost";
                $username = "wall";
                $password = "walladmin";
                // 创建连接
                $conn = new mysqli($servername, $username, $password,"wall");
                $sql = "select id from user where nickname='$nickname'";
                $data=mysqli_query($conn,$sql);
                $data=mysqli_fetch_assoc($data);
                if($data){
                    $contentStr= "您已经签到过了";
                }else{
                    $sql = "insert into user(nickname,headimgurl) values ('$nickname','$headimgurl')" ;
                    if(mysqli_query($conn,$sql)){
                        $contentStr= "签到成功";
                    }else{
                        $contentStr= "签到失败";
                    }
                }
                mysqli_close($conn);
                $resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, $msgType, $contentStr);
                echo $resultStr;
                exit;
            }else{
                $danmu=mb_substr($content , 0 , 2);
                if($danmu=="贝杰"){
                    $servername = "localhost";
                    $username = "wall";
                    $password = "walladmin";
                    // 创建连接
                    $conn = new mysqli($servername, $username, $password,"wall");
                    $sql = "select id from user where nickname='$nickname'";
                    $data=mysqli_query($conn,$sql);
                    $data=mysqli_fetch_assoc($data);
                    if($data){
                        $user_id=$data['id'];
                        $content=mb_substr($content , 2 , 30);
                        $time=time();
                        $sql="insert into text(user_id,content,time) values('$user_id','$content','$time')";
                        if(mysqli_query($conn,$sql)){
                            $contentStr= "发送成功";
                        }else{
                            $contentStr= "发送失败";
                        }
                    }else{
                        $contentStr= "请您先签到";
                    }
                    mysqli_close($conn);
                    $resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, $msgType, $contentStr);
                    echo $resultStr;
                }else{
                    echo "";
                    exit;
                }
            }
        }

        //如果用户发的event（事件）类型
        if($MsgT=="event"){
            $Event = $postObj->Event;
            if ($Event==subscribe) {
               $contentStr = "欢迎关注";
            }else{
                $contentStr = "希望您下次关注，但您收不到此条消息了";
            }

            $textTpl = "<xml>
                        <ToUserName><![CDATA[%s]]></ToUserName>
                        <FromUserName><![CDATA[%s]]></FromUserName>
                        <CreateTime>%s</CreateTime>
                        <MsgType><![CDATA[%s]]></MsgType>
                        <Content><![CDATA[%s]]></Content>
                        </xml>"; 
            $Title = $postObj->Title;
            $Description = $postObj->Description;
            $Url = $postObj->Url;
            $msgType = 'text';
            $resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, $msgType, $contentStr);
            echo $resultStr;
            exit;
        }
    }else{
            echo "";
            exit;
    }
}

    $echoStr = $_GET["echostr"];
    //如果有$echoStr说明是对接
    if (!empty($echoStr)) {
        //对接规则
        $signature = $_GET["signature"];
        $timestamp = $_GET["timestamp"];
        $nonce = $_GET["nonce"];
        $token = TOKEN;
        $tmpArr = array($token, $timestamp, $nonce);
        sort($tmpArr, SORT_STRING);
        $tmpStr = implode( $tmpArr );
        $tmpStr = sha1( $tmpStr );
        if( $tmpStr == $signature ){
            echo $echoStr;
        }else{
            echo "";
            exit;
        }
    }else{
        responseMsg();
    }
?>