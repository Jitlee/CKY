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
class ShopsAction extends Controller {
	public function index() {
		$this->assign('title', "商家");
		$this->assign('tabid', 'shops');
		
//		$m = D('M/Shops');
//		$list = $m->queryByPage();
//		$this->assign('list', $list);
		
		$this->display();
	}
	
	public function page() {
		$m = D('M/Shops');
		$list = $m->shops();
		$this->ajaxReturn($list, 'JSON');
	}
	
	// 商家详情
	public function detail($id = 0) {
		$this->assign('id', $id);
		$this->assign('title', "简约Coffee");
		$this->display();
	}
	
	// 商品详情
	public function goods($id = 0) {
		$this->assign('id', $id);
		$this->assign('title', "商品详情");
		$this->display();
	}
}