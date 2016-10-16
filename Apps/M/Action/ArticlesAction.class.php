<?php
namespace M\Action;
/**
 * ============================================================================
 * 粗卡云:  
 * 联系方式:
 * ============================================================================
 * 资讯 控制器
 */
use Think\Controller;
class ArticlesAction extends BaseAction {
	public function page() {
		
		 
		$this->ajaxReturn($list, 'JSON');
	}
	
	public function index() {
		
		$adarti = D('Articles');
		/****分享与定位***/
		$wxm= new WxUserInfo();
		$signPackage=$wxm->getSignPackage();			 
		$this->assign('signPackage', $signPackage);
		
		//广告相关处理
		$addb = D('ads');
		$ads = $addb->queryArticlesad();
		$ads0 = Array();
		$ads1 = Array();
		$ads2 = Array();
		$ads3 = Array();
		$ads4 = Array();
		$ads5 = Array();
		foreach($ads as $ad){
			$adPositionId=(int)$ad['adPositionId'];
			if($adPositionId == -60) Array_push($ads0,$ad);
			else if($adPositionId == -61) Array_push($ads1,$ad);
			else if($adPositionId == -62) Array_push($ads2,$ad);
			else if($adPositionId == -63) Array_push($ads3,$ad);
			else if($adPositionId == -64) Array_push($ads4,$ad);
			else if($adPositionId == -65) Array_push($ads5,$ad);
			
		}
		

		$this->assign('ads0', $ads);
		//echo dump($ads0);
		$this->assign('ads1', $ads0);
		$this->assign('ads2', $ads2);
		$this->assign('ads3', $ads3);		
		$this->assign('ads4', $ads4);
		$this->assign('ads5', $ads5);
			
		//查询新闻-推荐
		$artirecommend = $adarti->queryRecommend();
		$this->assign('artirecommend', $artirecommend);
		
		//查询分类
		$cates = $adarti->queryCats();
		$this->assign('cates', $cates);
		
		$this->assign('title', "资讯");
		$this->display();
	}
	
	 public function detail() {	 	
	 	$adarti = D('Articles');
	 	$data=$adarti->detail();
	 	$data['articleContent'] = htmlspecialchars_decode(html_entity_decode($data['articleContent']));
	 	$this->assign('data', $data);
	 	
	 	$this->assign('title', "资讯");
		$this->display();
	 }
}