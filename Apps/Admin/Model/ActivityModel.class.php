<?php
 namespace Admin\Model;
/**
 * ============================================================================
 * 粗卡云:
  
 * 联系方式:
 * ============================================================================
 * 文章服务类
 */
class ActivityModel extends BaseModel {
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
		$data["limitUseShopID"] = (int)I("limitUseShopID");
		
		$data["staffId"] =1;// (int)session('RTC_STAFF.staffId');
		$data["createTime"] = date('Y-m-d H:i:s');
	    if($this->checkEmpty($data,true)){
			$m = M('activity');
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
		$data["limitUseShopID"] = (int)I("limitUseShopID");
		
		$data["staffId"] =(int)session('RTC_STAFF.staffId');
	    if($this->checkEmpty($data,true)){	
			$m = M('activity');
		    $rs = $m->where("activityId=".(int)I('id',0))->save($data);
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
	 	$m = M('activity');
		return $m->where("activityId=".(int)I('id'))->find();
	 }
	 /**
	  * 分页列表
	  */
     public function queryByPage(){
        $m = M('activity');
	 	$sql = "
	 	select 
	 		a.activityTitle,a.activityId,a.efficacySDate,a.efficacyEDate,a.activitySort
	 		,a.isShow,a.createTime,c.catName,a.activityImg,s.staffName,sp.shopName
	 	from __PREFIX__activity a 
		left join __PREFIX__shops sp on sp.shopid=a.limitUseShopID  
	 	left join __PREFIX__goods_cats c on a.catId=c.catId
		left join __PREFIX__staffs s on a.staffId = s.staffId 
	 	where 
	 		a.catId=c.catId and a.staffId = s.staffId 
	 	";
	 	if(I('activityTitle')!='')$sql.=" and activityTitle like '%".I('activityTitle')."%'";
	 	$sql.=' order by activityId desc';
		return $m->pageQuery($sql);
	 }
	 /**
	  * 获取列表
	  */
	  public function queryByList(){
	     $m = M('activity');
	     $sql = "select * from __PREFIX__activity where isShow =1 order by activityId desc";
		 $rs = $m->query($sql);

		 return $rs;
	  }
	  
	 /**
	  * 删除
	  */
	 public function del(){
	 	$rd = array('status'=>-1);
	    $m = M('activity');
	    $rs = $m->delete((int)I('id'));
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
	 	$m = M('activity');
	 	$m->isShow = ((int)I('isShow')==1)?1:0;
	 	$rs = $m->where("activityId=".(int)I('id',0))->save();
	    if(false !== $rs){
			$rd['status']= 1;
		}
	 	return $rd;
	 }
};
?>