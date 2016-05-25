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
	
	public function _initialize() {
        vendor('Alipay.alipay.config');
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

        //商户订单号，商户网站订单系统中唯一订单号，必填
        $out_trade_no = $_POST['WIDout_trade_no'];
        //订单名称，必填
        $subject = $_POST['WIDsubject'];
        //付款金额，必填
        $total_fee = $_POST['WIDtotal_fee'];
        //收银台页面上，商品展示的超链接，必填
        $show_url = $_POST['WIDshow_url'];
        //商品描述，可空
        $body = $_POST['WIDbody'];

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
		echo $alipay_config['partner'];
//		$alipaySubmit = new \AlipaySubmit($alipay_config);
//		$html_text = $alipaySubmit->buildRequestForm($parameter,"get", "确认");
		echo $html_text;
		$this->display();	
	 }
}