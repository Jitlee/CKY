<?php
namespace M\Action;
use Think\Controller;
 

class CommonAction extends Controller {

	protected function _initialize() {
			$userlogin=session('userloginobj');
			$openid=$userlogin["openid"];
			if(!empty($openid))
			{
				//session('userloginobj',null);
				$openid=$userlogin["openid"];
				$this->assign('openid', $openid);
				$this->assign('nickname', $userlogin["nickname"]);
				$this->assign('headimgurl', $userlogin["headimgurl"]);
				//验证是否已经关联
				$mMember = D('M/Member');
				$result=$mMember->GetByOpenid($openid);
				if(!$result)
				{					
					$this->redirect('Home/selectreg');
					$cardid=$result["CardId"];
					session("cardid",$cardid);
				}
			}
			else
			{
				$this->redirect('Wx/getcodeurl');
//				session('loginbackurl',"");
			}
	}
}