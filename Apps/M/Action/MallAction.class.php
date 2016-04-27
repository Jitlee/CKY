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
	
	public function activityGoodsPage()
	{
		$pageSize = I("pageSize");
		$pageNum =  I("pageNum");
//		$content="$pageSize=".$pageSize;
//		$content=$content.',$pageNum='.$pageNum.',$type='.$type;
//		logger($content);
		
		$result=$this->activityGoodsPageLoad($pageSize,$pageNum);
		$this->ajaxReturn($result, "JSON");
	}
	public function activityGoodsPageLoad($pageSize = 10, $pageNum = 1)
	{
			$s=($pageNum-1)*$pageSize;
			$e=($pageNum)*$pageSize;
		$mMember = D('MallActivity');
		$result=$mMember->getActivityGoods($s,$e);
		return $result;
	}
	
	
	
}