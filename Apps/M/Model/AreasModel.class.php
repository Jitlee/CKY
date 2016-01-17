<?php
 namespace M\Model;
/**
 * ============================================================================
 * 粗卡云:
  
 * 联系方式:
 * ============================================================================
 * 行业服务类
 */
class AreasModel extends BaseModel {
	public function query() {
		$areaId = intval(I('areaId', 0));
		if($areaId > 0) {
			return $this->where('areaId='.$areaId)->order('areaSoft')->select();
		} else {
			$areaName = I('areaName', '深圳');
			$sql = 'select * from __PREFIX__areas a where exists(select 0 from __PREFIX__areas b where b.areaId = a.parentId and b.areaName like \''.$areaName.'%\') order by areaSort';
			return M()->query($sql);
		}
	}
	
	public function getCityCode() {
		$city = I('areaName', '深圳');
		$map = array('areaName'	=> array('LIKE', $city.'%'));
		$area = $this->where($map)->find();
		if($area) {
			return $area['areaId'];
		} else {
			return 0;
		}
	}
	
	
	
	public function GetProvince() 
	{
		$sql = 'select * from __PREFIX__areas  where   isShow=1 and areaType=0 order by areaSort';
		return M()->query($sql);
	}
	public function getCityByProvince($pid) 
	{
		$sql = 'select * from __PREFIX__areas  where   isShow=1 and areaType=1 and parentId='.$pid.' order by areaSort';		
		return M()->query($sql);
	}
	
	public function getCountyByCity($pid) 
	{
		$sql = 'select * from __PREFIX__areas  where   isShow=1 and parentId='.$pid.' order by areaSort';
		return M()->query($sql);
	}
	
};
?>