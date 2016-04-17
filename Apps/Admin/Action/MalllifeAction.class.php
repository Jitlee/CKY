<?php
 namespace Admin\Action;;
/**
 * ============================================================================
 * 粗卡云:
  
 * 联系方式:
 * ============================================================================
 * 惠生活控制器
 */
class MalllifeAction extends BaseAction{
	/**
	 * 跳到新增/编辑页面
	 */
	public function typeToEdit(){
		$this->isLogin();
	    $m = D('Admin/MalllifeType');
    	$object = array();
    	if(I('id',0)>0){
    		$this->checkPrivelege('jsgl_02');
    		$object = $m->get();
    	}else{
    		$this->checkPrivelege('jsgl_01');
    		$object = $m->getModel();
    	}
    	$this->assign('object',$object);
		$this->view->display('/malllife/typeedit');
	}
	/**
	 * 新增/修改操作
	 */
	public function typeEdit(){
		$this->isAjaxLogin();
		$m = D('Admin/MalllifeType');
    	$rs = array();
    	if(I('lifetypeid',0)>0){
    		$this->checkAjaxPrivelege('jsgl_02');
    		$rs = $m->edit();
    	}else{
    		$this->checkAjaxPrivelege('jsgl_01');
    		$rs = $m->insert();
    	}
    	$this->ajaxReturn($rs);
	}
	/**
	 * 删除操作
	 */
	public function typeDel(){
		$this->isAjaxLogin();
		$this->checkAjaxPrivelege('jsgl_03');
		$m = D('Admin/MalllifeType');
    	$rs = $m->del();
    	$this->ajaxReturn($rs);
	}
	/**
	 * 分页查询
	 */
	public function typeIndex(){
		$this->isLogin();
		$this->checkPrivelege('jsgl_00');
		$m = D('Admin/MalllifeType');
    	$page = $m->queryByPage();
    	$pager = new \Think\Page($page['total'],$page['pageSize']);// 实例化分页类 传入总记录数和每页显示的记录数
    	$page['pager'] = $pager->show();
    	$this->assign('Page',$page);
        $this->display("/malllife/typelist");
	}
	/**
	 * 列表查询
	 */
    public function typeQueryByList(){
    	$this->isAjaxLogin();
		$m = D('Admin/MalllifeType');
		$list = $m->queryList();
		$rs = array();
		$rs['status'] = 1;
		$rs['list'] = $list;
		$this->ajaxReturn($rs);
	}
	
	
	/*********惠生活*********/
	/**
	 * 跳到新增/编辑页面
	 */
	public function lifeToEdit(){
		$this->isLogin();
	    $m = D('Admin/Malllife');
    	$object = array();
    	if(I('id',0)>0){
    		$this->checkPrivelege('jsgl_02');
    		$object = $m->get();
    	}else{
    		$this->checkPrivelege('jsgl_01');
    		$object = $m->getModel();
			$object["efficacysdate"] = date('Y-m-d');
			$object["efficacyedate"] = date("Y-m-d",strtotime("+12 month"));
			$object["sort"] =0;
			$object["status"] =0;
			
    	}
		$m = D('Admin/MalllifeType');
    	$this->assign('catList',$m->queryByList());
		
    	$this->assign('object',$object);
		$this->view->display('/malllife/lifeedit');
	}
	/**
	 * 新增/修改操作
	 */
	public function lifeEdit(){
		$this->isAjaxLogin();
		$m = D('Admin/Malllife');
    	$rs = array();
    	if(I('lifeid',0)>0){
    		$this->checkAjaxPrivelege('jsgl_02');
    		$rs = $m->edit();
    	}else{
    		$this->checkAjaxPrivelege('jsgl_01');
    		$rs = $m->insert();
    	}
    	$this->ajaxReturn($rs);
	}
	
	
	/**
	 * 显示商品是否显示/隐藏
	 */
	 public function editiIsShow(){
	 	$this->isAjaxLogin();
	 	$this->checkAjaxPrivelege('jsgl_03');
	 	$m = D('Admin/Malllife');
		$rs = $m->editiIsShow();
		$this->ajaxReturn($rs);
	 }
	
	/**
	 * 删除操作
	 */
	public function lifeDel(){
		$this->isAjaxLogin();
		$this->checkAjaxPrivelege('jsgl_03');
		$m = D('Admin/Malllife');
    	$rs = $m->del();
    	$this->ajaxReturn($rs);
	}
	/**
	 * 分页查询
	 */
	public function lifeIndex(){
		$this->isLogin();
		$this->checkPrivelege('jsgl_00');
		$m = D('Admin/Malllife');
    	$page = $m->queryByPage();
    	$pager = new \Think\Page($page['total'],$page['pageSize']);// 实例化分页类 传入总记录数和每页显示的记录数
    	$page['pager'] = $pager->show();
    	$this->assign('Page',$page);
		$this->assign('MalllifeTitle',I('MalllifeTitle'));
        $this->display("/malllife/lifelist");
	}
	/**
	 * 列表查询
	 */
    public function lifeQueryByList(){
    	$this->isAjaxLogin();
		$m = D('Admin/Malllife');
		$list = $m->queryList();
		$rs = array();
		$rs['status'] = 1;
		$rs['list'] = $list;
		$this->ajaxReturn($rs);
	}
	
	
};
?>