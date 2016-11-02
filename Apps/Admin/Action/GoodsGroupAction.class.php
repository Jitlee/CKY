<?php
 namespace Admin\Action;;
/**
 * ============================================================================
 * 粗卡云:
  
 * 联系方式:
 * ============================================================================
 * 商家控制器
 */
class GoodsGroupAction extends BaseAction{
	public function index(){
		$this->isLogin();
		//获取商品分类信息		
		$m = D('Admin/GoodsCats');
		$cats=$m->queryBykey('goodsgroup');		
		$this->assign('goodsCatsList',$cats);
		$goodsCatId1=I('goodsCatId1',0);
		
		if($cats)
		{
			$goodsCatId1=$cats[0]["catId"];
		}
		
		$m = D('Admin/GoodsGroup');
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
        
   		$this->display('goodsgroup/list');
	}
	
	/**
	 * 跳到新增/编辑页面
	 */
	public function toEdit(){
		$this->isLogin();
		$USER = session('RTC_USER');
		//获取商品分类信息
		$m = D('Admin/GoodsCats');
		$this->assign('goodsCatsList',$m->queryBykey('goodsgroup'));
		
//		$mshop = D('Admin/Ticket');
//		$shops=$mshop->queryByList();		
//	    $this->assign('tickets',$shops);
//	    
//	    //商家
//	    $mshop = D('Admin/Shops');
//		$shops=$mshop->queryListForSelect();		
//	    $this->assign('shops',$shops);
	    
		//获取商品类型
		$m = D('Admin/GoodsGroup');
		$object = array();
    	if(I('id',0)>0){
    		$object = $m->get();
    	}else{
    		$object = $m->getModel();
			$object['groupStartTime'] = date('Y-m-d');
    		$object['groupEndTime'] = date('Y-m-d',strtotime("+12 month"));
//			$object["xiangou"]=0;
    	}
		//echo dump($object);
    	$this->assign('object',$object);
    	$this->assign("umark",I('umark'));
        $this->display("goodsgroup/edit");
	}
	/**
	 * 新增商品
	 */
	public function edit(){
		$this->isLogin();
		$m = D('Admin/GoodsGroup');
    	$rs = array('status'=>-1);
    	if((int)I('id',0)>0){
    		$rs = $m->edit();
    	}
    	else{
    		$rs = $m->insert();
    	}
    	$this->ajaxReturn($rs);
	}
};
?>