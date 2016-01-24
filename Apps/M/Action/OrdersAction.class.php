<?php
namespace M\Action;
/**
 * ============================================================================
 * 粗卡云:
  
 * 联系方式:
 * ============================================================================
 * 订单控制器
 */
class OrdersAction extends BaseUserAction {
	/**
	 * 快餐提交订单
	 */
	public function fast() {
		$this->assign('title', '订单确认');
		$this->display();
	}
	
	/**
	 * 商品提交订单
	 */
	public function shop() {
		$this->assign('title', '订单确认');
		$this->display();
	}
	
	
	/**
	 * 添加备注
	 */
	public function remarks() {
		$this->assign('title', '添加备注');
		$this->display();
	}
	
	public function detail() {
		$orderId = I('id');
		$map = array('orderId' => $orderId, 'userId' => getuid());
		
		// 获取订单信息	
		$m = D('M/Orders');
		$data = $m->getOrdersDetails($map);
		$this->assign('data', $data);
		
//		echo $m->getLastSql();
//		echo dump($data);
		
		// 获取订单商品里列表
		$gdb = D('M/OrderGoods');
		$goods = $gdb->goods($map);
		$this->assign('goods', $goods);
		
		$this->assign('title', $data['shopName']);
		
//		echo $gdb->getLastSql();
//		echo dump($goods);
		
		$this->display();
	}
	
	public function lst() {
		$this->assign('title', '订单');
		$this->display('list');
	}
	
	public function page() {
		$m = D('M/Orders');
		$map = array('userId' => getuid());
		$list = $m->query($map);
//		echo $m->getLastSql();
		$this->ajaxReturn($list, 'JSON');
	}
	/* 跳转到支付页面  */
	
	public function pay() {
		$m = D('M/Orders');
		$data = $m->getOrdersItem();
		//$data = $data[0];
		//获取订单ID
		$orderId=I("orderId");
		session("money", (float)$data['needPay']);
		session("type", 'order');
		session("orderid", $orderId);
		echo dump($data);
		//payType 是否在线支付 0 货到付款 1在线支付
		$payType=$data['payType'].'';
		echo '$payType='.$payType;
		if($payType==2)//余额支付
		{
			$this->redirect('Pay/payvalue');
		}
		else if($payType==1)//在线支付 
		{
			$this->redirect('Pay/index');
		}
	}
	
	/**
	 * 系统超时关闭订单
	 */
	public function close() {
		$obj = array(
			'userId'		=> getuid(),
			'orderId'		=> I('id'),
		);
		$m = D('M/Orders');
		$ret = $m->close($obj);
		$rdata['status'] = $ret ? 0 : -1;
		$this->ajaxReturn($rdata, 'JSON');
	}
	
	public function remove() {
		$obj = array(
			'userId'		=> getuid(),
			'orderId'		=> I('id'),
		);
		$m = D('M/Orders');
		$ret = $m->remove($obj);
//		echo $m->getLastSql();
		$rdata['status'] = $ret ? 0 : -1;
		$this->ajaxReturn($rdata, 'JSON');
	}
	
	//---------------
	// 源代码
	//---------------	
	
	/**
	 * 获取待付款的订单列表
	 */
	public function queryByPage(){
		$this->isUserLogin();
		$USER = session('RTC_USER');
		session('RTC_USER.loginTarget','User');
		//判断会员等级
		$morders = D('Home/UserRanks');
		session('RTC_USER.userRank',$morders->checkUserRank($USER['userScore']));
		//获取订单列表
		$morders = D('Home/Orders');
		$obj["userId"] = (int)$USER['userId'];
		$orderList = $morders->queryByPage($obj);
		$statusList = $morders->getUserOrderStatusCount($obj);
		$this->assign("umark","queryByPage");
		$this->assign("orderList",$orderList);
		$this->assign("statusList",$statusList);
		$this->display("default/users/orders/list");
	}
	/**
	 * 获取待付款的订单列表
	 */
	public function queryPayByPage(){
		$this->isUserLogin();
		$USER = session('RTC_USER');
		$morders = D('Home/Orders');
		self::RTCAssigns();
		$obj["userId"] = (int)$USER['userId'];
		$payOrders = $morders->queryPayByPage($obj);
		$this->assign("umark","queryPayByPage");
		$this->assign("payOrders",$payOrders);
		$this->display("default/users/orders/list_pay");
	}
    /**
	 * 获取待发货的订单列表
	 */
	public function queryDeliveryByPage(){
		$this->isUserLogin();
		$USER = session('RTC_USER');
		$morders = D('Home/Orders');
		self::RTCAssigns();
		$obj["userId"] = (int)$USER['userId'];
		$deliveryOrders = $morders->queryDeliveryByPage($obj);
		$this->assign("umark","queryDeliveryByPage");
		$this->assign("receiveOrders",$deliveryOrders);
		$this->display("default/users/orders/list_delivery");
	}
    /**
	 * 获取退款订单列表
	 */
	public function queryRefundByPage(){
		$this->isUserLogin();
		$USER = session('RTC_USER');
		$morders = D('Home/Orders');
		self::RTCAssigns();
		$obj["userId"] = (int)$USER['userId'];
		$refundOrders = $morders->queryRefundByPage($obj);
		$this->assign("umark","queryRefundByPage");
		$this->assign("receiveOrders",$refundOrders);
		$this->display("default/users/orders/list_refund");
	}
    /**
	 * 获取收货的订单列表
	 */
	public function queryReceiveByPage(){
		$this->isUserLogin();
		$USER = session('RTC_USER');
		$morders = D('Home/Orders');
		self::RTCAssigns();
		$obj["userId"] = (int)$USER['userId'];
		$receiveOrders = $morders->queryReceiveByPage($obj);
		$this->assign("umark","queryReceiveByPage");
		$this->assign("receiveOrders",$receiveOrders);
		$this->display("default/users/orders/list_receive");
	}
	
	/**
	 * 获取已取消订单
	 */
	public function queryCancelOrders(){
		$this->isUserLogin();
		$USER = session('RTC_USER');
		$morders = D('Home/Orders');
		self::RTCAssigns();
		$obj["userId"] = (int)$USER['userId'];
		$receiveOrders = $morders->queryCancelOrders($obj);
		$this->assign("umark","queryCancelOrders");
		$this->assign("receiveOrders",$receiveOrders);
		$this->display("default/users/orders/list_cancel");
	}
    
	/**
	 * 获取待评价订单
	 */
    public function queryAppraiseByPage(){
    	$this->isUserLogin();
    	$USER = session('RTC_USER');
    	$morders = D('Home/Orders');
    	self::RTCAssigns();
    	$obj["userId"] = (int)$USER['userId'];
		$appraiseOrders = $morders->queryAppraiseByPage($obj);
		$this->assign("umark","queryAppraiseByPage");
		$this->assign("appraiseOrders",$appraiseOrders);
		$this->display("default/users/orders/list_appraise");
	} 
	
	
	/**
	 * 订单詳情-买家专用
	 */
	public function getOrderInfo(){
		$this->isUserLogin();
		$USER = session('RTC_USER');
		$morders = D('Home/Orders');
		$obj["userId"] = (int)$USER['userId'];
		$obj["orderId"] = I("orderId");
		$rs = $morders->getOrderDetails($obj);
		$data["orderInfo"] = $rs;
		$this->assign("orderInfo",$rs);
		$this->display("default/order_details");
	}
	
	/**
	 * 取消订单
	 */
    public function cancel(){
    	$morders = D('M/Orders');
    	$obj["userId"] = getuid();
    	$obj["orderId"] = I("orderId");
		$rs = $morders->orderCancel($obj);
		$this->ajaxReturn($rs);
	} 
	
	/**
	 * 用户确认收货订单
	 */
    public function orderConfirm(){
    	$this->isUserAjaxLogin();
    	$USER = session('RTC_USER');
    	$morders = D('Home/Orders');
    	$obj["userId"] = (int)$USER['userId'];
    	$obj["orderId"] = I("orderId");
    	$obj["type"] = (int)I("type");
		$rs = $morders->orderConfirm($obj);
		$this->ajaxReturn($rs);
	} 
	
	/**
	 * 核对订单信息
	 */
	public function checkOrderInfo(){
		$this->isUserLogin();
		$mareas = D('Home/Areas');
		$morders = D('Home/Orders');
		$mgoods = D('Home/Goods');
		$maddress = D('Home/UserAddress');
		$gtotalMoney = 0;//商品总价（去除配送费）
		$totalMoney = 0;//商品总价（含配送费）
		$totalCnt = 0;
		$shopcat = session("RTC_CART")?session("RTC_CART"):array();	
		$catgoods = array();
	
		$shopColleges = array();
		$startTime = 0;
		$endTime = 24;
	    if(empty($shopcat)){
			$this->assign("fail_msg",'不能提交空商品的订单!');
			$this->display('default/order_fail');
			exit();
		}
		$paygoods = array();
		foreach($shopcat as $key=>$cgoods){
			$obj = array();
			$temp = explode('_',$key);		
			$obj["goodsId"] = (int)$temp[0];
			$obj["goodsAttrId"] = (int)$temp[1];	
			if($cgoods["ischk"]==1){
				$paygoods[] = $obj["goodsId"];
				$goods = $mgoods->getGoodsForCheck($obj);
				if($goods["isBook"]==1){
					$goods["goodsStock"] = $goods["goodsStock"]+$goods["bookQuantity"];
				}
				$goods["ischk"] = $cgoods["ischk"];
				$goods["cnt"] = $cgoods["cnt"];
				$totalCnt += $cgoods["cnt"];
				$totalMoney += $goods["cnt"]*$goods["shopPrice"];
				$gtotalMoney += $goods["cnt"]*$goods["shopPrice"];
				$ommunitysId = $maddress->getShopCommunitysId($goods["shopId"]);
				$shopColleges[$goods["shopId"]] = $ommunitysId;			
				if($startTime<$goods["startTime"]){
					$startTime = $goods["startTime"];
				}
				if($endTime>$goods["endTime"]){
					$endTime = $goods["endTime"];
				}
	
				$catgoods[$goods["shopId"]]["shopgoods"][] = $goods;
				$catgoods[$goods["shopId"]]["deliveryFreeMoney"] = $goods["deliveryFreeMoney"];//店铺免运费最低金额
				$catgoods[$goods["shopId"]]["deliveryMoney"] = $goods["deliveryMoney"];//店铺配送费
				$catgoods[$goods["shopId"]]["deliveryStartMoney"] = $goods["deliveryStartMoney"];//店铺配送费
				$catgoods[$goods["shopId"]]["totalCnt"] = $catgoods[$goods["shopId"]]["totalCnt"]+$cgoods["cnt"];
				$catgoods[$goods["shopId"]]["totalMoney"] = $catgoods[$goods["shopId"]]["totalMoney"]+($goods["cnt"]*$goods["shopPrice"]);
			}
		}
		
		foreach($catgoods as $key=> $cshop){
			if($cshop["totalMoney"]<$cshop["deliveryFreeMoney"]){
				$totalMoney = $totalMoney + $cshop["deliveryMoney"];
			}
		}
		session('RTC_PAY_GOODS',$paygoods);
		$USER = session('RTC_USER');
		//获取地址列表
        $areaId2 = $this->getDefaultCity();
		$addressList = $maddress->queryByUserAndCity($USER['userId'],$areaId2);
		$this->assign("addressList",$addressList);
		$this->assign("areaId2",$areaId2);
		//支付方式
		$pm = D('Home/Payments');
		$payments = $pm->getList();
		$this->assign("payments",$payments);
		
		//获取当前市的县区
		$m = D('Home/Areas');
		$areaList2 = $m->getDistricts($areaId2);
		$this->assign("areaList2",$areaList2);
		if($endTime==0){
			$endTime = 24;
			$cstartTime = (floor($startTime))*4;
			$cendTime = (floor($endTime))*4;
		}else{
			$cstartTime = (floor($startTime)+1)*4;
			$cendTime = (floor($endTime)+1)*4;
		}
		if(floor($startTime)<$startTime){
			$cstartTime = $cstartTime + 2;
		}
		if(floor($endTime)<$endTime){
			$cendTime = $cendTime + 2;
		}

		$this->assign("startTime",$cstartTime);
		$this->assign("endTime",$cendTime);
		$this->assign("shopColleges",$shopColleges);
		$this->assign("catgoods",$catgoods);
		$this->assign("gtotalMoney",$gtotalMoney);
		$this->assign("totalMoney",$totalMoney);
		$this->display('default/check_order');
	}
	
	/**
	 * 提交订单信息
	 * 
	 */
	public function submitOrder(){	
		$mshop = D('M/Shops');
		$mgoods = D('M/Goods');
		$morders = D('M/Orders');
		$userId = getuid();
		
		$consigneeId = (int)I("consigneeId");
		$payway = (int)I("payway"); // 支付途径
		$isself = (int)I("isself"); // 是否自取
		$cartGoods = (array)json_decode(html_entity_decode(stripslashes(I('goods'))));
		$needreceipt = (int)I("needreceipt"); // 是否需要票据
		$orderunique = I("orderunique"); 
		
		$result = array('status' => 0);
		
//		echo '-----';
//		echo html_entity_decode(html_entity_decode(stripslashes(I('goods'))));
//		echo dump($cartGoods);
//		exit();
		
		$shopGoods = array();	
		$order = array();
		// 整理及核对购物车
		foreach($cartGoods as $key => $cg) {
			$goodsId = $cg->goodsId;
			$count = $cg->count;
			$goods = $mgoods->info($goodsId,$goodsAttrId);
			if(empty($goods)) {
				$result['status']  = -1;
				$result['data'] = '对不起，商品'.$goods['goodsName'].'不存在!';
				break;
			}
			if(intval($goods['goodsStock']) <= 0) {
				$result['status']  = -2;
				$result['data'] = '对不起，商品'.$goods['goodsName'].'库存不足!';
				break;
			}
			if(intval($goods['isSale']) != 1){
				$result['status']  = -3;
				$result['data'] = '对不起，商品库'.$goods['goodsName'].'已下架!';
				break;
			}
			
			$goods["cnt"] = $count;
			$shopGoods[$goods["shopId"]]["shopgoods"][] = $goods;
			$shopGoods[$goods["shopId"]]["deliveryFreeMoney"] = $goods["deliveryFreeMoney"];//店铺免运费最低金额
			$shopGoods[$goods["shopId"]]["deliveryMoney"] = $goods["deliveryMoney"];//店铺免运费最低金额
			$shopGoods[$goods["shopId"]]["totalCnt"] = $shopGoods[$goods["shopId"]]["totalCnt"]+$cgoods["cnt"];
			$shopGoods[$goods["shopId"]]["totalMoney"] = $shopGoods[$goods["shopId"]]["totalMoney"]+($goods["cnt"]*$goods["shopPrice"]);
		}
		
		if($result['status'] == 0) {
			$result['data'] = $morders->addOrders($userId,$consigneeId,$payway,$needreceipt,$shopGoods,$orderunique,$isself);
		}
		
		$this->ajaxReturn($result, 'JSON');
	}
	
	/**
	 * 检查是否已支付
	 */
	public function checkOrderPay(){
		$morders = D('Home/Orders');
		$USER = session('RTC_USER');
		$obj["userId"] = (int)$USER['userId'];
		$obj["orderIds"] = I("orderIds");
		$rs = $morders->checkOrderPay($obj);
		$this->ajaxReturn($rs);
	}
	
	
	/**
	 * 订单詳情
	 */
	public function getOrderDetails(){
		$this->isUserLogin();
		$USER = session('RTC_USER');
		$morders = D('Home/Orders');
		$obj["userId"] = (int)$USER['userId'];
		$obj["shopId"] = (int)$USER['shopId'];
		$obj["orderId"] = I("orderId");
		$rs = $morders->getOrderDetails($obj);
		$data["orderInfo"] = $rs;
		$this->assign("orderInfo",$rs);
		$this->display("default/users/orders/details");
	}
	
	/*************************************************************************/
	/********************************商家訂單管理*****************************/
	/*************************************************************************/
	/**
	 * 跳转到商家订单列表
	*/
	public function toShopOrdersList(){
		$this->isShopLogin();
		$morders = D('Home/Orders');
		$this->assign("umark","toShopOrdersList");		
		$this->display("default/shops/orders/list");
	}
	/**
	 * 获取商家订单列表
	*/
	public function queryShopOrders(){
		$this->isShopAjaxLogin();
		$USER = session('RTC_USER');
		$morders = D('Home/Orders');
		$obj["shopId"] = (int)$USER["shopId"];
		$obj["userId"] = (int)$USER['userId'];
		$orders = $morders->queryShopOrders($obj);
		
		$this->ajaxReturn($orders);
	}
	/**
	 * 商家受理订单
	 */
    public function shopOrderAccept(){
    	$this->isShopAjaxLogin();
    	$USER = session('RTC_USER');
    	$morders = D('Home/Orders');
    	$obj["userId"] = (int)$USER['userId'];
    	$obj["shopId"] = (int)$USER['shopId'];
    	$obj["orderId"] = I("orderId");
		$rs = $morders->shopOrderAccept($obj);
		$this->ajaxReturn($rs);
	} 
    /**
	 * 商家批量受理订单
	 */
    public function batchShopOrderAccept(){
    	$this->isShopAjaxLogin();
    	$morders = D('Home/Orders');
		$rs = $morders->batchShopOrderAccept($obj);
		$this->ajaxReturn($rs);
	}
	/**
	 * 商家生产订单
	 */
    public function shopOrderProduce(){
    	$this->isShopAjaxLogin();
    	$USER = session('RTC_USER');
    	$morders = D('Home/Orders');
    	$obj["userId"] = (int)$USER['userId'];
    	$obj["shopId"] = (int)$USER['shopId'];
    	$obj["orderId"] = I("orderId");
		$rs = $morders->shopOrderProduce($obj);
		$this->ajaxReturn($rs);
	} 
	public function batchShopOrderProduce(){
    	$this->isShopAjaxLogin();
    	$morders = D('Home/Orders');
		$rs = $morders->batchShopOrderProduce($obj);
		$this->ajaxReturn($rs);
	} 
	/**
	 * 商家发货配送订单
	 */
    public function shopOrderDelivery(){
    	$this->isShopAjaxLogin();
    	$USER = session('RTC_USER');
    	$morders = D('Home/Orders');
    	$obj["userId"] = (int)$USER['userId'];
    	$obj["shopId"] = (int)$USER['shopId'];
    	$obj["orderId"] = I("orderId");
		$rs = $morders->shopOrderDelivery($obj);
		$this->ajaxReturn($rs);
	} 
	
    /**
	 * 商家发货配送订单
	 */
    public function batchShopOrderDelivery(){
    	$this->isShopAjaxLogin();
    	$morders = D('Home/Orders');
		$rs = $morders->batchShopOrderDelivery($obj);
		$this->ajaxReturn($rs);
	}
	
	/**
	 * 商家确认收货订单
	 */
    public function shopOrderReceipt(){
    	$this->isShopAjaxLogin();
    	$USER = session('RTC_USER');
    	$morders = D('Home/Orders');
    	$obj["userId"] = (int)$USER['userId'];
    	$obj["shopId"] = (int)$USER['shopId'];
    	$obj["orderId"] = I("orderId");
		$rs = $morders->shopOrderReceipt($obj);
		$this->ajaxReturn($rs);
	} 
	
	/**
	 * 商家同意拒收/不同意拒收
	 */
	public function shopOrderRefund(){
		$this->isShopAjaxLogin();
		$USER = session('RTC_USER');
    	$morders = D('Home/Orders');
    	$obj["userId"] = (int)$USER['userId'];
    	$obj["shopId"] = (int)$USER['shopId'];
    	$obj["orderId"] = I("orderId");
		$rs = $morders->shopOrderRefund($obj);
		$this->ajaxReturn($rs);
	}
	
	/**
	 * 获取用户订单消息提示
	 */
	public function getUserMsgTips(){
		$this->isUserAjaxLogin();
		$morders = D('Home/Orders');
		$USER = session('RTC_USER');
		$obj["userId"] = (int)$USER['userId'];
		$statusList = $morders->getUserOrderStatusCount($obj);
		$this->ajaxReturn($statusList);
	}
	
	/**
	 * 获取店铺订单消息提示
	 */
	public function getShopMsgTips(){
		$this->isShopAjaxLogin();
		$morders = D('Home/Orders');
		$USER = session('RTC_USER');
		$obj["shopId"] = (int)$USER['shopId'];
		$obj["userId"] = (int)$USER['userId'];
		$statusList = $morders->getShopOrderStatusCount($obj);
		$this->ajaxReturn($statusList);
	}
	
}