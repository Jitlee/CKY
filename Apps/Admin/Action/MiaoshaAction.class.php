<?php
 namespace Admin\Action;;
/**
 * ============================================================================
 * 粗卡云:
  
 * 联系方式:
 * ============================================================================
 * 广告控制器
 */
class MiaoshaAction extends BaseAction{
	/**
	 * 分页查询
	 */
	public function index(){
		$this->isLogin();
		//获取商品分类信息		
		$m = D('Admin/GoodsCats');
		$cats=$m->queryBykey('yyg');		
		$this->assign('goodsCatsList',$cats);
		$goodsCatId1=I('goodsCatId1',0);
		
		if($cats)
		{
			$goodsCatId1=$cats[0]["catId"];
		}
		
		$m = D('Admin/Miaosha');		 
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
        
   		$this->display('list');
	}
	public function history(){
		$this->isLogin(); 
		
		$m = D('Admin/Miaosha');		 
    	$page = $m->queryHistoryByPage();
    	$pager = new \Think\Page($page['total'],$page['pageSize']);// 实例化分页类 传入总记录数和每页显示的记录数
    	$page['pager'] = $pager->show();
    	$this->assign('Page',$page); 
        
   		$this->display('history');
	}

	public function order(){
		$this->isLogin(); 		
		$m = D('Admin/Miaosha');		 
    	$page = $m->queryOrderByPage();
    	$pager = new \Think\Page($page['total'],$page['pageSize']);// 实例化分页类 传入总记录数和每页显示的记录数
    	$page['pager'] = $pager->show();
    	$this->assign('Page',$page); 
        
   		$this->display('history');
	}
	
	/**
	 * 跳到新增/编辑页面
	 */
	public function toEdit(){
		$this->isLogin();
		$USER = session('RTC_USER');
		//获取商品分类信息
		$m = D('Admin/GoodsCats');
		$this->assign('goodsCatsList',$m->queryBykey('yyg'));

		//获取商品类型
		$m = D('Admin/Miaosha');
		$object = array();
    	if(I('id',0)>0){
    		$object = $m->get();
    	}else{
    		$object = $m->getModel();
			$object["maxqishu"]=100;
			$object["xiangou"]=0;
    	}
		//echo dump($object);
    	$this->assign('object',$object);
    	$this->assign("umark",I('umark'));
        $this->display("miaosha/edit");
	}
	/**
	 * 新增商品
	 */
	public function edit(){
		$this->isLogin();
		$m = D('Admin/Miaosha');
	    	$rs = array('status'=>-1);
	    	if((int)I('id',0)>0){
	    		$rs = $m->edit();
	    	}
	    	else{
	    		$m->startTrans();
	    		$rs = $m->insert();
			if($rs['status'] == 1) {
				// 创建云购码
				$mc = D('Admin/MiaoshaCode');
				$rs['status'] = $mc->createCodes($rs['data']['miaoshaId'], $rs['data']['qishu'], $rs['data']['count']);
			}
			if($rs['status'] == 1) {
				$m->commit();
			} else {
				$m->rollback();
			}
	    	}
//		echo dump($rs['data']);
	    	$this->ajaxReturn($rs);
	}
	/**
	 * 删除商品
	 */
	public function del(){
		$this->isLogin();
		$m = D('Home/Miaosha');
		$m->startTrans();
		$rs = $m->del($miaoshaId);
		$miaoshaId = I('id');
		if($rs['status'] == 1) {
			$mc = D('Admin/MiaoshaCode');
			$rs['status'] = $mc->del($miaoshaId, 1);
		}
		if($rs['status'] == 1) {
			$m->commit();
		} else {
			$m->rollback();
		}
		$this->ajaxReturn($rs);
	}
}