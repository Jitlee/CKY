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
	
	/***积分记录***/
	public function GetScoreList($uid,$pageSize = 10, $pageNum = 1)
	{ 
		$db = M('member_score');
		$filter["uid"]=$uid;
		return $db->order('OperateTime desc')->where($filter)->page($pageNum, $pageSize)->select();
	}
	/******储值记录****/
	public function GetRechargeList($uid,$pageSize = 10, $pageNum = 1)
	{ 
		$db = M('member_score');
		$filter["uid"]=$uid;
		return $db->order('OperateTime desc')->where($filter)->page($pageNum, $pageSize)->select();
	}

}