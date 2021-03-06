<?php
 namespace Home\Action;;
/**
 * ============================================================================
 * 粗卡云:
  
 * 联系方式:
 * ============================================================================
 * 品牌控制器
 */
class PaymentAction extends BaseAction{
	
	/**
	 * 获取支付宝URL
	 */
    public function getAlipayURL(){
    	$this->isUserLogin();
    	
    	$morders = D('Home/Orders');
		$USER = session('RTC_USER');
		$obj["userId"] = (int)$USER['userId'];
		$obj["orderIds"] = I("orderIds");
		$data = $morders->checkOrderPay($obj);
		
    	if($data["status"]){
    		$m = D('Home/Payment');
    		$url =  $m->getAlipayUrl();
    		$data["url"] = $url;
    	}

		$this->ajaxReturn($data);
		
	}
	
	/**
	 * 支付
	 */
	public function toPay(){
		$this->isUserLogin();
		$USER = session('RTC_USER');
		$morders = D('Home/Orders');
		//支付方式
		$pm = D('Home/Payment');
		$payments = $pm->getList();
		$this->assign("payments",$payments["onlines"]);
		$orderIds = I("orderIds");
		$obj["orderIds"] = $orderIds;
		$data = $morders->getPayOrders($obj);
		$orders = $data["orders"];
		$needPay = $data["needPay"];
		$this->assign("orderIds",$orderIds);
		$this->assign("orders",$orders);
		$this->assign("needPay",$needPay);
		$this->assign("orderCnt",count($orders));
		$this->display('default/order_pay');
	}
	
	

	
	/**
	 * 支付结果同步回调
	 */
	function response(){
		$request = $_GET;
		unset($request['_URL_']);
		$pay_res = D('Payment')->notify($request);
		if($pay_res['status']){
			//支付成功业务逻辑
		}else{
			$this->error('支付失败');
		}
	}
	
	/**
	 * 支付结果异步回调
	 */
	function notify(){
		$pm = D('Home/Payment');
		$request = $_POST;
		$pay_res = $pm->notify($request);
		if($pay_res['status']){
			
			//商户订单号
			$obj = array();
			$obj["out_trade_no"] = $_POST['out_trade_no'];
			$obj["total_fee"] = $_POST['total_fee'];
			$obj["userId"] = $_POST['extra_common_param'];
			//支付成功业务逻辑
			
			$payments = $pm->complatePay($obj);
			echo 'success';
		}else{
			
			echo 'fail';
		}
	}
	
	
	
	
	
};
?>