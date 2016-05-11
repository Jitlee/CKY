<?php
namespace M\Action;
/**
 * ============================================================================
 * 粗卡云:
  
 * 联系方式:
 * ============================================================================
 * 推荐餐厅（默认）控制器
 */
use Think\Controller;
class RecommendAction extends BaseUserAction {
	
	 
	public function index() {
		$this->assign('title', "推荐餐厅");
		//$this->assign('data', $result);
		
		 
		//****分享与定位***/
		$wxm= new WxUserInfo();
		$signPackage=$wxm->getSignPackage();			 
		$this->assign('signPackage', $signPackage);
		
		$addb = D('ads');
		$ads = $addb->queryByType(-2);
		$this->assign('ads', $ads); 
		
		$this->display();
	}
	
	
	public function page() {
		$m = D('M/Recommend');
		$list = $m->RecommendList();
		foreach ($list as $key =>$v) {
			$v["goodsItems"] = $m->getGoodsByShopid($v["shopId"]);
			$v["shoptypeskey"]=1;
			if(strstr($v["shoptypes"], '快餐'))
			{
				$v["shoptypeskey"]=0;	
			}
			if($newitem) {
				array_push($newitem,$v);
			} else {
				$newitem[]=$v;
			}
		}
		$this->ajaxReturn($newitem, 'JSON');
	}
}