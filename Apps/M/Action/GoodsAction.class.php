<?php
namespace M\Action;
/**
 * ============================================================================
 * 粗卡云:
  
 * 联系方式:
 * ============================================================================
 * 商家 控制器
 */
use Think\Controller;
class GoodsAction extends BaseAction {
	public function page() {
		$m = D('M/Goods');
		$list = $m->goods();
		$this->ajaxReturn($list, 'JSON');
	}
	
	// 商品详情
	public function detail() {
		$m = D('M/Goods');
		$data = $m->detail();
		$data['goodsDesc'] = htmlspecialchars_decode(html_entity_decode($data['goodsDesc']));
		$this->assign('data', $data);
		$this->assign('title', $data['goodsName']);
		$this->display();
	}
	
	public function gallerys() {
		$m = D('M/Goods');
		$list = $m->gallerys();
		$this->ajaxReturn($list, 'JSON');
	}
}