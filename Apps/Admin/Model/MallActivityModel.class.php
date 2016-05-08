<?php
 namespace Admin\Model;
/**
 * ============================================================================
 * 粗卡云:
  
 * 联系方式:
 * ============================================================================
 * 文章服务类
 */
class MallActivityModel extends BaseModel {
     /**
	  * 新增
	  */
	 public function insert(){
	 	$rd = array('status'=>-1);
	 	$id = (int)I("mactid",0);
		$data = array();
		 
		$data["mactname"] = I("mactname");		
		$data["logo"] =  I("logo");
		$data["logothums"] = I("logothums");
		$data["adpath"] = I("adpath");
		$data["adpaththums"] = I("adpaththums");
		$data["starttime"] = I("starttime");
		$data["endtime"] = I("endtime");
		

		$data["mode"] = I("mode","");
		$data["modecolor"] = I("modecolor","");
		$data["desc"] = I("desc","");
		$data["status"] = (int)I("status",0);
		$data["sort"] = (int)I("sort",0);
		
		$data["createtime"] = date('Y-m-d H:i:s');
		$staff = session('RTC_STAFF');
		$data["createuser"] = $staff["staffId"];
	    //if($this->checkEmpty($data,true)){
			$m = M('mall_activity');
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
		$data["mactname"] = I("mactname");		
		$data["logo"] =  I("logo");
		$data["logothums"] = I("logothums");
		$data["adpath"] = I("adpath");
		$data["adpaththums"] = I("adpaththums");
		$data["starttime"] = I("starttime");
		$data["endtime"] = I("endtime");
		

		$data["mode"] = I("mode","");
		$data["modecolor"] = I("modecolor","");
		$data["desc"] = I("desc","");
		$data["status"] = (int)I("status",0);
		$data["sort"] = (int)I("sort",0);
	    //if($this->checkEmpty($data,true)){	
			$m = M('mall_activity');
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
	 	$m = M('mall_activity');
		return $m->where("mactid=".(int)I('id'))->find();
	 }
	 /**
	  * 分页列表
	  */
     public function queryByPage(){
		
        $m = M('mall_activity');
	 	$sql = "
	 	select 
	 		a.*
	 	from __PREFIX__mall_activity a
	 	where 
	 		 1=1
	 	";
	 	if(I('mactname')!='')$sql.=" and mactname like '%".I('mactname')."%'";
	 	$sql.=' order by sort desc';
		return $m->pageQuery($sql);
	 }
	 /**
	  * 获取列表
	  */
	  public function queryByList(){
	     $m = M('mall_activity');
	     $sql = "select * from __PREFIX__mall_activity   order by mactid desc";
		 $rs = $m->query($sql);

		 return $rs;
	  }
	  
	 /**
	  * 删除
	  */
	 public function del(){
	 	$rd = array('status'=>-1);
	   	
	   	$mactid=I('id');
	    $mm = M('mall_activitym');
	    $rs = $mm->where("mactid=$mactid")->delete();
				
	    $m = M('mall_activity');
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
	 	$m = M('mall_activity');
	 	$m->isShow = ((int)I('isShow')==1)?1:0;
	 	$rs = $m->where("mactid=".(int)I('id',0))->save();
	    if(false !== $rs){
			$rd['status']= 1;
		}
	 	return $rd;
	 }
};
?>