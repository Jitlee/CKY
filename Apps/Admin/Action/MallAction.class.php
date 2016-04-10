<?php
 namespace Admin\Action;;
/**
 * ============================================================================
 * 粗卡云:
  
 * 联系方式:
 * ============================================================================
 * 促销商品控制器
 */
class MallAction extends BaseAction{
	public function sales() {
		$this->isLogin();
		$this->checkAjaxPrivelege('gggl_00');
		self::RTCAssigns();
		
		$m = D('Admin/MallMods');
		$mods = $m->queryByType(1);
		$this->assign('mods', $mods);
		
		$this->display("/mall/sales");
	}
	
	public function edit() {
		$rst = array('status' => -1);
		if(IS_POST) {
			$this->isAjaxLogin();
			$m = D('Admin/MallMods');
			$mod1 = I('mod1', 0);
			if($mod1 > 0) { // 编辑
				$this->checkAjaxPrivelege('gggl_02');
				$rst = $m->edit();	
			} else {	 // 新增
				$this->checkAjaxPrivelege('gggl_01');
				$rst = $m->inert();	
			}
		}
		$this->ajaxReturn($rst, 'JSON');
	}
}