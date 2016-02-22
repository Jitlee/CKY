<?php
 namespace Admin\Model;
/**
 * ============================================================================
 * 粗卡云:
  
 * 联系方式:
 * ============================================================================
 * 文章服务类
 */
class TicketModel extends BaseModel {
     /**
	  * 新增
	  */
	 public function insert(){
	 	$rd = array('status'=>-1);
	 	$id = (int)I("id",0);
		$data = array();
		$data["catId"] = (int)I("catId");
		$data["activityTitle"] = I("activityTitle");
		$data["isShow"] = (int)I("isShow",0);
		$data["activitySort"] = (int)I("activitySort");
		$data["efficacySDate"] = I("efficacySDate");
		$data["efficacyEDate"] = I("efficacyEDate");
		$data["activityContent"] = I("activityContent");
		$data["activityKey"] = I("activityKey");
		$data["activityImg"] = I("activityImg");		
		$data["staffId"] =1;// (int)session('RTC_STAFF.staffId');
		
		$data["createTime"] = date('Y-m-d H:i:s');
	    
	    if($this->checkEmpty($data,true)){
			$m = M('activity_ticket');
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
		$data = array();
		$data["catId"] = (int)I("catId");
		$data["activityTitle"] = I("activityTitle");
		$data["isShow"] = (int)I("isShow",0);
		$data["activitySort"] = (int)I("activitySort");
		$data["efficacySDate"] = I("efficacySDate");
		$data["efficacyEDate"] = I("efficacyEDate");
		
		$data["activityContent"] = I("activityContent");
		$data["activityKey"] = I("activityKey");
		$data["activityImg"] = I("activityImg");
		
		$data["staffId"] =(int)session('RTC_STAFF.staffId');
	    if($this->checkEmpty($data,true)){	
			$m = M('activity_ticket');
		    $rs = $m->where("ticketID=".I('id',0))->save($data);
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
	 	$m = M('activity_ticket');
		return $m->where("ticketID=".I('id'))->find();
	 }
	 /**
	  * 分页列表
	  */
     public function queryByPage(){
        $m = M('activity_ticket');
	 	$sql = "select a.* from __PREFIX__activity_ticket a";
	 	if(I('title')!='')$sql.=" and title like '%".I('title')."%'";
	 	$sql.=' order by createTime desc';
		return $m->pageQuery($sql);
	 }
	 /**
	  * 获取列表
	  */
	  public function queryByList(){
	     $m = M('activity_ticket');
	     $sql = "select * from __PREFIX__activity_ticket  order by createTime desc";
		 $rs = $m->query($sql);

		 return $rs;
	  }
	  
	 /**
	  * 删除
	  */
	 public function del(){
	 	$rd = array('status'=>-1);
	    $m = M('activity_ticket');
	    $rs = $m->delete(I('id'));
		if(false !== $rs){
		   $rd['status']= 1;
		}
		return $rd;
	 }
	 /**
	  * 显示分类是否显示/隐藏
	  */
	 public function editiIsShow(){
	 	$rd = array('status'=>-1);
	 	if(I('id',0)==0)return $rd;
	 	$m = M('activity_ticket');
	 	//$m->isShow = ((int)I('isShow')==1)?1:0;
	 	$rs = $m->where("ticketID=".I('id',0))->save();
	    if(false !== $rs){
			$rd['status']= 1;
		}
	 	return $rd;
	 }
};
?>