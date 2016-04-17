<?php
 namespace Admin\Model;
/**
 * ============================================================================
 * 粗卡云:
  
 * 联系方式:
 * ============================================================================
 * 角色服务类
 */
class MalllifeModel extends BaseModel {
    /**
	  * 新增
	  */
	 public function insert(){
	 	$rd = array('status'=>-1);
	 	 
		$data = array();
		$data["lifeid"] = (int)I("lifeid",0);
		$data["lifetitle"] = I("lifetitle");
		$data["lifetypeid"] = (int)I("lifetypeid");
		
		$data["logo"] = I("logo"); 
		$data["logothums"] = I("logothums");
		
		$data["content"] = I("content");
		$data["status"] = (int)I("status");
		$data["efficacysdate"] = I("efficacysdate");
		$data["efficacyedate"] = I("efficacyedate");
		$data["sort"] = (int)I("sort");

		$data["createtime"] = date('Y-m-d H:i:s');
		$data["createuser"] = session('RTC_STAFF')["staffId"];
		
	    //if($this->checkEmpty($data)){
			$m = M('mall_life');
			$rs = $m->add($data);
			if(false !== $rs){
				$rd['status']= 1;
			}
//		}
		return $rd;
	 } 
     /**
	  * 修改
	  */
	 public function edit(){
	 	$rd = array('status'=>-1);
	 	$id = (int)I("lifeid",0);

		$data = array();
		$data["lifetitle"] = I("lifetitle");
		$data["lifetypeid"] = (int)I("lifetypeid");
		
		$data["logo"] = I("logo"); 
		$data["logothums"] = I("logothums");
		
		$data["content"] = I("content");
		$data["status"] = (int)I("status");
		$data["efficacysdate"] = I("efficacysdate");
		$data["efficacyedate"] = I("efficacyedate");
		$data["sort"] = (int)I("sort");

		$data["createtime"] = date('Y-m-d H:i:s');
		$data["createuser"] = session('RTC_STAFF')["staffId"];
		
    	$m = M('mall_life');
	    $rs = $m->where("lifeid=".(int)I('lifeid',0))->save($data);
		if(false !== $rs){
			$rd['status']= 1;
		}
		return $rd;
	 } 
	 /**
	  * 获取指定对象
	  */
     public function get(){
	 	$m = M('mall_life');
		return $m->where("lifeid=".(int)I('id'))->find();
	 }
	 /**
	  * 分页列表
	  */
     public function queryByPage(){
        $m = M('mall_life');
	 	$sql = "select ml.*,c.lifetypename from __PREFIX__mall_life ml
	 	left join __PREFIX__mall_lifetype c on ml.lifetypeid=c.lifetypeid
	 	 order by sort desc,lifeid desc";
		return $m->pageQuery($sql);
	 }
	 /**
	  * 获取列表
	  */
	  public function queryByList(){
	     $m = M('mall_life');
		 return $m->select();
	  }
	  
	 /**
	  * 删除
	  */
	 public function del(){
	 	$rd = array('status'=>-1);
	 	$m = M('mall_life');
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
	 	$m = M('mall_life');
	 	$m->status = ((int)I('status')==1)?1:0;
	 	$rs = $m->where("lifeid=".(int)I('id',0))->save();
	    if(false !== $rs){
			$rd['status']= 1;
		}
	 	return $rd;
	 }
};
?>