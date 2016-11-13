<?php
 namespace Admin\Model;
/**
 * ============================================================================
 * 粗卡云:
  
 * 联系方式:
 * ============================================================================
 * 订单服务类
 */
class OrdersModel extends BaseModel {
	/**
	 * 获取订单详细信息
	 */
	 public function getDetail(){
	 	$m = M('orders');
	 	$id = (int)I('id',0);
		$sql = "select o.*,s.shopName from __PREFIX__orders o
	 	         left join __PREFIX__shops s on o.shopId=s.shopId 
	 	         where o.orderFlag=1 and o.orderId=".$id;
		$rs = $this->queryRow($sql);
		//获取用户详细地址
		$sql = 'select communityName,a1.areaName areaName1,a2.areaName areaName2,a3.areaName areaName3 from __PREFIX__communitys c 
		        left join __PREFIX__areas a1 on a1.areaId=c.areaId1 
		        left join __PREFIX__areas a2 on a2.areaId=c.areaId2
		        left join __PREFIX__areas a3 on a3.areaId=c.areaId3
		        where c.communityId='.$rs['communityId'];
		$cRs = $this->queryRow($sql);
		$rs['userAddress'] = $cRs['areaName1'].$cRs['areaName2'].$cRs['areaName3'].$cRs['communityName'].$rs['userAddress'];
		//获取日志信息
		$m = M('log_orders');
		$sql = "select lo.*,u.loginName,u.userType,s.shopName from __PREFIX__log_orders lo
		         left join __PREFIX__users u on lo.logUserId = u.userId
		         left join __PREFIX__shops s on u.userType!=0 and s.userId=u.userId
		         where orderId=".$id;
		$rs['log'] = $this->query($sql);
		//获取相关商品
		$sql = "select og.*,g.goodsThums,g.goodsName,g.goodsId from __PREFIX__order_goods og
			        left join __PREFIX__goods g on og.goodsId=g.goodsId
			        where og.orderId = ".$id;
		$rs['goodslist'] = $this->query($sql);
		
		return $rs;
	 }
	 /**
	  * 获取订单信息
	  */
	 public function get(){
	 	$m = M('orders');
	 	return $m->where('isRefund=0 and payType=1 and isPay=1 and orderFlag=1 and orderStatus in (-1,-4,-6,-7) and orderId='.(int)I('id'))->find();
	 }
	 /**
	  * 订单分页列表
	  */
     public function queryByPage(){
        $m = M('goods');
        $shopName = I('shopName');
     	$orderNo = I('orderNo');
     	$areaId1 = (int)I('areaId1',0);
     	$areaId2 = (int)I('areaId2',0);
     	$areaId3 = (int)I('areaId3',0);
     	$orderStatus = (int)I('orderStatus',-9999);
     	$dateRange=I('dateRange');
	 	$sql = "select o.orderId,o.orderNo,o.totalMoney,o.orderStatus,o.deliverMoney,o.payType,o.createTime,s.shopName,o.userName from __PREFIX__orders o
	 	         left join __PREFIX__shops s on o.shopId=s.shopId  
	 	         where o.orderFlag=1  and o.orderStatus >0  and (o.orderType !=-1 or (o.orderType = -1 and  o.orderStatus>1)) ";

	 	if($shopName!='')$sql.=" and (s.shopName like '%".$shopName."%' or s.shopSn like '%".$shopName."%')";
	 	if($orderNo!='')$sql.=" and o.orderNo like '%".$orderNo."%' ";
	 	if($orderStatus!=-9999 && $orderStatus!=-100)$sql.=" and o.orderStatus=".$orderStatus;
	 	if($orderStatus==-100)$sql.=" and o.orderStatus in(-6,-7)";
		if($orderStatus!=-2) $sql.=" and o.orderStatus not in (-2)";
		if($dateRange !=''){
			$dates = explode('至', I('dateRange'));
			$sql.=" and o.createTime between '".$dates[0]." 00:00:00' and '".$dates[1]." 23:59:59'"; 	
		}	
		
	 	$sql.=" order by orderId desc";   
		$page = $m->pageQuery($sql);
		//获取涉及的订单及商品
		if(count($page['root'])>0){
			$orderIds = array();
			foreach ($page['root'] as $key => $v){
				$orderIds[] = $v['orderId'];
			}
			$sql = "select og.orderId,og.goodsThums,og.goodsName,og.goodsId from __PREFIX__order_goods og
			        where og.orderId in(".implode(',',$orderIds).")";
		    $rs = $this->query($sql);
		    $goodslist = array();
		    foreach ($rs as $key => $v){
		    	$goodslist[$v['orderId']][] = $v;
		    }
		    foreach ($page['root'] as $key => $v){
		    	$page['root'][$key]['goodslist'] = $goodslist[$v['orderId']];
		    }
		}
		return $page;
	 }
	 /**
	  * 获取退款列表
	  */
     public function queryRefundByPage(){
        $m = M('goods');
        $shopName = I('shopName');
     	$orderNo = I('orderNo');
     	
     	$dateRange=I('dateRange');
	 	$sql = "select 
	o.orderId,o.orderNo,o.totalMoney,o.orderStatus,o.deliverMoney,o.payType,o.createTime,s.shopName,o.userName,g.groupStatus
from 
	cky_orders o
left join cky_shops s on o.shopId=s.shopId  
inner join cky_group_detail gd on o.orderType=-1 and o.mmid=gd.groupDetailId
inner join cky_group g on g.groupId=gd.groupId 
where 
	o.orderFlag=1  and o.orderType = -1   and orderStatus>0  
	 and `groupStatus` IN (1,2,3,4) ";

	 	if($shopName!='')$sql.=" and (s.shopName like '%".$shopName."%' or s.shopSn like '%".$shopName."%')";
	 	if($orderNo!='')$sql.=" and o.orderNo like '%".$orderNo."%' ";
	 	
		if($dateRange !=''){
			$dates = explode('至', I('dateRange'));
			$sql.=" and o.createTime between '".$dates[0]." 00:00:00' and '".$dates[1]." 23:59:59'"; 	
		}		
	 	$sql.=" order by orderId desc";  
	 	 
		$page = $m->pageQuery($sql);
		//获取涉及的订单及商品
		if(count($page['root'])>0){
			$orderIds = array();
			foreach ($page['root'] as $key => $v){
				$orderIds[] = $v['orderId'];
			}
			$sql = "select og.orderId,og.goodsThums,og.goodsName,og.goodsId from __PREFIX__order_goods og
			        where og.orderId in(".implode(',',$orderIds).")";
		    $rs = $this->query($sql);
		    $goodslist = array();
		    foreach ($rs as $key => $v){
		    	$goodslist[$v['orderId']][] = $v;
		    }
		    foreach ($page['root'] as $key => $v){
		    	$page['root'][$key]['goodslist'] = $goodslist[$v['orderId']];
		    }
		}
		return $page;
	 }
	 /**
	  * 退款
	  */
	 public function refund(){
	 	$rd = array('status'=>-1);
	 	
	 	$m = M('orders');
	 	$id = (int)I('id',0);
		$sql = "select o.*,s.shopName from __PREFIX__orders o
	 	         left join __PREFIX__shops s on o.shopId=s.shopId 
	 	         where o.orderFlag=1 and o.orderId=".$id;
		$rs = $this->queryRow($sql);
		if(false !== $rs){
			//执行退还金额到余额  //获取用户信息
			
			//更新表
			$data = array();
	 		$data['isRefund'] = 1;
	 		$data['refundRemark'] = I('content');
	 	    $rss = $m->where("orderId=".(int)I('id',0))->save($data);
			if(false !== $rs){
				$rd['status']= 1;
			}else{
				$rd['status']= -2;
			}
		}else{
			$rd['status']= -2;
			return $rd;
		}
		
//	 	$m = M('orders');
//	 	$rs = $m->where('isRefund=0 and orderFlag=1 and orderStatus in (-1,-4,-6,-7) and payType=1 and isPay=1 and orderId='.I('id'))->find();
//	 	if($rs['orderId']!=''){
//	 		$data = array();
//	 		$data['isRefund'] = 1;
//	 		$data['refundRemark'] = I('content');
//	 	    $rss = $m->where("orderId=".(int)I('id',0))->save($data);
//			if(false !== $rs){
//				$rd['status']= 1;
//			}else{
//				$rd['status']= -2;
//			}
//	 	}
	 	return $rd;
	 }
	
	/**
	 * 统计已付款商家未处理单的个数
	 */
	public function totalUntreated() {
		return $this->where('orderStatus = 1')->count();
	}
};
?>