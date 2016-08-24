<?php
namespace M\Action;
/**
 * ============================================================================
 * 粗卡云:
  
 * 联系方式:
 * ============================================================================
 * 购物车 控制器
 */
use Think\Controller;
class CartAction extends BaseAction {
	public function index() {
		$this->assign('title', '购物车');
		$this->display();
	}
	
	public function lst() {
		$this->assign('title', '购物车');
		$this->display("list");
	}
}