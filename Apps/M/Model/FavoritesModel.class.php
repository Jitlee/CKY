<?php
 namespace M\Model;
/**
 * ============================================================================
 * 粗卡云:
  
 * 联系方式:
 * ============================================================================
 * 收藏服务类
 */
class FavoritesModel extends BaseModel {
   	public function insert($uid) {
   		$data = array();
		$data['userId'] = $uid;
		$data['favoriteType'] = (int)I('favoriteType', 0);
		$data['targetType'] = (int)I('targetType', 0);
		$data['targetId'] = (int)I('targetId', 0);
		return $this->add($data);
   	}
	
	public function del($uid) {
		$id = (int)I('id', 0);
		$map = array();
		$map['userId'] = $uid;
		$map['favoriteId'] = $id
		return $this->where($map)->delete();		
	}
	
	public function lst($uid) {
		$pageSize = (int)I('pageSize', 20);
		$pageNo = intval(I('pageNo', 1));
		$favoriteType = (int)I('favoriteType', 0);
		
		$map = array();
		$map['userId'] = $uid;
		$map['favoriteType'] = $favoriteType;
		$order = "f.favoriteId desc";
		if($favoriteType == 1) { // 商品
			$field .= "f.favoriteId, f.favoriteType, f.targetId, g.goodsName, g.goodsThums, g.shopPrice, g.goodsUnit, gc.catName, f.createTime";
			$list = $this->$field($field)
				->join("f inner join __GOODS__ g on f.targetId = g.goodsId")
				->join("inner join __GOODS_CATS__ gc on gc.catId = g.goodsCatId2")
				->where($map)->order($order)->page($pageNo, $pageSize)->select();
		} else if($favoriteType == 2) { // 商家
			$field .= "f.favoriteId, f.favoriteType, f.targetId, f.targetType, s.shopName, replace(s.shopImg, '.', '_thumb.') shopImg, f.createTime";
			$list = $this->$field($field)
				->join("f inner join __SHOPS__ s on f.targetId = s.shopId")
				->where($map)->order($order)->page($pageNo, $pageSize)->select();
		}
	}
};
?>