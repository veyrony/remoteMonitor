<?php


define("TOKEN", "weixin");

/*
https://mp.weixin.qq.com/wiki/4/2ccadaef44fe1e4b0322355c2312bfa8.html
加密/校验流程如下：
1. 将token、timestamp、nonce三个参数进行字典序排序
2. 将三个参数字符串拼接成一个字符串进行sha1加密
3. 开发者获得加密后的字符串可与signature对比，标识该请求来源于微信
*/
$wechatObj = new WechatRemoteMonitor();
if (!isset($_GET['echostr'])) {
	$wechatObj->responseMsg();  
} else { 
	$wechatObj->valid();
}

//config APPID and APPSECRET
define("APPID", "wxxxxxxxxxxxxxxxxx");
define("APPSECRET", "xxxxxxxxxxxxxxxxxxxxxxxx");


class WechatRemoteMonitor
{
    //验证签名
    public function valid()
    {
        $echoStr = $_GET["echostr"];
        $signature = $_GET["signature"];
        $timestamp = $_GET["timestamp"];
        $nonce = $_GET["nonce"];
        $token = TOKEN;
        $tmpArr = array($token, $timestamp, $nonce);
        sort($tmpArr);
        $tmpStr = implode($tmpArr);
        $tmpStr = sha1($tmpStr);
        if($tmpStr == $signature){
            echo $echoStr;
            exit;
        }
    }
    //响应消息
    public function responseMsg()
    {
        $postStr = $GLOBALS["HTTP_RAW_POST_DATA"];
        if (!empty($postStr)){
            $this->logger("receiveMsg: ".$postStr);
            $postObj = simplexml_load_string($postStr, 'SimpleXMLElement', LIBXML_NOCDATA);
            $RX_TYPE = trim($postObj->MsgType);
             
            //消息类型分离
            switch ($RX_TYPE)
            {
                case "event":
                    $result = $this->receiveEvent($postObj);
                    break;
                case "text":
                    $result = $this->receiveText($postObj);
                    break;

                default:
                    $result = "unknown msg type: ".$RX_TYPE;
                    break;
            }
            $this->logger("RspMsg: ".$result);
            echo $result;
        }else {
            echo "";
            exit;
        }
    }

	private function getAccessToken()
	{
		$token_access_url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=" . APPID . "&secret=" . APPSECRET;
		$this->logger("token_url=".$token_access_url);
		$res = file_get_contents($token_access_url);

		$result = json_decode($res, true);
		$this->logger("token responseMsg: ".$result);
		$access_token = $result['access_token'];
		$this->logger("token= ".$access_token);
		return $access_token;
	}

	//workaround：将拍摄的照片上传到微信
	private function uploadResource($accessToken, $type, $mediaFile)
	{
		$filePath = "/usr/share/nginx/www/test.jpg";
		$fileData = array("media" => "@".$filePath);
		$url = "https://api.weixin.qq.com/cgi-bin/media/upload?access_token=$accessToken&type=$type" ;
		$result = https_request($url, $fileData);
		return $result;
	}

    //接收事件消息
    private function receiveEvent($object)
    {
        $content = "";
        switch ($object->Event)
        {
            case "subscribe":
                $content = "欢迎关注xxxx！本公众号当前仅供开发测试用！谢谢！    ";
                $content .= (!empty($object->EventKey))?("\n来自二维码场景 ".str_replace("qrscene_","",$object->EventKey)):"";
                break;
            case "unsubscribe":
                $content = "谢谢惠顾！";
                break;
           
            default:
                $content = "receive a new event: ".$object->Event;
                break;
        }
		
        $result = $this->transmitText($object, $content);
        return $result;
    }

    //接收文本命令
    private function receiveText($object)
    {
        $keyword = trim($object->Content);
			
            if (strstr($keyword, "温度")) { //发送命令“温度”
                $temp = array();
                exec("sudo python ./Sensor/getTemp.py", $temp, $ret);
                if (!$ret){
                    $content = "当前的温度：\n";
                    $content .= $temp[0]. " ℃";
                } else {
                    $content = "温度读取错误！";
                }
            } else if (strstr($keyword,"拍照")){ //发送命令“拍照”
                exec("sudo fswebcam -d /dev/video0 -r 640x480 --bottom-banner /usr/share/nginx/www/test.jpg ", $output, $ret);
                if(0!=$ret){
                    $this->logger("fswebcam capture failed");
                    $result = $this->transmitText($object, "拍照失败！Error:01");
                    return $result;
                }
                $accessToken = $this->getAccessToken();
                //$accessToken = 'xxxxxxxxxxxxxxxxxxxxxxxx-xxxxxxxxxxxxxxxxxxxxxxxx';
                //$uploadRes = uploadResource($accessToken, "image", "test.jpg");
				//workaround: 使用curl上传图片
                $cmd = "curl -F media=@/usr/share/nginx/www/test.jpg".' "https://api.weixin.qq.com/cgi-bin/media/upload?access_token='.$accessToken.'&type=image"';
                exec("sudo $cmd", $uploadRes, $ret);
                if(0!=$ret){
                    $this->logger("upload capture failed");
                    $result = $this->transmitText($object, "拍照失败！Error:02");
                    return $result;
                }
                $this->logger("upload response= "."    $uploadRes[0]\r\n"."status= ".$ret);
                $imageObject = json_decode($uploadRes[0], true); 
                $result = $this->responseImage($object , $imageObject);
                $this->logger("responseImage->\r\n".$result);

                return $result;
            } else {
                $content = date("Y-m-d H:i:s",time())."\n".$object->FromUserName."\n";
            }
            
            if(is_array($content)){
                if (isset($content[0]['PicUrl'])){
                    $result = $this->transmitNews($object, $content);
                }else if (isset($content['MusicUrl'])){
                    $result = $this->transmitMusic($object, $content);
                }
            }else{
                $result = $this->transmitText($object, $content);
            }

        return $result;
    }

    //回复文本消息
    private function transmitText($object, $content)
    {
        $xmlTpl = "<xml>
<ToUserName><![CDATA[%s]]></ToUserName>
<FromUserName><![CDATA[%s]]></FromUserName>
<CreateTime>%s</CreateTime>
<MsgType><![CDATA[text]]></MsgType>
<Content><![CDATA[%s]]></Content>
</xml>";
        $result = sprintf($xmlTpl, $object->FromUserName, $object->ToUserName, time(), $content);
        return $result;
    }

    //回复图片
    private function responseImage($object, $imageArray)
    {
        $itemTpl = "<Image>
    <MediaId><![CDATA[%s]]></MediaId>
</Image>";

        $item_str = sprintf($itemTpl, $imageArray['media_id']);

        $xmlTpl = "<xml>
<ToUserName><![CDATA[%s]]></ToUserName>
<FromUserName><![CDATA[%s]]></FromUserName>
<CreateTime>%s</CreateTime>
<MsgType><![CDATA[image]]></MsgType>
$item_str
</xml>";

        $result = sprintf($xmlTpl, $object->FromUserName, $object->ToUserName, time());
        return $result;
    }

    //日志记录
    private function logger($log_content)
    {
        if($_SERVER['SERVER_NAME'] == "localhost"){ //LOCALHOST
            $max_size = 10000;
            $log_filename = "/usr/share/nginx/www/syslog.log";
            if(file_exists($log_filename) and (abs(filesize($log_filename)) > $max_size)){
                unlink($log_filename);
            }
            file_put_contents($log_filename, date('H:i:s')."    ".var_export($log_content, true)."\r\n", FILE_APPEND);
        }
    }
}
?>