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
class IndexAction extends Controller {
	 
		
	public function index() {
//		if ( strpos($_SERVER['HTTP_USER_AGENT'], 'MicroMessenger') == false) {  
////	        $openid="orSWIvz7Ot-dIpP1kMSs3tnftz6Q";
//			$openid="o4CBRwu4gN7w8JZsVCw6leu9g2-Y";
//			session('openid',$openid);
//	    }    
		  
		if($_SERVER['SERVER_NAME'] != 'localhost' && strpos($_SERVER['SERVER_NAME'], '192.168.') === false) {
			//try_login();
		}
		else
		{
			$openid="o4CBRwu4gN7w8JZsVCw6leu9g2-Y";
			session('openid',$openid);
			//try_login();			
		}
//		logger("dddddd");
		/****分享与定位***/
		$wxm= new WxUserInfo();
		$signPackage=$wxm->getSignPackage();			 
		$this->assign('signPackage', $signPackage);
		/****地图apk*******/
		$m = D('Home/System');
		$GLOBALS['CONFIG'] = $m->loadConfigs();
		$this->assign('CONF',$GLOBALS['CONFIG']);	
		
		
		$this->assign('title', "粗卡");
		$this->assign('tabid', 'home');
		
		$addb = D('ads');
		$ads = $addb->queryByType(-1);
		$this->assign('ads', $ads); 
		 
		$this->display();
	}
	public function indext() {
//		if ( strpos($_SERVER['HTTP_USER_AGENT'], 'MicroMessenger') == false) {  
//	        $openid="o4CBRwu4gN7w8JZsVCw6leu9g2-Y";
//			session('openid',$openid);
//	    }    
		  
		if($_SERVER['SERVER_NAME'] != 'localhost' && strpos($_SERVER['SERVER_NAME'], '192.168.') === false) {
			//try_login();
		}
		else
		{
//			$openid="o4CBRwu4gN7w8JZsVCw6leu9g2-Y";
//			session('openid',$openid);
			//try_login();			
		}
//		logger("dddddd");
		/****分享与定位***/
		$wxm= new WxUserInfo();
		$signPackage=$wxm->getSignPackage();			 
		$this->assign('signPackage', $signPackage);
		/****地图apk*******/
		$m = D('Home/System');
		$GLOBALS['CONFIG'] = $m->loadConfigs();
		$this->assign('CONF',$GLOBALS['CONFIG']);	
		
		
		$this->assign('title', "粗卡");
		$this->assign('tabid', 'home');
		
		$addb = D('ads');
		$ads = $addb->queryByType(-1);
		$this->assign('ads', $ads); 
		 
		$this->display();
	}
	public function guess() {
		$m = D('M/Goods');
		$list = $m->guess();
		$this->ajaxReturn($list, 'JSON');
	}
}