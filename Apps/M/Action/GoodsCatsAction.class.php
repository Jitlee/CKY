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
class GoodsCatsAction extends BaseAction {
	public function query() {
		$m = D('M/GoodsCats');
		$list = $m->queryByParentId();
		$this->ajaxReturn($list, 'JSON');
	}
}