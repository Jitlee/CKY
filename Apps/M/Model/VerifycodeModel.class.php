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
	
	public function Check($mobile,$code)
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
	
}