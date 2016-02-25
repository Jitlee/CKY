<?php
 namespace Admin\Action;;
/**
 * ============================================================================
 * 粗卡云:
  
 * 联系方式:
 * ============================================================================
 * 活动控制器
 */
class TicketAction extends BaseAction{
	/**
	 * 跳到新增/编辑页面
	 */
	public function toEdit(){
		$this->isLogin();
	    $m = D('Admin/Ticket');
	    	$object = array();
			
	    	if(strlen(I('id'))>0){
	    		$this->checkPrivelege('wzlb_02');
	    		$object = $m->get();
				//dump($object);
	    	}else{
	    		$this->checkPrivelege('wzlb_01');
	    		$object = $m->getModel();
				$object["limitDayUse"]=1;
				$object["limitDayGet"]=1;
				$object["limitGetnum"]=1;
				$object["miniConsumption"]=0;
				$object["efficacySDate"] = date('Y-m-d');
				$object["efficacyEDate"] = date("Y-m-d",strtotime("+1 month"));	
	    	}
//	    	$m = D('Admin/GoodsCats');
//	    	$this->assign('catList',$m->queryByList(347));
	    	$this->assign('object',$object);
			$this->view->display('/ticket/edit');
	}
	/**
	 * 新增/修改操作 
	 */
	public function edit(){
		$this->isAjaxLogin();
		$rs = array('status'=>-1);
		try {
			$m = D('Admin/Ticket');
		    
		    	if(strlen(I('ticketID'))>0){
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
		$m = D('Admin/Ticket');
	    	$rs = $m->del();
	    	$this->ajaxReturn($rs);
	}
   /**
	 * 查看
	 */
	public function toView(){
		$this->isLogin();
		$this->checkPrivelege('wzlb_00');
		$m = D('Admin/Ticket');
		if(I('id')>0){
			$object = $m->get();
			$this->assign('object',$object);
		}
		$this->view->display('/ticket/view');
	}
	/**
	 * 分页查询
	 */
	public function index(){
		$this->isLogin();
		$this->checkPrivelege('wzlb_00');
		$m = D('Admin/Ticket');
    	$page = $m->queryByPage();
    	$pager = new \Think\Page($page['total'],$page['pageSize']);// 实例化分页类 传入总记录数和每页显示的记录数
    	$page['pager'] = $pager->show();
    	$this->assign('Page',$page);
    	$this->assign('articleTitle',I('articleTitle'));
        $this->display("/ticket/list");
	}
	/**
	 * 列表查询
	 */
    public function queryByList(){
    	$this->isAjaxLogin();
		$m = D('Admin/Ticket');
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
	 	$m = D('Admin/Ticket');
		$rs = $m->editiIsShow();
		$this->ajaxReturn($rs);
	 }
};
?>