<?php
namespace M\Action;
use Think\Controller;
use P\Model;

class HomeAction extends Controller {
		
	public function selectreg() {
			
		$userlogin=session('userloginobj');
		$openid=$userlogin["openid"];
		$this->assign('openid', $openid);
		
		echo dump($userlogin);
		echo "</br>";
		echo "wxaccess_token=</br>";
		
		$wxaccess_token=session("wxaccess_token");
		echo dump($wxaccess_token);
		echo "</br>";
		echo "</br>";
		//session("wxposition","aa");
		echo "</br>wxposition=";
		$wxposition=session("wxposition");
		echo $wxposition;
		echo session("wxposition");
		$this->assign('wxposition', $wxposition);
		 echo "</br>openid2=";
		
		$openid2=session("openid2");
		$this->assign('$openid2', $openid2);
		$this->display();
	}
	
	public function getwxerror() {
		$this->display();
	}

}