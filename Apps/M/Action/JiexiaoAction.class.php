<?php
namespace M\Action;
use Think\Controller;
use P\Model;

class JiexiaoAction extends BaseAction {

	public function tickSync() {
		$m = D('M/OneCardTick');
		$rd = $m->GetTick();
		$this->ajaxReturn($rd, 'JSON');
	}
	
}