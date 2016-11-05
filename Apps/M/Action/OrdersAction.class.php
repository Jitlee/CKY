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
	 * 商品提交订单
	 */
	public function group() {
		$m = D('GoodsGroup');
		$groupGoodsId = (int)I('groupGoodsId', 0);
		$groupId = (int)I('groupId', 0);
		$data = $m->goods($groupGoodsId);
		$this->assign('data', $data);
		$this->assign('title', '订单确认');
		$this->display();
	}
	
	/**
	 * 商品提交订单
	 */
	public function toPay() {
		$this->assign('title', '订单确认');
		$this->display("pay");
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
		
		$orderType = (int)$data['orderType'];
		
		if($orderType == 1 || $orderType == 2) {
			// 快餐
			$this->display("timeout_detail");
		} else {
			$this->display("detail");
		}
	}
	
	public function lst() {
//		$m = D('M/Orders');
//		$uid = getuid();
//		$m->closeTimeoutOrders($uid);
//		echo $m->getLastSql();
		$this->assign('title', '订单');
		$this->display('list');
	}
	
	public function page() {
		$m = D('M/Orders');
		$map = array('userId' => getuid());
		$list = $m->lst($map);
		$time = time();
//		foreach($list as $idx => $order) {
//			if((int)$order['orderStatus'] == 0
//				&& $time - strtotime($order['createTime']) > 300) {
//				// 超时5分钟
//				$list[$idx]['orderStatus'] = -2;
//			}
//		}
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
		//echo dump($data);
		//payType 是否在线支付 0 货到付款 1在线支付
		$payType=$data['payType'].'';
		//echo '$payType='.$payType;
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
	
	public function coupon() {
		$m = D('M/ActivityTicket');
		$uid = getuid();
		$t = I('_t', "");
		$ids =  explode(',', $t); // 采用shopId1, amount1, shopId2, amount2,... 存储
		$shopIds = array();
		$amounts = array();
		$totalAmount = 0;
		for ($i=0; $i < count($ids); $i += 2) {
			array_push($shopIds, (int)$ids[$i]);
			array_push($amounts, (float)$ids[$i + 1]);
			$totalAmount += (float)$ids[$i + 1];
		} 
		$list = $m->queryUseAll($uid, join($shopIds));
//		echo $m->getLastSql();
		$valids = array();
		$invalids = array();
		foreach($list as $ticket) {
			$ticket['valid'] = (int)$ticket['valid'];
			$ticket['miniConsumption'] = (int)$ticket['miniConsumption'];
			$ticket['maxiConsumption'] = (int)$ticket['maxiConsumption'];
			$ticket['shopId'] = (int)$ticket['shopId'];
			$amount = 0;
			if($ticket['shopId'] > 0) {
				$index = array_search($ticket['shopId'], $shopIds);
				$amount = $amounts[$index];
			} else {
				$amount = $totalAmount;
			}
			
			if($ticket['valid'] == 1
				&& $ticket['typeName'] == 'djq' // 目前只有代金券可以在线上使用
				&& ($ticket['miniConsumption'] == 0
				|| $ticket['miniConsumption'] <= $amount)
				&& ($ticket['maxiConsumption'] == 0 ||
				$ticket['maxiConsumption'] >= $amount)) {
				array_push($valids, $ticket);
			} else {
				array_push($invalids, $ticket);
			}
		}
		$this->assign('valids', $valids);
		$this->assign('invalids', $invalids);
		$this->assign('title', '使用优惠券');
		$this->display();
	}
	
	public function countcoupon() {
		$m = D('M/ActivityTicket');
		$uid = getuid();
		$t = I('_t', "");
		$ids =  explode(',', $t); // 采用shopId1, amount1, shopId2, amount2,... 存储
		$shopIds = array();
		$amounts = array();
		$totalAmount = 0;
		for ($i=0; $i < count($ids); $i += 2) {
			array_push($shopIds, (int)$ids[$i]);
			array_push($amounts, (float)$ids[$i + 1]);
			$totalAmount += (float)$ids[$i + 1];
		} 
		$list = $m->queryUseAll($uid, join($shopIds));
//		echo $m->getLastSql();
		$count = 0;
		foreach($list as $ticket) {
			$ticket['valid'] = (int)$ticket['valid'];
			$ticket['miniConsumption'] = (int)$ticket['miniConsumption'];
			$ticket['maxiConsumption'] = (int)$ticket['maxiConsumption'];
			$ticket['shopId'] = (int)$ticket['shopId'];
			$amount = 0;
			if($ticket['shopId'] > 0) {
				$index = array_search($ticket['shopId'], $shopIds);
				$amount = $amounts[$index];
			} else {
				$amount = $totalAmount;
			}
			
			if($ticket['valid'] == 1
				&& $ticket['typeName'] == 'djq' // 目前只有代金券可以在线上使用
				&& ($ticket['miniConsumption'] == 0
				|| $ticket['miniConsumption'] <= $amount)
				&& ($ticket['maxiConsumption'] == 0 ||
				$ticket['maxiConsumption'] >= $amount)) {
				$count++;
			}
		}
		$this->ajaxReturn($count, 'JSON');
	}
	 
	
	/**
	 * 取消订单
	 */
    public function cancel(){
    		$db = D('M/Orders');
	    	$uid = getuid();
	    	$orderId = (int)I("orderId");
	    	$orderStatus = (int)I("type", 0);
		$rst = null;
		if($orderStatus == -1 || $orderStatus == -2 || $orderStatus == 0) {
			$rst = $db->orderCancel($uid, $orderId, $orderStatus);
			$rst["status"]=1;
		} else {
			$rst = array('status' => -100, 'data' => '取消订单的操作类型错误');
		}
		$this->ajaxReturn($rst, 'JSON');
	}
	 
	
	/**
	 * 提交订单信息
	 * 
	 */
	public function submitOrder(){	
		$mshop = D('M/Shops');
		$mgoods = D('M/Goods');
		$morders = D('M/Orders');
		$mticket = D('M/ActivityTicket');
		$mgroup = D('M/GoodsGroup');
		$userId = getuid();
		
		$result = array('status' => 1);
		$consigneeId = (int)I("consigneeId");
		$payway = (int)I("payway"); // 支付途径
		$isself = (int)I("isself"); // 是否自取
		$needBox = (int)I("needBox", 0); // 是需要打包盒
		$cartGoods = (array)json_decode(html_entity_decode(stripslashes(I('goods'))));
		$needreceipt = (int)I("needreceipt"); // 是否需要票据
		$orderunique = I("orderunique");
		
		$ticketId = I('ticketId'); // 优惠券Id
		$ticket = null;
		if(!empty($ticketId)) {
			$ticket = $mticket->getById($ticketId, $userId);
			$ticket['limitUseShopID'] = (int)$ticket['limitUseShopID'];
			$ticket['ticketAmount'] = (float)$ticket['ticketAmount'];
			$ticket['miniConsumption'] = (int)$ticket['miniConsumption'];
			$ticket['maxiConsumption'] = (int)$ticket['maxiConsumption'];
			$ticket['ticketMStatus'] = (int)$ticket['ticketMStatus'];
			$ticket['stime'] = (int)$ticket['stime'];
			$ticket['etime'] = (int)$ticket['etime'];
		}
//		echo dump($ticket);
//		return;
		
//		echo '-----';
//		echo dump(I('goods'));
//		echo html_entity_decode(html_entity_decode(stripslashes(I('goods'))));
//		echo dump($cartGoods);
//		exit();
		
		$shopGoods = array();	
		$order = array();
		
		// 拼团
		$groupGoodsId = (int)I('groupGoodsId', 0); // 如果有则是拼团
		$groupId = (int)I('groupId', 0); // 如果有则是参团，没有是开团
				
		logger("/****************拼团日志$groupGoodsId****************/");
		if($groupGoodsId > 0) {
			if(count($cartGoods) != 1) {
				$result['status']  = -50;
				$result['data'] = '拼团数据异常!';
			} else {	
				$result = $mgroup->checkOrder($groupGoodsId, $groupId);
			}
		} 
		if($result['status'] == 1) {
			// 整理及核对购物车
			foreach($cartGoods as $key => $cg) {
				$goodsId = $cg->goodsId;
				$count = (int)$cg->count;
				if($count==0) {
					$result['status']  = -1;
					$result['data'] = '商品数量错误!';
					break;
				}
				
				$goods = $mgoods->info($goodsId,$goodsAttrId);
//				echo $mgoods->getLastSql();
				if(empty($goods)) {
					$result['status']  = -1;
					$result['data'] = '对不起，商品['.$goodsId.']不存在!';
					break;
				}
				
				if(!empty($goods['miaoshaId']) && intval($goods['shengyurenshu']) < $count) {
					$result['status']  = -8;
					$result['data'] = '对不起，商品【'.$goods['goodsName'].'】剩余人次不足!';
					break;
				}
				
				if(empty($goods['miaoshaId']) && intval($goods['goodsStock']) < $count) {
					$result['status']  = -2;
					$result['data'] = '对不起，商品【'.$goods['goodsName'].'】库存不足!';
					break;
				}
				if(intval($goods['isSale']) != 1){
					$result['status']  = -3;
					$result['data'] = '对不起，商品库【'.$goods['goodsName'].'】已下架!';
					break;
				}
				
				if($groupGoodsId > 0) { // 拼团免运费
					$count = 1;
					$goods["shopPrice"] = $result['groupPrice'];
					$goods["deliveryFreeMoney"] = 0;
					$goods["deliveryMoney"] = 0;
					$goods['goodsCatId1'] = -1;
				}
				
				$goods["cnt"] = $count;
				$shopGoods[$goods["shopId"]]["shopgoods"][] = $goods;
				$shopGoods[$goods["shopId"]]["deliveryFreeMoney"] = $goods["deliveryFreeMoney"];//商家免运费最低金额
				$shopGoods[$goods["shopId"]]["deliveryMoney"] = $goods["deliveryMoney"];//商家免运费最低金额
				$shopGoods[$goods["shopId"]]["totalCnt"] = $shopGoods[$goods["shopId"]]["totalCnt"]+$cgoods["cnt"];
				$shopGoods[$goods["shopId"]]["totalMoney"] = $shopGoods[$goods["shopId"]]["totalMoney"]+($goods["cnt"]*$goods["shopPrice"]) - $this->_calcFreeMoney($goods);
				$shopGoods[$goods["shopId"]]['ticketId'] = $ticketId;
				$shopGoods[$goods["shopId"]]['deductible'] = 0;
				
				$shopGoods[$goods["shopId"]]['orderType'] = (int)$goods['goodsCatId1']; // 0普通商品、1快餐、2一元购,-1拼团商品
	//			if((int)$goods['goodsCatId1'] < 3) {
	//				$shopGoods[$goods["shopId"]]['orderType'] = (int)$goods['goodsCatId1'];
	//			}		
			}
		}
		
		// 核对优惠券信息
		if($ticket && $result['status'] == 1) {
			// 是否过期
			$today = strtotime("today");
			if($ticket['ticketMStatus'] != 0) {
				$result['status']  = -7;
				$result['data'] = '对不起，次优惠券已被使用过!';
			} else if($today >= $ticket['stime'] && $today <= $ticket['etime']) {
				if($ticket['limitUseShopID'] > 0) { // 指定商铺券
					if($ticket['typeName'] == 'djq') { // 只处理代金券
						$_amount = $shopGoods[$ticket['limitUseShopID']]['totalMoney'];
						if($isself != 1 && $_amount < $shopGoods[$ticket['limitUseShopID']]['deliveryFreeMoney']) {
							$_amount += $shopGoods[$ticket['limitUseShopID']]['deliveryMoney'];
						}
						if($ticket['miniConsumption'] > $_amount) {
							$result['status']  = -5;
							$result['data'] = '对不起，消费总额未能达到代金券的使用要求!';
						} else {
							// 直接抵扣现金使用
							$shopGoods[$ticket['limitUseShopID']]['deductible'] = min($ticket['ticketAmount'], $_amount);
						}
					}
				} else { // 全平台券
					$_totalAmount = 0; // 所有金额总和
					$surplus = $ticket['ticketAmount'];
					foreach ($shopGoods as $shopId=> $shop) {
						$_amount = $shopGoods[$shopId]['totalMoney'];
						if($isself != 1 && $_amount < $shopGoods[$shopId]['deliveryFreeMoney']) {
							$_amount += $shopGoods[$shopId]['deliveryMoney'];
						}
						$_totalAmount += $_amount;
						
						if($ticket['typeName'] == 'djq') { // 只处理代金券
							if($surplus > 0) { // 从第一家开始扣起，直到为0
								if($surplus > $_amount) {
									$surplus -= $_amount;
									$shopGoods[$shopId]['deductible'] = $_amount;
								} else {
									$shopGoods[$shopId]['deductible'] = $surplus;
									$surplus = 0;
								}
							}
						}
					}
					
					if($ticket['miniConsumption'] > $_totalAmount) {
						$result['status']  = -6;
						$result['data'] = '对不起，消费总额未能达到代金券的使用要求!';
					}
				}
			} else {
				$result['status']  = -4;
				$result['data'] = '对不起，请在优惠券的有效期内使用!';
			}
		}
		if($result['status'] == 1) {
			$result = $morders->addOrders($userId,$consigneeId,$payway,$needreceipt,$shopGoods,$orderunique,$isself, $ticket, $groupGoodsId, $groupId, $needBox);
		}
		
		$this->ajaxReturn($result, 'JSON');
	}

	function _calcFreeMoney($goods) {
		$freeMoney = 0;
		if(!empty($goods['activeId'])) {
			switch($goods["activeType"]) {
				case "m2f1": // 买二付一
					$freeMoney = floor($goods["cnt"] / 2.0) * $goods["shopPrice"];
					break;
				case "m2mustless": // 第二件立减
					if($goods["activeAmount"] > 0) {
						$freeMoney = $goods["cnt"] > 1 ? $goods["activeAmount"] : 0;
					}
					break;
			}
		}
		return $freeMoney;
	}
	 

	public function test() {
		$orderId = (int)I('id');
		$db = D('M/Orders');
		$db->startTrans();
		$rst = $db->payOrder($orderId);
		if($rst['status'] == 1) {
			$db->commit();
		} else {
			$db->rollback();
		}
		$this->ajaxReturn($rst, 'JSON');
	}
	
}