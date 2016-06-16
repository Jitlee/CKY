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
	
	// 根据UID获取用户的头像，名称
	public function findByUid($uid = 0) {
		return $this->field("uid, replace(concat('/', ImagePath), '/http://', 'http://') userImg, INSERT(trueName,ROUND(CHAR_LENGTH(trueName) / 2),ROUND(CHAR_LENGTH(trueName) / 4),'****') userName")->find($uid);
	} 
	 
	public function Insert($dataInfo)
	{ 
		$rd = array('status'=>-1);
		$user = array();
		
        $user["OpenID"]=  $dataInfo["OpenID"];
        $user["CardId"]=  $dataInfo["CardId"];
        $user["Sex"]=  $dataInfo["Sex"];   // 是 性别 
        $user["TrueName"]=  $dataInfo["TrueName"];   // 是 姓名 
        $user["Mobile"]=  $dataInfo["Mobile"];   // 是 手机号码 
        $user["Tel"]=  $dataInfo["Tel"];   // 是 电话号码 
        $user["Birthday"]=  $dataInfo["Birthday"];   // 是 生日 
        $user["IsLunar"]=  $dataInfo["IsLunar"];   // 是 生日是是是农历 
        $user["UserAccount"]=  $dataInfo["UserAccount"];   // 是 登记操作员 
        $user["StoreName"]=  $dataInfo["StoreName"];   // 是 登记店面 
        $user["ImagePath"]=  $dataInfo["ImagePath"];   // 是 会员头像图片路径 
        $user["MemberGroupName"]=  $dataInfo["MemberGroupName"];   // 是 会员级别名称 
        $user["RecommendMemberCardId"]=  $dataInfo["RecommendMemberCardId"];   // 是 推荐人卡号 
        $user["RecommendMemberName"]=  $dataInfo["RecommendMemberName"];   // 是 推荐人姓名 
        $user["RegisterTime"]=  $dataInfo["RegisterTime"];   // 是 注册时间 
        $user["DurationTime"]=  $dataInfo["DurationTime"];   // 是 到期时间 
        $user["Meno"]=  $dataInfo["Meno"];   				// 是 备注 
        $user["ProvinceId"]=  $dataInfo["ProvinceId"];   // 是 省Id 
        $user["ProvinceName"]=  $dataInfo["ProvinceName"];   // 是 省 
        $user["CityId"]=  $dataInfo["CityId"];   			// 是 市Id 
        $user["CityName"]=  $dataInfo["CityName"];   		// 是 市 
        $user["CountyId"]=  $dataInfo["CountyId"];   		// 是 区Id 
        $user["CountyName"]=  $dataInfo["CountyName"];  	 // 是 区 
        $user["Address"]=  $dataInfo["Address"];   			// 是 详细地址 
        $user["TotalPoint"]=  $dataInfo["TotalPoint"];   // 是 历史总积分 
        $user["TotalValue"]=  $dataInfo["TotalValue"];   // 是 历史总储值 
        $user["TotalMoney"]=  $dataInfo["TotalMoney"];   // 是 历史总金额（包括该会员以各种方式付的总金额） 
        $user["EnablePoint"]=  $dataInfo["EnablePoint"];   // 是 可用积分 
        $user["EnableValue"]=  $dataInfo["EnableValue"];   // 是 可用储值 
        //$user["FreezedValue"]=  $dataInfo["FreezedValue"];   // 是 冻结储值
		
//		if($this->checkEmpty($dataInfo,true)){
			$db = M('member');
			$rs = $db->add($user);
			if(false !== $rs){
				$rd['status']= 1;
			}
//		}
		return $rd;
	}

	public function getScore($uid) {
		$data = $this->field("EnablePoint")->find($uid);
		return (int)$data["EnablePoint"];
	}
	
	public function GetByCardID($key)
	{
		$db = M('member');
		$filter["CardId"]=$key;
		return $db->where($filter)->find();
	}
	
	public function GetByMobile($key)
	{
		$db = M('member');
		$filter["Mobile"]=$key;
		return $db->where($filter)->find();
	}
	
	public function GetByOpenid($openid)
	{
		$db = M('member');
		$filter["OpenID"]=$openid;
		return $db->where($filter)->find();
	}
	public function ChangeImgPath($openid,$ImagePath)
	{
		$db = M('member');
		$filter["OpenID"]=$openid;
		$MemberItem=  $db->where($filter)->find();
		$MemberItem["ChangeTime"]=date('y-m-d-h-i-s');
		$MemberItem["ImagePath"]=$ImagePath;
		$db->save($MemberItem);
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
		$db = M('member_recharge');
		$filter["uid"]=$uid;
		return $db->order('OperateTime desc')->where($filter)->page($pageNum, $pageSize)->select();
	}
	/******消费记录****/
	public function GetConsumeList($uid,$pageSize = 10, $pageNum = 1)
	{ 
		$db = M('member_consume');
		$filter["uid"]=$uid;
		return $db->order('OperateTime desc')->where($filter)->page($pageNum, $pageSize)->select();
	}
}