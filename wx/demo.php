<?php
include_once "WXBizMsgCrypt.php";
// 第三方发送消息给公众平台 
$encodingAesKey = "d4624c36b6795d1d99dcf0547af5443d"; 
$token = "aaabbb"; 
$corpId ="wx9c7c9bb54952b54d"; //这里已正确填写自己的corpid 
//公众号服务器数据
$sReqMsgSig = $sVerifyMsgSig = $_GET['msg_signature'];
$sReqTimeStamp = $sVerifyTimeStamp = $_GET['timestamp'];
$sReqNonce = $sVerifyNonce = $_GET['nonce'];
$sReqData = file_get_contents("php://input");;
$sVerifyEchoStr = $_GET['echostr']; 
$wxcpt = new WXBizMsgCrypt($token, $encodingAesKey, $corpId); 
if($sVerifyEchoStr){
	$sEchoStr = "";
	$errCode = $wxcpt->VerifyURL($sVerifyMsgSig, $sVerifyTimeStamp, $sVerifyNonce, $sVerifyEchoStr, $sEchoStr);
	if ($errCode == 0) {
		print($sEchoStr); 
	} 
	else 
	{
		print($errCode . "\n\n");
	}
	exit;
}
 //decrypt
$sMsg = "";  //解析之后的明文
$errCode = $wxcpt->DecryptMsg($sReqMsgSig, $sReqTimeStamp, $sReqNonce, $sReqData, $sMsg);
if ($errCode == 0) { 
$xml = new DOMDocument();
$xml->loadXML($sMsg); 
$reqToUserName = $xml->getElementsByTagName('ToUserName')->item(0)->nodeValue;
$reqFromUserName = $xml->getElementsByTagName('FromUserName')->item(0)->nodeValue;
$reqCreateTime = $xml->getElementsByTagName('CreateTime')->item(0)->nodeValue;
$reqMsgType = $xml->getElementsByTagName('MsgType')->item(0)->nodeValue;
$reqContent = $xml->getElementsByTagName('Content')->item(0)->nodeValue;
$reqMsgId = $xml->getElementsByTagName('MsgId')->item(0)->nodeValue;
$reqAgentID = $xml->getElementsByTagName('AgentID')->item(0)->nodeValue; 
switch($reqContent){
	case "马云":
		$mycontent="您好，马云！我知道您创建了阿里巴巴！";
		break;
	case "马化腾":
		$mycontent="您好，马化腾！我知道创建了企鹅帝国！";
		break;
	case "史玉柱":
		$mycontent="您好，史玉柱！我知道您创建了巨人网络！";
		break;
	default :
		$mycontent="你是谁啊？！一边凉快去！";
		break;
}
$sRespData = 
"<xml>
<ToUserName><![CDATA[".$reqFromUserName."]]></ToUserName>
<FromUserName><![CDATA[".$corpId."]]></FromUserName>
<CreateTime>".sReqTimeStamp."</CreateTime>
<MsgType><![CDATA[text]]></MsgType>
<Content><![CDATA[".$mycontent."]]></Content>
</xml>";
$sEncryptMsg = ""; //xml格式的密文
$errCode = $wxcpt->EncryptMsg($sRespData, $sReqTimeStamp, $sReqNonce, $sEncryptMsg);
if ($errCode == 0) {
//file_put_contents('smg_response.txt', $sEncryptMsg); //debug:查看smg
	print($sEncryptMsg);
} else {
	print($errCode . "\n\n");
}
} else {
	print($errCode . "\n\n");
}
?>