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
		$user_agent = $_SERVER['HTTP_USER_AGENT'];
		if (strpos($user_agent, 'MicroMessenger') === true) {
			try_login();
		}
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
		$mldb = D('M/Malllife');
		$lifes = $mldb->getTop();
		$this->assign('lifes', $lifes);
		 
		/****分享与定位***/
		$wxm= new WxUserInfo();
		$signPackage=$wxm->getSignPackage();			 
		$this->assign('signPackage', $signPackage);
		
		$this->assign('title', '商城');
		$this->display();
	}
	
	// 商品类目
	public function category() {
		$this->assign('title', '商品分类');
		$this->display();
	}
	
	// 类目分页
	public function cc() {
		$gcdb = D('GoodsCats');
		$cats = $gcdb->queryMallCats();
		$this->ajaxReturn($cats, 'JSON');
	}
	
	// 商品类目明细
	public function cd() {
		$bdb = D('Brands');
		$brands = $bdb->queryByCatId3();
		$topBrands = array_slice($brands, 0, 8);
		$this->assign('brands', $brands);
		$this->assign('topBrands', $topBrands);
		$this->assign('title', '类目明细');
		$this->display("Mall/category_detail");
	}
	
	// 热门市场
	public function market() {
		$db = D('Goods');
		$list = $db->pageTop(1, 5, 0);
		$this->assign('title', '商品分类');
		$this->display();
	}
	
	public function indexs() {
		// 获取广告
		$wxm= new WxUserInfo();
		$signPackage=$wxm->getSignPackage();			 
		$this->assign('signPackage', $signPackage);
			 		
		$this->assign('title', '商城-微信API测试');
		$this->display();
	 
	}
	
	public function activitydetail() {
		$madb = D('MallActivity');
		
		$object = $madb->getActivity();
		$this->assign('object', $object);
		
		$activityms = $madb->getActivityClass();
		$this->assign('activityms', $activityms);
		//echo dump($activityms);
		
		$goods = $this->activityGoodsPageLoad();
		$this->assign('goods', $goods);
		//echo dump($goods);
		$this->assign('title', '商城-'.$object["mactname"]);
		$this->display();
	} 
	
	public function activitylist(){
		$this->assign('title', '促销活动');
		$this->display();
	}
	
	public function activityPage(){
		$m = D('M/MallActivity');
		$list = $m->activityPage(); 
		$this->ajaxReturn($list, 'JSON');
	}
	
	public function activityGoodsPage()
	{		 
		$pageNum =  I("pageNo");
		$result=$this->activityGoodsPageLoad($pageNum);
		$this->ajaxReturn($result, "JSON");
	}
	public function activityGoodsPageLoad($pageNum = 1)
	{
		$pageSize = 10;
		$s=($pageNum-1)*$pageSize;
		$e=($pageNum)*$pageSize;
		$mMember = D('MallActivity');
		$result=$mMember->getActivityGoods($s,$e);
		return $result;
	}
	public function lifeindex() {
		$this->assign('title', '惠生活');	
		$this->display();
	}
}