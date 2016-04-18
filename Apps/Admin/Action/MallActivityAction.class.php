<?php
 namespace Admin\Action;;
/**
 * ============================================================================
 * 粗卡云:
  
 * 联系方式:
 * ============================================================================
 * 商城活动控制器
 */
class MallActivityAction extends BaseAction{
	/**
	 * 跳到新增/编辑页面
	 */
	public function toEdit(){
		$this->isLogin();
	    $m = D('Admin/MallActivity');
    	$object = array();
    	if(I('id',0)>0){
    		$this->checkPrivelege('wzlb_02');
    		$object = $m->get();
		//dump($object);
    	}else{
    		$this->checkPrivelege('wzlb_01');
    		$object = $m->getModel();
			$object["efficacySDate"] = date('Y-m-d');
			$object["efficacyEDate"] = date("Y-m-d",strtotime("+1 month"));
			$object["activitySort"] =0;
    	}
    	$m = D('Admin/GoodsCats');
    	$this->assign('catList',$m->queryByParentkey("activity"));
		
		//select shops
		$mshop = D('Admin/Shops');
		$shops=$mshop->queryListForSelect();		
	    $this->assign('shops',$shops);
		//end select shops
		//卡券加载
		$mshop = D('Admin/Ticket');
		$shops=$mshop->queryByList();		
	    $this->assign('tickets',$shops);
		
    	$this->assign('object',$object);
		$this->view->display('/mallactivity/edit');
	}
	/**
	 * 新增/修改操作
	 */
	public function edit(){
		$this->isAjaxLogin();
		$rs = array('status'=>-1);
		try {
			$m = D('Admin/MallActivity');
		    
		    	if(I('id',0)>0){
		    		$this->checkAjaxPrivelege('wzlb_02');
		    		$rs = $m->edit();
		    	}else{
		    		$this->checkAjaxPrivelege('wzlb_01');
		    		$rs = $m->insert();
		    	}
		}catch (Exception $e){
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
		$m = D('Admin/MallActivity');
	    	$rs = $m->del();
	    	$this->ajaxReturn($rs);
	}
   /**
	 * 查看
	 */
	public function toView(){
		$this->isLogin();
		$this->checkPrivelege('wzlb_00');
		$m = D('Admin/MallActivity');
		if(I('id')>0){
			$object = $m->get();
			$this->assign('object',$object);
		}
		$this->view->display('/mallactivity/view');
	}
	/**
	 * 分页查询
	 */
	public function index(){
		$this->isLogin();
		$this->checkPrivelege('wzlb_00');
		$m = D('Admin/MallActivity');
    	$page = $m->queryByPage();
    	$pager = new \Think\Page($page['total'],$page['pageSize']);// 实例化分页类 传入总记录数和每页显示的记录数
    	$page['pager'] = $pager->show();
    	$this->assign('Page',$page);
    	$this->assign('mactname',I('mactname'));
        $this->display("/mallactivity/list");
	}
	/**
	 * 列表查询
	 */
    public function queryByList(){
    	$this->isAjaxLogin();
		$m = D('Admin/MallActivity');
		$list = $m->queryByList();
		$rs = array();
		$rs['status'] = 1;
		$rs['list'] = $list;
		$this->ajaxReturn($rs);
	}
    /**
	 * 显示商品是否显示/隐藏
	 */
	 public function editiIsShow(){ 
	 	$this->isAjaxLogin();
	 	$this->checkAjaxPrivelege('wzlb_02');
	 	$m = D('Admin/MallActivity');
		$rs = $m->editiIsShow();
		$this->ajaxReturn($rs);
	 }
};
?>