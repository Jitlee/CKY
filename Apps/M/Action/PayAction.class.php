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
class PayAction extends BaseUserAction {
	 
	//在类初始化方法中，引入相关类库
    public function _initialize() {
        vendor('Weixinpay.WxPayJsApiPay');
    }

	
	Public function error()
	{
		$this->assign('title', "支付失败");
		$this->display();	
	}
	
	Public function success()
	{
		$this->assign('title', "支付成功");
		$this->display();	
	}
	// 支付类型 余额支付
	Public function payvalue(){
		//1、获取openid
		$payno=I("orderno");
		$mMPay = D('M/MemberPay');
		$dataInfo=$mMPay->GetByPayNo($payno);		
		$money=$dataInfo["money"];
		$type=$dataInfo["PayType"];
		
		$result["status"]=-1;	
		if($dataInfo && $dataInfo["PayType"]=="order" && $dataInfo["Status"]==0)	
		{
			//从一卡易会员卡扣钱
			 $dataInfo["ChangeTime"]=date('Y-m-d H:i:s');
			 $dataInfo["Status"]=99;
			 $cardid=$dataInfo["cardid"];
			 $res=$mMPay->OrderValuePay($dataInfo,$cardid);
			 //更新订单状态
			 $orderid=$dataInfo["extendid"]; 
			if($res["status"] == 0)
			{
				$result["status"]=1;				 
			}
			else
			{
				$result["msg"]=$res["message"];	
			}
		} 
		
		$this->ajaxReturn($result, "JSON");
	}
    Public function index(){

       

		$money=session("money");
		$type=session("type");

		if($type=="recharge")
		{
			$Body="会员卡充值";	
		}
		else if($type=="order")
		{
			$Body="订单支付";
			$dataInfo["extendid"]=session("orderid");
		}
		else
		{
			$Body="未定义类型";
		}
		//保存系统支付订单
		$dataInfo["uid"]=session("uid");
		$dataInfo["openId"]=$this->GetOpenid();
		$dataInfo["cardid"]=$this->GetCardId(); 
		
		$dataInfo["payNo"]='cky'.date('ymdhis').rand(10000,99999); 
		$dataInfo["PayType"]=$type;
		$dataInfo["PayTypeName"]=$Body;		
		$dataInfo["TotalMoney"]=$money;
		$dataInfo["CreateTime"]=date('Y-m-d H:i:s');
		$dataInfo["ChangeTime"]=date('Y-m-d H:i:s');
		$dataInfo["Status"]=0;

		
		$mPay= D('M/MemberPay');		 
		$result=$mPay->InitPay($dataInfo);
		if($result['status']==1)
		{
			$this->assign('title', $Body);
			$this->assign('money', $money);
			$tfee=$money * 100;		//整数单位为分
			//$tfee=1;				//整数单位为分
			$setattach=$dataInfo["payNo"]; //附加信息原样返回			
	        //2、统一下单
	        if(strpos($_SERVER['SERVER_NAME'] , 'localhost') === false 
	        	&& strpos($_SERVER['SERVER_NAME'], '192.168.') === false 
	        	&& strpos($_SERVER['SERVER_NAME'], 'cky.ritacc.net') === false) {		
		
				 //1、获取openid
		        $tools = new \JsApiPay();
		        $openId = $tools->GetOpenid();
				
		        $input = new \WxPayUnifiedOrder();
		        $input->SetBody($Body);
		        $input->SetAttach($setattach);
		        $input->SetOut_trade_no(\WxPayConfig::MCHID.date("YmdHis"));
		        $input->SetTotal_fee($tfee);
		        $input->SetTime_start(date("YmdHis"));
		        $input->SetTime_expire(date("YmdHis", time() + 600));
		        $input->SetGoods_tag("Goods_test");
		        $input->SetNotify_url("http://cukayun.cn/index.php/M/Pay/notify/");   //支付回调地址，这里改成你自己的回调地址。
		        $input->SetTrade_type("JSAPI");
		        $input->SetOpenid($openId);
		        $order = \WxPayApi::unifiedOrder($input);
	
		        $jsApiParameters = $tools->GetJsApiParameters($order);
		        $this->jsApiParameters=$jsApiParameters;
			}
			if($type=="order")
			{
				$usrinfo=session("MemberItem");
				//echo dump($usrinfo);
				$this->assign('account', $usrinfo);
				$this->assign('orderno', $setattach);
				//本地另外一个页面
				if(strpos($_SERVER['SERVER_NAME'] , 'localhost') === false 
	        	&& strpos($_SERVER['SERVER_NAME'], '192.168.') === false 
	        	&& strpos($_SERVER['SERVER_NAME'], 'cky.ritacc.net') === false) {
					$this->display("orderpay");	//正式环境
				}
				else
				{
					$this->display("orderpaytest");	//正式环境
				}
			}
			else
			{
				$this->display();
			}
		}
		else
		{
			$this->assign('title', "保存订单出错");
			$this->display();	
		}
    }

    //回调
    Public function notify(){
        //这里没有去做回调的判断，可以参考手机做一个判断。
        $xmlObj=simplexml_load_string($GLOBALS['HTTP_RAW_POST_DATA']); //解析回调数据
        
        $appid=$xmlObj->appid;//微信appid
        $mch_id=$xmlObj->mch_id;  //商户号
        $nonce_str=$xmlObj->nonce_str;//随机字符串
        $sign=$xmlObj->sign;//签名
        $result_code=$xmlObj->result_code;//业务结果
        $openid=$xmlObj->openid;//用户标识
        $is_subscribe=$xmlObj->is_subscribe;//是否关注公众帐号
        $trace_type=$xmlObj->trade_type;//交易类型，JSAPI,NATIVE,APP
        $bank_type=$xmlObj->bank_type;//付款银行，银行类型采用字符串类型的银行标识。
        $total_fee=$xmlObj->total_fee;//订单总金额，单位为分
        $fee_type=$xmlObj->fee_type;//货币类型，符合ISO4217的标准三位字母代码，默认为人民币：CNY。
        $transaction_id=$xmlObj->transaction_id;//微信支付订单号
        $out_trade_no=$xmlObj->out_trade_no;//商户订单号
        $attach=$xmlObj->attach;//商家数据包，原样返回
        $time_end=$xmlObj->time_end;//支付完成时间
        $cash_fee=$xmlObj->cash_fee;
        $return_code=$xmlObj->return_code;

        //下面开始你可以把回调的数据存入数据库，或者和你的支付前生成的订单进行对应了。
		//更新到订单表
		$attach=$attach.'';
		$result_code=$result_code.'';
		//echo $attach;
		$mMPay = D('M/MemberPay');
		$dataInfo=$mMPay->GetByPayNo($attach);
		
		if($dataInfo && $dataInfo["PayType"]=="recharge" && $dataInfo["Status"]==0 && $result_code=='SUCCESS')	
		{
			 $dataInfo["ChangeTime"]=date('Y-m-d H:i:s');
			 $dataInfo["result_code"]=$result_code.'';
			 $dataInfo["fee_type"]=$fee_type.'';
			 $dataInfo["transaction_id"]=$transaction_id.'';
			 $dataInfo["cash_fee"]=$cash_fee.'';
			 $dataInfo["Status"]=99;
			 
			 $cardid=$dataInfo["cardid"];
			 $result=$mMPay->UpdateRechange($dataInfo,$cardid);
		}
		else if($dataInfo && $dataInfo["PayType"]=="order" && $dataInfo["Status"]==0 && $result_code=='SUCCESS')	
		{
			 $dataInfo["ChangeTime"]=date('Y-m-d H:i:s');
			 $dataInfo["result_code"]=$result_code.'';
			 $dataInfo["fee_type"]=$fee_type.'';
			 $dataInfo["transaction_id"]=$transaction_id.'';
			 $dataInfo["cash_fee"]=$cash_fee.'';
			 $dataInfo["Status"]=99;
			 
			 //$cardid=$dataInfo["cardid"];
			 $result=$mMPay->UpdatePayOrder($dataInfo);
		}
		else
		{
			$content="-----------------出错啦-----------------";
			$content=$content.',PayType='.$dataInfo["PayType"].',Status='.$dataInfo["Status"];
			logger($content);
			logger($GLOBALS['HTTP_RAW_POST_DATA']);
		}
		echo 'SUCCESS';
        //需要记住一点，就是最后在输出一个success.要不然微信会一直发送回调包的，只有需出了succcess微信才确认已接收到信息不会再发包.
        
    }

	 

 
}