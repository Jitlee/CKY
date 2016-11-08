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
class PayAction extends PayBaseAction {
	 
	//在类初始化方法中，引入相关类库
    public function _initialize() {
//      vendor('Weixinpay.WxPayJsApiPay');
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
		$accountmoney=I("accountmoney");
		$accountscore=I("accountscore");
				
		$mMPay = D('M/MemberPay');
		$dataInfo=$mMPay->GetByPayNo($payno);		
		$money=$dataInfo["money"];
		$type=$dataInfo["PayType"];
		
		$dataInfo['amount']=0;
		$dataInfo['amountStatus']=0;		
		$dataInfo['accountmoney']=$accountmoney;
		$dataInfo['accountmoneyStatus']=0;		
		$dataInfo['accountscore']=$accountscore;
		$dataInfo['accountscoreStatus']=0;	
		
		$result["status"]=-1;	
		if($dataInfo && $dataInfo["PayType"]=="order" && $dataInfo["Status"]==0)	
		{
			//从一卡易会员卡扣钱
			 $dataInfo["ChangeTime"]=date('Y-m-d H:i:s');
			 $dataInfo["Status"]=99;
			 $cardid=$dataInfo["cardid"];
			 $res=$mMPay->OrderValuePay($dataInfo,$cardid);
			 //更新订单状态
//			 $orderid=$dataInfo["extendid"]; 
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
		$orderType=session("orderType");
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
		$payno='cky'.date('ymdhis').rand(10000,99999);
		$dataInfo["payNo"]= $payno;
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
			$this->assign('orderType', $orderType);
//			$tfee=$money * 100;		//整数单位为分
			//$tfee=1;				//整数单位为分
			$setattach=$dataInfo["payNo"]; //附加信息原样返回			
	        //2、统一下单 
			if($type=="order")
			{
				$scorerate =(int)C("scorerate");//积分兑换比例
				//重新查询
				$openid = get_user_open_id();
				$mMember = D('M/Member');
				$usrinfo=$mMember->GetByOpenid($openid);
				$usrinfo=session("MemberItem");
				$EnablePoint=(int)$usrinfo["EnablePoint"];
				$EnablePoint=floor($EnablePoint/$scorerate);
				$EnablePoint=$EnablePoint*$scorerate;
				//echo dump($usrinfo);
				
				$this->assign('scorerate', $scorerate);
				$this->assign('EnablePoint', $EnablePoint);
				$this->assign('account', $usrinfo);
				$this->assign('orderno', $setattach);
				$this->display("orderpay");	//正式环境
			}
			else//index页面
			{
				$this->assign('orderno', $setattach);
				$this->display();
			}
		}
		else
		{
			$this->assign('title', "保存订单出错");
			$this->display();	
		}
    }
	
	public function thirdpay()
	{
		$orderno=$_POST['orderno'].'';
		if($orderno)
		{
			$orderType=$_POST['orderType'];		//订单类型
			session("orderno",$orderno);
			session("orderType",$orderType);
			$Body=$_POST['goodsName'].'';
			
			$accountmoney=$_POST['accountmoney'];		//帐户余额支付
			$accountscore=$_POST['accountscore'];		//低扣积分
			$tfee=$_POST['amount'];		//三方支付金额
			$paytype=$_POST['paytype'].'';		//三方支付金额
			
			
			$mMPay = D('M/MemberPay');
			$dataInfo=$mMPay->GetByPayNo($orderno);	
			$extendid	=	$dataInfo["extendid"];
			$dataInfo['amount']=$tfee;
			$dataInfo['amountStatus']=0;		
			$dataInfo['accountmoney']=$accountmoney;
			$dataInfo['accountmoneyStatus']=0;		
			$dataInfo['accountscore']=$accountscore;
			$dataInfo['accountscoreStatus']=0;		
			$dataInfo['thirdpaytype']=$paytype;		
			 
			$rdpay=$mMPay->UpdatePay($dataInfo);
			if($rdpay['status'] !==1)
			{
				echo '更新状态失败。'.$rd['status'];
				$this->display();
				return;
			}
		}
		else
		{
			$orderno=session("orderno");	
			$orderType=session("orderType");
			if(empty($orderno))
			{
				$this->display("Pay/error");
				return;
			}
			
			$mMPay = D('M/MemberPay');
			$dataInfo=$mMPay->GetByPayNo($orderno);	
			if(empty($dataInfo))
			{
				return;
			}	
			$extendid	=	$dataInfo["extendid"];
			$tfee=$dataInfo['amount'];				
			$accountmoney=$dataInfo['accountmoney'];
			$accountscore=$dataInfo['accountscore'];
			$paytype=$dataInfo['thirdpaytype'];		
			
		}
		if($dataInfo["Status"]==99)
		{
			$this->display("Pay/success");
			return;
		}
//		//更新各支付金额		
		if($paytype =="wx")
		{
			vendor('Weixinpay.WxPayJsApiPay');
//			 
//			//1、获取openid
	        $tools = new \JsApiPay();
	        $openId = $tools->GetOpenid();
			
			$this->assign('money', $tfee);
			$this->assign('orderType', $orderType);
			$tfee=$tfee * 100;		//整数单位为分
			//如果拼团、查询拼团跳转参数
			if( $orderType== -1)
			{
				$mMPay = D('M/MemberPay');
				$data=$mMPay->GetGroupParaByOrderID($extendid);	
				$groupGoodsId=$data["groupGoodsId"];
				$this->assign('groupGoodsId', $groupGoodsId);
			}
			
			
			$uid=(int)session("uid");			
			if($uid==104)
			{
				$tfee=1;
			}
			$this->assign('title', $Body); 
 
//			
			$Body="订单支付";
			if($dataInfo["PayType"]=="recharge")	
			{
				$subject='粗卡充值';
				$body='粗卡充值';
			}
	        $input = new \WxPayUnifiedOrder();
	        $input->SetBody($Body);
	        $input->SetAttach($orderno);
	        $input->SetOut_trade_no(\WxPayConfig::MCHID.date("YmdHis"));
	        $input->SetTotal_fee($tfee);
	        $input->SetTime_start(date("YmdHis"));
	        $input->SetTime_expire(date("YmdHis", time() + 600));
	        $input->SetGoods_tag("Goods");
	        $input->SetNotify_url("http://cukayun.cn/index.php/M/Pay/notify/");   //支付回调地址，这里改成你自己的回调地址。
	        $input->SetTrade_type("JSAPI");
	        $input->SetOpenid($openId);
//			echo dump($input);
//			exit;
	        $order = \WxPayApi::unifiedOrder($input);
//			echo dump($order);


	        $jsApiParameters = $tools->GetJsApiParameters($order);
	        $this->jsApiParameters=$jsApiParameters;
			
			//与用户的openid作一个比较
			$this->display("thirdpay");
		}
		else	//支付宝支付
		{	 
			header('Location:'.U('PayAli/index', '','')."/orderno/$orderno");
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
         
		//更新到订单表
		$attach=$attach.'';
		$result_code=$result_code.'';
		
		if($result_code=='SUCCESS')
		{
			$parameter = array(
				"OrderNo"			=> $attach, //商户订单编号；
				"transaction_id"	=> $transaction_id,     //支付宝交易号；
				"cash_fee"			=> $total_fee,    //交易金额；
			); 
			$res=$this->PayNotify($parameter);
			if($res["status"]==1)
			{
				$content="--微信支付成功--订单号：$attach , 交易号:$transaction_id----";
				logger($content);
				echo 'SUCCESS';
				return;
			}
		} 
		$content="-----------------出错啦-----------------";
		$content=$content.',PayType='.$dataInfo["PayType"].',Status='.$dataInfo["Status"];
		logger($content);
		logger($GLOBALS['HTTP_RAW_POST_DATA']);		 
		echo 'Fail';
    }


	
	 

 
}