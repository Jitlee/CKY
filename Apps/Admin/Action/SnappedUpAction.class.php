<?php
 namespace Admin\Action;;
/**
 * ============================================================================
 * 粗卡云:
  
 * 联系方式:
 * ============================================================================
 * 抢购
 */
class SnappedUpAction extends BaseAction{
	/**
	 * 分页查询
	 */
	public function index(){
		$this->isLogin();
		//获取商品分类信息		
		$m = D('Admin/GoodsCats');
		$cats=$m->queryBykey('miaosha');		
		$this->assign('goodsCatsList',$cats);
		$goodsCatId1=I('goodsCatId1',0);
		
		if($cats)
		{
			$goodsCatId1=$cats[0]["catId"];
		}
		
		$m = D('Admin/SnappedUp');
    	$page = $m->queryByPage($goodsCatId1);
    	$pager = new \Think\Page($page['total'],$page['pageSize']);// 实例化分页类 传入总记录数和每页显示的记录数
    	$page['pager'] = $pager->show();
    	$this->assign('Page',$page);
    	$this->assign('shopName',I('shopName'));
    	$this->assign('goodsName',I('goodsName'));
    	$this->assign('areaId1',I('areaId1',0));
    	$this->assign('areaId2',I('areaId2',0));
    	$this->assign('goodsCatId1',$goodsCatId1);
    	$this->assign('goodsCatId2',I('goodsCatId2',0));
        
   		$this->display('snappedup/list');
	}
	
	/**
	 * 跳到新增/编辑页面
	 */
	public function toEdit(){
		$this->isLogin();
		$USER = session('RTC_USER');
		//获取商品分类信息
		$m = D('Admin/GoodsCats');
		$this->assign('goodsCatsList',$m->queryBykey('miaosha'));
		
		$mshop = D('Admin/Ticket');
		$shops=$mshop->queryByList();		
	    $this->assign('tickets',$shops);
	    
	    //商家
	    $mshop = D('Admin/Shops');
		$shops=$mshop->queryListForSelect();		
	    $this->assign('shops',$shops);
	    
		//获取商品类型
		$m = D('Admin/SnappedUp');
		$object = array();
    	if(I('id',0)>0){
    		$object = $m->get();
    	}else{
    		$object = $m->getModel();
			 
			$object["xiangou"]=0;
    	}
		//echo dump($object);
    	$this->assign('object',$object);
    	$this->assign("umark",I('umark'));
        $this->display("snappedup/edit");
	}
	/**
	 * 新增商品
	 */
	public function edit(){
		$this->isLogin();
		$m = D('Admin/SnappedUp');
    	$rs = array('status'=>-1);
    	if((int)I('id',0)>0){
    		$rs = $m->edit();
    	}
    	else{
    		$rs = $m->insert();
    	}
    	$this->ajaxReturn($rs);
	}
	/**
	 * 删除商品
	 */
	public function del(){
		$this->isLogin();
		$m = D('Home/SnappedUp');
		$rs = $m->del($miaoshaId);
		$this->ajaxReturn($rs);
	}
	
	/*********开始cats***********/
	public function catsindex(){
		$this->isLogin();
		 
		$m = D('Admin/SnappedupCats');
    	$page = $m->queryByList();
    	$pager = new \Think\Page($page['total'],$page['pageSize']);// 实例化分页类 传入总记录数和每页显示的记录数
    	$page['pager'] = $pager->show();
    	
    	$this->assign('Page',$page);
    	$this->assign('CName',I('CName'));
        
   		$this->display('snappedup/catslist');
	}
	
	/**
	 * 跳到新增/编辑页面
	 */
	public function catsToEdit(){
		$this->isLogin();
		$USER = session('RTC_USER');
		 
	    
		//获取商品类型
		$m = D('Admin/SnappedupCats');
		$object = array();
    	if(I('id',0)>0){
    		$object = $m->catsget();
    	}else{
    		$object = $m->getModel();
    	}
		//echo dump($object);
    	$this->assign('object',$object);
    	$this->assign("umark",I('umark'));
        $this->display("snappedup/catsedit");
	}
	/**
	 * 新增商品
	 */
	public function catsedit(){
		$this->isLogin();
		$m = D('Admin/SnappedupCats');
    	$rs = array('status'=>-1);
    	if((int)I('id',0)>0){
    		$rs = $m->catsedit();
    	}
    	else{
    		$rs = $m->catsinsert();
    	}
    	$this->ajaxReturn($rs);
	}
	
	public function catsEditiIsShow(){
		$this->isLogin();
		$m = D('Admin/SnappedupCats');
		$rs = $m->editiIsShow();
		$this->ajaxReturn($rs);
	}
	
	/**
	 * 删除商品
	 */
//	public function catsdel(){
//		$this->isLogin();
//		$m = D('Admin/SnappedupCats');
//		$rs = $m->catsdel();
//		$this->ajaxReturn($rs);
//	}
	/*********结束cats***********/
	
	/*********开始catsactivity***********/
	public function catsactivityindex(){
		$this->isLogin();
		 
		$m = D('Admin/SnappedupCatsActivity');
    	$page = $m->queryByList();
    	$pager = new \Think\Page($page['total'],$page['pageSize']);// 实例化分页类 传入总记录数和每页显示的记录数
    	$page['pager'] = $pager->show();
    	
    	$this->assign('Page',$page);
    	$this->assign('CName',I('CName'));
   		
   		$this->display('snappedup/catsactivitylist');
	}
	
	/**
	 * 跳到新增/编辑页面
	 */
	public function catsactivityToEdit(){
		$this->isLogin();
		$USER = session('RTC_USER');
		
	    //商家
	    $m = D('Admin/SnappedupCats');
    	$cats= $m->queryByListForDorpdown();	
	    $this->assign('cats',$cats);
	    
		//获取商品类型
		$m = D('Admin/SnappedupCatsActivity');
		$object = array();
    	if(I('id',0)>0){
    		$object = $m->get();
    	}else{
    		$object = $m->getModel();
    	}
		//echo dump($object);
    	$this->assign('object',$object);
    	$this->assign("umark",I('umark'));
        $this->display("snappedup/catsactivityedit");
	}
	/**
	 * 新增商品
	 */
	public function catsactivityedit(){
		$this->isLogin();
		$m = D('Admin/SnappedupCatsActivity');
    	$rs = array('status'=>-1);
    	if((int)I('id',0)>0){
    		$rs = $m->edit();
    	}
    	else{
    		$rs = $m->insert();
    	}
    	$this->ajaxReturn($rs);
	}
	
	public function catsactivityEditiIsShow(){
		$this->isLogin();
		$m = D('Admin/SnappedupCatsActivity');
		$rs = $m->editiIsShow();
		$this->ajaxReturn($rs);
	}
	/**
	 * 删除商品
	 */
//	public function catsactivitydel(){
//		$this->isLogin();
//		$m = D('Home/SnappedUp');
//		$rs = $m->catsactivitydel($miaoshaId);
//		$this->ajaxReturn($rs);
//	}
	/*********结束catsactivity***********/
	
	
	public function catsactivitygoodslist(){
		$this->isLogin(); 
		
		$m = D('Admin/SnappedUp');		 
//  	$page = $m->queryHistoryByPage();
		$SUCatsActivityId=I("id");

    	$pager = new \Think\Page($page['total'],$page['pageSize']);// 实例化分页类 传入总记录数和每页显示的记录数
    	$page['pager'] = $pager->show();
    	$this->assign('Page',$page); 
        
		$this->assign('SUCatsActivityId',$SUCatsActivityId); 
   		$this->display('snappedup/catsactivitygoodslist');
	}
	
	public function catsactivitygoodsadd(){
		$this->isLogin(); 
		
		$SUCatsActivityId=I("id");
		
		$m = D('Admin/SnappedupCatsActivityGoods');		 
//  	$page = $m->queryHistoryByPage();
    	$pager = new \Think\Page($page['total'],$page['pageSize']);// 实例化分页类 传入总记录数和每页显示的记录数
    	$page['pager'] = $pager->show();
    	$this->assign('Page',$page); 
        
   		$this->display('snappedup/catsactivitygoodsadd');
	}
 
	
}