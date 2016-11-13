<?php
 namespace M\Model;
/**
 * ============================================================================
 * 粗卡云:
  
 * 联系方式:
 * ============================================================================
 * 品牌服务类
 */
class ShopsSubModel extends BaseModel {
	
	 
	/**
	  * 获取列表
	  */
	public function queryByList($shopId){
	     $m = M('shopssub');
		 $rs = $m->where("shopId=$shopId")->select();
		 return $rs;
	}
	
}