<?php
namespace M\Action;
/**
 * ============================================================================
 * 粗卡云:
  
 * 联系方式:
 * ============================================================================
 * 首页（默认）控制器
 */
use Think\Controller;
class IndexAction extends Controller {
	public function index() {
		try_login();
		
		$this->assign('title', "粗卡");
		$this->assign('tabid', 'home');
		$addb = D('ads');
		$ads = $addb->queryByType(-1);
		$this->assign('ads', $ads); 
		
		$this->display();
	}
	
	public function guess() {
		$m = D('M/Goods');
		$list = $m->guess();
		$this->ajaxReturn($list, 'JSON');
	}
}