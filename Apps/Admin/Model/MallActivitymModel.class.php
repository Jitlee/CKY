<?php
 namespace Admin\Model;
/**
 * ============================================================================
 * 粗卡云:
  
 * 联系方式:
 * ============================================================================
 * 活动，类别
 */
class MallActivitymModel extends BaseModel {
     /**
	  * 新增
	  */
	 public function insert(){
	 	$rd = array('status'=>-1);
	 	$mactmid = (int)I("mactmid",0);
		$data = array();
		 
		$data["mactid"] = I("mactid");
		$data["mactname"] = I("mactname");			
		$data["mlogo"] =  I("mlogo");
		$data["mlogothums"] = I("mlogothums");
		$data["pricemode"] = I("pricemode");
		$data["amount"] = I("amount");
		$data["consamount1"] = I("consamount1");
		$data["discount1"] = I("discount1");
		$data["consamount2"] = I("consamount2");
		$data["discount2"] = I("discount2");
		
		$data["lineshownum"] = (int)I("lineshownum");
		$data["sort"] = (int)I("sort",0);
		$data["mstatus"] = 1; 
		
		$data["createtime"] = date('Y-m-d H:i:s');
		$data["createuser"] = session('RTC_STAFF')["staffId"];
	    //if($this->checkEmpty($data,true)){
			$m = M('mall_activitym');
			$rs = $m->add($data);
		    if(false !== $rs){
				$rd['status']= 1;
			}
		//}
		return $rd; 
	 } 
     /**
	  * 修改
	  */
	 public function edit(){
	 	$rd = array('status'=>-1);
	 	$mactid = (int)I("mactid",0);
		$data = array();
		$data["mactid"] = I("mactid");
		$data["mactname"] = I("mactname");			
		$data["mlogo"] =  I("mlogo");
		$data["mlogothums"] = I("mlogothums");
		$data["pricemode"] = I("pricemode");
		$data["amount"] = I("amount");
		$data["consamount1"] = I("consamount1");
		$data["discount1"] = I("discount1");
		$data["consamount2"] = I("consamount2");
		$data["discount2"] = I("discount2");
		
		$data["lineshownum"] = (int)I("lineshownum");
		$data["sort"] = (int)I("sort",0);
		$data["mstatus"] = 1; 
		
		$data["createtime"] = date('Y-m-d H:i:s');
		$data["createuser"] = session('RTC_STAFF')["staffId"];
	    //if($this->checkEmpty($data,true)){	
			$m = M('mall_activitym');
		    $rs = $m->where("mactid=".$mactid)->save($data);
			if(false !== $rs){
				$rd['status']= 1;
			}
		//}
		return $rd;
	 } 
	 /**
	  * 获取指定对象
	  */
     public function get(){
	 	$m = M('mall_activitym');
		return $m->where("mactid=".(int)I('mactid'))->find();
	 }
	 /**
	  * 分页列表
	  */
     public function queryByPage($mactid){
        $m = M('mall_activitym');
	 	$sql = "
	 	select 
	 		a.*
	 	from __PREFIX__mall_activitym a
	 	where 
	 		 mactid=$mactid
	 	";
	 	
	 	$sql.=' order by sort desc';
		return $m->pageQuery($sql);
	 }
	 /**
	  * 获取列表
	  */
	  public function queryByList(){
	     $m = M('mall_activity');
	     $sql = "select * from __PREFIX__mall_activitym   order by mactid desc";
		 $rs = $m->query($sql);

		 return $rs;
	  }
	  
	 /**
	  * 删除
	  */
	 public function del(){
	 	$rd = array('status'=>-1);
	    $m = M('mall_activitym');
	    $rs = $m->delete((int)I('id'));
		if(false !== $rs){
		   $rd['status']= 1;
		}
		return $rd;
	 }
	 /**
	  * 显示分类是否显示/隐藏
	  */
	 public function editStatus(){
	 	$rd = array('status'=>-1);
	 	if(I('id',0)==0)return $rd;
	 	$m = M('mall_activitym');
	 	$m->isShow = ((int)I('isShow')==1)?1:0;
	 	$rs = $m->where("mactid=".(int)I('id',0))->save();
	    if(false !== $rs){
			$rd['status']= 1;
		}
	 	return $rd;
	 }
};
?>