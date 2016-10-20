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
		$m = D('GoodsGroup');
		$list = $m->goods();
   		$this->assign('list',$list);
   		$this->display('default/group/goods');
	}
	
    public function lst(){
   		$m = D('GoodsGroup');
		$list = $m->lst();
   		$this->assign('list',$list);
   		$this->display('default/group/list');
    }
    
    public function insert() {
    		$m = D('GoodsGroup');
		$rd = $m->insert();
   		$this->ajaxReturn($rd, 'JSON');
    }
    
    public function update() {
    		$m = D('GoodsGroup');
		$rd = $m->update();
   		$this->ajaxReturn($rd, 'JSON');
    }
    
    public function remove() {
    		$m = D('GoodsGroup');
		$rd = $m->remove();
   		$this->ajaxReturn($rd, 'JSON');
    }
}