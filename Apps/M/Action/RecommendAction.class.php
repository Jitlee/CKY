<?php
namespace M\Action;
/**
 * ============================================================================
 * 粗卡云:
  
 * 联系方式:
 * ============================================================================
 * 推荐餐厅（默认）控制器
 */
use Think\Controller;
class RecommendAction extends BaseUserAction {
	
	 
	public function index() {
		$this->assign('title', "推荐餐厅");
		//$this->assign('data', $result);
		$addb = D('ads');
		$ads = $addb->queryByType(-2);
		$this->assign('ads', $ads); 
		
		$this->display();
	}
	
	
	public function page() {
		$m = D('M/Recommend');
		$list = $m->RecommendList();
		//echo dump($list);
		foreach ($list as $key =>$v){
			$v["goodsitems"]=$this->GetGoods($v["shopId"]);
			if($newitem)
			{
				array_push($newitem,$v);
			}
			else
			{
				$newitem[]=$v;
			}
		}
		$this->ajaxReturn($newitem, 'JSON');
	}

	function GetGoods($shopid=0)
	{
		$m = D('M/Recommend');
    	$goods=$m->getGoodsByShopid($shopid);
    	$html="<ul class='cky-table-view'>";
    	foreach ($goods as $v) {
 			$html=$html."<li class='cky-table-view-cell cky-fast-goods-cell'>";
			$html=$html."<a>";
			$html=$html."	<div class='cky-table-cell-thumb cky-table-cell-thumb60' style='{ backgroundImage:/" . $v["goodsThums"] . "'></div>";
			$html=$html."	<div class='cky-media'>";
			$html=$html."		<span class='cky-media-title font17'>".$v["goodsName"]."</span>";
//			$html=$html."		<div class='cky-media-sub font13 font-gray2'>";
//			$html=$html."			<span>已售" . $v["saleCount"] . "单</span>";
//			$html=$html."		</div>";
			$html=$html."		<div class='cky-relative'>";
			$html=$html."			<span class='font-red font17'>¥".$v["shopPrice"] . "</span>";
			$html=$html."			<span class='font13 font-gray'>".$v["goodsUnit"] . "</span>";
			$html=$html."			<span>已售" . $v["saleCount"] . "</span>";
			$html=$html."		</div>";
			$html=$html."   </div>";
			$html=$html."</a>";			
			$html=$html."</li>";
		}
		$html=$html."</ul>";
		
		return $html;
	}
	
}