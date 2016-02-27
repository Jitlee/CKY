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
		
		$addb = D('M/Ads');
		$ads = $addb->queryByType(-3);
		
		if(!empty($ads)) {
			$this->assign('ads', $ads[0]);
		}
		
		$m = D('Admin/GoodsCats');
		$categories = array_slice($m->queryByList(347), 0, 5);
	    	$this->assign('catList', $categories);
		$this->display();
	}
	
	public function page() {
		$m = D('M/Activity');
		$list = $m->queryByCatId();
//		echo $m->getLastSql();
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
		$uid = getuid();
//		$uid = 3;
		$list = $m->queryAll($uid);
//		echo $m->getLastSql();
		$this->ajaxReturn($list, 'JSON');
	}
	
	public function pick() {
		if(IS_POST) {
			$ticketId = I('ticketId');
			$uid = getuid();
//			$uid = 2;
			$mm = D('M/ActivityTicketM');
			$status = -1;
			
			if($mm->isReceived($uid, $ticketId)) {				
				$status = -2;				
			} else if($mm->isNewUser($uid, $ticketId)) {
				$status = -3;
			} else {
				$mm->startTrans();
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
			}
			
			$this->ajaxReturn($status, 'JSON');
		}
	}
	
	public function pagePersonCoupons() {
		$m = D('M/ActivityTicket');
		$uid = getuid();
		$list = $m->queryPersonAll($uid);
//		echo $m->getLastSql();
		$this->ajaxReturn($list, 'JSON');
	}
	
	public function comming() {
		$this->assign('title', '活动预告');
		$this->display();
	}
	
	public function pageComing() {
		$m = D('M/Activity');
		$list = $m->queryComing();
		$this->ajaxReturn($list, 'JSON');
	}
}