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
		
		$this->assign('title', '商城');
		$this->display();
	}
	
	public function indexs() {
		// 获取广告
		$wxm= new WxUserInfo();
		$signPackage=$wxm->getSignPackage();
			 
		$this->assign('signPackage', $signPackage);
		
		$this->assign('title', '商城');
		$this->display();
	}
	
}