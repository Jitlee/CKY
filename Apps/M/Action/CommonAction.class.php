<?php
namespace M\Action;
use Think\Controller;
 

class CommonAction extends Controller {

//session("loginbackurl")
	protected function _initialize() {
//		$openid=session("loginopenid");
//		if(isset($openid))
//		{
//			//session('loginbackurl',"Home/selectreg");
//			
//		}
//		else
//		{
//			//session("loginopenid","wxid");
//			session('loginbackurl',"Home/selectreg");
//			$this->redirect('Wx/getcodeurl');
//		}
			
			$userlogin=session('userloginobj');
			if(isset($userlogin))
			{
				//session('userloginobj',null);
				$this->assign('openid', $userlogin["openid"]);
				$this->assign('nickname', $userlogin["nickname"]);
				$this->assign('headimgurl', $userlogin["headimgurl"]);
			}
			else
			{
				$this->redirect('Wx/getcodeurl');
//				session('loginbackurl',"");
			}
	}
}