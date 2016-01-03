<?php
namespace M\Action;
/**
 * ============================================================================
 * 粗卡云:
  
 * 联系方式:
 * ============================================================================
 * 商家 控制器
 */
use Think\Controller;
class ShopAction extends Controller {
	public function index() {
		$this->assign('title', "商家");
		$this->assign('tabid', 'shop');
		
		$this->display();
	}
}