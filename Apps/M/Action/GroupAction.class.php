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
	public function goods($groupGoodsId = 0) {
		$m = D('GoodsGroup');
		$data = $m->goods($groupGoodsId);
		$this->assign('data', $data);
		if(empty($object['group'])) {	
			$this->display('goods');
		} else {
			$this->display('goods_opened');
		}
	}
}