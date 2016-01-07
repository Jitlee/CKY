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
class ShopsAction extends BaseAction {
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
	public function detail() {		
		$m = D('M/Shops');
		$data = $m->detail();
		$data['shopDesc'] = htmlspecialchars_decode(html_entity_decode($data['shopDesc']));
		$this->assign('data', $data);
		$this->assign('title', $data['shopName']);
		$this->display();
	}
	
	public function map() {
		$this->assign('title', I('shopName'));
		$this->display();
	}
}