<?php
namespace Admin\Model;
/**
 * ============================================================================
 * 粗卡云:
  
 * 联系方式:
 * ============================================================================
 * 商品分类服务类
 */
class SnappedupCatsModel extends BaseModel {
    /**
	  * 新增
	  */
	 public function catsinsert(){
	 	$rd = array('status'=>-1);
	 	$id = (int)I("id",0);
		$data = array();
		$data["CName"] = I("CName");
		$data["isShow"] = I("isShow");
		$data["sort"] = (int)I("sort");
		
		if($this->checkEmpty($data,true)){
			$m = M('snappedup_cats');
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
	 public function catsedit(){
	 	$rd = array('status'=>-1);
	 	$id = (int)I("id",0);
		$data = array();
		$data["CName"] = I("CName");
		$data["sort"] = (int)I("sort");
	    if($this->checkEmpty($data)){
	    	$data["isShow"] = (int)I("isShow",0);
	    	
	    	$m = M('snappedup_cats');
			$rs = $m->where("SUCatsId=".(int)I('id'))->save($data);
			if(false !== $rs){
				$rd['status']= 1;
			}
		}
		return $rd;
	 }
	  
	 /**
	  * 获取指定对象
	  */
     public function catsget(){
     	$id = (int)I("id",0);
	 	$m = M('snappedup_cats');
		return $m->where("SUCatsId=".(int)$id)->find();
	 }
	 /**
	  * 获取列表
	  */
	  public function queryByList(){
	     $m = M('snappedup_cats');
	     $sql="select * from __PREFIX__snappedup_cats ";
		 $rs = $m->pageQuery($sql);
		 return $rs;
	  }
	 
	 
	 /**
	  * 获取列表
	  */
	  public function queryByListForDorpdown(){
	     $m = M('snappedup_cats');
	     $sql="select * from __PREFIX__snappedup_cats ";
		 $rs = $m->query($sql);
		 return $rs;
	  }
	 
	 
	 /**
	  * 删除
	  */
//	 public function catsdel(){
//	    $rd = array('status'=>-1);
//	 	$m = M('snappedup_cats');
//		$rs = $m->delete((int)I('id'));
//		if($rs){
//		   $rd['status']= 1;
//		}
//		else
//		{
////			$rd['error']=$m->
//		}
//		return $rd;
//	 }
	 
	 /**
	  * 显示分类是否显示/隐藏
	  */
	 public function editiIsShow(){
	 	$rd = array('status'=>-1);
	 	$id = (int)I("id",0);
		$data = array();
		$data["isShow"] = (int)I("isShow",0);
	    if($this->checkEmpty($data)){
	    	$m = M('snappedup_cats');
			$rs = $m->where("SUCatsId=".(int)I('id'))->save($data);
			if(false !== $rs){
				$rd['status']= 1;
			}
		}
		return $rd;
	}
};
?>