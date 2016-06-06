<?php
namespace M\Action;
/**
 * ============================================================================
 * 粗卡云:
  
 * 联系方式:
 * ============================================================================
 * 首页（默认）控制器
 */
use Think\Controller;
class backAction extends Controller {
 	/*JsApi相关接口参数*/
 	public function getsharekey() {
		$wxm= new WxUserInfo();
		$signPackage=$wxm->getSignPackage();
		$this->ajaxReturn($signPackage, 'JSON');
	}
	
	/*订单成功，通知到商家*/
 	public function SendOrderNotifyToShops() {
		$wxm= new WxUserInfo();
		$m = D('M/Orders');		 
		$obj = $m->orderDetailForNotify(283);
		if(!$obj)  return; 
		$openid = $obj["wxopenid"];
		//没有设置提醒
		if(empty($openid))  return; 
		$ACCESS_TOKEN =$wxm->accessToken();
		$url="https://api.weixin.qq.com/cgi-bin/message/template/send?access_token=$ACCESS_TOKEN";
		
		$createTime = $obj["createTime"];
		$totalMoney = $obj["totalMoney"];
		$goods = $obj["goods"];
		$orderType=$obj["orderType"];
		$orderTypeName="";
		if($orderType=="1" || $orderType=="2"){ $orderTypeName="一元购订单"; }
		else if($orderType=="3"){ $orderTypeName="商城订单"; }
		else  { $orderTypeName="商家订单"; }
		
		$username='f';
		$orderItemData="订单金额：$totalMoney
商品详情：$goods";
		
		$json='
{
	"touser":"'.$openid.'",
	"template_id":"JsODEGXf-FzqYgRddUCbgMmTDHG8htzE0uKDFKhC6xA",
	"url":"http://www.cukayun.cn/index.php/M",
	"topcolor":"#FF0000",
	"data":{
		"orderType": {
			"value":"'.$orderTypeName.'",
			"color":"#173177"
		},
		"tradeDateTime":{
			"value":"06月07日 19时24分",
			"color":"#173177"
		},
		"customerInfo":{
			"value":"'.$username.'",
			"color":"#173177"
		},
		"orderItemName":{
			"value":"商品信息",
			"color":"#173177"
		},
		"orderItemData":{
			"value":"'.$orderItemData.'",
			"color":"#173177"
		},
		"remark":{
			"value":"remark",
			"color":"#173177"
		}
	}
}
		';
		
		$result= $this->postData($url, $json);
		echo $json;
		echo $result;
					
	}
	
	function postData($url, $data){
	    $ch = curl_init();
	    $timeout = 300;
	    curl_setopt($ch, CURLOPT_URL, $url);
	    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
	    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
	    //下面这句是绕过SSL验证
	    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); 
	    $handles = curl_exec($ch);
	    curl_close($ch);
	    return $handles;
	}
	
	
	
	
}
