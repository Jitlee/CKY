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
		layout('default/layout');
		$this->assign('title', "生活");
		$addb = D('ads');
		$ads = $addb->queryByType(0);
		$this->assign('ads', $ads);
		
		$this->display('default/index');
	}
}