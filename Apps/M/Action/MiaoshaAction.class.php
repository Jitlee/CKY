<?php
namespace M\Action;
/**
 * ============================================================================
 * 粗卡云:
  
 * 联系方式:
 * ============================================================================
 * 秒杀控制器
 */
use Think\Controller;

class MiaoshaAction extends BaseAction {

	public function index() {
		$this->assign('title', '热门秒杀');
		
		$this->assign('tabId', 0);
		
		$this->display();
	}
	
	public function jiexiao() {
		$this->assign('title', '即将揭晓');
		
		$this->assign('category', $category);
		$this->assign('categoryName', $categoryName);
		
		$this->assign('tabId', 1);
		
		$this->display();
	}
	
	// 查询热门秒杀和即将揭晓
	public function ms() {
		$db = D('M/Miaosha');
		$list = $db->queryMiaosha();
//		echo $db->getLastSql();
		$this->ajaxReturn($list, 'JSON');
	}
	
	public function history() {
		$this->assign('title', '最新揭晓');
		
		$this->assign('tabId', 2);
		
		$this->display();
	}
	
	// 查询历史
	public function hs() {
		$db = D('M/Miaosha');
		$list = $db->queryHistory();
//		echo $db->getLastSql();
		$this->ajaxReturn($list, 'JSON');
	}
	
	public function view() {
		$this->assign('title', '商品详情');
		$this->display();
	}
	
	public function m() {
		$db = D('M/Miaosha');
		$data = $db->get();
		
		if((int)$data['miaoshaStatus'] < 2) {
			$db = D('M/GoodsGallery');
			$galleries = $db->query();
			if(empty($galleries)) {
				$galleries[] = array('goodsImg'	=> $data['goodsImg']);
			}
			$data['galleries'] = $galleries;
		}
		$this->ajaxReturn($data, 'JSON');
	}
}