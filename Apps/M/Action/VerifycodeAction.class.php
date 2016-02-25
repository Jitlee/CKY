<?php
namespace M\Action;
/**
 * ============================================================================
 * 粗卡云:
  
 * 联系方式:
 * ============================================================================
 * 订单控制器
 */
class VerifycodeAction extends BaseAction {
	
	public function Send($mobile)
	{
		$rs = array('status'=>-1);
		
		$mOne = D('M/OneCard');
		//$mobile="18617097726";		
		$code=rand(1010,9797);		
		$content="尊敬的用户：".$code."是本次操作的验证码，5分钟内有效。";		
		$rs=$mOne->SendVerycode($mobile,$content);
		$status= (int)$rs["status"];
		if($status == 0)
		{
			$mcode = D('M/Verifycode');	
			$rs=$mcode->Insert($mobile,$code);
			$status= (int)$rs["status"];
			if($status == 1)
			{
				//echo "成功";	
				$rs['status']= 1;
			}
		}
		$this->ajaxReturn($rs);
	}
	
	
	public function Check($mobile,$code)
	{
		$rs = array('status'=>-1); 
		$mcode = D('M/Verifycode');	
		$rs=$mcode->Check($mobile,$code);
		$status= (int)$rs["status"];
		if($status == 1)
		{
			$rs['status']= 1;
		}	 
		//$this->ajaxReturn($rs);
		echo dump($rs);
		echo $rs;
	}

}
	