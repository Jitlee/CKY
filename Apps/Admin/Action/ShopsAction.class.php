<?php
 namespace Admin\Action;;
/**
 * ============================================================================
 * 粗卡云:
  
 * 联系方式:
 * ============================================================================
 * 店铺控制器
 */
class ShopsAction extends BaseAction{
	/**
	 * 跳到新增/编辑页面
	 */
	public function toEdit(){
		$this->isLogin();
		
		//获取地区信息
		$m = D('Admin/Areas');
		$this->assign('areaList',$m->queryShowByList(0));
		//获取银行列表
		$m = D('Admin/Banks');
		$this->assign('bankList',$m->queryByList(0));
		//获取商品信息
	    $m = D('Admin/Shops');
    	$object = array();
    	if(I('id',0)>0){
    		$this->checkPrivelege('ppgl_02');
    		$object = $m->get();
			// 获取行业分类
			$m = M('ShopPlates');
			$plates = $m->where(array('shopId' => I('id')))->select();
			$object['plates'] = $plates;
    	}else{
    		$this->checkPrivelege('ppgl_01');
    		$object = $m->getModel();
    	}
		
		//获取商品分类信息
		$m = D('Admin/GoodsCats');
		$cats = $m->queryByList();
		foreach($cats as $key => $cat) {
			$childPlates = $m->queryByList($cat['catId']);
			$cats[$key]['children'] = $childPlates;
			if(!empty($plates)) { // 设置是否选中  设置第一项
				foreach($plates as $plate1) {
					if($plate1['plateId1'] == $cat['catId']) {
						$cats[$key]['checked'] = true;
						$cats[$key]['plateSort'] = $plate1['plateSort'];
						
						// 设置是否选中  设置第二项
						foreach($childPlates as $key2 => $plate2) {
							if($plate2['catId'] == $plate1['plateId2']) {
								$cats[$key]['children'][$key2]['checked'] = true;
								break;
							}
						}
						break;
					}
				}
			}
		}

		$this->assign('goodsCatsList', $cats);
    	
    	$this->assign('object',$object);
    	$this->assign('src',I('src','index'));
		$this->view->display('/shops/edit');
	}
	/**
	 * 新增/修改操作
	 */
	public function edit(){
		$this->isAjaxLogin();
		$m = D('Admin/Shops');
		$m->startTrans();
    	$rs = array();
    	if(I('id',0)>0){
    		$this->checkAjaxPrivelege('ppgl_02');
    		if(I('shopStatus',0)<=-1){
    			$rs = $m->reject();
    		}else{
    		    $rs = $m->edit();
    		}
    	}else{
    		$this->checkAjaxPrivelege('ppgl_01');
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
	 * 删除操作
	 */
	public function del(){
		$this->isAjaxLogin();
		$this->checkAjaxPrivelege('ppgl_03');
		$m = D('Admin/Shops');
    	$rs = $m->del();
    	$this->ajaxReturn($rs);
	}
   /**
	 * 查看
	 */
	public function toView(){
		$this->isLogin();
		$this->checkPrivelege('ppgl_00');
		$m = D('Admin/Shops');
		if(I('id')>0){
			$object = $m->get();
			$this->assign('object',$object);
		}
		$this->view->display('/shops/view');
	}
	/**
	 * 分页查询
	 */
	public function index(){
		$this->isLogin();
		$this->checkPrivelege('ppgl_00');
		//获取地区信息
		$m = D('Admin/Areas');
		$this->assign('areaList',$m->queryShowByList(0));
		$m = D('Admin/Shops');
    	$page = $m->queryByPage();
//		echo $m->getLastSql();
    	$pager = new \Think\Page($page['total'],$page['pageSize']);// 实例化分页类 传入总记录数和每页显示的记录数
    	$page['pager'] = $pager->show();
    	
    	$this->assign('Page',$page);
    	$this->assign('shopName',I('shopName'));
    	$this->assign('shopSn',I('shopSn'));
    	$this->assign('areaId1',I('areaId1',0));
    	$this->assign('areaId2',I('areaId2',0));
        $this->display("/shops/list");
	}
    /**
	 * 分页查询[待审核列表]
	 */
	public function queryPeddingByPage(){
		$this->isLogin();
		$this->checkPrivelege('dpsh_00');
		//获取地区信息
		$m = D('Admin/Areas');
		$this->assign('areaList',$m->queryShowByList(0));
		$m = D('Admin/Shops');
    	$page = $m->queryPeddingByPage();
    	$pager = new \Think\Page($page['total'],$page['pageSize']);
    	$pager->setConfig('header','');
    	$page['pager'] = $pager->show();
    	$this->assign('Page',$page);
    	$this->assign('shopName',I('shopName'));
    	$this->assign('shopSn',I('shopSn'));
    	$this->assign('shopStatus',I('shopStatus',-999));
    	$this->assign('areaId1',I('areaId1',0));
    	$this->assign('areaId2',I('areaId2',0));
        $this->display("/shops/list_pendding");
	}
	/**
	 * 列表查询
	 */
    public function queryByList(){
    	$this->isAjaxLogin();
		$m = D('Admin/Shops');
		$list = $m->queryList();
		$rs = array();
		$rs['status'] = 1;
		$rs['list'] = $list;
		$this->ajaxReturn($rs);
	}
    /**
	 * 获取待审核的店铺数量
	 */
	public function queryPenddingGoodsNum(){
		$this->isAjaxLogin();
    	$m = D('Admin/Shops');
    	$rs = $m->queryPenddingShopsNum();
    	$this->ajaxReturn($rs);
	}
};
?>