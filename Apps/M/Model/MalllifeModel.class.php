<?php
 namespace M\Model;
/**
 * ============================================================================
 * 粗卡云:
  
 * 联系方式:
 * ============================================================================
 * 角色服务类
 */
class MalllifeModel extends BaseModel {
     
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
     public function lifePage(){
        $pageSize = 5;
		$pageNo = intval(I('pageNo', 1));
		$time= strftime("%Y-%m-%d");
		$map = array(
			'status'				=> 1,
			'efficacysdate'		=> array('ELT', $time),
			'efficacyedate'		=> array('EGT', $time),
		);
		$m = M('mall_life');
		return $m->field('lifeid, lifetitle, logothums') ->order('sort,createtime desc') ->where($map)->page($pageNo, $pageSize)->select();
	 }
	 
	 public function getgoods(){
	 	$m = M('mall_lifegoods');
		$id=(int)I('id');
		$sql = "select  ag.*,g.goodsthums,g.shopprice,g.goodsname from __PREFIX__goods g inner join __PREFIX__mall_lifegoods ag on ag.goodsid=g.goodsid 
 	      where  ag.lifeid=$id";			  
		return $m->query($sql);
	 }
	 
};
?>