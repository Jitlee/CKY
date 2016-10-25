<?php
namespace Home\Action;
/**
 * ============================================================================
 * 粗卡云:
  
 * 联系方式:
 * ============================================================================
 * 商品拼团控制器
 */
class GoodsGroupAction extends BaseAction {
	public function goods() {
		$m = D('Home/ShopsCats');
		$shopId=(int)session('RTC_USER.shopId');
		$cats = $m->queryByList($shopId,0);
		$this->assign('shopCatsList',$cats);
		$m = D('GoodsGroup');
		$page = $m->goods();
		$pager = new \Think\Page($page['total'],$page['pageSize']);
	    	$page['pager'] = $pager->show();
	    	$this->assign("umark",'queryGoodsGroup');
	    	$this->assign("shopCatId2",I('shopCatId2'));
	    	$this->assign("shopCatId1",I('shopCatId1'));
	    	$this->assign("goodsName",I('goodsName'));
   		$this->assign('Page',$page);
   		$this->display('default/shops/group/goods');
	}
	
    public function lst(){
    		$m = D('Home/ShopsCats');
		$shopId=(int)session('RTC_USER.shopId');
		$cats = $m->queryByList($shopId,0);
		$this->assign('shopCatsList',$cats);
		$m = D('GoodsGroup');
		$page = $m->lst();
		$pager = new \Think\Page($page['total'],$page['pageSize']);
	    	$page['pager'] = $pager->show();
	    	$this->assign("umark",'queryGoodsGroup');
	    	$this->assign("shopCatId2",I('shopCatId2'));
	    	$this->assign("shopCatId1",I('shopCatId1'));
	    	$this->assign("goodsName",I('goodsName'));
   		$this->assign('Page',$page);
   		$this->display('default/shops/group/list');
    }
    
    public function toEdit() {
    		$m = D('GoodsGroup');
    		$detail = $m->detail();
    		if(empty($detail)) {
    			$goodsId = (int)I('goodsId', 0);
    			if($goodsId == 0) {
    				$this->error('缺少必要的参数','/Home/GoodsGroup/lst',5);
    				return;
    			}
    			
    			$detail = array(
    				'groupStartTime'	 => date('Y-m-d'),
    				'groupEndTime'	 => date('Y-m-d', strtotime('+7 days')),
    				'goodsId'	=> $goodsId
    			);
    		}
    		$this->assign("umark",'queryGoodsGroup');
    		$this->assign('object',$detail);
    		$this->display('default/shops/group/edit');
    }
    
    public function edit() {
    		$groupGoodsId = I('groupGoodsId', 0);
    		$m = D('GoodsGroup');
    		$rd = null;
    		if($groupGoodsId == 0) {
    			$rd = $m->insert();
    		} else {
    			$rd = $m->update();
    		}
   		$this->ajaxReturn($rd, 'JSON');
    }
    
    public function remove() {
    		$m = D('GoodsGroup');
		$rd = $m->remove();
   		$this->ajaxReturn($rd, 'JSON');
    }
}