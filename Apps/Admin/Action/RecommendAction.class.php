<?php
 namespace Admin\Action;;
/**
 * ============================================================================
 * 粗卡云:
 * 联系方式:
 * ============================================================================
 * 推荐餐厅控制器
 */
class RecommendAction extends BaseAction{
		/**
	 * 新增/修改操作
	 */
	public function torecommend(){
		$this->isLogin(); 
		//获取商品信息
	    $m = D('Admin/Shops');
		$mrec = D('Admin/Recommend'); 
		
    	$object = array();
		$object = $m->get();
		$recom = $mrec->getModel();
		if(I('recommid',0)>0){
			$recobject = $mrec->get();
			$this->assign('recom',$recobject);
			$object["shopsid"]=I('id');
    	}else{ 
    		if(I('id',0)>0)
    		{
				$object["shopsid"]=I('id');
				$object["RecommStatus"]=1;
	    	}
	    	else
	    	{
	    		$object = $m->getModel();
	    	}
    	}
    	
    	$this->assign('object',$object);
		$this->assign('src',I('src','index'));
		$this->view->display('/recommend/edit'); 
	}
	
	/**
	 * 新增/修改操作
	 */
	public function edit(){
		$m = D('Admin/Recommend');
		$m->startTrans();
	    	$rs = array();
	    	if(I('recommid',0)>0){ 
	    		    $rs = $m->edit(); 
	    	}else{ 
	    		$rs = $m->insert();
	    	}	
		if($rs['status'] < 0) {
			$m->rollback();
		} else {
			$m->commit();
		}
	    	$this->ajaxReturn($rs);
	}
	
		/**
	 * 分页查询
	 */
	public function index(){
		$this->isLogin();
		//获取地区信息
		$m = D('Admin/Recommend');
    	$page = $m->queryByPage();
    	$pager = new \Think\Page($page['total'],$page['pageSize']);// 实例化分页类 传入总记录数和每页显示的记录数
    	$page['pager'] = $pager->show();
    	
    	$this->assign('Page',$page);
    	$this->assign('shopName',I('shopName'));
    	$this->assign('shopSn',I('shopSn'));
    	$this->assign('areaId1',I('areaId1',0));
    	$this->assign('areaId2',I('areaId2',0));
        $this->display("list");
	}
		
}
	