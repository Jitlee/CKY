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
		$this->display();
	}
	/*关联根据手机号码*/
	public function conncardmobile() {
		$this->display();
	}
	/*关联会员卡根据卡号，密码*/
	public function conncardbycardid() {
		$this->display();
	}
	
	

}