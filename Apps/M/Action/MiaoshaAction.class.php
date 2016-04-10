<?php
namespace M\Action;
/**
 * ============================================================================
 * 粗卡云:
  
 * 联系方式:
 * ============================================================================
 * 首页（默认）控制器
 */
use Think\Controller;

class MiaoshaAction extends BaseAction {

	public function index($category = 0, $categoryName = '商品分类') {
		$this->assign('title', '热门秒杀');
		$this->assign('pid', 'jiexiao');
		
		$this->assign('category', $category);
		$this->assign('categoryName', $categoryName);
		
		$this->assign('tabId', 0);
		
		$this->display();
	}
	
	public function pageAll($pageSize, $pageNum) {
		// 分页
		$db = M('miaosha');
		$filter['jishijiexiao'] = 0;
		$filter['type'] = 1;
		
		$cid = intval(I('get.cid'));
		if($cid > 0) {
			$filter['cid'] = $cid;
		}
		
		$type = I("get.type");
		$order = null;
		switch($type) {
			case 2:
				$order = "time desc";
				break;
			case 3:
				$order = "shengyurenshu desc";
				break;
			case 4: // 总需人数
				$order = "zongrenshu desc";
				break;
			default: // 人气
				$order = "canyurenshu desc";
				break;
		}
		
		$list = $db->where($filter)->order($order)->page($pageNum, $pageSize)->field('gid,title,thumb,money,danjia,xiangou,status, qishu, canyurenshu, zongrenshu,type')->select();
		$this->ajaxReturn($list, "JSON");
	}
	
	
}