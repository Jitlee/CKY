<?php
namespace Mobile\Action;
/**
 * ============================================================================
 * 粗卡云:
  
 * 联系方式:
 * ============================================================================
 * 首页（默认）控制器
 */

use Think\Controller;
class GroupAction extends Controller {
	public function index() {
		$this->assign('title', '粗卡');
		$this->display();
	}
}
?>