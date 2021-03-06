<?php
 namespace M\Model;
/**
 * ============================================================================
 * 粗卡云:
  
 * 联系方式:
 * ============================================================================
 * 广告服务类
 */
class VerifycodeModel extends BaseModel {
	 
	public function Insert($mobile,$code)
	{ 
		$rd = array('status'=>-1);	 			
		$data = array();		 
		$data["mobile"] = $mobile;
		$data["code"] = $code;
		$data["codestatus"] = 0;
		$data["createTime"] = date('Y-m-d H:i:s');	    
	    if($this->checkEmpty($data,true)){
			$m = M('verifycode');
			$rs = $m->add($data);
		    if(false !== $rs){
				$rd['status']= 1;
			}
		}
		return $rd;
	}
	//http://localhost:505/index.php/M/Verifycode/Check/mobile/18617097726/code/7843
	public function Check($mobile,$code)
	{
		$sql="select * from cky_verifycode
where mobile='$mobile' and createTime > date_add(now(),interval -50 minute) and `code`='$code'  and codestatus=0
order by createTime desc limit 1 ";

		$rd = array('status'=>-1);
		$num= M()->query($sql);
 		if($num) {
 			$id=(int)$num[0]["codeid"];
			if($id>0)
			{
				$m = M('verifycode');
				$data["codestatus"] = 1;
				$m->where("codeid=".$id)->save($data);				 
				$rd['status']= 1;
			}
		}
		return $rd;
	}
	
}