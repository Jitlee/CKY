<?php
namespace Home\Action;
/**
 * ============================================================================
 * 粗卡云:
  
 * 联系方式:
 * ============================================================================
 * 商家控制器
 */
class ShopsSubAction extends BaseAction {
	/**
	 * 跳到商家中心页面
	 */
	public function index(){
		$this->isShopLogin();
		$this->assign("umark","toShopsSub");
		$USER = session('RTC_USER');
		
		$m = D('Home/ShopsSub');
    	$page = $m->queryByPage($USER['shopId']);
    	$pager = new \Think\Page($page['total'],$page['pageSize']);
    	$page['pager'] = $pager->show();
    	$this->assign('Page',$page);    	
    	$this->assign("shopName",I('shopName'));
		$this->display("default/shops/shopssub/index");
	}
	/**
	 * 编辑商家资料
	 */
	public function toEdit(){
		$this->isShopLogin();
		$USER = session('RTC_USER');
		
		$m = D('Home/ShopsSub');
		if(I('id',0)>0){
    		$object = $m->get();
    	}else{
    		$object = $m->getModel();
    	}
    	
    	
    	$this->assign('object',$object);
		$this->assign("umark","toShopsSub");
		$this->display("default/shops/shopssub/edit");
	}
	
	public function edit(){
		$this->isShopLogin();
		$m = D('Home/ShopsSub');
    	$rs = array();
    	if((int)I('id',0)>0){
    		$rs = $m->edit();
    	}else{
    		$rs = $m->insert();
    	}
    	$this->ajaxReturn($rs);
	}
	
	/**
	 * 删除商品
	 */
	public function del(){
		$this->isShopLogin();
		$m = D('Home/ShopsSub');
		$rs = $m->del();
		$this->ajaxReturn($rs);
	}
	

}