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
		$this->display('default/index');
	}
}