<?php
 namespace M\Model;
/**
 * ============================================================================
 * 粗卡云:
  
 * 联系方式:
 * ============================================================================
 * 广告服务类
 */
class OneCardModel extends BaseModel {
	 
	/*用户登录*/
	public function MemberLogin($cardId,$password)
	{
		$data = array("cardId"=>$cardId,"password"=>$password);
		$url='OpenApi/MemberLogin';		
		return $this->GetData($url, $data);
	}
	
	/*获取用户信息*/
	public function GetUserInfo($cardId)
	{
		//$data='{"cardId":"18620554231","password":"","isGetExtValue":"false"}';
		$data = array("cardId"=>$cardId,"password"=>"" ,"isGetExtValue"=>FALSE);
		$url='OpenApi/Get_MemberInfo';		
		return $this->GetData($url, $data);
	}
	
	public function GetUserInfoObj($cardId)
	{
		//用户信息
		$res=$this->GetUserInfo($cardId);
		$status= $res["status"];
		if($status == 0)
		{
			$data=$res["data"][0];
			return $data; 
		}
		return null;
	}
	
	/*获取用户积分列表*/
	public function GetScoreList($cardId,$pageIndex=1,$pageSize=10)
	{
		//https://openapi.1card1.cn/OpenApi/Get_PointNotePaged?openId
		//data={"userAccount":"10002","cardId":"cardIdTest","memberPassword":"000","where":"", "pageIndex":0,"pageSize":10,"orderBy":" OperateTime desc "} 
		$data = array(
			"userAccount"=>"10000"
			,"cardId"=>$cardId
			,"memberPassword"=>""
			,"where"=>""
			, "pageIndex"=>$pageIndex
			,"pageSize"=>$pageSize
			,"orderBy"=>" OperateTime desc "
		);
		$url='OpenApi/Get_PointNotePaged';		
		return $this->GetData($url, $data);
	}
	
	/*获取用户储值记录列表*/
	public function GetRechargeList($cardId,$pageIndex=1,$pageSize=10)
	{
		//https://openapi.1card1.cn/OpenApi/Get_PointNotePaged?openId
		//data={"userAccount":"10002","cardId":"cardIdTest","memberPassword":"000","where":"", "pageIndex":0,"pageSize":10,"orderBy":" OperateTime desc "} 
		$data = array(
			"userAccount"=>"10000"
			,"cardId"=>$cardId
			,"memberPassword"=>""
			,"where"=>""
			, "pageIndex"=>$pageIndex
			,"pageSize"=>$pageSize
			,"orderBy"=>" OperateTime desc "
		);
		$url='OpenApi/Get_ValueNotePaged';		
		return $this->GetData($url, $data);
	}
	
	
	/*获取用户消费记录列表*/
	public function GetConsumeList($cardId,$pageIndex=1,$pageSize=10)
	{
		//data={"userAccount":"10003","where":" 1=1 and cardId='13632651195' ", "pageIndex":0,"pageSize":10,"orderBy":" cardId desc"} 
		$data = array(
			"userAccount"=>"10000"
			,"where"=>" 1=1 and cardId='".$cardId."' "
			, "pageIndex"=>$pageIndex
			,"pageSize"=>$pageSize
			,"orderBy"=>" OperateTime desc "
		);
		$url='OpenApi/Get_ConsumeNotePaged';		
		return $this->GetData($url, $data);
	}
	/****我的充次*****/
	public function GetCountList($cardId,$pageIndex=1,$pageSize=10)
	{
		//data={"userAccount":"10003","where":" 1=1 and cardId='13632651195' ", "pageIndex":0,"pageSize":10,"orderBy":" cardId desc"} 
		$data = array(
			"userAccount"=>"10000"
			,"cardId"=>$cardId
			,"memberPassword"=>""
			,"where"=>" 1=1 and cardId='".$cardId."' "
			, "pageIndex"=>$pageIndex
			,"pageSize"=>$pageSize
			,"orderBy"=>" OperateTime desc "
		); 
		
		$url='OpenApi/Get_CountListPaged';		
		return $this->GetData($url, $data);
	}
	/*充值*/
	public function AddValue($cardId,$value)
	{
		$data = array(
			"cardId"=>$cardId
			,"value"=>$value
			//,"valuePlus"=>$value
			,"paidMoney"=>$value
			,"meno"=>"粗卡云管理平台充值"
			,"userAccount"=>"10000"
		);
		$url='OpenApi/Add_Value';
		return $this->GetData($url, $data);		
	}
	
	/*卡内余额支付*/
	public function PayValue($cardId,$value)
	{
		$data = array(
			"cardId"=>$cardId
			,"value"=>$value
			//,"valuePlus"=>$value
//			,"paidMoney"=>$value
			,"meno"=>"平台支付"
			,"userAccount"=>"10000"
		);
		//data={"cardId":"cardidTest","password":"123456","userAccount":"10000","value":999,"meno":"我是备注信息"} 
		$url='OpenApi/ValueConsume';
		return $this->GetData($url, $data);		
	}
	
	/********修改密码*******/
	public function UpdatePassword($cardId,$oldpwd,$newpwd)
	{
		$data = array(
			"cardId"=>$cardId
			,"password"=>$oldpwd
			,"newPassword"=>$newpwd
			,"userAccount"=>"10000"
		);
		$url='OpenApi/Update_MemberPassword';		
		return $this->GetData($url, $data);
	}
	
	/********用户注册*******/
	public function UserReg($data)
	{
		$url='OpenApi/Add_Member';		
		return $this->GetData($url, $data);
	}
	 
	/********获取会员级别列表*******/
	public function GetMemberGroup()
	{
		$data = array(
			"userAccount"=>"10000"
			,"where"=>" 1=1 "
			, "pageIndex"=>0
			,"pageSize"=>20
			,"orderBy"=>"  Guid desc "
		);
		$url='OpenApi/Get_MemberGroupPaged';		
		return $this->GetData($url, $data);
	}
	
	public function SendVerycode($mobile,$content)
	{
		$data = array(
			"userAccount"=>"10000"
			,"mobile"=>$mobile
			,"content"=>$content
		);
		
		$url='OpenApi/SendSms';		
		return $this->GetData($url, $data);
	}
	
	
	function GetData($url,$data)
	{
		$OpenId  ="5BC3C691C69C43D1BA1C6420C51F60C5";//32位OpenId，大写
        $Secret  ="7G0CL7";                          //6位Secret，大写	 
	    $TimeStamp=time();
		 		 
        $data = json_encode($data);
        $Signature = strtoupper(md5($OpenId.$Secret.$TimeStamp.$data)); //MD5加密后的字符串，大写
        $urlroot="https://openapi.1card1.cn/";
		//https://openapi.1card1.cn/OpenApi/Get_MemberInfo?openId=[OpenId]&signature=[Signature]&timestamp=[TimeStamp]
        $_url = $urlroot.$url."?openId=".$OpenId."&signature=".$Signature."&timestamp=".$TimeStamp;
		//echo $_url; 
        //postData：该参数post到指定Url，注意postData与data 的区别
        $postData = "data=".$data;
        $json_data =$this->postData($_url, $postData);
		$array = json_decode($json_data,true);
		
		return $array;
	}
	
	//lib/api.php 里面的方法是：
	function postData($url, $data){
	    $ch = curl_init();
	    $timeout = 300;
	    curl_setopt($ch, CURLOPT_URL, $url);
	    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
	    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
	    //下面这句是绕过SSL验证
	    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); 
	    $handles = curl_exec($ch);
	    curl_close($ch);
	    return $handles;
	}

// 	public function Get_CitiesByProvince(){
// 		$provinces=$_POST["provinces"];
//		$db = M('add');
//		$filter["addparent"] = $provinces;
//		$filter["addtype"] = 1;
//		$data= $db->where($filter)->select();		
//		$this->ajaxReturn($data,'JSON');
//  }
//	public function Get_CountyByCity(){
// 		$cityid=$_POST["city"];
//		$db = M('add');
//		$filter["addparent"] = $cityid;
//		$filter["addtype"] = 2;
//		$data= $db->where($filter)->select();		
//		//$this->ajaxReturn($data,'JSON');
//  }

}