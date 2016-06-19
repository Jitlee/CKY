<?php
namespace M\Model;
/**
 * ============================================================================
 * 粗卡云:
  
 * 联系方式:
 * ============================================================================
 * 订单服务类
 */
class OrdersModel extends BaseModel {
	
	/*
	 *  获取订单列表
	 */
	public function lst($obj) {
		$userId = $obj["userId"];
		$pageSize = 20;
		$pageNo = intval(I('pageNo', 1));
		$map = array('o.userId'	=> $userId, 'o.orderFlag' => array('neq', -1));
		$field = "o.orderId, orderNo, o.orderType, o.createTime, o.shopId, o.isAppraises, shopName, replace(s.shopImg, '.', '_thumb.') shopImg, (totalMoney + deliverMoney) AS totalMoney,orderStatus, needPay, payType, GROUP_CONCAT(goodsName ORDER BY og.id) goods";
		$join = 'o inner join __SHOPS__ s on o.shopId = s.shopId inner join __ORDER_GOODS__ og on og.orderId = o.orderId';
		$group = 'o.orderId';
		$list = $this->field($field)->join($join)->where($map)
			->order('createTime desc')->group($group)->page($pageNo, $pageSize)->select();
//		echo $this->getLastSql();
		return $list;
	}
	
	public function orderDetailForNotify($orderid) {
		$userId = $obj["userId"];
		 
		$map = array('o.orderId'	=> $orderid);
		$field = "o.orderId, orderNo, o.orderType, o.createTime, o.shopId, o.isAppraises, shopName,s.wxopenid,s.wxopenid1,s.wxopenid2, replace(s.shopImg, '.', '_thumb.') shopImg, (totalMoney + deliverMoney) AS totalMoney,orderStatus, needPay, payType, GROUP_CONCAT(goodsName ORDER BY og.id) goods";
		$join = 'o inner join __SHOPS__ s on o.shopId = s.shopId inner join __ORDER_GOODS__ og on og.orderId = o.orderId';
		$group = 'o.orderId';
		$list = $this->field($field)->join($join)->where($map)
			->order('createTime desc')->group($group)->find();
 
		return $list;
	}
	
	/**
	 * 超时关闭订单
	 */
	public function close($obj) {
		$userId = $obj["userId"];
		$orderId = $obj["orderId"];
		$map = array(
			'userId'		=> $userId,
			'orderId'		=> $orderId,
			'orderType'		=> array("in", "1,2"), // 快餐和一元购的订单才关闭
			'orderStatus'	=> 0,  // 只有超时的订单才能关闭
		);
		
		$this->orderStatus = -2;
		return $this->where($map)->save();
	}
	
	/**
	 * 移除订单（用户逻辑删除）
	 */
	 public function remove($obj) {
	 	$userId = $obj["userId"];
		$orderId = $obj["orderId"];
		$map = array(
			'userId'		=> $userId,
			'orderId'		=> $orderId,
			'orderStatus'	=> array('IN', array(-1, -2)),  // 只有完成的订单才能逻辑删除
		);
		
		$this->orderFlag = -1;
		return $this->where($map)->save();
	 }

//	public function closeTimeoutOrders($uid) {
//		$sql = "update __ORDERS__ set orderStatus = -2 where orderStatus = 0 and userId = $uid and  DATE_ADD(createTime,INTERVAL 5 MINUTE) < now()";
//		$this->query($sql);
//	}
	
	//----------------------------
	// 以下源代码方法
	//----------------------------
	
	/**
	 * 获以订单列表
	 */
	public function getOrdersList($obj){
		$userId = $obj["userId"];
		$m = M('orders');
		$sql = "SELECT * FROM __PREFIX__orders WHERE userId = $userId AND orderStatus <>-1 order by createTime desc";		
		return $m->pageQuery($sql);
	}
	
	/**
	 * 取消订单记录 
	 */
	public function getcancelOrderList($obj){		
		$userId = $obj["userId"];
		$m = M('orders');
		$sql = "SELECT * FROM __PREFIX__orders WHERE userId = $userId AND orderStatus =-1 order by createTime desc";		
		return $m->pageQuery($sql);
		
	}

	/**
	 * 获取订单详情
	 */
	public function getOrdersDetails($obj){		
		$orderId = $obj["orderId"];
		$map = array('orderId' => $orderId);
		$field = 'cky_orders.*, sp.shopName, sp.shopTel';
		return $this->field($field)
			->join('__SHOPS__ sp on sp.shopId = __ORDERS__.shopId')
			->where($map)->find();
	}
	
	/**
	 * 获取订单记录信息
	 */
	public function getOrdersItem($obj){		
		$orderId = I("orderId");
		$map = array('orderId' => $orderId);
		$field = 'cky_orders.*, sp.shopName, sp.shopTel';
		return $this->field($field)
			->join('__SHOPS__ sp on sp.shopId = __ORDERS__.shopId')
			->where($map)->find();
	}
	/**
	 * 
	 * 获取订单商品详情
	 */
	public function getOrdersGoodsDetails($obj){	
			
		$orderId = $obj["orderId"];
		$sql = "SELECT g.*,og.goodsNums as ogoodsNums,og.goodsPrice as ogoodsPrice ,ga.id as gaId
				FROM __PREFIX__order_goods og, __PREFIX__goods g 
				LEFT JOIN __PREFIX__goods_appraises ga ON g.goodsId = ga.goodsId AND ga.orderId = $orderId
				WHERE og.orderId = $orderId AND og.goodsId = g.goodsId";		
		$rs = $this->query($sql);	
		return $rs;
		
	}
	
	/**
	 *
	 * 获取订单商品详情
	 */
	public function getPayOrders($obj){
			
		$orderIds = $obj["orderIds"];
		$sql = "SELECT o.orderId, o.orderNo, g.goodsId, g.goodsName ,og.goodsAttrName , og.goodsNums ,og.goodsPrice 
				FROM __PREFIX__order_goods og, __PREFIX__goods g, __PREFIX__orders o
				WHERE o.orderId = og.orderId AND og.goodsId = g.goodsId AND o.payType=1 AND orderFlag =1 AND o.isPay=0 AND o.needPay>0 AND o.orderStatus = -2 AND og.orderId in ($orderIds)";
		$rslist = $this->query($sql);
		$orders = array();
		foreach ($rslist as $key => $order) {
			$orders[$order["orderNo"]][] = $order;
		}
		$sql = "SELECT SUM(needPay) needPay FROM __PREFIX__orders WHERE orderId IN ($orderIds) AND isPay=0 AND payType=1 AND needPay>0 AND orderStatus = -2 AND orderFlag =1";
		$payInfo = self::queryRow($sql);
		$data["orders"] = $orders;
		$data["needPay"] = $payInfo["needPay"];
		return $data;
	
	}
	
	
	/**
	 * 提交订单
	 */
	public function addOrders($userId,$consigneeId,$payway,$needreceipt,$catgoods,$orderunique,$isself, $ticket){

        $this->startTrans();
        
        $rst = $this->_addOrder($userId,$consigneeId,$payway,$needreceipt,$catgoods,$orderunique,$isself, $ticket);
		if($rst['status'] == 1){
			$this->commit();
		}else{
			$this->rollback();
		}
		return $rst;
		
	}
	
	function _addOrder($userId,$consigneeId,$payway,$needreceipt,$catgoods,$orderunique,$isself, $ticket) {
		$rst = array('status' => 1);
		$m = M('orderids');
		$orderInfos = array();
		$orderIds = array();
		$orderNos = array();
		$remarks = I("remarks");
		$yadb = D('M/UserAddress');
		$addressInfo = $yadb->getAddressDetails($consigneeId);
		$tm = D('M/ActivityTicket');
        $tmm = D('M/ActivityTicketM');
        $gm = D('M/Goods');
        $gam = D('M/GoodsAttributes');
        
        if($ticket) {
	        	// 更新优惠券使用数量
	        	$tm->updateUsedCount($ticket['ticketID']);
	        	// 标记$ticket已使用
	        	$tmm->updateStatus($userId, $ticket['ticketID']);
        }
        
		foreach ($catgoods as $key=> $shopgoods){
			//生成订单ID
			$orderSrcNo = $m->add(array('rnd'=>microtime(true)));
			$orderNo = $orderSrcNo."".(fmod($orderSrcNo,7));
			//创建订单信息
			$data = array();
			$pshopgoods = $shopgoods["shopgoods"];
			$shopId = $key;
			$data["orderNo"] = $orderNo;
			$data["shopId"] = $shopId;	
//			$deliverType = $shopgoods['deliverType'];
			$deliverType = 1; // 送递方式  1 商铺自己送
			$data["userId"] = $userId;	
				
			$data["orderFlag"] = 1;
			$data["totalMoney"] = $shopgoods["totalMoney"];
			if($isself==1){//自提
				$deliverMoney = 0;
			}else{
				$deliverMoney = ($shopgoods["totalMoney"]<$shopgoods["deliveryFreeMoney"])?$shopgoods["deliveryMoney"]:0;
			}
			$data["deliverMoney"] = $deliverMoney;
			$data["payType"] = $payway;
			$data["deliverType"] = $deliverType;
			$data["userName"] = $addressInfo["userName"];
			$data["areaId1"] = $addressInfo["areaId1"];
			$data["areaId2"] = $addressInfo["areaId2"];
			$data["areaId3"] = $addressInfo["areaId3"];
			$data["communityId"] = $addressInfo["communityId"];
			$data["userAddress"] = $addressInfo["paddress"]." ".$addressInfo["address"];
			$data["userTel"] = $addressInfo["userTel"];
			$data["userPhone"] = $addressInfo["userPhone"];
			
			$data['orderScore'] = round($data["totalMoney"]+$data["deliverMoney"],0);
			$data["isInvoice"] = $needreceipt;		
			$data["orderRemarks"] = $remarks;
			$data["requireTime"] = I("requireTime");
			$data["invoiceClient"] = I("invoiceClient");
			$data["isAppraises"] = 0;
			$data["isSelf"] = $isself;
			$data["orderType"] = $shopgoods["orderType"];  // 0普通商品、1快餐、2一元购
			$data["ticketId"] = $shopgoods["ticketId"]; // 优惠券Id
			$data["deductible"] = $shopgoods["deductible"]; // 抵扣金额(元)
			$data["needPay"] = $shopgoods["totalMoney"]+$deliverMoney - $shopgoods["deductible"];

			$data["createTime"] = date("Y-m-d H:i:s");
			
			if($data["needPay"] == 0) { // 0元直接跳过支付
				$data["orderStatus"] = 1; // 未处理
			} else if($payway==1){
				$data["orderStatus"] = 0; // 待支付
			} else{
				$data["orderStatus"] = 1; // 未处理
			}
			
			$data["orderunique"] = $orderunique;
			$data["isPay"] = 0;
			$orderId = $this->add($data);
			
			$orderNos[] = $data["orderNo"];
			$orderInfos[] = array("orderId"=>$orderId,"orderNo"=>$data["orderNo"], "orderStatus"=>$data["orderStatus"]) ;
			//订单创建成功则建立相关记录
			if($orderId>0){
				$orderIds[] = $orderId;
				//建立订单商品记录表
				$mog = M('order_goods');
				foreach ($pshopgoods as $key=> $sgoods){
					$data = array();
					$data["orderId"] = $orderId;
					$data["goodsId"] = $sgoods["goodsId"];
					$data["goodsAttrId"] = (int)$sgoods["goodsAttrId"];
					if($sgoods["attrVal"]!='')$data["goodsAttrName"] = $sgoods["attrName"].":".$sgoods["attrVal"];
					$data["goodsNums"] = $sgoods["cnt"];
					$data["goodsPrice"] = $sgoods["shopPrice"];
					
					$data["goodsName"] = $sgoods["goodsName"];
					$data["goodsThums"] = $sgoods["goodsThums"];
					
					if($mog->add($data) === FALSE) {
						$rst['status'] = -101;
						$rst['data'] = '创建订单商品失败';
						return $rst;
					}
					
					if(!empty($sgoods['miaoshaId'])) {
						$rst = $this->_orderMiaosha($userId, $sgoods['miaoshaId'], $sgoods['cnt'], $orderId);
						if($rst['status'] != 1) {
							return $rst;
						}
					}
					
					if($gm->reduceStock($sgoods["goodsId"], $sgoods['cnt']) === FALSE) {
						$rst['status'] = -102;
						$rst['data'] = '修改库存失败';
						return $rst;
					}
					if((int)$sgoods["goodsAttrId"]>0){
						if($gam->reduceStock((int)$sgoods["goodsAttrId"], $sgoods['cnt']) === FALSE) {
							$rst['status'] = -103;
							$rst['data'] = '修改库存失败';
							return $rst;
						}
					}
				}
			
				if($rst['status'] == 1 && $payway==0){
					//建立订单记录
					$data = array();
					$data["orderId"] = $orderId;
					$data["logContent"] = ($pshopgoods[0]["deliverType"]==0)? "下单成功":"下单成功等待审核";
					$data["logUserId"] = $userId;
					$data["logType"] = 0;
					$data["logTime"] = date('Y-m-d H:i:s');
					$mlogo = M('log_orders');
					$mlogo->add($data);
					
					//建立订单提醒
					$sql ="SELECT userId,shopId,shopName FROM __PREFIX__shops WHERE shopId=$shopId AND shopFlag=1  ";
					$users = $this->query($sql);
					$morm = M('order_reminds');
					for($i=0;$i<count($users);$i++){
						$data = array();
						$data["orderId"] = $orderId;
						$data["shopId"] = $shopId;
						$data["userId"] = $users[$i]["userId"];
						$data["userType"] = 0;
						$data["remindType"] = 0;
						$data["createTime"] = date("Y-m-d H:i:s");
						$morm->add($data);
					}
				}else{
					$data = array();
					$data["orderId"] = $orderId;
					$data["logContent"] = "订单已提交，等待支付";
					$data["logUserId"] = $userId;
					$data["logType"] = 0;
					$data["logTime"] = date('Y-m-d H:i:s');
					$mlogo = M('log_orders');
					$mlogo->add($data);
				}
			} else {
				$rst['status'] = -102;
				$rst['data'] = '创建订单号失败';
				return $rst;
			}
		}
		$rst["orderIds"]=$orderIds;
		$rst["orderInfos"]=$orderInfos;
		$rst["orderNos"]=$orderNos;
		return $rst;
	}

	function _orderMiaosha($uid, $miaoshaId, $goodsCount, $orderId) {
		$rst = array('status' => 1);
		$mdb = M('miaosha');
		$mmap = array(
			'miaoshaId'		=> $miaoshaId,
			'miaoshaStatus'	=> array('lt', 2),
		);
		$miaosha = $mdb->field('qishu')->find($miaoshaId);
		
		if(empty($miaosha)) {
			$rst['status'] = -205;
			$rst['data'] = '秒杀商品不存在';
			return $rst;
		}
		
		$qishu = (int)$miaosha['qishu'];
		
		// 增加云购纪录
		$mmdb = M('MemberMiaosha');
		$mmdata = array(
			'miaoshaId'		=> $miaoshaId,
			'qishu'			=> $qishu,
			'uid'			=> $uid,
			'miaoshaCount'	=> $goodsCount,
			'orderId'		=> $orderId,
			'ms'				=> rand(0, 999),
		);
		$mmid = $mmdb->add($mmdata);
		if(!($mmid > 0)) {
			$rst['status'] = -202;
			$rst['data'] = '添加云购纪录失败';
			return $rst;
		}
		
		// 修改秒杀商品库存
		$mddata = array(
			'goumaicishu'		=> array('exp', '`goumaicishu` + 1'),
			'canyurenshu'		=> array('exp', '`canyurenshu` + '.$goodsCount),
			'shengyurenshu'		=> array('exp', ' `shengyurenshu` - '.$goodsCount),
			'miaoshaStatus'		=> 1
		);
		if($mdb->where(array('miaoshaId'=>$miaoshaId))->save($mddata) === FALSE) {
			$rst['status'] = -204;
			$rst['data'] = '修改秒杀商品失败';
			return $rst;
		}
		return $rst;
	}
	
//	// 处理秒杀商品
//	function _addMiaosha($uid, $sgoods, $orderId) {
////		echo dump($sgoods);
//		$rst = array('status' => 1);
//		// 获取云购码
//		$mcdb = M('MiaoshaCode');
//		$mcmap = array(
//			'mc.miaoshaId'		=> $sgoods['miaoshaId'],
//			'mc.uid'				=> 0,
//			'mc.mmid'			=> 0,
//			'm.miaoshaStatus'	=> array('lt', 2), 
//		);
//		$pageSize = $sgoods['cnt'];
//		$codes = $mcdb->field('mc.mcid, mc.qishu,mc.miaoshaCode,mc.miaoshaCode,rand() factor')
//			->join('mc  inner join __GOODS__ g on mc.miaoshaId = g.miaoshaId')
//			->join('inner join __MIAOSHA__ m on mc.miaoshaId = m.miaoshaId and mc.qishu=m.qishu')
//			->where($mcmap)
//			->order('factor asc')->page(1, $pageSize)->select();
//		
//		if(empty($codes)) {
//			$rst['status'] = -201;
//			$rst['data'] = '生成云购码失败';
////			$rst['data'] = $mcdb->getLastSql();
//			return $rst;
//		}
//		
//		// 增加云购纪录
//		$mmdb = M('MemberMiaosha');
//		$mmdata = array(
//			'miaoshaId'		=> $sgoods['miaoshaId'],
//			'qishu'			=> $codes[0]['qishu'],
//			'uid'			=> $uid,
//			'count'			=> $sgoods['cnt'],
//			'orderId'		=> $orderId，
//			'isPay'			=> 0,
//		);
//		$mmid = $mmdb->add($mmdata);
//		if(!($mmid > 0)) {
//			$rst['status'] = -202;
//			$rst['data'] = '添加云购纪录失败';
//			return $rst;
//		}
//		
//		// 标记云购码
//		$mcids = array();
//		foreach($codes as $code) {
//			array_push($mcids, $code['mcid']);
//		}
//		
//		if($mcdb->where('mcid in('.join(',', $mcids).')')
//			->save(array( 'uid'=>$uid, 'mmid' => $mmid)) === FALSE) {
//			$rst['status'] = -203;
//			$rst['data'] = '获取云购码失败';
////			$rst['data'] = $mcdb->getLastSql();
//			return $rst;
//		}
//		
//		// 修改秒杀商品库存
//		$mdb = M('miaosha');
//		$mddata = array(
//			'goumaicishu'		=> array('exp', '`goumaicishu` + 1'),
//			'canyurenshu'		=> array('exp', '`canyurenshu` + '.$sgoods['cnt']),
//			'shengyurenshu'		=> array('exp', ' `shengyurenshu` - '.$sgoods['cnt']),
//			'miaoshaStatus'		=> ($sgoods['cnt'] == (int)$sgoods['goodsStock'] ? 2 : 1)
//		);
//		if($mdb->where(array('miaoshaId'=>$sgoods['miaoshaId']))->save($mddata) === FALSE) {
//			$rst['status'] = -204;
//			$rst['data'] = '修改秒杀商品失败';
////			$rst['data'] = $mdb->getLastSql();
//			return $rst;
//		}
//		
//		return $rst;
//	}
	
	/**
	 * 获取待付款订单
	 */
	public function queryByPage($obj){
		$userId = $obj["userId"];
		$pcurr = (int)I("pcurr",0);
		$sql = "SELECT o.* FROM __PREFIX__orders o
				WHERE userId = $userId AND orderFlag=1 order by orderId desc";
		$pages = $this->pageQuery($sql,$pcurr);	
		$orderList = $pages["root"];
		if(count($orderList)>0){
			$orderIds = array();
			for($i=0;$i<count($orderList);$i++){
				$order = $orderList[$i];
				$orderIds[] = $order["orderId"];
			}
			//获取涉及的商品
	        $sql = "SELECT og.goodsId,og.goodsName,og.goodsThums,og.orderId FROM __PREFIX__order_goods og
					WHERE og.orderId in (".implode(',',$orderIds).")";	
	        $glist = $this->query($sql);
			$goodslist = array();
			for($i=0;$i<count($glist);$i++){
				$goods = $glist[$i];
				$goodslist[$goods["orderId"]][] = $goods;
			}
			//放回分页数据里
			for($i=0;$i<count($orderList);$i++){
				$order = $orderList[$i];
				$order["goodslist"] = $goodslist[$order['orderId']];
				$pages["root"][$i] = $order;
			}
		}
		return $pages;
	}

	/**
	 * 获取待付款订单
	 */
	public function queryPayByPage($obj){
		$userId = (int)$obj["userId"];
		$orderNo = I("orderNo");
		$orderStatus = (int)I("orderStatus",0);
		$goodsName = I("goodsName");
		$shopName = I("shopName");
		$userName = I("userName");
		$sdate = I("sdate");
		$edate = I("edate");
		$pcurr = (int)I("pcurr",0);
		
		$sql = "SELECT o.orderId,o.orderNo,o.shopId,o.orderStatus,o.userName,o.totalMoney,
		        o.createTime,o.payType,o.isRefund,o.isAppraises,sp.shopName 
		        FROM __PREFIX__orders o,__PREFIX__shops sp 
		        WHERE o.userId = $userId AND o.orderStatus =-2 AND o.isPay = 0 AND needPay >0 AND o.payType = 1 AND o.shopId=sp.shopId ";
		if($orderNo!=""){
			$sql .= " AND o.orderNo like '%$orderNo%'";
		}
		if($userName!=""){
			$sql .= " AND o.userName like '%$userName%'";
		}
		if($shopName!=""){
			$sql .= " AND sp.shopName like '%$shopName%'";
		}
		if($sdate!=""){
			$sql .= " AND o.createTime >= $sdate";
		}
		if($edate!=""){
			$sql .= " AND o.createTime <= $edate";
		}
		$sql .= " order by o.orderId desc";
		$pages = $this->pageQuery($sql,$pcurr);	
		$orderList = $pages["root"];
		if(count($orderList)>0){
			$orderIds = array();
			for($i=0;$i<count($orderList);$i++){
				$order = $orderList[$i];
				$orderIds[] = $order["orderId"];
			}
			//获取涉及的商品
	        $sql = "SELECT og.goodsId,og.goodsName,og.goodsThums,og.orderId FROM __PREFIX__order_goods og
					WHERE og.orderId in (".implode(',',$orderIds).")";	
			$glist = $this->query($sql);
			$goodslist = array();
			for($i=0;$i<count($glist);$i++){
				$goods = $glist[$i];
				$goodslist[$goods["orderId"]][] = $goods;
			}
			//放回分页数据里
			for($i=0;$i<count($orderList);$i++){
				$order = $orderList[$i];
				$order["goodslist"] = $goodslist[$order['orderId']];
				$pages["root"][$i] = $order;
			}
		}
		return $pages;
	}
	
	/**
	 * 获取待确认收货
	 */
	public function queryReceiveByPage($obj){
		$userId = (int)$obj["userId"];
		$orderNo = I("orderNo");
		$orderStatus = (int)I("orderStatus",0);
		$goodsName = I("goodsName");
		$shopName = I("shopName");
		$userName = I("userName");
		$sdate = I("sdate");
		$edate = I("edate");
		$pcurr = (int)I("pcurr",0);

		$sql = "SELECT o.orderId,o.orderNo,o.shopId,o.orderStatus,o.userName,o.totalMoney,
		        o.createTime,o.payType,o.isRefund,o.isAppraises,sp.shopName 
		        FROM __PREFIX__orders o,__PREFIX__shops sp WHERE o.userId = $userId AND o.orderStatus =3 AND o.shopId=sp.shopId ";
		if($orderNo!=""){
			$sql .= " AND o.orderNo like '%$orderNo%'";
		}
		if($userName!=""){
			$sql .= " AND o.userName like '%$userName%'";
		}
		if($shopName!=""){
			$sql .= " AND sp.shopName like '%$shopName%'";
		}
		if($sdate!=""){
			$sql .= " AND o.createTime >= $sdate";
		}
		if($edate!=""){
			$sql .= " AND o.createTime <= $edate";
		}
		$sql .= " order by o.orderId desc";
		$pages = $this->pageQuery($sql,$pcurr);	
		$orderList = $pages["root"];
		if(count($orderList)>0){
			$orderIds = array();
			for($i=0;$i<count($orderList);$i++){
				$order = $orderList[$i];
				$orderIds[] = $order["orderId"];
			}
			//获取涉及的商品
	        $sql = "SELECT og.goodsId,og.goodsName,og.goodsThums,og.orderId FROM __PREFIX__order_goods og
					WHERE og.orderId in (".implode(',',$orderIds).")";	
			$glist = $this->query($sql);
			$goodslist = array();
			for($i=0;$i<count($glist);$i++){
				$goods = $glist[$i];
				$goodslist[$goods["orderId"]][] = $goods;
			}
			//放回分页数据里
			for($i=0;$i<count($orderList);$i++){
				$order = $orderList[$i];
				$order["goodslist"] = $goodslist[$order['orderId']];
				$pages["root"][$i] = $order;
			}
		}
		return $pages;
	}
    /**
	 * 获取待发货订单
	 */
	public function queryDeliveryByPage($obj){
		$userId = (int)$obj["userId"];
		$orderNo = I("orderNo");
		$orderStatus = (int)I("orderStatus",0);
		$goodsName = I("goodsName");
		$shopName = I("shopName");
		$userName = I("userName");
		$sdate = I("sdate");
		$edate = I("edate");
		$pcurr = (int)I("pcurr",0);

		$sql = "SELECT o.orderId,o.orderNo,o.shopId,o.orderStatus,o.userName,o.totalMoney,
		        o.createTime,o.payType,o.isRefund,o.isAppraises,sp.shopName 
		        FROM __PREFIX__orders o,__PREFIX__shops sp 
		        WHERE o.userId = $userId AND o.orderStatus in ( 0,1,2 ) AND o.shopId=sp.shopId ";
		if($orderNo!=""){
			$sql .= " AND o.orderNo like '%$orderNo%'";
		}
		if($userName!=""){
			$sql .= " AND o.userName like '%$userName%'";
		}
		if($shopName!=""){
			$sql .= " AND sp.shopName like '%$shopName%'";
		}
		if($sdate!=""){
			$sql .= " AND o.createTime >= $sdate";
		}
		if($edate!=""){
			$sql .= " AND o.createTime <= $edate";
		}
		$sql .= " order by o.orderId desc";
		$pages = $this->pageQuery($sql,$pcurr);
       	
		$orderList = $pages["root"];
		if(count($orderList)>0){
			$orderIds = array();
			for($i=0;$i<count($orderList);$i++){
				$order = $orderList[$i];
				$orderIds[] = $order["orderId"];
			}
			//获取涉及的商品
	        $sql = "SELECT og.goodsId,og.goodsName,og.goodsThums,og.orderId FROM __PREFIX__order_goods og
					WHERE og.orderId in (".implode(',',$orderIds).")";
			$glist = $this->query($sql);
			$goodslist = array();
			for($i=0;$i<count($glist);$i++){
				$goods = $glist[$i];
				$goodslist[$goods["orderId"]][] = $goods;
			}
			//放回分页数据里
			for($i=0;$i<count($orderList);$i++){
				$order = $orderList[$i];
				$order["goodslist"] = $goodslist[$order['orderId']];
				$pages["root"][$i] = $order;
			}
		}
		return $pages;
	}
    /**
	 * 获取退款
	 */
	public function queryRefundByPage($obj){
		$userId = (int)$obj["userId"];
		$orderNo = I("orderNo");
		$orderStatus = (int)I("orderStatus",0);
		$goodsName = I("goodsName");
		$shopName = I("shopName");
		$userName = I("userName");
		$sdate = I("sdate");
		$edate = I("edate");
		$pcurr = (int)I("pcurr",0);
		//必须是在线支付的才允许退款

		$sql = "SELECT o.orderId,o.orderNo,o.shopId,o.orderStatus,o.userName,o.totalMoney,
		        o.createTime,o.payType,o.isRefund,o.isAppraises,sp.shopName 
		        FROM __PREFIX__orders o,__PREFIX__shops sp 
		        WHERE o.userId = $userId AND (o.orderStatus in (-3,-4,-5) or (o.orderStatus in (-1,-4,-6,-7) and payType =1 AND o.isPay =1)) AND o.shopId=sp.shopId ";
		if($orderNo!=""){
			$sql .= " AND o.orderNo like '%$orderNo%'";
		}
		if($userName!=""){
			$sql .= " AND o.userName like '%$userName%'";
		}
		if($shopName!=""){
			$sql .= " AND sp.shopName like '%$shopName%'";
		}
		if($sdate!=""){
			$sql .= " AND o.createTime >= $sdate";
		}
		if($edate!=""){
			$sql .= " AND o.createTime <= $edate";
		}
		$sql .= " order by o.orderId desc";
		
		$pages = $this->pageQuery($sql,$pcurr);	
		$orderList = $pages["root"];
		if(count($orderList)>0){
			$orderIds = array();
			for($i=0;$i<count($orderList);$i++){
				$order = $orderList[$i];
				$orderIds[] = $order["orderId"];
			}
			//获取涉及的商品
	        $sql = "SELECT og.goodsId,og.goodsName,og.goodsThums,og.orderId FROM __PREFIX__order_goods og
					WHERE og.orderId in (".implode(',',$orderIds).")";	
			$glist = $this->query($sql);
			$goodslist = array();
			for($i=0;$i<count($glist);$i++){
				$goods = $glist[$i];
				$goodslist[$goods["orderId"]][] = $goods;
			}
			//放回分页数据里
			for($i=0;$i<count($orderList);$i++){
				$order = $orderList[$i];
				$order["goodslist"] = $goodslist[$order['orderId']];
				$pages["root"][$i] = $order;
			}
		}
		return $pages;
	}
	
	/**
	 * 获取取消的订单
	 */
	public function queryCancelOrders($obj){
		$userId = (int)$obj["userId"];
		$orderNo = I("orderNo");
		$orderStatus = (int)I("orderStatus",0);
		$goodsName = I("goodsName");
		$shopName = I("shopName");
		$userName = I("userName");
		$sdate = I("sdate");
		$edate = I("edate");
		$pcurr = (int)I("pcurr",0);

		$sql = "SELECT o.orderId,o.orderNo,o.shopId,o.orderStatus,o.userName,o.totalMoney,
		        o.createTime,o.payType,o.isRefund,o.isAppraises,sp.shopName 
		        FROM __PREFIX__orders o,__PREFIX__shops sp 
		        WHERE o.userId = $userId AND o.orderStatus in (-1,-6,-7) AND o.shopId=sp.shopId ";
		if($orderNo!=""){
			$sql .= " AND o.orderNo like '%$orderNo%'";
		}
		if($userName!=""){
			$sql .= " AND o.userName like '%$userName%'";
		}
		if($shopName!=""){
			$sql .= " AND sp.shopName like '%$shopName%'";
		}
		if($sdate!=""){
			$sql .= " AND o.createTime >= $sdate";
		}
		if($edate!=""){
			$sql .= " AND o.createTime <= $edate";
		}
		$sql .= " order by o.orderId desc";
		$pages = $this->pageQuery($sql,$pcurr);	
		$orderList = $pages["root"];
		if(count($orderList)>0){
			$orderIds = array();
			for($i=0;$i<count($orderList);$i++){
				$order = $orderList[$i];
				$orderIds[] = $order["orderId"];
			}
			//获取涉及的商品
	        $sql = "SELECT og.goodsId,og.goodsName,og.goodsThums,og.orderId FROM __PREFIX__order_goods og
					WHERE og.orderId in (".implode(',',$orderIds).")";	
			$glist = $this->query($sql);
			$goodslist = array();
			for($i=0;$i<count($glist);$i++){
				$goods = $glist[$i];
				$goodslist[$goods["orderId"]][] = $goods;
			}
			//放回分页数据里
			for($i=0;$i<count($orderList);$i++){
				$order = $orderList[$i];
				$order["goodslist"] = $goodslist[$order['orderId']];
				$pages["root"][$i] = $order;
			}
		}
		return $pages;
	}
	
	/**
	 * 获取待评价交易
	 */
	public function queryAppraiseByPage($obj){
		$userId = (int)$obj["userId"];
		$orderNo = I("orderNo");
		$goodsName = I("goodsName");
		$shopName = I("shopName");
		$userName = I("userName");
		$sdate = I("sdate");
		$edate = I("edate");
		$pcurr = (int)I("pcurr",0);
		$sql = "SELECT o.orderId,o.orderNo,o.shopId,o.orderStatus,o.userName,o.totalMoney,
		        o.createTime,o.payType,o.isRefund,o.isAppraises,sp.shopName 
		        FROM __PREFIX__orders o,__PREFIX__shops sp WHERE o.userId = $userId AND o.shopId=sp.shopId ";	
		if($orderNo!=""){
			$sql .= " AND o.orderNo like '%$orderNo%'";
		}
		if($userName!=""){
			$sql .= " AND o.userName like '%$userName%'";
		}
		if($shopName!=""){
			$sql .= " AND sp.shopName like '%$shopName%'";
		}
		if($sdate!=""){
			$sql .= " AND o.createTime >= $sdate";
		}
		if($edate!=""){
			$sql .= " AND o.createTime <= $edate";
		}
		$sql .= " AND o.orderStatus = 4";
		$sql .= " order by o.orderId desc";
		$pages = $this->pageQuery($sql,$pcurr);	
		$orderList = $pages["root"];
		if(count($orderList)>0){
			$orderIds = array();
			for($i=0;$i<count($orderList);$i++){
				$order = $orderList[$i];
				$orderIds[] = $order["orderId"];
			}
			//获取涉及的商品
	        $sql = "SELECT og.goodsId,og.goodsName,og.goodsThums,og.orderId FROM __PREFIX__order_goods og
					WHERE og.orderId in (".implode(',',$orderIds).")";	
			$glist = $this->query($sql);
			$goodslist = array();
			for($i=0;$i<count($glist);$i++){
				$goods = $glist[$i];
				$goodslist[$goods["orderId"]][] = $goods;
			}
			//放回分页数据里
			for($i=0;$i<count($orderList);$i++){
				$order = $orderList[$i];
				$order["goodslist"] = $goodslist[$order['orderId']];
				$pages["root"][$i] = $order;
			}
		}
		return $pages;
	}
	
	/**
	 * 取消订单
	 */
	public function orderCancel($uid, $orderId){
		$rsdata = array('status' => -1, 'message' => '取消订单失败');
		$rs = $this->execute("call p_cancel_order($orderId, $uid, -1)");
		$rsdata['aa'] = $rs;
//		if($rs == 1) {
			$rsData['status'] = 1;
//		}
		return $rsdata;
	}
	/**
	 * 用户确认收货
	 */
	public function orderConfirm ($obj){		
		$userId = (int)$obj["userId"];
		$orderId = (int)$obj["orderId"];
		$type = (int)$obj["type"];
		$rsdata = array();
		$sql = "SELECT orderId,orderNo,orderScore,orderStatus FROM __PREFIX__orders WHERE orderId = $orderId and userId=".$userId;		
		$rsv = $this->queryRow($sql);
		if($rsv["orderStatus"]!=3){
			$rsdata["status"] = -1;
			return $rsdata;
		}		
        //收货则给用户增加积分
        if($type==1){
        	$sql = "UPDATE __PREFIX__orders set orderStatus = 4 WHERE orderId = $orderId and userId=".$userId;			
        	$rs = $this->execute($sql);
        	
        	//修改商品销量
        	$sql = "UPDATE __PREFIX__goods g, __PREFIX__order_goods og, __PREFIX__orders o SET g.saleCount=g.saleCount+og.goodsNums WHERE g.goodsId= og.goodsId AND og.orderId = o.orderId AND o.orderId=$orderId AND o.userId=".$userId;
        	$rs = $this->execute($sql);
        	
        	$sql = "UPDATE __PREFIX__users set userScore=userScore+".$rsv["orderScore"]." WHERE userId=".$userId;
        	$rs = $this->execute($sql);
        }else{
        	if(I('rejectionRemarks')=='')return $rsdata;//如果是拒收的话需要填写原因
        	$sql = "UPDATE __PREFIX__orders set orderStatus = -3 WHERE orderId = $orderId and userId=".$userId;			
        	$rs = $this->execute($sql);
        }
        //增加记录
		$data = array();
		$m = M('log_orders');
		$data["orderId"] = $orderId;
		$data["logContent"] = ($type==1)?"用户已收货":"用户拒收：".I('rejectionRemarks');
		$data["logUserId"] = $userId;
		$data["logType"] = 0;
		$data["logTime"] = date('Y-m-d H:i:s');
		$ra = $m->add($data);
		$rsdata["status"] = $ra;;
		return $rsdata;
	}
	
    /**
     * 获取订单详情
     */
	public function getOrderDetails($obj){
		$userId = (int)$obj["userId"];
		$shopId = (int)$obj["shopId"];
		$orderId = (int)$obj["orderId"];
		$data = array();
		$sql = "SELECT * FROM __PREFIX__orders WHERE orderId = $orderId and (userId=".$userId." or shopId=".$shopId.")";	
		$order = $this->queryRow($sql);
		if(empty($order))return $data;
		$data["order"] = $order;

		$sql = "select og.orderId, og.goodsId ,g.goodsSn, og.goodsNums, og.goodsName , og.goodsPrice shopPrice,og.goodsThums,og.goodsAttrName,og.goodsAttrName 
				from __PREFIX__goods g , __PREFIX__order_goods og 
				WHERE g.goodsId = og.goodsId AND og.orderId = $orderId";
		$goods = $this->query($sql);
		$data["goodsList"] = $goods;
		
		for($i=0;$i<count($ogoodsList);$i++){
			$sgoods = $ogoodsList[$i];
			$sql="update __PREFIX__goods set goodsStock=goodsStock+".$sgoods['goodsNums']." where goodsId=".$sgoods["goodsId"];
			$this->execute($sql);
		}
		
		$sql = "SELECT * FROM __PREFIX__log_orders WHERE orderId = $orderId ";	
		$logs = $this->query($sql);
		$data["logs"] = $logs;
		
		return $data;
		
	}
	/**
	 * 获取用户指定状态的订单数目
	 */
	public function getUserOrderStatusCount($obj){
		$userId = (int)$obj["userId"];
		$data = array();
		$sql = "select orderStatus,COUNT(*) cnt from __PREFIX__orders WHERE orderStatus in (0,1,2,3) and orderFlag=1 and userId = $userId GROUP BY orderStatus";
		$olist = $this->query($sql);
		$data = array('-3'=>0,'-2'=>0,'2'=>0,'3'=>0,'4'=>0);
		for($i=0;$i<count($olist);$i++){
			$row = $olist[$i];
			if($row["orderStatus"]==0 || $row["orderStatus"]==1 || $row["orderStatus"]==2){
				$row["orderStatus"] = 2;
			}
			$data[$row["orderStatus"]] = $data[$row["orderStatus"]]+$row["cnt"];
		}
		//获取未支付订单
		$sql = "select COUNT(*) cnt from __PREFIX__orders WHERE orderStatus = -2 and isRefund=0 and payType=1 and orderFlag=1 and isPay = 0 and needPay >0 and userId = $userId";
		$olist = $this->query($sql);
		$data[-2] = $olist[0]['cnt'];
		
		//获取退款订单
		$sql = "select COUNT(*) cnt from __PREFIX__orders WHERE orderStatus in (-3,-4,-6,-7) and isRefund=0 and payType=1 and orderFlag=1 and userId = $userId";
		$olist = $this->query($sql);
		$data[-3] = $olist[0]['cnt'];
		//获取待评价订单
		$sql = "select COUNT(*) cnt from __PREFIX__orders WHERE orderStatus =4 and isAppraises=0 and orderFlag=1 and userId = $userId";
		$olist = $this->query($sql);
		$data[4] = $olist[0]['cnt'];
		
		//获取商城信息
		$sql = "select count(*) cnt from __PREFIX__messages WHERE  receiveUserId=".$userId." and msgStatus=0 and msgFlag=1 ";
		$olist = $this->query($sql);
		$data[100000] = empty($olist)?0:$olist[0]['cnt'];
		
		return $data;
		
	}
	
	/**
	 * 获取用户指定状态的订单数目
	 */
	public function getShopOrderStatusCount($obj){
		$shopId = (int)$obj["shopId"];
		$rsdata = array();
		//待受理订单
		$sql = "SELECT COUNT(*) cnt FROM __PREFIX__orders WHERE shopId = $shopId AND orderStatus = 0 ";
		$olist = $this->queryRow($sql);
		$rsdata[0] = $olist['cnt'];
		
		//取消-商家未知的 / 拒收订单
		$sql = "SELECT COUNT(*) cnt FROM __PREFIX__orders WHERE shopId = $shopId AND orderStatus in (-3,-6)";
		$olist = $this->queryRow($sql);
		$rsdata[5] = $olist['cnt'];
		$rsdata[100] = $rsdata[0]+$rsdata[5];
		
		//获取商城信息
		$sql = "select count(*) cnt from __PREFIX__messages WHERE  receiveUserId=".(int)$obj["userId"]." and msgStatus=0 and msgFlag=1 ";
		$olist = $this->query($sql);
		$rsdata[100000] = empty($olist)?0:$olist[0]['cnt'];
		
		return $rsdata;
	
	}
	
	
	/**
	 * 获取商家订单列表
	 */
	public function queryShopOrders($obj){		
		$userId = (int)$obj["userId"];
		$shopId = (int)$obj["shopId"];
		$pcurr = (int)I("pcurr",0);
		$orderStatus = (int)I("statusMark");
		
		$orderNo = I("orderNo");
		$userName = I("userName");
		$userAddress = I("userAddress");
		$rsdata = array();
		$sql = "SELECT orderNo,orderId,userId,userName,userAddress,totalMoney,orderStatus,createTime FROM __PREFIX__orders WHERE shopId = $shopId ";
		if($orderStatus==5){
			$sql.=" AND orderStatus in (-3,-4,-5,-6,-7)";
		}else{
			$sql.=" AND orderStatus = $orderStatus ";	
		}
		if($orderNo!=""){
			$sql .= " AND orderNo like '%$orderNo%'";
		}
		if($userName!=""){
			$sql .= " AND userName like '%$userName%'";
		}
		if($userAddress!=""){
			$sql .= " AND userAddress like '%$userAddress%'";
		}
		$sql.=" order by orderId desc ";
		$data = $this->pageQuery($sql,$pcurr);
		//获取取消/拒收原因
		$orderIds = array();
		$noReadrderIds = array();
		foreach ($data['root'] as $key => $v){	
			if($v['orderStatus']==-6)$noReadrderIds[] = $v['orderId'];
			$sql = "select logContent from __PREFIX__log_orders where orderId =".$v['orderId']." and logType=0 and logUserId=".$v['userId']." order by logId desc limit 1";
			$ors = $this->query($sql);
			$data['root'][$key]['rejectionRemarks'] = $ors[0]['logContent'];
		}
		
		//要对用户取消【-6】的状态进行处理,表示这一条取消信息商家已经知道了
		if($orderStatus==5 && count($noReadrderIds)>0){
			$sql = "UPDATE __PREFIX__orders set orderStatus=-7 WHERE shopId = $shopId AND orderId in (".implode(',',$noReadrderIds).")AND orderStatus = -6 ";
			$this->execute($sql);
		}
		return $data;
	}
	
	/**
	 * 商家受理订单-只能受理【未受理】的订单
	 */
	public function shopOrderAccept ($obj){		
		$userId = (int)$obj["userId"];
		$orderId = (int)$obj["orderId"];
		$shopId = (int)$obj["shopId"];
		$rsdata = array();
		$sql = "SELECT orderId,orderNo,orderStatus FROM __PREFIX__orders WHERE orderId = $orderId AND orderFlag=1 and shopId=".$shopId;		
		$rsv = $this->queryRow($sql);
		if($rsv["orderStatus"]!=0){
			$rsdata["status"] = -1;
			return $rsdata;
		}

		$sql = "UPDATE __PREFIX__orders set orderStatus = 1 WHERE orderId = $orderId and shopId=".$shopId;		
		$rs = $this->execute($sql);		

		$data = array();
		$m = M('log_orders');
		$data["orderId"] = $orderId;
		$data["logContent"] = "商家已受理订单";
		$data["logUserId"] = $userId;
		$data["logType"] = 0;
		$data["logTime"] = date('Y-m-d H:i:s');
		$ra = $m->add($data);
		$rsdata["status"] = $ra;
		return $rsdata;
	}
	
    /**
	 * 商家批量受理订单-只能受理【未受理】的订单
	 */
	public function batchShopOrderAccept(){		
		$USER = session('RTC_USER');
		$userId = (int)$USER["userId"];
		$orderIds = I("orderIds");
		$shopId = (int)$USER["shopId"];
		if($orderIds=='')return array('status'=>-2);
		$orderIds = explode(',',$orderIds);
		$orderNum = count($orderIds);
		$editOrderNum = 0;
		foreach ($orderIds as $orderId){
			if($orderId=='')continue;//订单号为空则跳过
			$sql = "SELECT orderId,orderNo,orderStatus FROM __PREFIX__orders WHERE orderId = $orderId AND orderFlag=1 and shopId=".$shopId;		
			$rsv = $this->queryRow($sql);
			if($rsv["orderStatus"]!=0)continue;//订单状态不符合则跳过
			$sql = "UPDATE __PREFIX__orders set orderStatus = 1 WHERE orderId = $orderId and shopId=".$shopId;		
			$rs = $this->execute($sql);		
	
			$data = array();
			$m = M('log_orders');
			$data["orderId"] = $orderId;
			$data["logContent"] = "商家已受理订单";
			$data["logUserId"] = $userId;
			$data["logType"] = 0;
			$data["logTime"] = date('Y-m-d H:i:s');
			$ra = $m->add($data);
			$editOrderNum++;
		}
		if($editOrderNum==0)return array('status'=>-1);//没有符合条件的执行操作
		if($editOrderNum<$orderNum)return array('status'=>-2);//只有部分订单符合操作
		return array('status'=>1);
	}
	
	/**
	 * 商家打包订单-只能处理[受理]的订单
	 */
	public function shopOrderProduce ($obj){		
		$userId = (int)$obj["userId"];
		$shopId = (int)$obj["shopId"];
		$orderId = (int)$obj["orderId"];
		$rsdata = array();
		$sql = "SELECT orderId,orderNo,orderStatus FROM __PREFIX__orders WHERE orderId = $orderId AND orderFlag =1 and shopId=".$shopId;		
		$rsv = $this->queryRow($sql);
		if($rsv["orderStatus"]!=1){
			$rsdata["status"] = -1;
			return $rsdata;
		}

		$sql = "UPDATE __PREFIX__orders set orderStatus = 2 WHERE orderId = $orderId and shopId=".$shopId;		
		$rs = $this->execute($sql);		
		$data = array();
		$m = M('log_orders');
		$data["orderId"] = $orderId;
		$data["logContent"] = "订单打包中";
		$data["logUserId"] = $userId;
		$data["logType"] = 0;
		$data["logTime"] = date('Y-m-d H:i:s');
		$ra = $m->add($data);
		$rsdata["status"] = $ra;;
		return $rsdata;
	}
	
    /**
	 * 商家批量打包订单-只能处理[受理]的订单
	 */
	public function batchShopOrderProduce (){		
		$USER = session('RTC_USER');
		$userId = (int)$USER["userId"];
		$orderIds = I("orderIds");
		$shopId = (int)$USER["shopId"];
		if($orderIds=='')return array('status'=>-2);
		$orderIds = explode(',',$orderIds);
		$orderNum = count($orderIds);
		$editOrderNum = 0;
		foreach ($orderIds as $orderId){
			if($orderId=='')continue;//订单号为空则跳过
			$sql = "SELECT orderId,orderNo,orderStatus FROM __PREFIX__orders WHERE orderId = $orderId AND orderFlag =1 and shopId=".$shopId;		
			$rsv = $this->queryRow($sql);
			if($rsv["orderStatus"]!=1)continue;//订单状态不符合则跳过
	
			$sql = "UPDATE __PREFIX__orders set orderStatus = 2 WHERE orderId = $orderId and shopId=".$shopId;		
			$rs = $this->execute($sql);		
			$data = array();
			$m = M('log_orders');
			$data["orderId"] = $orderId;
			$data["logContent"] = "订单打包中";
			$data["logUserId"] = $userId;
			$data["logType"] = 0;
			$data["logTime"] = date('Y-m-d H:i:s');
			$ra = $m->add($data);
			$editOrderNum++;
		}
		if($editOrderNum==0)return array('status'=>-1);//没有符合条件的执行操作
		if($editOrderNum<$orderNum)return array('status'=>-2);//只有部分订单符合操作
		return array('status'=>1);
	}
	
	/**
	 * 订单支付状态
	 */
	public function payOrder($orderId ,$netpayamount,$accountmoney,$accountscoremoney,$accountscore,$payType=0){
		$rst = array('status' => 1);
		$filter = array();
		$filter['orderId'] = $orderId;
		$filter['isPay'] = 0;	// 未支付
		$filter['orderStatus'] = 0; // 待支付
		$order = $this->field("userId,orderType")->where($filter)->find();
		if(empty($order)) {
			$rst['status'] = -301;
			return $rst;
		}
		
		//更新支付金额方式
		
		$orderType = (int)$order['orderType'];
		$uid = (int)$order['userId'];
		
		// 修改orders表状态
		$data = array();
		$data['orderStatus'] = $orderType == 2 ? 6 : 1; // 一元购直接完结商品
		$data['isPay'] = 1;
		$data['payType'] = $payType;
		 
		$data['netpayamount'] = $netpayamount;
		$data['accountmoney'] = $accountmoney;
		$data['accountscoremoney'] = $accountscoremoney;
		$data['accountscore'] = $accountscore;
		 
		if($this->where($filter)->save($data) === FALSE) {
			$rst['status'] = -302;
			return $rst;
		}
		if($orderType == 2) {
			//完成一元购流程
			return $this->_payMiaosha($uid, $orderId);
		} else { // 一元购商品 不需要纪录和提醒，中奖后才会提醒
			//建立订单记录
			$data = array();
			$data["orderId"] = $orderId;
			$data["logContent"] = ($pshopgoods[0]["deliverType"]==0)? "下单成功":"下单成功等待审核";
			$data["logUserId"] = $uid;
			$data["logType"] = 0;
			$data["logTime"] = date('Y-m-d H:i:s');
			$mlogo = M('log_orders');
			$mlogo->add($data);
			
			//提醒商品有订单来了
			
			
			//建立订单提醒
			$sql ="SELECT userId,shopId,shopName FROM __PREFIX__shops WHERE shopId=$shopId AND shopFlag=1  ";
			$users = $this->query($sql);
			$morm = M('order_reminds');
			for($i=0;$i<count($users);$i++){
				$data = array();
				$data["orderId"] = $orderId;
				$data["shopId"] = $shopId;
				$data["userId"] = $users[$i]["userId"];
				$data["userType"] = 0;
				$data["remindType"] = 0;
				$data["createTime"] = date("Y-m-d H:i:s");
				$morm->add($data);
			}
		}
		return array('status'=>1);
	}

	// 处理秒杀商品
	function _payMiaosha($uid, $orderId) {
		$rst = array('status' => 1);
		$status = $this->query("select f_pay_miaosha($uid, $orderId) rst");
		if((int)$status['rst'] < 0) {
			$rst['status'] = -206;
			$rst['data'] = '获取云购纪录失败';
		}
		$rst['uid'] = $uid;
		$rst['orderId'] = $orderId;
		$rst['result'] = $status;
		return $rst;
		
//		$rst = array('status' => 1);
//		// 获取云购纪录码
//		$mmdb = M('MemberMiaosha');
//		$mmmap = array(
//			'orderId'	=> $orderId,
//		);
//		$memberMiaosha = $mmdb->field('mmid, miaoshaId, qishu, miaoshaCount')->where($mmmap)->find();
//		if(empty($memberMiaosha)) {
//			$rst['status'] = -206;
//			$rst['data'] = '获取云购纪录失败';
//			return $rst;
//		}
//		$mmid = (int)$memberMiaosha['mmid'];
//		$qishu = (int)$memberMiaosha['qishu'];
//		$miaoshaId = $memberMiaosha['miaoshaId'];
//		$goodsCount = (int)$memberMiaosha['miaoshaCount'];
//		
//		// 获取云购码
//		$mcdb = M('MiaoshaCode');
//		$mcmap = array(
//			'mc.miaoshaId'		=> $miaoshaId,
//			'mc.uid'				=> 0,
//			'mc.mmid'			=> 0,
//			'm.miaoshaStatus'	=> array('lt', 2), 
//		);
//		$codes = $mcdb->field('mc.mcid, mc.qishu,mc.miaoshaCode,mc.miaoshaCode,rand() factor')
//			->join('mc  inner join __GOODS__ g on mc.miaoshaId = g.miaoshaId')
//			->join('inner join __MIAOSHA__ m on mc.miaoshaId = m.miaoshaId and mc.qishu=m.qishu')
//			->where($mcmap)
//			->order('factor asc')->page(1, $goodsCount)->select();
//		
//		if(empty($codes)) {
//			$rst['status'] = -201;
//			$rst['data'] = '生成云购码失败';
////			$rst['sql'] = $mcdb->getLastSql();
//			return $rst;
//		}
//		
//		// 标记云购码
//		$mcids = array();
//		foreach($codes as $code) {
//			array_push($mcids, $code['mcid']);
//		}
//		
//		if($mcdb->where('mcid in('.join(',', $mcids).')')
//			->save(array( 'uid'=>$uid, 'mmid' => $mmid)) === FALSE) {
//			$rst['status'] = -203;
//			$rst['data'] = '获取云购码失败';
//			return $rst;
//		}
//		
//		$mdb = M('miaosha');
//		// 判断是否该标记结束
//		$sql = "select 1 from cky_miaosha m WHERE miaoshaId='$miaoshaId' and shengyurenshu=0 and not EXISTS(select 0 from cky_member_miaosha mm,cky_orders o where mm.miaoshaId=m.miaoshaId and mm.qishu=m.qishu and o.orderId = mm.orderId and o.isPay=0 and o.orderStatus = 0)";
//		$isEnd = $mdb->query($sql);
//		
//		if(!$isEnd) {
//			$rst['status'] = -207;
//			$rst['data'] = '获取秒杀商品状态失败';
//			return $rst;
//		}
//		
//		
//		
//			$rst['data0'] = 'is end = false';
//		if(!empty($isEnd)) {
//			// 结束商品
//			$rst['data1'] = 'is end = true';
//			$mddata = array('miaoshaStatus'	=> 2);
//			if($mdb->where(array('miaoshaId'=>$miaoshaId))->save($mddata) === FALSE) {
//				$rst['status'] = -204;
//				$rst['data'] = '修改秒杀购买次数失败';
//				return $rst;
//			}	
//			
////			$rst['data'] = $mdb->getLastSql();
//		}
//		
//		return $rst;
	}
	
	/**
	 * 商家发货配送订单
	 */
	public function shopOrderDelivery ($obj){		
		$userId = (int)$obj["userId"];
		$orderId = (int)$obj["orderId"];
		$shopId = (int)$obj["shopId"];
		$rsdata = array();
		$sql = "SELECT orderId,orderNo,orderStatus FROM __PREFIX__orders WHERE orderId = $orderId AND orderFlag =1 and shopId=".$shopId;		
		$rsv = $this->queryRow($sql);
		if($rsv["orderStatus"]!=2){
			$rsdata["status"] = -1;
			return $rsdata;
		}

		$sql = "UPDATE __PREFIX__orders set orderStatus = 3 WHERE orderId = $orderId and shopId=".$shopId;		
		$rs = $this->execute($sql);		

		$data = array();
		$m = M('log_orders');
		$data["orderId"] = $orderId;
		$data["logContent"] = "商家已发货";
		$data["logUserId"] = $userId;
		$data["logType"] = 0;
		$data["logTime"] = date('Y-m-d H:i:s');
		$ra = $m->add($data);
		$rsdata["status"] = $ra;;
		return $rsdata;
	}
	
    /**
	 * 商家发货配送订单
	 */
	public function batchShopOrderDelivery ($obj){		
		$USER = session('RTC_USER');
		$userId = (int)$USER["userId"];
		$orderIds = I("orderIds");
		$shopId = (int)$USER["shopId"];
		if($orderIds=='')return array('status'=>-2);
		$orderIds = explode(',',$orderIds);
		$orderNum = count($orderIds);
		$editOrderNum = 0;
		foreach ($orderIds as $orderId){
			if($orderId=='')continue;//订单号为空则跳过
			$sql = "SELECT orderId,orderNo,orderStatus FROM __PREFIX__orders WHERE orderId = $orderId AND orderFlag =1 and shopId=".$shopId;		
			$rsv = $this->queryRow($sql);
			if($rsv["orderStatus"]!=2)continue;//状态不符合则跳过
	
			$sql = "UPDATE __PREFIX__orders set orderStatus = 3 WHERE orderId = $orderId and shopId=".$shopId;		
			$rs = $this->execute($sql);		
	
			$data = array();
			$m = M('log_orders');
			$data["orderId"] = $orderId;
			$data["logContent"] = "商家已发货";
			$data["logUserId"] = $userId;
			$data["logType"] = 0;
			$data["logTime"] = date('Y-m-d H:i:s');
			$ra = $m->add($data);
		    $editOrderNum++;
		}
		if($editOrderNum==0)return array('status'=>-1);//没有符合条件的执行操作
		if($editOrderNum<$orderNum)return array('status'=>-2);//只有部分订单符合操作
		return array('status'=>1);
	}
	
	/**
	 * 商家确认收货
	 */
	public function shopOrderReceipt ($obj){		
		$userId = (int)$obj["userId"];
		$shopId = (int)$obj["shopId"];
		$orderId = (int)$obj["orderId"];
		$rsdata = array();
		$sql = "SELECT orderId,orderNo,orderStatus FROM __PREFIX__orders WHERE orderId = $orderId AND orderFlag =1 and shopId=".$shopId;		
		$rsv = $this->queryRow($sql);
		if($rsv["orderStatus"]!=4){
			$rsdata["status"] = -1;
			return $rsdata;
		}

		$sql = "UPDATE __PREFIX__orders set orderStatus = 5 WHERE orderId = $orderId and shopId=".$shopId;		
		$rs = $this->execute($sql);		

		$data = array();
		$m = M('log_orders');
		$data["orderId"] = $orderId;
		$data["logContent"] = "商家确认已收货，订单完成";
		$data["logUserId"] = $userId;
		$data["logType"] = 0;
		$data["logTime"] = date('Y-m-d H:i:s');
		$ra = $m->add($data);
		$rsdata["status"] = $ra;;
		return $rsdata;
	}
	/**
	 * 商家确认拒收/不同意拒收
	 */
	public function shopOrderRefund ($obj){		
		$userId = (int)$obj["userId"];
		$orderId = (int)$obj["orderId"];
		$shopId = (int)$obj["shopId"];
		$type = (int)I('type');
		$rsdata = array();
		$sql = "SELECT orderId,orderNo,orderStatus FROM __PREFIX__orders WHERE orderId = $orderId AND orderFlag = 1 and shopId=".$shopId;		
		$rsv = $this->queryRow($sql);
		if($rsv["orderStatus"]!= -3){
			$rsdata["status"] = -1;
			return $rsdata;
		}
		//同意拒收
        if($type==1){
			$sql = "UPDATE __PREFIX__orders set orderStatus = -4 WHERE orderId = $orderId and shopId=".$shopId;		
			$rs = $this->execute($sql);
			//加回库存
			if($rs>0){
				$sql = "SELECT goodsId,goodsNums,goodsAttrId from __PREFIX__order_goods WHERE orderId = $orderId";
				$oglist = $this->query($sql);
				foreach ($oglist as $key => $ogoods) {
					$goodsId = $ogoods["goodsId"];
					$goodsNums = $ogoods["goodsNums"];
					$goodsAttrId = $ogoods["goodsAttrId"];
					$sql = "UPDATE __PREFIX__goods set goodsStock = goodsStock+$goodsNums WHERE goodsId = $goodsId";
					$this->execute($sql);
					if($goodsAttrId>0){
						$sql = "UPDATE __PREFIX__goods_attributes set attrStock = attrStock+$goodsNums WHERE id = $goodsAttrId";
						$this->execute($sql);
					}
				}
			}	
        }else{//不同意拒收
        	if(I('rejectionRemarks')=='')return $rsdata;//不同意拒收必须填写原因
        	$sql = "UPDATE __PREFIX__orders set orderStatus = -5 WHERE orderId = $orderId and shopId=".$shopId;		
			$rs = $this->execute($sql);
        }
		$data = array();
		$m = M('log_orders');
		$data["orderId"] = $orderId;
		$data["logContent"] = ($type==1)?"商家同意拒收":"商家不同意拒收：".I('rejectionRemarks');
		$data["logUserId"] = $userId;
		$data["logType"] = 0;
		$data["logTime"] = date('Y-m-d H:i:s');
		$ra = $m->add($data);
		$rsdata["status"] = $ra;;
		return $rsdata;
	}
	
	/**
	 * 检查订单是否已支付
	 */
	public function checkOrderPay ($obj){
		$userId = (int)$obj["userId"];
		$orderIds = $obj["orderIds"];
		$sql = "SELECT count(orderId) counts FROM __PREFIX__orders WHERE userId = $userId AND orderId in ($orderIds) AND orderFlag = 1 AND orderStatus = -2 AND isPay = 0 AND payType = 1";
		$rsv = $this->query($sql);
		$ocnt = count(explode(",",$orderIds));
		$data = array();
		
		if($ocnt==$rsv[0]['counts']){
			
			$sql = "SELECT og.goodsId,og.goodsName,og.goodsAttrName,g.goodsStock,og.goodsNums, og.goodsAttrId, ga.attrStock FROM  __PREFIX__goods g ,__PREFIX__order_goods og
					left join __PREFIX__goods_attributes ga on ga.goodsId=og.goodsId and og.goodsAttrId=ga.id
					WHERE og.goodsId = g.goodsId and og.orderId in($orderIds)";
			$glist = $this->query($sql);
			if(count($glist)>0){
				$rlist = array();
				foreach ($glist as $goods) {
					if($goods["goodsAttrId"]>0){
						if($goods["attrStock"]<$goods["goodsNums"]){
							$rlist[] = $goods;
						}
					}else{
						if($goods["goodsStock"]<$goods["goodsNums"]){
							$rlist[] = $goods;
						}
					}
				}
				if(count($rlist)>0){
					$data["status"] = -2;
					$data["rlist"] = $rlist;
				}else{
					$data["status"] = 1;
				}
			}else{
				$data["status"] = 1;
			}
		}else{
			$data["status"] = -1;
		}
		
		
		return $data;
	}
}
	
	
	
	