<?php
 namespace Admin\Model;
/**
 * ============================================================================
 * 粗卡云:
  
 * 联系方式:
 * ============================================================================
 * 银行服务类
 */
class PaymentModel extends BaseModel {
    /**
	  * 新增
	  */
	 public function add(){
	 	$rd = array('status'=>-1);
		$data = array();
		$data["payCode"] = I("payCode");
		$data["payName"] = I("payName");
		$data["payDesc"] = I("payDesc");
		;
		if($this->checkEmpty($data,true)){
			
			$data["payOrder"] = I("payOrder");
			$data["payConfig"] = I("payConfig");
			$data["enabled"] = I("enabled");
			$data["isOnline"] = I("isOnline");
			
			$m = M('Payment');
			$rs = $m->add($data);
			if(false !== $rs){
				$rd['status']= 1;
			}
		}
		return $rd;
	 } 
     /**
	  * 修改
	  */
	 public function edit(){
	 	$rd = array('status'=>-1);
	 	$id = (int)I("id",0);
	    
		$m = M('Payment');
		$data["payName"] = I("payName");
		$data["payDesc"] = I("payDesc");
		$data["payOrder"] = I("payOrder");
		$data["payConfig"] = json_encode(I("payConfig")) ;
		$data["enabled"] = 1;
		if($this->checkEmpty($data)){	
			$rs = $m->where("id=".I('id'))->save($data);
			if(false !== $rs){
				$rd['status']= 1;
			}
		}
		return $rd;
	 } 
	 /**
	  * 获取指定对象
	  */
     public function get(){
	 	$m = M('Payment');
		$payment = $m->where("id=".I('id'))->find();
		$payConfig = json_decode($payment["payConfig"]) ;
		foreach ($payConfig as $key => $value) {
			$payment[$key] = $value;
		}
		return $payment;
	 }
	 /**
	  * 分页列表
	  */
     public function queryByPage(){
        $m = M('Payment');
	 	$sql = "select * from __PREFIX__payment order by payOrder asc";
		$rs = $m->pageQuery($sql);
		
		foreach ($rs["root"] as $key => $value) {
			
			 $rs["root"][$key]["payDesc"] = htmlspecialchars_decode($value["payDesc"]) ;
		
		}
		return $rs;
	 }
	 /**
	  * 获取列表
	  */
	  public function queryByList(){
	     $m = M('Payment');
		 $rs = $m->select();
		 return $rs;
	  }
	  
	 /**
	  * 删除
	  */
	 public function del(){
		$m = M('Payment');
		$data["enabled"] = 0;
		$rs = $m->where("id=".I('id'))->save($data);
		if(false !== $rs){
			$rd['status']= 1;
		}
		return $rd;
	 }
};
?>