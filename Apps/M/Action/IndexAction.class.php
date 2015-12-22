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
		$this->display('default/index');
	}
}