<?php
 namespace M\Model;
/**
 * ============================================================================
 * 粗卡云:
  
 * 联系方式:
 * ============================================================================
 * 行业服务类
 */
class GoodsCatsModel extends BaseModel {
	public function query() {
		$parentId = I('parentId', 0);
		$map = array(
			'parentId'	=> $parentId,
			'isShow'		=> '1',
		);
		return $this->where($map)->order('catSort')->select();
	}
	
	public function queryByParentkey($key) {
		$m = M('goods_cats');
		$sql = "select gc.catId,gc.catName,gc.catKey from __PREFIX__goods_cats gp
		left join  __PREFIX__goods_cats gc on gc.parentId=gp.catId
		where gc.catFlag=1  and  gp.catkey='".$key."' order by gc.catSort asc";
		//echo $sql;
	  	$rs1 = $m->query($sql);
		return $rs1;
	}
};
?>