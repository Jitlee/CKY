<?php
 namespace Admin\Action;;
/**
 * ============================================================================
 * 粗卡云:
  
 * 联系方式:
 * ============================================================================
 * 商城活动控制器
 */
class MallActivitymAction extends BaseAction{
	/**
	 * 跳到新增/编辑页面
	 */
	public function toEdit(){
		$this->isLogin();
	    $m = D('Admin/MallActivitym');
    	$object = array();
    	if(I('mactmid',0)>0){
    		$this->checkPrivelege('wzlb_02');
    		$object = $m->get();
    	}else{
    		$this->checkPrivelege('wzlb_01');
    		$object = $m->getModel();
			$object["starttime"] = date('Y-m-d');
			$object["endtime"] = date("Y-m-d",strtotime("+1 month"));
			$object["sort"] =0;
    	}		
		
		$this->assign('mactid',I("mactid"));
    	$this->assign('object',$object);
		$this->view->display('/mallactivity/activitymedit');
	}
	/**
	 * 新增/修改操作
	 */
	public function edit(){
		$this->isAjaxLogin();
		$rs = array('status'=>-1);
		try {
			$m = D('Admin/MallActivitym');		    
	    	if(I('mactmid',0)>0){
	    		$this->checkAjaxPrivelege('wzlb_02');
	    		$rs = $m->edit();
	    	}else{
	    		$this->checkAjaxPrivelege('wzlb_01');
	    		$rs = $m->insert();
	    	}
		}
		catch (Exception $e){
            $rs["msg"]=$e;
        }
	    $this->ajaxReturn($rs);
	}
	/**
	 * 删除操作
	 */
	public function del(){
		$this->isAjaxLogin();
		$this->checkAjaxPrivelege('wzlb_03');
		$m = D('Admin/MallActivitym');
	    	$rs = $m->del();
	    	$this->ajaxReturn($rs);
	}
   /**
	 * 查看
	 */
	public function toView(){
		$this->isLogin();
		$this->checkPrivelege('wzlb_00');
		$m = D('Admin/MallActivitym');
		if(I('id')>0){
			$object = $m->get();
			$this->assign('object',$object);
		}
		$this->view->display('/mallactivity/activitymview');
	}
	/**
	 * 分页查询
	 */
	public function index($mactid){
		$this->isLogin();
		$this->checkPrivelege('wzlb_00');
		$m = D('Admin/MallActivitym');
    	$page = $m->queryByPage($mactid);

    	$this->assign('Page',$page);
    	$this->assign('mactname',I('mactname'));
		$this->assign('mactid',I("mactid"));
        $this->display("/mallactivity/activitymlist");
	}
	/**
	 * 列表查询
	 */
    public function queryByList(){
    	$this->isAjaxLogin();
		$m = D('Admin/MallActivitym');
		$list = $m->queryByList();
		$rs = array();
		$rs['status'] = 1;
		$rs['list'] = $list;
		$this->ajaxReturn($rs);
	}
    /**
	 * 显示商品是否显示/隐藏
	 */
	 public function editStatus(){ 
	 	$this->isAjaxLogin();
	 	$this->checkAjaxPrivelege('wzlb_02');
	 	$m = D('Admin/MallActivitym');
		$rs = $m->editStatus();
		$this->ajaxReturn($rs);
	 }
};
?>