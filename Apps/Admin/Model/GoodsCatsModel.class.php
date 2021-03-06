<?php
namespace Admin\Model;
/**
 * ============================================================================
 * 粗卡云:
  
 * 联系方式:
 * ============================================================================
 * 商品分类服务类
 */
class GoodsCatsModel extends BaseModel {
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
	   * 根据上级key查询子级分类
	   */
	public function queryByParentkey($key) {
		$m = M('goods_cats');
		$sql = "select gc.catId,gc.catName,gc.catKey from __PREFIX__goods_cats gp
		left join  __PREFIX__goods_cats gc on gc.parentId=gp.catId
		where gc.catFlag=1  and  gp.catkey='".$key."' order by gc.catSort asc";
		//echo $sql;
	  	$rs1 = $m->query($sql);
		return $rs1;
	}
	public function queryBykey($key) {
		$m = M('goods_cats');
		$sql = "select gp.* from __PREFIX__goods_cats gp where gp.catFlag=1  and  gp.catkey='$key'";
		//echo $sql;
	  	$rs1 = $m->query($sql);
		return $rs1;
	}
	  
	  public function queryByListforshops(){
	     $m = M('goods_cats');
	     $rs = $m->where('catFlag=1 and showinshop=1 and parentId=0')->select(); 
		 return $rs;
	  }

	  /**
	   * 获取树形分类
	   */
	  public function getCatAndChild(){
	  	  $m = M('goods_cats');
	  	  //获取第一级分类
	  	  $sql = "select * from __PREFIX__goods_cats where catFlag=1 and parentId =0  and  (catkey is null or catkey!='mall') order by catSort asc";
	  	  $rs1 = $m->query($sql);
	  	  if(count($rs1)>0){
	  	  	 $ids = array();
	  	  	 foreach ($rs1 as $key =>$v){
	  	  	 	$ids[] = $v['catId'];
	  	  	 }
	  	  	 //获取第二级分类
	  	     $sql = "select * from __PREFIX__goods_cats where catFlag=1 and parentId in (".implode(',',$ids).")  order by catSort asc";
	  	     $rs2 = $m->query($sql);
	  	     if(count($rs2)>0){
	  	     	 $ids = array();
		  	  	 foreach ($rs2 as $key =>$v){
		  	  	 	$ids[] = $v['catId'];
		  	  	 }
		  	  	 //获取第三级分类
		  	     $sql = "select * from __PREFIX__goods_cats where catFlag=1 and parentId in (".implode(',',$ids).")  order by catSort asc";
		  	     $rs3 = $m->query($sql);
		  	     $tmpArr = array();
		  	     if(count($rs3)>0){
			  	     foreach ($rs3 as $key =>$v){
			  	  	 	$tmpArr[$v['parentId']][] = $v;
			  	  	 }
		  	     }
		  	     //把第三级归类到第二级下
		  	     foreach ($rs2 as $key =>$v){
		  	     	$rs2[$key]['child'] = $tmpArr[$v['catId']];
		  	     	$rs2[$key]['childNum'] = count($tmpArr[$v['catId']]);
		  	     }
		  	     $tmpArr = array();
	  	         foreach ($rs2 as $key =>$v){
			  	  	 $tmpArr[$v['parentId']][] = $v;
			  	 }
		  	 }
		  	 //把二季归类到第一级下
	  	     foreach ($rs1 as $key =>$v){
	  	  	 	$rs1[$key]['child'] = $tmpArr[$v['catId']];
	  	  	 	$rs1[$key]['childNum'] = count($tmpArr[$v['catId']]);
	  	  	 }
	  	  }
	  	  return $rs1;
	  }

	   /**
	   * 获取树形分类
	   */
	  public function getCatAndChildBykey($key){
	  	  $m = M('goods_cats');
	  	  //获取第一级分类
	  	  $sql = "select * from __PREFIX__goods_cats gp where gp.catFlag=1 and gp.parentId =0   and  gp.catkey='$key' order by gp.catSort asc";
	  	  $rs1 = $m->query($sql);
	  	  if(count($rs1)>0){
	  	  	 $ids = array();
	  	  	 foreach ($rs1 as $key =>$v){
	  	  	 	$ids[] = $v['catId'];
	  	  	 }
	  	  	 //获取第二级分类
	  	     $sql = "select * from __PREFIX__goods_cats where catFlag=1 and parentId in (".implode(',',$ids).")  order by catSort asc";
	  	     $rs2 = $m->query($sql);
	  	     if(count($rs2)>0){
	  	     	 $ids = array();
		  	  	 foreach ($rs2 as $key =>$v){
		  	  	 	$ids[] = $v['catId'];
		  	  	 }
		  	  	 //获取第三级分类
		  	     $sql = "select * from __PREFIX__goods_cats where catFlag=1 and parentId in (".implode(',',$ids).")  order by catSort asc";
		  	     $rs3 = $m->query($sql);
		  	     $tmpArr = array();
		  	     if(count($rs3)>0){
			  	     foreach ($rs3 as $key =>$v){
			  	  	 	$tmpArr[$v['parentId']][] = $v;
			  	  	 }
		  	     }
		  	     //把第三级归类到第二级下
		  	     foreach ($rs2 as $key =>$v){
		  	     	$rs2[$key]['child'] = $tmpArr[$v['catId']];
		  	     	$rs2[$key]['childNum'] = count($tmpArr[$v['catId']]);
		  	     }
		  	     $tmpArr = array();
	  	         foreach ($rs2 as $key =>$v){
			  	  	 $tmpArr[$v['parentId']][] = $v;
			  	 }
		  	 }
		  	 //把二季归类到第一级下
	  	     foreach ($rs1 as $key =>$v){
	  	  	 	$rs1[$key]['child'] = $tmpArr[$v['catId']];
	  	  	 	$rs1[$key]['childNum'] = count($tmpArr[$v['catId']]);
	  	  	 }
	  	  }
	  	  return $rs1;
	  }

	 /**
	  * 迭代获取下级
	  */
	 public function getChild($ids = array(),$pids = array()){
	 	$m = M('goods_cats');
	 	$sql = "select catId from __PREFIX__goods_cats where catFlag=1 and parentId in(".implode(',',$pids).")";
	 	$rs = $m->query($sql);
	 	if(count($rs)>0){
	 		$cids = array();
		 	foreach ($rs as $key =>$v){
		 		$cids[] = $v['catId'];
		 	}
		 	$ids = array_merge($ids,$cids);
		 	return $this->getChild($ids,$cids);
		 	
	 	}else{
	 		return $ids;
	 	}
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