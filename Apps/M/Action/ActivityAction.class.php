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
	public function coupon() {
		$this->assign('title', '领券中心');
		$this->display();
	}
	
	public function pageCoupons() {
		$m = D('M/ActivityTicket');
//		$uid = getuid();
		$uid = 3;
		$list = $m->queryAll($uid);
//		echo $m->getLastSql();
		$this->ajaxReturn($list, 'JSON');
	}
	
	public function pick() {
		if(IS_POST) {
			$ticketId = I('ticketId');
//			$uid = getuid();
			$uid = 2;
			$mm = D('M/ActivityTicketM');
			$mm->startTrans();
			$status = -1;
			if($mm->pick($ticketId, $uid) !== false) {
				$m = D('M/ActivityTicket');
				if($m->updateUsedCount($ticketId) !== false) {
					$status = 1;
				} 
			}
			if($status > 0) {
				$mm->commit();
			} else {
				$mm->rollback();
			}
			
			$this->ajaxReturn($status > 0, 'JSON');
		}
	}
}