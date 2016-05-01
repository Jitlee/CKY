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
     public function get($id = 0){
     	$m = M('mall_life');
     	if($id == 0) {
     		$id = (int)I('id');
     	}
		return $m->find($id);
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
	 
	public function getTopOne() {
		$m = M('mall_life');
		$sql = "select lifeid, lifeTitle, logothums from cky_mall_life where now() between efficacysdate and efficacyedate order by sort desc, lifeId desc limit 0,2";			  
		return $m->query($sql);
	}
	 
};
?>