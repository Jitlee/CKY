<?php
namespace M\Action;
use Think\Controller;
class WeixinAction extends Controller{
        public function index()
        {
                $echoStr = $_GET["echostr"];
                //valid signature , option
                if($this->checkSignature()){
                        $this->responseMsg();
                }
        }

        public function responseMsg()
        {
        	$postStr = $GLOBALS["HTTP_RAW_POST_DATA"];
                if (!empty($postStr)){
                        libxml_disable_entity_loader(true);
                        $postObj = simplexml_load_string($postStr, 'SimpleXMLElement', LIBXML_NOCDATA);
                        $fromUsername = $postObj->FromUserName;
                        $toUsername = $postObj->ToUserName;
                        $event = $postObj->Event;
                        $keyword = $event=='CLICK'?$postObj->EventKey : trim($postObj->Content);
                        $time = time();
                        $textTpl = "<xml>
                                                        <ToUserName><![CDATA[%s]]></ToUserName>
                                                        <FromUserName><![CDATA[%s]]></FromUserName>
                                                        <CreateTime>%s</CreateTime>
                                                        <MsgType><![CDATA[%s]]></MsgType>
                                                        <Content><![CDATA[%s]]></Content>
                                                        <FuncFlag>0</FuncFlag>
                                                </xml>";
                        if(!empty( $keyword ))
                        {
                                $msgType = "text";
                                $contentStr ='ssss';
                                $resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, $msgType, $contentStr);
                                echo $resultStr;
                        }else{
                                echo "Input something...";
                        }

                }else {
                        echo "";
                        exit;
                }
        }

        private function checkSignature()
        {
                $signature = $_GET["signature"];
                $timestamp = $_GET["timestamp"];
                $nonce = $_GET["nonce"];

                $token = '7a89bf';
                $tmpArr = array($token, $timestamp, $nonce);
                // use SORT_STRING rule
                sort($tmpArr, SORT_STRING);
                $tmpStr = implode( $tmpArr );
                $tmpStr = sha1( $tmpStr );

                if( $tmpStr == $signature ){
                        return true;
                }else{
                        return false;
                }
        }

}