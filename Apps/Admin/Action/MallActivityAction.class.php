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
			$object["starttime"] = date('Y-m-d');
			$object["endtime"] = date("Y-m-d",strtotime("+1 month"));
			$object["sort"] =0;
    	}		
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
	    	if(I('mactid',0)>0){
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
	 public function editStatus(){ 
	 	$this->isAjaxLogin();
	 	$this->checkAjaxPrivelege('wzlb_02');
	 	$m = D('Admin/MallActivity');
		$rs = $m->editStatus();
		$this->ajaxReturn($rs);
	 }
	 
	 /*********明细管理**********/
	 public function activityGoods(){ 
	 	$this->isAjaxLogin();
	 	$this->checkAjaxPrivelege('wzlb_02');
		
	 	$m = D('Admin/MallActivityGoods');
    	$page = $m->queryByPageForActivityClass();
    	$pager = new \Think\Page($page['total'],$page['pageSize']);// 实例化分页类 传入总记录数和每页显示的记录数
    	$page['pager'] = $pager->show();
    	$this->assign('Page',$page);
		$this->assign('mactid',I("mactid"));
		$this->display("/mallactivity/activitygoods");
	 }
	 
	 public function addGoodsList(){ 
		$this->isLogin();
		$this->checkPrivelege('splb_00');
		
		//获取商品分类信息
		$m = D('Admin/GoodsCats');
		$cats=$m->queryByParentkey('mall');
		$this->assign('goodsCatsList2',$cats);
		 
		if($goodsCatId1==0)
		{
			$goodsCatId1=(int)$cats["catId"];
		}
		//活动分类
		$mactid=I("mactid");
		$mactim = D('Admin/MallActivitym');
    	$mactimpage = $mactim->queryByPage($mactid);	
    	$this->assign('mActimPage',$mactimpage['root']);
		 
		 
		$m = D('Admin/MallActivityGoods');
		$page = $m->queryByPageForAddGoods();
		$pager = new \Think\Page($page['total'],$page['pageSize']);// 实例化分页类 传入总记录数和每页显示的记录数
		$page['pager'] = $pager->show();
		$this->assign('Page',$page);
		$this->assign('shopName',I('shopName'));
		$this->assign('goodsName',I('goodsName'));		
		$this->assign('goodsCatId1',$goodsCatId1);
		$this->assign('goodsCatId2',I('goodsCatId2',0));		
		
		$this->assign('mactid',$mactid);
		$this->display("/mallactivity/activitygoodsadd");	
	 }
	 
	 
	 /**
	 * 批量设置推荐
	 */
	public function activityGoodsAdd(){
		$this->isAjaxLogin();
		$this->checkAjaxPrivelege('splb_04');
		$m = D('Admin/MallActivityGoods');
		$rs = $m->addGoods();
		$this->ajaxReturn($rs);
	}
	
	public function activityGoodsDel(){
		$this->isAjaxLogin();
		$this->checkAjaxPrivelege('splb_04');
		$m = D('Admin/MallActivityGoods');
		$rs = $m->activityGoodsDel();
		$this->ajaxReturn($rs);
	}
	 
};
?>