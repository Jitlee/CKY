<?php
 namespace M\Model;
/**
 * ============================================================================
 * 粗卡云:
  
 * 联系方式:
 * ============================================================================
 * 广告服务类
 */
class MemberModel extends BaseModel {
	 
	public function Insert($dataInfo)
	{
		$rd = array('status'=>-1);
		
		if($this->checkEmpty($data,true)){
			$db = M('member');
			$rs = $db->add($dataInfo);
			if(false !== $rs){
				$rd['status']= 1;
			}
		}
		return $rd;
	}
	
	public function Get($key)
	{
		$db = M('member');
		$filter["CardId"]=$key;
		return $db->where($filter)->find();
	}
	 

}