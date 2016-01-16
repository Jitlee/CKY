<?php
namespace M\Action;
/**
 * ============================================================================
 * 粗卡云:
  
 * 联系方式:
 * ============================================================================
 * 商家 控制器
 */
use Think\Controller;
class AreasAction extends BaseAction {
	public function query() {
		$m = D('M/Areas');
		$list = $m->query();
		$this->ajaxReturn($list, 'JSON');
	}
	
	public function getCityCode() {
		$m = D('M/areas');
		$areaId = $m->getCityCode();
		$this->ajaxReturn($areaId, "JSON");
	}
	
	public function getcitys() {
		$cityid=$_POST["provinces"];
		$m = D('M/areas');
		$area = $m->getCityByProvince($cityid);
		
		
		$content="-----------------出错啦-----------------";
		$content=$content.',$cityid='.count($area);
		logger($content);
		$data="ffffffffff";
		$this->ajaxReturn($data,'JSON');
		 
		//$this->ajaxReturn($area, "JSON");
	}
	
	public function getcounty() {
		$cityid=$_POST["city"];
		$m = D('M/areas');
		$area = $m->getCountyByCity($cityid);
		$this->ajaxReturn($area, "JSON");
	}
}