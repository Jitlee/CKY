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
		$cityid=$_POST["areaId1"];
		$m = D('M/areas');
		$area = $m->getCityByProvince($cityid); 
		$this->ajaxReturn($area,'JSON');
	}
	
	public function getcounty() {
		$cityid=$_POST["areaId2"];
		$m = D('M/areas');
		$area = $m->getCountyByCity($cityid); 
		$this->ajaxReturn($area,'JSON');
		
//		$cityid=$_POST["areaId2"];
//		$m = D('M/areas');
//		$area = $m->getCountyByCity($cityid);
//		$this->ajaxReturn($area, "JSON");
	}
}