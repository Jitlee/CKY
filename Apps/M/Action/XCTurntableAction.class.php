<?php
namespace M\Action;
/**
 * ============================================================================
 * 粗卡云:  
 * 联系方式:
 * ============================================================================
 * 大转盘 控制器
 */
use Think\Controller;
class XCTurntableAction extends BaseAction {
	
	public function index() {
		layout(false);
		$this->display();
	}
	
	
}