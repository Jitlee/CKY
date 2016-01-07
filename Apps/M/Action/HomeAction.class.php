<?php
namespace M\Action;
use Think\Controller;
use P\Model;

class HomeAction extends Controller {
		
	public function selectreg() {
			
		$userlogin=session('userloginobj');
		$openid=$userlogin["openid"];
		
		if(empty($openid))			 
		{
			echo dump($userlogin);
			$this->display("getwxerror");
			exit;
		}
		$this->assign('openid', $openid);
		//echo dump($userlogin);

//		echo "</br>wxposition=";
//		$wxposition=session("wxposition");
//		echo $wxposition;
//		echo session("wxposition");
//		$this->assign('wxposition', $wxposition);
//		 echo "</br>openid2=";
//		
//		$openid2=session("openid2");
//		$this->assign('$openid2', $openid2);
		session('userloginobj',null);
		
		$this->display();
	}
	/*错误提示*/
	public function getwxerror() {
		$this->display();
	}
	/*申请新卡车*/
	public function reg() {
		if(IS_POST) {
			$result["status"]=0;
			$result["msg"]="注册成功。";
			
			$openid=getopenid();
			
			//$password= md5($_POST['password']);				  
			$password= $_POST['password'];
			//$db = M('member');
			$Mobile = $_POST['mobile'];
			
			$mMember = D('M/Member');
			$user=$mMember->Get($openid);	//根据手机查询
			if($user) {
				$result["status"]=1000;
				//$this->redirect('Person/index');//已经关联直接跳转到 个人中心
				exit;
			}
			
			$user=$mMember->GetByMobile($Mobile);	//根据手机查询	
			//$user = $db->where($data)->find();
			if($user) {
				$result["msg"]='手机号码已经注册。';
			}
			else{
				$data = array(
					"cardId"	=>$Mobile
					,"password"	=>$password 
					,"mobile" 	=>$_POST['mobile']
					,"memberGroupName"=>"416f147d-0a89-e511-ab53-001018640e2a"	//默认级别
					,"trueName"	=>$_POST['trueName']
					,"sex"		=>$_POST['sex']
				);
				
				$mOnecard = D('M/OneCard');
				$res=$mOnecard->UserReg($data);//可能有多条。
				$status= $res["status"];
				if($status == 0)//查询结果并写入到数据库
				{
					 //查询
					 //		$m = D('M/OneCard');
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
							$result["msg"]=  "注册失败-add database。";
						}						
					}
					else
					{
						$result["msg"]= $res["message"]."code:002";						
					}
				}
				else
				{
					$result["msg"]= $res["message"]."code:002";
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
		$this->display();
	}
	/*关联会员卡根据卡号，密码*/
	public function conncardbycardid() {
		$this->display();
	}
	
	
	
	
	
	
	public function ftest()
	{
		$m = D('M/OneCard');
		$cardid="18620554231";
		$res=$m->GetMemberGroup();//可能有多条。	
		echo dump($res);
	}

}