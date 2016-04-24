<?php
namespace M\Action;
/**
 * ============================================================================
 * 粗卡云:
  
 * 联系方式:
 * ============================================================================
 * 惠生活控制器
 */
class MalllifeAction extends BaseAction{
	  
	/*********惠生活*********/ 
	/**
	 * 分页查询
	 */
	public function lifeIndex(){	
		$this->assign('title', '惠生活');
		 
        $this->display("/Mall/lifeindex");
	}
	
	/**
	 * 分页-查询
	 */
    public function lifePage(){
		$m = D('M/Malllife');
		$list = $m->lifePage(); 
		$this->ajaxReturn($list, 'JSON');
	}
	
	public function lifeDetail(){
		$m = D('M/Malllife');
		$data = $m->get();
		$goods=$m->getgoods();
		$data['content'] = htmlspecialchars_decode(html_entity_decode($data['content']));
		$this->assign('data', $data);
		$this->assign('childitems', $goods);
		//echo dump($goods);
		$this->assign('title', '商城-惠生活');
		$this->display("/Mall/lifedetail");
	}
	
	
	
};
?>