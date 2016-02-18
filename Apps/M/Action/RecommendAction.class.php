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
		$ads = $addb->queryByType(-1);
		$this->assign('ads', $ads); 
		
		$this->display();
	}
	
	
	public function page() {
		$m = D('M/Recommend');
		$list = $m->fast();
		//echo dump($list);
		
		$this->ajaxReturn($list, 'JSON');
	}


	
}