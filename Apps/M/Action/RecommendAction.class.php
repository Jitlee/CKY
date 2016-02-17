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
		$this->display();
	}
	
	
	public function page() {
		$m = D('M/Recommend');
		$list = $m->fast();

		$this->ajaxReturn($list, 'JSON');
	}


	
}