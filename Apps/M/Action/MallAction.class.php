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