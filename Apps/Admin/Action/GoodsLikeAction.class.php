<?php
 namespace Admin\Action;;
/**
 * ============================================================================
 * 粗卡云:
 * 联系方式:
 * ============================================================================
 * 猜你喜欢控制器
 */
class GoodsLikeAction extends BaseAction{
		/**
	 * 新增/修改操作
	 */
	public function toedit(){
		$this->isLogin(); 
		//获取商品信息
	    $m = D('Admin/Goods');
		$mrec = D('Admin/GoodsLike'); 
		
    	$likeobj = array();
		$goodsobj = $m->get();
		$likeobj = $mrec->getModel();
		if(I('likeid',0)>0){
			$likeobj = $mrec->get();
			$this->assign('like',$recobject);
    	}else{
    		 
    		$likeobj = $mrec->getbygoodsid();
			if($likeobj)
			{
				
			}	
			else  if(I('id',0)>0)
    		{
				$likeobj["goodsId"]=I('id');
				$likeobj["likestatus"]=1;
				$likeobj["sort"]=0;
				$likeobj["efficacysdate"] = date('Y-m-d');			
				$likeobj["efficacyedate"] = date("Y-m-d",strtotime("+1 month"));
	    	}
	    	else
	    	{
	    		$object = $m->getModel();
	    	}
    	}
    	$this->assign('like',$likeobj);
    	$this->assign('object',$goodsobj);
		$this->assign('src',I('src','index'));
		$this->view->display('/goodslike/edit'); 
	}
	
	/**
	 * 新增/修改操作
	 */
	public function edit(){
		$m = D('Admin/GoodsLike');
		//$m->startTrans();
    	$rs = array();
    	if(I('likeid',0)>0){ 
    		$rs = $m->edit(); 
    	}
    	else
    	{ 
    		$rs = $m->insert();
    	}	
 
	    $this->ajaxReturn($rs);
	}
	
		/**
	 * 分页查询
	 */
	public function index(){
		$this->isLogin();
		//获取地区信息
		$m = D('Admin/GoodsLike');
    	$page = $m->queryByPage();
    	$pager = new \Think\Page($page['total'],$page['pageSize']);// 实例化分页类 传入总记录数和每页显示的记录数
    	$page['pager'] = $pager->show();
    	
    	$this->assign('Page',$page);
    	$this->assign('goodsName',I('goodsName'));
    	$this->assign('goodsSn',I('goodsSn'));
//  	$this->assign('areaId1',I('areaId1',0));
//  	$this->assign('areaId2',I('areaId2',0));
        //$this->display("list");
        $this->display('/goodslike/list'); 
	}
		
	public function del(){
		$this->isAjaxLogin();		
		$m = D('Admin/GoodsLike');
    	$rs = $m->del();
    	$this->ajaxReturn($rs);
	}
		
}
	