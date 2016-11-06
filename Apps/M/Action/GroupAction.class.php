<?php
namespace M\Action;
/**
 * ============================================================================
 * 粗卡云:
  
 * 联系方式:
 * ============================================================================
 * 拼团 控制器
 */
use Think\Controller;
class GroupAction extends BaseAction {
	public function index() {
		Header("HTTP/1.1 301 Moved Permanently");
     	Header("Location:/index.php/Mobile/Group/index");
	}
	
	public function goods() {
		$user_agent = $_SERVER['HTTP_USER_AGENT'];
		if (strpos($user_agent, 'MicroMessenger') >0) {
			try_login();
		}
		
		$groupGoodsId = (int)I('groupGoodsId', 0);
		$groupId = (int)I('groupId', 0);
		$m = D('GoodsGroup');
		$data = $m->goods($groupGoodsId);
		$data['goods']['goodsDesc'] = htmlspecialchars_decode(html_entity_decode($data['goods']['goodsDesc']));
		$this->assign('data', $data);
		$this->assign('title', '拼团详情');
		$group = $data['group'];
		$goods = $data['goods'];
//		echo dump($group);
		if(!empty($group)) {
			$groupStatus = (int)$group['groupStatus'];
			if($groupStatus == 2) {
				$this->display('goods_done');
			} else if((float)$group['now'] < (float)$group['endTime']) {
				if((int)$group['isPay'] == 0) {
					$this->display('goods_nopay');
				} else if($groupId > 0) {
					$this->display('goods_opened');
				} else {
					$groupId = (int)$group["groupId"];
					Header("HTTP/1.1 301 Moved Permanently");
     				Header("Location:/index.php/M/Group/goods?groupGoodsId=$groupGoodsId&groupId=$groupId");
				}
			} else { // 超期
				$this->display('goods_timeout');
			}
		} else if($groupId > 0 && (float)$goods['now'] < (float)$goods['endTime']) { // 参团链接
			$group = $m->group($groupId);
			if(empty($group)) {
				$this->display('goods');	
			} else {
				$data['group'] = $group;
				$this->assign('data', $data);
				$this->display('goods_join');
			}
		} else if((float)$goods['now'] < (float)$goods['endTime']) {
			$this->display('goods');	
		} else {
			$this->index();
		}
	}	
	
	public function detail($groupDetailId = 0) {
		$groupDetailId = (int)I('id', $groupDetailId);
		$m = D('GoodsGroup');
		$group = $m->detail($groupDetailId);
		$groupGoodsId = (int)$group['groupGoodsId'];
		$groupId = (int)$group['groupId'];
		Header("HTTP/1.1 301 Moved Permanently");
     	Header("Location:/index.php/M/Group/goods?groupGoodsId=$groupGoodsId&groupId=$groupId");
	}
	
	public function members($groupId = 0) {
		$groupId = (int)I('groupId', $groupId);
		$m = D('GoodsGroup');
		$members = $m->members($groupId);
		$this->ajaxReturn($members, 'JSON');
	}
}