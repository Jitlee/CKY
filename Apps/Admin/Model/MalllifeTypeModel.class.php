<?php
 namespace Admin\Model;
/**
 * ============================================================================
 * 粗卡云:
  
 * 联系方式:
 * ============================================================================
 * 角色服务类
 */
class MalllifeTypeModel extends BaseModel {
    /**
	  * 新增
	  */
	 public function insert(){
	 	$rd = array('status'=>-1);
	 	//$id = (int)I("lifetypeid",0);
		$data = array();
		$data["lifetypename"] = I("lifetypename");
		$data["createtime"] = date('Y-m-d H:i:s');
		$data["createuser"] = session('RTC_STAFF')["staffId"];
		
	    if($this->checkEmpty($data)){
			$m = M('mall_lifetype');
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
	 	$id = (int)I("lifetypeid",0);
	 	$m = M('mall_lifetype');
		$m->lifetypename = I("lifetypename");
	    if($this->checkEmpty($data)){
			$rs = $m->where("lifetypeid=".$id)->save();
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
	 	$m = M('mall_lifetype');
		return $m->where("lifetypeid=".(int)I('id'))->find();
	 }
	 /**
	  * 分页列表
	  */
     public function queryByPage(){
        $m = M('mall_lifetype');
	 	$sql = "select * from __PREFIX__mall_lifetype order by lifetypeid desc";
		return $m->pageQuery($sql);
	 }
	 /**
	  * 获取列表
	  */
	  public function queryByList(){
	     $m = M('mall_lifetype');
		 return $m->select();
	  }
	  
	 /**
	  * 删除
	  */
	 public function del(){
	 	$rd = array('status'=>-1);
	 	$m = M('mall_lifetype');
	    $rs = $m->delete((int)I('id'));
		if(false !== $rs){
			$rd['status']= 1;
		}
		return $rd;
	 }
};
?>