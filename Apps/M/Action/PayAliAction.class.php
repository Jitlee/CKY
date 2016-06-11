<?php
namespace M\Action;
/**
 * ============================================================================
 * 粗卡云:
  
 * 联系方式:
 * ============================================================================
 * 支付宝支付控件类
 */
use Think\Controller;
class PayAliAction extends PayBaseAction{
	
//	public function _initialize() {
//      vendor('Alipay.alipay_submit');
//  }
	public function _initialize() {
		vendor('Alipay.Corefunction');
		vendor('Alipay.Md5function');
		vendor('Alipay.Submit');    
		vendor('Alipay.Notify');
	}
	
	function is_weixin(){  
	    if ( strpos($_SERVER['HTTP_USER_AGENT'], 'MicroMessenger') !== false ) {  
	            return true;  
	    }    
	    return false;  
	}
	

	Public function index($orderno=''){
		$mMPay = D('M/MemberPay');
		$dataInfo=$mMPay->GetByPayNo($orderno);
		if(empty($dataInfo))
		{
			$this->display("error");
			return;
		}
		if($this->is_weixin())
		{
			if($dataInfo["Status"]==99)
			{
				$this->display("Pay/success");
				return;
			}
			$this->display("payinwx");
		}
		else
		{
			if($dataInfo["Status"]==99)
			{
				$this->display("success");
				return;
			}
			$tfee=$dataInfo['amount'];
			$accountmoney=$dataInfo['accountmoney'];
			$accountscore=$dataInfo['accountscore'];
			$paytype=$dataInfo['thirdpaytype'];	
			
			
			$uid=$dataInfo["uid"];			
			if($uid==104)
			{
				$tfee=0.02;
			}
			
			$out_trade_no = $orderno;//商户订单号，商户网站订单系统中唯一订单号，必填
			$subject ='粗卡订单';// $_POST['WIDsubject'];//订单名称，必填        
			$total_fee =$tfee;// $_POST['WIDtotal_fee'];//付款金额，必填        
			$show_url ="";// $_POST['WIDshow_url'];//收银台页面上，商品展示的超链接，必填        
			$body = "粗卡订单";//$_POST['WIDbody'];//商品描述，可空
			if$dataInfo["PayType"]=="recharge")	
			{
				$subject='粗卡充值';
				$body='粗卡充值';
			}		
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
			$alipaySubmit = new \AlipaySubmit($alipay_config);
			$html_text = $alipaySubmit->buildRequestForm($parameter,"get", "确认");
			echo $html_text;	
	
		}		
	}

	public function indextest()
	{
	 	$this->assign('title', "支付成功");
		
		$ptyno=strftime('%Y%m%d%H%M%S',time());
		$WIDtotal_fee="0.01";
		$WIDsubject="TP测试";		
		$this->assign('WIDout_trade_no', $ptyno);
		$this->assign('WIDsubject', $WIDsubject);
		$this->assign('WIDtotal_fee', $WIDtotal_fee); 
		$this->display("index");
	}
 
//	 Public function payapi(){
//      $out_trade_no = $_POST['WIDout_trade_no'];//商户订单号，商户网站订单系统中唯一订单号，必填
//      $subject = $_POST['WIDsubject'];//订单名称，必填        
//      $total_fee = $_POST['WIDtotal_fee'];//付款金额，必填        
//      $show_url = $_POST['WIDshow_url'];//收银台页面上，商品展示的超链接，必填        
//      $body = $_POST['WIDbody'];//商品描述，可空		
//		$alipay_config=C('alipay_config');  
//      $parameter = array(
//				"service"       => $alipay_config['service'],
//				"partner"       => $alipay_config['partner'],
//				"seller_id"  	=> $alipay_config['seller_id'],
//				"payment_type"	=> $alipay_config['payment_type'],
//				"notify_url"	=> $alipay_config['notify_url'],
//				"return_url"	=> $alipay_config['return_url'],
//				"_input_charset"	=> trim(strtolower($alipay_config['input_charset'])),
//				"out_trade_no"	=> $out_trade_no,
//				"subject"	=> $subject,
//				"total_fee"	=> $total_fee,
//				"show_url"	=> $show_url,
//				"body"	=> $body,
//		); 
//		$alipaySubmit = new \AlipaySubmit($alipay_config);
//		$html_text = $alipaySubmit->buildRequestForm($parameter,"get", "确认");
//		echo $html_text;
//	 }

	 Public function notify(){        
        //这里还是通过C函数来读取配置项，赋值给$alipay_config
        $alipay_config=C('alipay_config');
       
        $alipayNotify = new \AlipayNotify($alipay_config);
        $verify_result = $alipayNotify->verifyNotify(); 
		
        if($verify_result) {
           //验证成功
           //获取支付宝的通知返回参数，可参考技术文档中服务器异步通知参数列表
			$out_trade_no   = $_POST['out_trade_no'];      //商户订单号
			$trade_no       = $_POST['trade_no'];          //支付宝交易号
			$trade_status   = $_POST['trade_status'];      //交易状态
			$total_fee      = $_POST['total_fee'];         //交易金额
			$notify_id      = $_POST['notify_id'];         //通知校验ID。
			$notify_time    = $_POST['notify_time'];       //通知的发送时间。格式为yyyy-MM-dd HH:mm:ss。
			$buyer_email    = $_POST['buyer_email'];       //买家支付宝帐号；

			$cash_fee=$total_fee*100;
			if($_POST['trade_status'] == 'TRADE_FINISHED') {               
				$content=$content."------处理失败------订单号：$out_trade_no，交易号：$trade_no";
				logger($content);
            }
            else if ($_POST['trade_status'] == 'TRADE_SUCCESS') {
				$parameter = array(
					"OrderNo"     => $out_trade_no, //商户订单编号；
					"transaction_id"     => $trade_no,     //支付宝交易号；
					"cash_fee"     => $cash_fee,    //交易金额；
//					"trade_status"     => $trade_status, //交易状态
//					"notify_id"     => $notify_id,    //通知校验ID。
//					"notify_time"   => $notify_time,  //通知的发送时间。
//					"buyer_email"   => $buyer_email,  //买家支付宝帐号；
				); 
				$res=$this->PayNotify($parameter);
				if($res["status"]==1)
				{
					$content="--支付宝支付成功--订单号：$out_trade_no , 交易号:$trade_no----";
					logger($content);
					echo 'success';
					return;
				}
			}
			
			$content="-----------------支付成功，但是处理出错啦-----------------";
			$content=$content.',PayType='.$dataInfo["PayType"].',Status='.$dataInfo["Status"];
			logger($content);
			logger($GLOBALS['HTTP_RAW_POST_DATA']);
			echo 'Fail';
        }
        else {      
             echo "fail";       //验证失败
        }  
		
	 }

	/*
	         页面跳转处理方法；
	         这里其实就是将return_url.php这个文件中的代码复制过来，进行处理； 
	 */
	function returnurl(){	    
	    $alipay_config=C('alipay_config');
	    $alipayNotify = new \AlipayNotify($alipay_config);//计算得出通知验证结果
	    $verify_result = $alipayNotify->verifyReturn();
		 
	    if($verify_result) {
	        //验证成功
	        $out_trade_no   = $_GET['out_trade_no'];      //商户订单号
	        $trade_no       = $_GET['trade_no'];          //支付宝交易号
	        $trade_status   = $_GET['trade_status'];      //交易状态
	        $total_fee      = $_GET['total_fee'];         //交易金额
	        $notify_id      = $_GET['notify_id'];         //通知校验ID。
	        $notify_time    = $_GET['notify_time'];       //通知的发送时间。
	        $buyer_email    = $_GET['buyer_email'];       //买家支付宝帐号；	         
	        
	        if($_GET['trade_status'] == 'TRADE_SUCCESS') 
	        {
	           $this->display("success");
			   return;
	        }
	    }
		$this->display("error");
	}

}