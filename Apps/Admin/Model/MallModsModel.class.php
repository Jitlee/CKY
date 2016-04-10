<?php
 namespace Admin\Model;
/**
 * ============================================================================
 * 粗卡云:
  
 * 联系方式:
 * ============================================================================
 * 商城模块服务类
 */
class MallModsModel extends BaseModel {
	public function queryByType($mod) {
		return $this->where(array('mod2' => $mod))->order('modSort desc, createTime desc')->select();
	}
	
	public function insert() {
		$rst = array('status' => -1);
		$data["mod1"] = (int)$this->max('mod1') + 1;
		$data["mod2"] = (int)I("mod2");
		$data["modName"] = I("modName");
		$data["bigImage"] = I("bigImage");
		$data["smallImage"] = I("smallImage");
		$data["modSort"] = I("modSort");
		$data["modTheme"] = I("modTheme", 0);
	    if($this->add($data) !== FALSE){
			$rst['status']= 1;
		}
		return $rst;
	}
	
	public function edit() {
		$rst = array('status' => -1);
		$mod1 = (int)I("mod1");
		$data["modName"] = I("modName");
		$data["bigImage"] = I("bigImage");
		$data["smallImage"] = I("smallImage");
		$data["modTheme"] = I("modTheme", 0);
	    if($this->where(array('mod1'=>$mod1))->save($data) !== FALSE){
			$rst['status']= 1;
		}
		return $rst;
	}
};
?>