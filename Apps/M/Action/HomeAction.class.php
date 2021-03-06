<?php
namespace M\Action;
use Think\Controller;
use P\Model;

class HomeAction extends BaseAction {
	
	
	public function _initialize(){
		$this->assign('tabid', "member");
	}
	
	public function selectreg() {
		$openid=$this->GetOpenid();
		if(empty($openid))			 
		{
			//echo dump($userlogin);
			$this->display("getwxerror");
			exit;
		}
		//echo $openid;
		$this->assign('openid', $openid); 
		$this->display();
	}
	/*错误提示*/
	public function getwxerror() {
		$openid=session('openid');
		//echo $openid;
		$openid=''.$openid;		
		if($openid && strlen($openid)>10)
		{
			$this->redirect('Person/index');
			exit;
		}
		$this->assign('data', $userlogin);
		$this->display();
	}
	/*申请新卡车*/
	public function reg() {
		if(IS_POST) {
			$result["status"]=0;
			$result["msg"]="注册成功。";			
			$openid=$this->GetOpenid();
			
			$openid=session('openid'); 
			if(empty($openid))
			{
				$result["msg"]="获取参数失败。".$openid;	
				$this->ajaxReturn($result, "JSON");
				exit;
			}
			
					  
			$password= $_POST['password'];
			$Mobile = $_POST['mobile'];
			$verycode=I("verycode");
					
			//验证验证码.
			$mcode = D('M/Verifycode');	
			$rs=$mcode->Check($Mobile,$verycode);
			$status= (int)$rs["status"];
			if($status != 1)
			{
				 $result["msg"]="验证码无效。";
				 $this->ajaxReturn($result, "JSON");
				 return;
			}
			
			
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

	public function trylogin()
	{
		
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
			$verycode=I("verycode");
			
			//验证验证码.
			$mcode = D('M/Verifycode');	
			$rs=$mcode->Check($Mobile,$verycode);
			$status= (int)$rs["status"];
			if($status != 1)
			{
				 $result["msg"]="验证码无效。";
				 $this->ajaxReturn($result, "JSON");
				 return;
			}
		
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
//						$result["status"]=-10;
//						$result["msg"]=$data[""]; 
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
					$result["status"]=-10;
					$result["msg"]= $res["msg"].$Mobile;
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
	
	

	
public function create_guid($namespace = '') {     
    static $guid = '';
    $uid = uniqid("", true);
    $data = $namespace;
    $data .= $_SERVER['REQUEST_TIME'];
    $data .= $_SERVER['HTTP_USER_AGENT'];
    $data .= $_SERVER['LOCAL_ADDR'];
    $data .= $_SERVER['LOCAL_PORT'];
    $data .= $_SERVER['REMOTE_ADDR'];
    $data .= $_SERVER['REMOTE_PORT'];
    $hash = strtoupper(hash('ripemd128', $uid . $guid . md5($data)));
    $guid = '' .   
            substr($hash,  0,  8) . 
            '-' .
            substr($hash,  8,  4) .
            '-' .
            substr($hash, 12,  4) .
            '-' .
            substr($hash, 16,  4) .
            '-' .
            substr($hash, 20, 12) .
            '';
    return $guid;
}

//	function GetGoods($shopid=0)
//	{
//		$m = D('M/Recommend');
//  	$goods=$m->getGoodsByShopid($shopid);
//  	$html="<ul class='cky-table-view'>";
//  	foreach ($goods as $v) {
// 			$html=$html."<li class='cky-table-view-cell cky-fast-goods-cell'>";
//			$html=$html."<a>";
//			$html=$html."	<div class='cky-table-cell-thumb cky-table-cell-thumb60' style='{ backgroundImage:/" . $v["goodsThums"] . "'></div>">;
//			$html=$html."	<div class='cky-media'>";
//			$html=$html."		<span class='cky-media-title font17'>".$v["goodsName"]."</span>";
//			$html=$html."		<div class='cky-media-sub font13 font-gray2'>";
//			$html=$html."			<span>已售" . $v["saleCount"] . "单</span>";
//			$html=$html."		</div>";
//			$html=$html."		<div class='cky-relative'>";
//			$html=$html."			<span class='font-red font17'>¥".$v["shopPrice"] . "</span>";
//			$html=$html."			<span class='font13 font-gray'>".$v["goodsUnit"] . "</span>";
//			$html=$html."		</div>";
//			$html=$html."   </div>";
//			$html=$html."</a>";			
//			$html=$html."</li>";
//		}
//		$html=$html."</ul>";
		
//		echo dump($goods);
//		return $html;
//	}


	public function ftest()
	{
//		$m = D('M/OneCardTest');
//		$list = $m->GetTickMList("18620554231");
//		echo dump($list); 
//		$list = $m->GetTick(); 
//		$m = D('M/OneCard');
//		$list = $m->GetMemberGroup();
//		echo dump($list);		

//		$m = D('M/OneCardTick');
//		$list = $m->UpdateMember('18620554231','postCode','518000');
//		echo dump($list);

//				$uid=session("uid");
/*
				$key="18620554231";
				$mdb = D('M/Member');
				$email='2';
				$result=$mdb->UpdateMember($key,'TrueName',$email);
				*/
				
		$kj_id=104;    
        $filter=array('kj_id'=> $kj_id);
        //$rs=$mdb->where($map)->select(); 
		$m=M("kanjia");
		$rs= $m->where("kj_id='$kj_id'")->select();
		echo dump($rs);

//			$mMember = D('M/OneCard');
//			$key="18620554231";
//			$Birthday="2";
//			$result = $mMember->UpdateMember($key,'Sex',$Birthday);
			
			
			
//		$m = D('M/ActivityTicket');
//		$uid = getuid();
//		
//		$list = $m->queryAll($uid);
//		echo $uid;	
//		echo dump($list);
		 
		$this->display();
//		

	}
	
	 
}