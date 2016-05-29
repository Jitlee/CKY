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
class PayAliAction extends BaseUserAction {
	
//	public function _initialize() {
//      vendor('Alipay.alipay_submit');
//  }
	public function _initialize() {
		vendor('Alipay.Corefunction');
		vendor('Alipay.Md5function');
		vendor('Alipay.Submit');    
		vendor('Alipay.Notify');
	}

	Public function index(){
	 	$this->assign('title', "支付成功");
		
		$ptyno=strftime('%Y%m%d%H%M%S',time());
		$WIDtotal_fee="0.01";
		$WIDsubject="TP测试";
		
		$this->assign('WIDout_trade_no', $ptyno);
		$this->assign('WIDsubject', $WIDsubject);
		$this->assign('WIDtotal_fee', $WIDtotal_fee); 
		
		$this->display();	
	}
 
	 Public function payapi(){
//		require_once("alipay.config.php");
//		require_once("lib/alipay_submit.class.php");
        $out_trade_no = $_POST['WIDout_trade_no'];//商户订单号，商户网站订单系统中唯一订单号，必填
        $subject = $_POST['WIDsubject'];//订单名称，必填        
        $total_fee = $_POST['WIDtotal_fee'];//付款金额，必填        
        $show_url = $_POST['WIDshow_url'];//收银台页面上，商品展示的超链接，必填        
        $body = $_POST['WIDbody'];//商品描述，可空		
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
		 
//		echo $alipay_config['partner'];
		$alipaySubmit = new \AlipaySubmit($alipay_config);
		$html_text = $alipaySubmit->buildRequestForm($parameter,"get", "确认");
		echo $html_text;
	 }

	 Public function notify(){
	 	/*同理去掉以下两句代码；*/ 
        //require_once("alipay.config.php");
        //require_once("lib/alipay_notify.class.php");
                
        //这里还是通过C函数来读取配置项，赋值给$alipay_config
        $alipay_config=C('alipay_config');

		$content="/************接收到通知信息*************/";
		logger($content);
        //计算得出通知验证结果
       
        $alipayNotify = new \AlipayNotify($alipay_config);
        $verify_result = $alipayNotify->verifyNotify();
		
		$content='*********$verify_result='.$verify_result;
		logger($content);
		logger($GLOBALS['HTTP_RAW_POST_DATA']);
		
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
			$parameter = array(
				"out_trade_no"     => $out_trade_no, //商户订单编号；
				"trade_no"     => $trade_no,     //支付宝交易号；
				"total_fee"     => $total_fee,    //交易金额；
				"trade_status"     => $trade_status, //交易状态
				"notify_id"     => $notify_id,    //通知校验ID。
				"notify_time"   => $notify_time,  //通知的发送时间。
				"buyer_email"   => $buyer_email,  //买家支付宝帐号；
			);  
			
			if($_POST['trade_status'] == 'TRADE_FINISHED') {
                        //
            }
            else if ($_POST['trade_status'] == 'TRADE_SUCCESS') {
					$attach=$out_trade_no.'';
					$result_code=$trade_no.'';
					//echo $attach;
					$mMPay = D('M/MemberPay');
					$dataInfo=$mMPay->GetByPayNo($attach);
					
					if($dataInfo && $dataInfo["PayType"]=="recharge" && $dataInfo["Status"]==0)	
					{
						 $dataInfo["ChangeTime"]=date('Y-m-d H:i:s');
						 $dataInfo["result_code"]=$result_code.'';
						 $dataInfo["fee_type"]='';
						 $dataInfo["transaction_id"]=$notify_id.'';
						 $dataInfo["cash_fee"]=$total_fee.'';
						 $dataInfo["Status"]=99;
						 
						 $cardid=$dataInfo["cardid"];
						 $result=$mMPay->UpdateRechange($dataInfo,$cardid);
					}
					else if($dataInfo && $dataInfo["PayType"]=="order" && $dataInfo["Status"]==0)	
					{
						 $dataInfo["ChangeTime"]=date('Y-m-d H:i:s');
						 $dataInfo["result_code"]=$result_code.'';
						 $dataInfo["fee_type"]='';
						 $dataInfo["transaction_id"]=$notify_id.'';
						 $dataInfo["cash_fee"]=$total_fee.'';
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

            }
			
			$content=$content.',$out_trade_no='.$out_trade_no.',$trade_status='.$trade_status;
			logger($content);
			logger($GLOBALS['HTTP_RAW_POST_DATA']);
			
            echo "success";        //请不要修改或删除
        }else {
             //验证失败
             echo "fail";
        }  
		
	 }



	/*
	         页面跳转处理方法；
	         这里其实就是将return_url.php这个文件中的代码复制过来，进行处理； 
	 */
	function returnurl(){
	    //头部的处理跟上面两个方法一样，这里不罗嗦了！
	    $alipay_config=C('alipay_config');
 		 
	    $alipayNotify = new \AlipayNotify($alipay_config);//计算得出通知验证结果
	    $verify_result = $alipayNotify->verifyReturn();
	    if($verify_result) {
	        //验证成功
	        //获取支付宝的通知返回参数，可参考技术文档中页面跳转同步通知参数列表
	        $out_trade_no   = $_GET['out_trade_no'];      //商户订单号
	        $trade_no       = $_GET['trade_no'];          //支付宝交易号
	        $trade_status   = $_GET['trade_status'];      //交易状态
	        $total_fee      = $_GET['total_fee'];         //交易金额
	        $notify_id      = $_GET['notify_id'];         //通知校验ID。
	        $notify_time    = $_GET['notify_time'];       //通知的发送时间。
	        $buyer_email    = $_GET['buyer_email'];       //买家支付宝帐号；
	             
	        $parameter = array(
	            "out_trade_no"     => $out_trade_no,      //商户订单编号；
	            "trade_no"     => $trade_no,          //支付宝交易号；
	            "total_fee"      => $total_fee,         //交易金额；
	            "trade_status"     => $trade_status,      //交易状态
	            "notify_id"      => $notify_id,         //通知校验ID。
	            "notify_time"    => $notify_time,       //通知的发送时间。
	            "buyer_email"    => $buyer_email,       //买家支付宝帐号
	        );
	         
	        if($_GET['trade_status'] == 'TRADE_FINISHED' || $_GET['trade_status'] == 'TRADE_SUCCESS') 
	        {
	            $this->redirect("Pay/success");//跳转到配置项中配置的支付成功页面；
	        }
	    }
        //验证失败
        //如要调试，请看alipay_notify.php页面的verifyReturn函数
		$this->redirect("Pay/error"); 
	}

}