<?php
namespace M\Action;
use Think\Controller;
use P\Model;

class HomeAction extends BaseAction {
		
	public function selectreg() {
		$openid=$this->GetOpenid();
		if(empty($openid))			 
		{
			echo dump($userlogin);
			$this->display("getwxerror");
			exit;
		}
		$this->assign('openid', $openid); 
		$this->display();
	}
	/*错误提示*/
	public function getwxerror() {
		$userlogin=session('userloginobj');
		$userlogin2=session('userloginobj2');
		echo dump($userlogin);
		
		echo	session("access_token");
		echo dump($userlogin2);
		
		$this->assign('data', $userlogin); 
		$this->display();
	}
	/*申请新卡车*/
	public function reg() {
		if(IS_POST) {
			$result["status"]=0;
			$result["msg"]="注册成功。";			
			$openid=$this->GetOpenid();
			
			$userlogin=session('userloginobj');
			$openid=$userlogin["openid"];	
			if(empty($openid))
			{
				$result["msg"]="获取参数失败。".$openid;	
				$this->ajaxReturn($result, "JSON");
				exit;
			}
					  
			$password= $_POST['password'];
			$Mobile = $_POST['mobile'];
						
			$mMember = D('M/Member');
			$user=$mMember->GetByMobile($Mobile);	//根据手机查询	
			$userOpenkey=$mMember->GetByOpenid($openid);	//根据手机查询
			
			if($userOpenkey && $userOpenkey["OpenID"]==$openid) {
				$result["status"]=1000;
				$result["msg"]='您已是会员不能重复注册。';
			}
			else if($user && $user["Mobile"]==$Mobile) 
			{
				$result["msg"]="手机号：".$Mobile." 已经绑定。";
			}
			else
			{
				$data = array(
					"cardId"	=>$Mobile
					,"password"	=>$password 
					,"mobile" 	=>$_POST['mobile']
					,"memberGroupName"=>"默认级别"//"416f147d-0a89-e511-ab53-001018640e2a"	//默认级别
					,"trueName"	=>$_POST['trueName']
					,"sex"		=>$_POST['sex']
					,"userAccount"=>"10000"
				);
				
				$mOnecard = D('M/OneCard');
				$res=$mOnecard->UserReg($data);//可能有多条。
				$status= $res["status"];
				if($status == 0)//查询结果并写入到数据库
				{
					 //查询
					$res=$mOnecard->GetUserInfo($Mobile);					
					$status= $res["status"];
					if($status == 0)
					{
						$data=$res["data"][0];
						session("cardid",$data["CardId"]);
						$mMember = D('M/Member');
						$data["OpenID"]=$openid;
						$result=$mMember->Insert($data);
						if($result["status"]==-1)
						{
							$result["msg"]=  "绑定失败-add database。";
						}
						else
						{
							$result["status"]=1;
						}
					}
					else
					{
						$result["msg"]= $res["message"]."code:003";						
					}
				}
				else
				{
					$result["msg"]= $res["message"].$Mobile;
				}
			}
			$this->ajaxReturn($result, "JSON");
		}
		else
		{
			$this->assign('title', '会员注册');
			$this->display();
		}
		
	}
	/*关联根据手机号码*/
	public function conncardmobile() {
		if(IS_POST) {
			$result["status"]=0;
			$result["msg"]="绑定成功。";			
			
			$openid=$this->GetOpenid();
			if(empty($openid))
			{
				$result["msg"]="获取参数失败。";	
				$this->ajaxReturn($result, "JSON");
				exit;
			}				  
			//$verycode= $_POST['verycode'];
			$Mobile = $_POST['mobile'];
						
			$mMember = D('M/Member');
			$user=$mMember->GetByMobile($Mobile);	//根据手机查询	
			$userOpenkey=$mMember->GetByOpenid($openid);	//根据手机查询
			
			if($userOpenkey && $userOpenkey["OpenID"]==$openid) {
				$result["status"]=1000;
				$result["msg"]='您已是会员不需要重复绑定。';
			}
			else if($user && $user["Mobile"]==$Mobile) 
			{
				$result["msg"]="手机号：".$Mobile." 已经绑定。";
			}
			else
			{
				$mOnecard = D('M/OneCard');
				$res=$mOnecard->GetUserInfo($Mobile);//查询					
				$status= $res["status"];
				if($status == 0)
				{
					$data=$res["data"][0];
					session("cardid",$data["CardId"]);
					$mMember = D('M/Member');
					$data["OpenID"]=$openid;
					$result=$mMember->Insert($data);
					if($result["status"]==-1)
					{
						$result["msg"]=  "绑定失败-add database。";
					}
					else
					{
						//同步数据
						$mMember = D('M/MemberOneCardSync');
						$result=$mMember->DataSync($data["CardId"]);
						$result["status"]=1;
					}
				}
				else
				{
					$result["msg"]= $res["message"]."code:003";						
				}
			}
			$this->ajaxReturn($result, "JSON");
		}
		else
		{
			$this->assign('title', '绑定会员卡');
			$this->display();
		}
	}
	/*关联会员卡根据卡号，密码*/
	public function conncardbycardid() {
		if(IS_POST) {
			$result["status"]=0;
			$result["msg"]="注册成功。";			
			$openid=$this->GetOpenid();
			if(empty($openid))
			{
				$result["msg"]="获取参数失败。";	
				$this->ajaxReturn($result, "JSON");
				exit;
			}
			
			$password= $_POST['password'];
			$CardId = $_POST['cardid'];
						
			$mMember = D('M/Member');
			$user=$mMember->GetByCardID($CardId);	//根据手机查询	
			$userOpenkey=$mMember->GetByOpenid($openid);	//根据手机查询
			
			if($userOpenkey && $userOpenkey["OpenID"]==$openid) {
				$result["status"]=1000;
				$result["msg"]='您已绑定不需要重复。';
			}
			else if($user && $user["CardId"]==$CardId) 
			{
				$result["msg"]="会员卡：".$CardId." 已经绑定。";
			}
			else
			{
				$mOnecard = D('M/OneCard');
				$res=$mOnecard->MemberLogin($CardId,$password);
				$status= $res["status"];
				if($status == 0)//查询结果并写入到数据库
				{
					$res=$mOnecard->GetUserInfo($CardId);					
					$status= $res["status"];
					if($status == 0)
					{
						$data=$res["data"][0];
						session("cardid",$data["CardId"]);
						$mMember = D('M/Member');
						$data["OpenID"]=$openid;
						$result=$mMember->Insert($data);
						if($result["status"]==-1)
						{
							$result["msg"]="绑定失败-add database。";
						}
						else
						{
							$mMember = D('M/MemberOneCardSync');
							$result=$mMember->DataSync($data["CardId"]);
							$result["status"]=1;
						}
					}
					else
					{
						$result["msg"]= $res["message"]." E:003";						
					}
				}
				else
				{
					$result["msg"]= $res["message"].$Mobile;
				}
			}
			$this->ajaxReturn($result, "JSON");
		}
		else
		{
			$openid=$this->GetOpenid();
			
			$this->assign('openid', $openid); 
			$this->assign('title', '绑定会员卡');
			$this->display();
		}
	}
	
	
	public function ftest()
	{
//		$m = D('M/OneCard');
//		$cardid="18620554231";
//		$res=$m->GetMemberGroup();//可能有多条。	
		//echo dump($res);
		
//		$CardId="18620554231";//$this->GetCardId();
//		$mMember = D('M/MemberOneCardSync');
//		$result=$mMember->DataSync($CardId);
//		echo dump($result);

		//	$code ="18620554231";// $_GET['code'];
			
		$gg='<xml><appid><![CDATA[wx06dcafb051f5e21f]]></appid>
<attach><![CDATA[cky16011602074896785]]></attach>
<bank_type><![CDATA[CMB_CREDIT]]></bank_type>
<cash_fee><![CDATA[1]]></cash_fee>
<fee_type><![CDATA[CNY]]></fee_type>
<is_subscribe><![CDATA[Y]]></is_subscribe>
<mch_id><![CDATA[1292995201]]></mch_id>
<nonce_str><![CDATA[01n3ona0apsyujdec9w93j9a59ekeoe0]]></nonce_str>
<openid><![CDATA[o4CBRwu4gN7w8JZsVCw6leu9g2-Y]]></openid>
<out_trade_no><![CDATA[129299520120160116141125]]></out_trade_no>
<result_code><![CDATA[SUCCESS]]></result_code>
<return_code><![CDATA[SUCCESS]]></return_code>
<sign><![CDATA[47C94D5AEFBB6167AA5F0788126DE885]]></sign>
<time_end><![CDATA[20160116141133]]></time_end>
<total_fee>1</total_fee>
<trade_type><![CDATA[JSAPI]]></trade_type>
<transaction_id><![CDATA[1008850043201601162729660088]]></transaction_id>
</xml>';
			$xmlObj=simplexml_load_string($gg); //解析回调数据
		
		
		
        $appid=$xmlObj->appid;//微信appid
        $mch_id=$xmlObj->mch_id;  //商户号
        $nonce_str=$xmlObj->nonce_str;//随机字符串
        $sign=$xmlObj->sign;//签名
        $result_code=$xmlObj->result_code;//业务结果
        $openid=$xmlObj->openid;//用户标识
        $is_subscribe=$xmlObj->is_subscribe;//是否关注公众帐号
        $trace_type=$xmlObj->trade_type;//交易类型，JSAPI,NATIVE,APP
        $bank_type=$xmlObj->bank_type;//付款银行，银行类型采用字符串类型的银行标识。
        $total_fee=$xmlObj->total_fee;//订单总金额，单位为分
        $fee_type=$xmlObj->fee_type;//货币类型，符合ISO4217的标准三位字母代码，默认为人民币：CNY。
        $transaction_id=$xmlObj->transaction_id;//微信支付订单号
        $out_trade_no=$xmlObj->out_trade_no;//商户订单号
        $attach=$xmlObj->attach;//商家数据包，原样返回
        $time_end=$xmlObj->time_end;//支付完成时间
        $cash_fee=$xmlObj->cash_fee;
        $return_code=$xmlObj->return_code;
		
		
		$attach=$attach.'';
		$result_code=$result_code.'';
		//echo $attach;
		$mMPay = D('M/MemberPay');
		$dataInfo=$mMPay->GetByPayNo($attach);
		
		if($dataInfo && $dataInfo["PayType"]=="recharge" && $dataInfo["Status"]==0 && $result_code=='SUCCESS')	
		{
			 $dataInfo["ChangeTime"]=date('y-m-d-h-i-s');
			 $dataInfo["result_code"]=$result_code.'';
			 $dataInfo["fee_type"]=$fee_type.'';
			 $dataInfo["transaction_id"]=$transaction_id.'';
			 $dataInfo["cash_fee"]=$cash_fee.'';
			 $dataInfo["Status"]=99;
			 
			 $cardid=$this->GetCardId();
			 $result=$mMPay->UpdateRechange($dataInfo,$cardid);
		} 
		else
		{
			$content="-----------------出错啦-----------------";
			$content=$content.$dataInfo["PayType"] .$dataInfo["Status"];
			echo $content;
		}
		
			echo getuid();
 
	}

	
	 
}