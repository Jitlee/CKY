<?php
namespace M\Action;
use Think\Controller;
class WxMsgAction extends Controller{
        public function index()
        {
            traceHttp();
			define("TOKEN", "weixin");
			//$wechatObj = new wechatCallbackapiTest();
			if (isset($_GET['echostr'])) {
			    $this->valid();
			}else{
			    $this->responseMsg();
			}
        }
		
	    public function valid()
	    {
	        $echoStr = $_GET["echostr"];
	        if($this->checkSignature()){
	            echo $echoStr;
	            exit;
	        }
	    }
		
        public function responseMsg()
        {
        		$postStr = $GLOBALS["HTTP_RAW_POST_DATA"];

		        if (!empty($postStr)){
		        	logger($postStr);
		            $postObj = simplexml_load_string($postStr, 'SimpleXMLElement', LIBXML_NOCDATA);
		            $fromUsername = $postObj->FromUserName;
		            $toUsername = $postObj->ToUserName;
		            $keyword = trim($postObj->Content);
					$EventKey= trim($postObj->EventKey);
		            $time = time();
		            $textTpl = "<xml>
		                        <ToUserName><![CDATA[%s]]></ToUserName>
		                        <FromUserName><![CDATA[%s]]></FromUserName>
		                        <CreateTime>%s</CreateTime>
		                        <MsgType><![CDATA[%s]]></MsgType>
		                        <Content><![CDATA[%s]]></Content>
		                        <FuncFlag>0</FuncFlag>
		                        </xml>";
					$contentStr=" ";
					if (!empty($EventKey)){
						switch($EventKey)
						{
							case "introduct": //简介
								$contentStr="关于简介，它。。。。";
								break;
							case "MGOOD":
								$contentStr="感谢您的支持，我们一定会做得更好。";
								break;
							default:
								$contentStr="需然不知道你说的是什么，相信它一定是对的。\n";
						}
					}
					else
				 	{
				 		$contentStr="您说的是：".$keyword;
				 	}
		            //$contentStr=$contentStr.$fromUsername.$toUsername;
		            
	                $msgType = "text";
	                //$contentStr = date("Y-m-d H:i:s",time());
	                $resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, $msgType, $contentStr);
	                echo $resultStr;
		             
		        }else{
		            echo "";
		            exit;
		        }
        }

        private function checkSignature()
        {
           $signature = $_GET["signature"];
	        $timestamp = $_GET["timestamp"];
	        $nonce = $_GET["nonce"];
			//define("TOKEN", "weixin");
	        $token = "weixin";
	        $tmpArr = array($token, $timestamp, $nonce);
	        sort($tmpArr);
	        $tmpStr = implode( $tmpArr );
	        $tmpStr = sha1( $tmpStr );
	
	        if( $tmpStr == $signature ){
	            return true;
	        }else{
	            return false;
	        }
        }

}

/*

//	function accessToken(){
		$tokenFile = "./access_tokenkey.txt";//缓存文件名 
//		$data = json_decode(file_get_contents($tokenFile)); 
//		session("wxposition","2");
//		session("token",$data);
//		if (!$data or $data->expire_time < time() or !$data->expire_time) {
			//session("wxposition","3"); 
			$appid = "wx06dcafb051f5e21f";
			$secret = "F39CA8630A1F402E984B99EC96D1ED68";
			$code = $_GET["code"];
			$get_token_url = 'https://api.weixin.qq.com/sns/oauth2/access_token?appid='.$appid.'&secret='.$secret.'&code='.$code.'&grant_type=authorization_code';
				 //https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=wx9c7c9bb54952b54d&secret=d4624c36b6795d1d99dcf0547af5443d
			$res = $this->getJson($get_token_url); 
			return $res;
//			$access_token = $res['access_token']; 
//			if($access_token) 
//			{
//				//session("wxposition","4");  
//		        $data2['expire_time'] = time() + 7000; 
//		        $data2['access_token'] = $res['access_token']; 
//				$k["123"]=11;
//				$k["abc"]="dg";
//		        $fp = fopen($tokenFile, "w+");
//		        fwrite($fp, json_encode($res));
//		        fclose($fp);
//				$t2=json_decode(file_get_contents($tokenFile));
//				session("wxac",$k);
//				return $access_token;
//	      	}
//			return $access_token;
//	    }
//	    else
//	    {
//	      session("wxposition","5");  
//	      $access_token = $data->access_token; 
//	    }
//	    return $access_token;
	} 

 * */