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
	  * 修改名称
	  */
	 public function editName(){
	 	$rd = array('status'=>-1);
	 	$id = (int)I("id",0);
		$data = array();
		$data["CName"] = I("CName");
	    if($this->checkEmpty($data)){
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
     public function catsget($id){
	 	$m = M('snappedup_cats');
		return $m->where("SUCatsId=".(int)$id)->find();
	 }
	 /**
	  * 获取列表
	  */
	  public function queryByList(){
	     $m = M('snappedup_cats');
	     $rs = $m->select(); 
		 return $rs;
	  }
	 
	 
	 /**
	  * 删除
	  */
	 public function catsdel(){
	 	$rd = array('status'=>-1);
	 	//获取子集
	 	$ids = array();
		$ids[] = (int)I('id');
	 	$ids = $this->getChild($ids,$ids);
	 	$m = M('snappedup_cats');
	 	//把相关的商品下架了
	 	$sql = "update __PREFIX__goods set isSale=0 where goodsCatId1 in(".implode(',',$ids).")";
	 	$m->execute($sql);
	 	$sql = "update __PREFIX__goods set isSale=0 where goodsCatId2 in(".implode(',',$ids).")";
	 	$m->execute($sql);
	 	$sql = "update __PREFIX__goods set isSale=0 where goodsCatId3 in(".implode(',',$ids).")";
	 	$m->execute($sql);
	 	//设置商品分类为删除状态
	 	$m->catFlag = -1;
		$rs = $m->where(" catId in(".implode(',',$ids).")")->save();
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