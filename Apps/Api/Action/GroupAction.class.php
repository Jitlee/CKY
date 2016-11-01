<?php
namespace Api\Action;
/**
 * ============================================================================
 * 粗卡云:
  
 * 联系方式:
 * ============================================================================
 * 首页（默认）控制器
 */

class GroupAction extends BaseAction {
	public function newest() {
		$m = D('GoodsGroup');
		$data = $m->queryPage();
		$this->json($data);
	}
	
	public function page($pageNo = 1, $pageSize = 10) {
		$m = D('GoodsGroup');
		$data = $m->queryPage($pageNo, $pageSize);
		$this->json($data);
	}
}
?>