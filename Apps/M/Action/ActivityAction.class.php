<?php
namespace M\Action;
/**
 * ============================================================================
 * 粗卡云:
  
 * 联系方式:
 * ============================================================================
 * 活动控制器
 */
use Think\Controller;
class ActivityAction extends Controller {
	public function index() {
		$this->assign('title', '活动');
		
		$m = D('Admin/GoodsCats');
		$categories = array_slice($m->queryByList(347), 0, 5);
	    	$this->assign('catList', $categories);
		$this->display();
	}
	
	public function page() {
		$m = D('M/Activity');
		$list = $m->queryByCatId();
		$this->ajaxReturn($list, 'JSON');
	}
	
	public function detail() {
		$m = D('M/Activity');
		$data = $m->getById();
		$data['activityContent'] = htmlspecialchars_decode(html_entity_decode($data['activityContent']));
		$this->assign('title', $data['activityTitle']);
		$this->assign('data', $data);
		$this->display();
	}
	
	/**
	 * 获取卡券列表
	 */
	public function coupons() {
		
	}
}