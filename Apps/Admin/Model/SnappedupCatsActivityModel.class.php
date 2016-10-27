<?php
namespace Admin\Model;
/**
 * ============================================================================
 * 粗卡云:
  
 * 联系方式:
 * ============================================================================
 * 商品分类服务类
 */
class SnappedupCatsActivityModel extends BaseModel {
    /**
	  * 新增
	  */
	 public function insert(){
	 	$rd = array('status'=>-1);
	 	$id = (int)I("id",0);
		$data = array();
		$data["CAName"] = I("CAName");
		$data["SUCatsId"] = I("SUCatsId");
		$data["CADate"] = I("CADate");
		$data["StartTime"] = (int)I("StartTime",0);
		$data["ActivityLen"] = (int)I("ActivityLen",0);
	    $data["isShow"] = (int)I("isShow",0);
	    
		if($this->checkEmpty($data,true)){
			$m = M('snappedup_catsactivity');
			$rs = $m->add($data);
			if($rs){
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
		$data["CAName"] = I("CAName");
		$data["SUCatsId"] = I("SUCatsId");
		$data["CADate"] = I("CADate");
		$data["StartTime"] = (int)I("StartTime",0);
		$data["ActivityLen"] = (int)I("ActivityLen",0);
	    $data["isShow"] = (int)I("isShow",0);
	    if($this->checkEmpty($data)){
			 
	    	$m = M('snappedup_catsactivity');
			$rs = $m->where(" SUCatsActivityId=".(int)I('id'))->save($data);
			if(false !== $rs){
				$rd['status']= 1;
			}
		}
		return $rd;
	 } 
	 
	 /**
	  * 获取指定对象
	  */
     public function get($id){
     	$id = (int)I("id",0);
	 	$m = M('snappedup_catsactivity');
		return $m->where("SUCatsActivityId=".(int)$id)->find();
	 }
	 /**
	  * 获取列表
	  */
	  public function queryByList(){
//	     $m = M('');
//	     $rs = $m->where('SUCatsActivityId='.(int)$pid)->select(); 
//		 return $rs;
//		 
		  	$m = M('snappedup_catsactivity');
		     $sql="select * from __PREFIX__snappedup_catsactivity ";
			 $rs = $m->pageQuery($sql);
			 return $rs;
		 
	  }
	 
	 
	 
	 
	 /**
	  * 显示分类是否显示/隐藏
	  */
	 public function editiIsShow(){
	 	$rd = array('status'=>-1);
	 	$id = (int)I("id",0);
		$data = array();
		$data["isShow"] = (int)I("isShow",0);
	    if($this->checkEmpty($data)){
	    	$m = M('snappedup_catsactivity');
			$rs = $m->where("SUCatsActivityId=".(int)I('id'))->save($data);
			if(false !== $rs){
				$rd['status']= 1;
			}
		}
		return $rd;
	 }
};
?>