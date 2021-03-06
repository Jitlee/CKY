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
class ActivityAction extends BaseUserAction {
	public function index() {
		$user_agent = $_SERVER['HTTP_USER_AGENT'];
		if (strpos($user_agent, 'MicroMessenger') >0) {
			try_login();
		}
		$this->assign('title', '活动');
		$addb = D('M/Ads');
		$ads = $addb->queryByType(-3);
		
		if(!empty($ads)) {
			$this->assign('ads', $ads[0]);
		}
		
		$m = D('M/GoodsCats');
		$categories = array_slice($m->queryByParentkey("activity"), 0, 5);
	    $this->assign('catList', $categories);
		//echo dump($categories);
		$this->display();
	}
	
	public function page() {
		$m = D('M/Activity');
		$list = $m->queryByCatId();
//		echo $m->getLastSql();
		$this->ajaxReturn($list, 'JSON');
	}
	
	public function detail() {
		
		$mdb = D("Member");
		$score = $mdb->getScore();
		$this->assign("score", $score);
		
		$m = D('M/Activity');
		$data = $m->getById();
//		echo $m->getLastSql();
		$data['activityContent'] = htmlspecialchars_decode(html_entity_decode($data['activityContent']));
//		echo dump($data);
		$this->assign('title', $data['activityTitle']);
		$this->assign('data', $data);
		$this->display();
	}
	
	/**
	 * 获取卡券列表
	 */
	public function coupon() {
		$this->assign('title', '领券中心');
		
		$mdb = D("Member");
		$score = $mdb->getScore();
		$this->assign("score", $score);
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
			
			if(!$mm->isReceived($uid, $ticketId)) {				
				$status = -2;				
			} else if(!$mm->isNewUser($uid, $ticketId)) {
				$status = -3;
			} if(!$mm->isScoreBalance($uid, $ticketId)) {
				$status = -5;
			} else {
				$mm->startTrans();
				$rst = $mm->pick($ticketId, $uid);
				if($rst !== FALSE && $rst['status'] != -1) {
					$m = D('M/ActivityTicket');
					if($m->updateSendCount($ticketId) !== FALSE) {
						$status = 1;
					} 
				}
				
				if($rst['status'] == -1) {
					$status = -4; // 一卡易发送卡券失败
				}
				
				if($status > 0) {
					$mm->commit();
				} else {
					$mm->rollback();
				}
		}
			$this->ajaxReturn($status, 'JSON');
//			$this->ajaxReturn($rst, 'JSON');
			
		}
	}
	
	public function pagePersonCoupons() {
		$m = D('M/ActivityTicket');
		$uid = getuid();
		$list = $m->queryPersonAll($uid);
//		echo $m->getLastSql();
		$this->ajaxReturn($list, 'JSON');
	}
	
	public function coming() {
		$this->assign('title', '活动预告');
		$this->display();
	}
	
	public function pageComing() {
		$m = D('M/Activity');
		$list = $m->queryComing();
//		echo $m->getLastSql();
		$this->ajaxReturn($list, 'JSON');
	}
	
	public function shopActivities() {
		$m = D('M/Activity');
		$list = $m->queryByShopId();
		$this->ajaxReturn($list, 'JSON');
	}
	
	public function coupondetail() {
		$m = D('M/ActivityTicket');
		$id = I('id');
		$uid = getuid();
		$data = $m->getById($id, $uid);
//		echo $m->getLastSql();
		$data['detail'] = htmlspecialchars_decode(html_entity_decode($data['detail']));
		$this->assign('data', $data);
		$this->assign('title', $data['title']);
		$this->display();
	}
}