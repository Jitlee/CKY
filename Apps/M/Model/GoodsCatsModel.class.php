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
			'parentId'	=> $parentId
			'isShow'		=> '1'
		);
		return $this->where($map)->order('catSort')->select();
	}
};
?>