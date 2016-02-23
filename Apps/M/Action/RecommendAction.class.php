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
			if($newitem) {
				array_push($newitem,$v);
			} else {
				$newitem[]=$v;
			}
		}
		$this->ajaxReturn($newitem, 'JSON');
	}
}