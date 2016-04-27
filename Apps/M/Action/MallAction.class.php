<?php
namespace M\Action;
/**
 * ============================================================================
 * 粗卡云:
  
 * 联系方式:
 * ============================================================================
 * 商城控制器
 */
use Think\Controller;
class MallAction extends BaseAction {
	public function index() {
		// 获取广告
		$addb = D('ads');
		$ads = $addb->queryByType(-4);
		$this->assign('ads', $ads);
		
		// 获取一元购顶端广告
		$ads = $addb->queryByType(-5);
		$this->assign('yygAds', $ads[0]);
		
		// 促销活动
		$madb = D('MallActivity');
		$activities = $madb->queryTop6();
		$this->assign('activites', $activities);
		
		// 热门市场
		$gcdb = D('GoodsCats');
		$markets = $gcdb->queryMallCats();
		$this->assign('markets', $markets);
		
		// 惠生活
		$mldb = D('MallLife');
		$life = $mldb->getTopOne();
		$this->assign('life', $life);
		
		$this->assign('title', '商城');
		$this->display();
	}
	
	// 获取所有商品
	public function ptg() {
		$db = D('Goods');
		$list = $db->pageTop();
		$this->ajaxReturn($list, 'JSON');
	}
	
	public function category() {
		$this->assign('title', '商品分类');
		$this->display();
	}
	
	public function cc() {
		$gcdb = D('GoodsCats');
		$cats = $gcdb->queryMallCats();
		$this->ajaxReturn($cats, 'JSON');
	}
	
	public function indexs() {
		// 获取广告
		$wxm= new WxUserInfo();
		$signPackage=$wxm->getSignPackage();
			 
		$this->assign('signPackage', $signPackage);
		
		$this->assign('title', '商城');
		$this->display();
	}
	public function activitydetail() {
		$madb = D('MallActivity');
		
		$object = $madb->getActivity();
		$this->assign('object', $object);
		
		$activityms = $madb->getActivityClass();
		$this->assign('activityms', $activities);
		echo dump($activities);
		
		$goods = $madb->getActivityGoods();
		$this->assign('goods', $goods);
		
		$this->assign('title', '商城-'.$object["mactname"]);
		$this->display();
	} 


	
	
	
}