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
	
	/**
	 * 分页查询
	 */
	public function mallcatindex(){
		$this->isLogin();
		$this->checkPrivelege('spfl_00');
		$m = D('Admin/GoodsCats');
    	$list = $m->getCatAndChildBykey('mall');
    	$this->assign('List',$list);
        $this->display("/mall/mallcatlist");
	}
	
	public function mallcatToEdit(){
		$this->isLogin();
	    $m = D('Admin/GoodsCats');
    	$object = array();
    	if(I('id',0)>0){
    		$this->checkPrivelege('spfl_02');
    		$object = $m->get(I('id',0));
    	}else{
    		$this->checkPrivelege('spfl_01');
    		if(I('parentId',0)>0){
    		   $object = $m->get(I('parentId',0));
    		   $object['parentId'] = $object['catId'];
    		   $object['catName'] = '';
			   $object['catIcon'] = '';
    		   $object['catSort'] = 0;
    		   $object['catId'] = 0;
    		}else{
    		   $object = $m->getModel();
    		}
    	}
    	$this->assign('object',$object);
		$this->view->display('/mall/mallcatedit');
	}
	
}