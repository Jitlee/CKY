<?php
 namespace M\Action;;
/**
 * ============================================================================
 * 粗卡云:
  
 * 联系方式:
 * ============================================================================
 * 商品评价控制器
 */
class GoodsAppraisesAction extends BaseAction{
	/**
	 * 分页查询
	 */
//	public function index(){
//		$this->isShopLogin();
//		$USER = session("RTC_USER");
//		//获取商家商品分类
//		$m = D('Home/ShopsCats');
//		$this->assign('shopCatsList',$m->queryByList($USER['shopId'],0));
//		$m = D('Home/Goods_appraises');
//  	$page = $m->queryByPage($USER['shopId']);
//  	$pager = new \Think\Page($page['total'],$page['pageSize']);
//  	$page['pager'] = $pager->show();
//  	$this->assign('Page',$page);
//  	$this->assign("shopCatId2",I('shopCatId2'));
//  	$this->assign("shopCatId1",I('shopCatId1'));
//  	$this->assign("goodsName",I('goodsName'));
//  	$this->assign("umark","GoodsAppraises");
//      $this->display("default/shops/goodsappraises/list");
//	}
	
	
	/**
	 * 获取指定商品评价
	 */
	public function getAppraise(){
		 
		$m = D('M/Goods_appraises');
		$goodsId = (int)I("goodsId");
		$shopId = (int)I("shopId");
		if($goodsId>0)
		{
			$appraise = $m->getGoodsAppraises($goodsId);	
		}
		else 
		{
			$appraise = $m->getShopAppraises($shopId);
		}
    	$this->assign('appraise',$appraise);
    	//echo dump($appraise);
    	$this->assign('goodsId',$goodsId);
		$this->assign('shopId',$shopId);
		
    	$this->assign('title', "评价");
        $this->display("Orders/goodsappraise");
	}
	public function getAppraiseLoad(){
		 
		$m = D('M/Goods_appraises');
		$goodsId = (int)I("goodsId");
		$shopId = (int)I("shopId");
		
		$pageSize = I("pageSize");
		$pageNum =  I("pageNum");
		
    	$res = $m->getAppraisesPage($goodsId,$shopId,$pageSize,$pageNum);
    	$this->ajaxReturn($res, "JSON");
	}
	/******************************************************************
	 *                         会员操作
	 ******************************************************************/
	/**
	 * 订单评价
	 */
    public function toAppraise(){
    	$morders = D('M/Goods_appraises');
    	$obj["userId"] = session("uid");
    	$obj["orderId"] = (int)I("orderId");
		$rs = $morders->getOrderAppraises($obj);
		$this->assign("orderInfo",$rs);
		$this->assign('title', "订单评价");
		$this->display("Orders/appraise");
	}
	/**
	 * 添加评价
	 */
    public function addGoodsAppraises(){
    	//$this->isUserAjaxLogin();
    	//$USER = session('RTC_USER');
    	$morders = D('M/Goods_appraises');
    	$obj["userId"] = session("uid");
    	$obj["orderId"] = (int)I("orderId");
    	$obj["goodsId"] = (int)I("goodsId");
    	$obj["goodsAttrId"] = (int)I("goodsAttrId");
		$rs = $morders->addGoodsAppraises($obj);
		$this->ajaxReturn($rs);
	}	
	/**
	 * 获取评价
	 */
//  public function getAppraisesList(){
//  	$this->isUserLogin();
//  	$USER = session('RTC_USER');
//  	$morders = D('Home/Goods_appraises');
//  	$obj["userId"] = $USER['userId'];
//  	$this->assign("umark","getAppraisesList");
//		$appraiseList = $morders->getAppraisesList($obj);
//		$this->assign("appraiseList",$appraiseList);
//		$this->display("default/users/orders/list_appraise_manage");
//	} 
	/**
	 * 获取前台评价列表
	 */
//	public function getGoodsappraises(){	
//		$goods = D('Home/Goods_appraises');
//		$goodsAppraises = $goods->getGoodsAppraises();
//		$this->ajaxReturn($goodsAppraises);
//	}

};
?>