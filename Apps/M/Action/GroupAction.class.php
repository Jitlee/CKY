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
		$this->assign('title', '拼团');
		$this->display('index');
	}
	
	public function goods($groupGoodsId = 0, $groupId = 0) {
		$m = D('GoodsGroup');
		$data = $m->goods($groupGoodsId);
		$this->assign('data', $data);
//		echo dump($data);
		$this->assign('title', '拼团详情');
		$group = $data['group'];
		$goods = $data['goods'];
		if(!empty($group)) {
			$groupStatus = (int)$group['groupStatus'];
			if($groupStatus == 2) {
				$this->display('goods_done');
			} else if((float)$group['now'] < (float)$group['endTime']) {
				if((int)$group['isPay'] == 0) {
					$this->display('goods_nopay');
				} else {
					$this->display('goods_opened');
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
	
	public function members($groupId = 0) {
		$m = D('GoodsGroup');
		$members = $m->members($groupId);
		$this->ajaxReturn($members, 'JSON');
	}
}