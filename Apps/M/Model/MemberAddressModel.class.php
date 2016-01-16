<?php
 namespace M\Model;
/**
 * ============================================================================
 * 粗卡云:
  
 * 联系方式:
 * ============================================================================
 * 广告服务类
 */
class MemberAddressModel extends BaseModel {
		
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
	
	public function GetByID($key)
	{
		$db = M('member');
		$filter["CardId"]=$key;
		return $db->where($filter)->find();
	}
	
	public function GetList($uid)
	{ 
		$db = M('user_address');
		$filter["userId"]=$uid;
		return $db->order('createTime desc')->where($filter)->select();
	}
	
	
}