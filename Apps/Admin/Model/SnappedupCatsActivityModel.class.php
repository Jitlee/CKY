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
		$data["catName"] = I("catName");
		if($this->checkEmpty($data,true)){
			$data["catIcon"] = I("catIcon");
			$data["parentId"] = I("parentId",0);
		    $data["isShow"] = (int)I("isShow",0);
			$data["catSort"] = (int)I("catSort",0);
			$data["catFlag"] = 1;;
			$m = M('goods_cats');
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
		$data["catName"] = I("catName");
	    if($this->checkEmpty($data)){
			$data["catIcon"] = I("catIcon");
	    	$data["isShow"] = (int)I("isShow",0);
	    	$data["catSort"] = (int)I("catSort",0);
	    	$m = M('goods_cats');
			$rs = $m->where("catFlag=1 and catId=".(int)I('id'))->save($data);
			if(false !== $rs){
				if ($data['isShow'] == 0) {//修改子栏目是否隐藏
					$this->editiIsShow();
				}
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
		$data["catName"] = I("catName");
	    if($this->checkEmpty($data)){
	    	$m = M('goods_cats');
			$rs = $m->where("catFlag=1 and catId=".(int)I('id'))->save($data);
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
	 	$m = M('goods_cats');
		return $m->where("catId=".(int)$id)->find();
	 }
	 /**
	  * 获取列表
	  */
	  public function queryByList($pid = 0){
	     $m = M('goods_cats');
	     $rs = $m->where('catFlag=1 and parentId='.(int)$pid)->select(); 
		 return $rs;
	  }
	 
	 
	 /**
	  * 删除
	  */
	 public function del(){
	 	$rd = array('status'=>-1);
	 	//获取子集
	 	$ids = array();
		$ids[] = (int)I('id');
	 	$ids = $this->getChild($ids,$ids);
	 	$m = M('goods_cats');
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
	 	if(I('id',0)==0)return $rd;
	 	$isShow = (int)I('isShow');
	 	//获取子集
	 	$ids = array();
		$ids[] = (int)I('id');
	 	$ids = $this->getChild($ids,$ids);
	 	$m = M('goods_cats');
	 	if($isShow!=1){
	 		//把相关的商品下架了
		 	$sql = "update __PREFIX__goods set isSale=0 where goodsCatId1 in(".implode(',',$ids).")";
		 	$m->execute($sql);
		 	$sql = "update __PREFIX__goods set isSale=0 where goodsCatId2 in(".implode(',',$ids).")";
		 	$m->execute($sql);
		 	$sql = "update __PREFIX__goods set isSale=0 where goodsCatId3 in(".implode(',',$ids).")";
		 	$m->execute($sql);
	 	}
	 	$m->isShow = ($isShow==1)?1:0;
	 	$rs = $m->where("catId in(".implode(',',$ids).")")->save();
	    if(false !== $rs){
			$rd['status']= 1;
		}
	 	return $rd;
	 }
};
?>