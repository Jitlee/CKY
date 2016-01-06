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
	
	public function getwxerror() {
		 echo "</br>openid2=";		
		$openid2=session("openid2");
		$this->assign('$openid2', $openid2);
		echo $openid2;
		$this->display();
	}

}