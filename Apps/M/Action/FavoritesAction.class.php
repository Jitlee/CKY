<?php
namespace M\Action;
use Think\Controller;
 

class FavoritesAction extends BaseAction {
	public function add() {
		$uid = (int)session('uid');
		$status = -1;
		if($uid > 0) {
			$db = D('Favorites');
			$id = $db->insert($uid);
//			echo $db->getLastSql();
			if($id > 0) {
				$status = 1;
			}
		} else {
			$status = -2;
		}
		$this->ajaxReturn($status, "JSON");
	}
	
	public function check() {
		$uid = (int)session('uid');
		if($uid > 0) {
			$db = D('Favorites');
			$fav = $db->check($uid);
			if(!empty($fav)) {
				$this->ajaxReturn(true, "JSON");
				return;
			}
		}
		$this->ajaxReturn(false, "JSON");
	}
	
	public function cancel() {
		$uid = (int)session('uid');
		if($uid > 0) {
			$db = D('Favorites');
			if($db->cancel($uid)) {
				$this->ajaxReturn(true, "JSON");
				return;
			}
		}
		$this->ajaxReturn(false, "JSON");
	}
	
	public function del() {
		$uid = (int)session('uid');
		$rst = array('status'=>-1);
		if($uid > 0) {
			$db = D('Favorites');
			$id = $db->del($uid);
			if($id > 0) {
				$status = 1;
			}
		} else {
			$rst['status'] = -2;
		}
		$this->ajaxReturn($rst, "JSON");
	}
	
	public function index() {
		try_login();
		
		$uid = (int)session('uid');
		$db = D('Favorites');
		$cnts = $db->cnt($uid);
		$this->assign('counts', $cnts);
		$this->assign('title', '我的收藏');
		$this->display();
	}
	
	public function pf() {
		$uid = (int)session('uid');
		if($uid > 0) {
			$db = D('Favorites');
			$list = $db->lst($uid);
			$this->ajaxReturn($list, "JSON");
		}
	}
}