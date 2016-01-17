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
			$db = M('user_address');
			$rs = $db->add($dataInfo);
			if(false !== $rs){
				$rd['status']= 1;
			}
		}
		return $rd;
	}
	public function Edit($dataInfo)
	{ 
		$rd = array('status'=>-1);		
		
		$db = M('user_address');
		$rs = $db->save($dataInfo);
		if(false !== $rs){
			$rd['status']= 1;
		}
		
		return $rd;
	}
	public function GetByID($areaId)
	{
		$db = M('user_address');
		$filter["addressId"]=$areaId;
		return $db->where($filter)->find();
	}
	public function delete($addressId) 
	{
		$sql = 'delete from __PREFIX__user_address  where   addressId='.$addressId;
		return M()->query($sql);
	}
	public function setdefalut($addressId,$uid) 
	{
		$sql = 'update __PREFIX__user_address set  isDefault=0  where  userId='.$uid;
		M()->query($sql);
		
		$sql = 'update  __PREFIX__user_address set   isDefault=1 where  addressId='.$addressId;
		M()->query($sql);
	}
	
	
	public function GetList($uid)
	{ 
		$db = M('user_address');
		$filter["userId"]=$uid;
		return $db->order('createTime desc')->where($filter)->select();
	}
	
	
}