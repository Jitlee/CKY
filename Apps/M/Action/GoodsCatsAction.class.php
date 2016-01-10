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
		$list = $m->query();
		$this->ajaxReturn($list, 'JSON');
	}
}