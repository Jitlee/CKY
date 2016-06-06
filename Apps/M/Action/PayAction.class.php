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
			session("orderno",$orderno);
			$Body=$_POST['goodsName'].'';
			
			$accountmoney=$_POST['accountmoney'];		//帐户余额支付
			$accountscore=$_POST['accountscore'];		//低扣积分	
			$tfee=$_POST['amount'];		//三方支付金额
			$paytype=$_POST['paytype'].'';		//三方支付金额
			
			$mMPay = D('M/MemberPay');
			$dataInfo=$mMPay->GetByPayNo($orderno);		
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
			if(empty($orderno))
			{
				return;
			}
			
			$mMPay = D('M/MemberPay');
			$dataInfo=$mMPay->GetByPayNo($orderno);	
			if(empty($dataInfo))
			{
				return;
			}	
			$tfee=$dataInfo['amount'];				
			$accountmoney=$dataInfo['accountmoney'];
			$accountscore=$dataInfo['accountscore'];
			$paytype=$dataInfo['thirdpaytype'];		
			
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
			$tfee=$tfee * 100;		//整数单位为分
			
			$uid=(int)session("uid");			
			if($uid==104)
			{
				$tfee=1;
			} 
			$this->assign('title', $Body); 
 
//			
			$Body="订单支付";
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
				/*
				vendor('Alipay.Corefunction');
				vendor('Alipay.Md5function');
				vendor('Alipay.Submit');    
				vendor('Alipay.Notify');
				
				$out_trade_no = $payid;//商户订单号，商户网站订单系统中唯一订单号，必填
		        $subject =$Body;// $_POST['WIDsubject'];//订单名称，必填        
		        $total_fee =$tfee;// $_POST['WIDtotal_fee'];//付款金额，必填        
		        $show_url ="";// $_POST['WIDshow_url'];//收银台页面上，商品展示的超链接，必填        
		        $body = "";//$_POST['WIDbody'];//商品描述，可空		
				$alipay_config=C('alipay_config');  
		        $parameter = array(
						"service"       => $alipay_config['service'],
						"partner"       => $alipay_config['partner'],
						"seller_id"  	=> $alipay_config['seller_id'],
						"payment_type"	=> $alipay_config['payment_type'],
						"notify_url"	=> $alipay_config['notify_url'],
						"return_url"	=> $alipay_config['return_url'],
						"_input_charset"	=> trim(strtolower($alipay_config['input_charset'])),
						"out_trade_no"	=> $out_trade_no,
						"subject"	=> $subject,
						"total_fee"	=> $total_fee,
						"show_url"	=> $show_url,
						"body"	=> $body,
				);
				//建立请求
				//echo $alipay_config['partner'];
				$alipaySubmit = new \AlipaySubmit($alipay_config);
				$html_text = $alipaySubmit->buildRequestForm($parameter,"get", "确认");
				echo $html_text;
				 
				 */
//			}
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
			 
			if($result["status"] == 1)//订单支付状态
			{
				echo 'SUCCESS';
				return;			 
			} 
		}
		else if($dataInfo && $dataInfo["PayType"]=="order" && $dataInfo["Status"]==0 && $result_code=='SUCCESS')	
		{
			 $dataInfo["ChangeTime"]=date('Y-m-d H:i:s');
			 $dataInfo["result_code"]=$result_code.'';
			 $dataInfo["fee_type"]=$fee_type.'';
			 $dataInfo["transaction_id"]=$transaction_id.'';
			 $dataInfo["cash_fee"]=$cash_fee.'';
			 $dataInfo["Status"]=99;
			 
			 
			$tfee=$dataInfo['amount'];
			$accountmoney=$dataInfo['accountmoney'];					
			$accountscore=$dataInfo['accountscore'];
			//扣余额
			$orderid=$dataInfo["extendid"]; 
			if($accountmoney >0 || $accountscore>0)
			{
				$cardid=$dataInfo["cardid"];
				$res=$mMPay->OrderValuePay($dataInfo,$cardid);
				 //更新订单状态
				
				if($res["status"] == 0)//返回状态
				{
					$wxm= new WxNotify();
					$wxm->SendOrderNotifyToShops($orderid);
					echo 'SUCCESS';
					return;			 
				}
			}
			else //直接在线支付
			{
				$cardid=$dataInfo["cardid"];
			 	$result=$mMPay->UpdatePayOrder($dataInfo);	
				if($result["status"] == 1)//订单支付状态
				{
					$wxm= new WxNotify();
					$wxm->SendOrderNotifyToShops($orderid);
					echo 'SUCCESS';
					return;			 
				} 
			}
		}
		else if($dataInfo  && $dataInfo["Status"]==99)
		{
			echo 'SUCCESS';
			return;
		}
		else
		{
			$content="-----------------未处理的对象-----------------";
			$content=$content."attach=$attach===result_code=$result_code";
			logger($content);
			echo 'SUCCESS';
			return;		
		}
		 
		$content="-----------------出错啦-----------------";
		$content=$content.',PayType='.$dataInfo["PayType"].',Status='.$dataInfo["Status"];
		logger($content);
		logger($GLOBALS['HTTP_RAW_POST_DATA']);
		 
		echo 'Fail';
        //需要记住一点，就是最后在输出一个success.要不然微信会一直发送回调包的，只有需出了succcess微信才确认已接收到信息不会再发包.
        
    }


	
	 

 
}