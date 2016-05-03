<?php
 namespace Admin\Model;
/**
 * ============================================================================
 * 粗卡云:
  
 * 联系方式:
 * ============================================================================
 * 商品服务类
 */
class MallLifeGoodsModel extends BaseModel {
    
	 
	 
	 /**
	  * 分页列表-商场活动，分类查询[获取已审核列表]
	  */
     public function queryByPageForLife(){
        $m = M('mall_lifegoods');
        $shopName = I('shopName');
     	$goodsName = I('goodsName');

     	$lifeid	= I('lifeid',0);
	 	$sql = "select g.*,gc.catName,sc.catName shopCatName,p.shopName
	 		,ag.lifegoodsid,ag.lifecontent,ag.sort,ag.lifetitle
		 from __PREFIX__goods g
	 		  inner join __PREFIX__mall_lifegoods ag on ag.goodsid=g.goodsid	 		
	 	      left join  __PREFIX__goods_cats gc on g.goodsCatId3=gc.catId 
	 	      left join  __PREFIX__shops_cats sc on sc.catId=g.shopCatId2,__PREFIX__shops p 
	 	      where goodsStatus=1 and goodsFlag=1 and p.shopId=g.shopId and g.isSale=1   and g.goodsCatId1=3  and ag.lifeid=$lifeid ";

	 	if($shopName!='')$sql.=" and (p.shopName like '%".$shopName."%' or p.shopSn like '%".$shopName."%')";
	 	if($goodsName!='')$sql.=" and (g.goodsName like '%".$goodsName."%' or g.goodsSn like '%".$goodsName."%')";
	 	$sql.="  order by ag.sort desc";   
		return $m->pageQuery($sql);
	 }
	 
	 /**
	  * 惠生活-选择商品
	  */
     public function queryByPageForLifeAddGoods($goodsCatId2){
     	$lifeid	= I('lifeid',0);
        $m = M('mall_lifegoods');
        $shopName = I('shopName');
     	$goodsName = I('goodsName');
     	 
     	$goodsCatId1 = (int)I('goodsCatId1',0);
     	$goodsCatId2 = (int)I('goodsCatId2',0);
	 	$sql = "select g.*,gc.catName,sc.catName shopCatName,p.shopName from __PREFIX__goods g 
	 		  left join __PREFIX__mall_lifegoods ag on ag.goodsid=g.goodsid  and ag.lifeid=$lifeid
	 	      left join __PREFIX__goods_cats gc on g.goodsCatId3=gc.catId 
	 	      left join __PREFIX__shops_cats sc on sc.catId=g.shopCatId2,__PREFIX__shops p 
	 	      where goodsStatus=1 and goodsFlag=1 and p.shopId=g.shopId and g.isSale=1  and g.goodsCatId1=3  and ISNULL(ag.lifegoodsid)";
	 	

//	 	if($goodsCatId2>0)$sql.=" and g.goodsCatId2=".$goodsCatId2;

	 	if($shopName!='')$sql.=" and (p.shopName like '%".$shopName."%' or p.shopSn like '%".$shopName."%')";
	 	if($goodsName!='')$sql.=" and (g.goodsName like '%".$goodsName."%' or g.goodsSn like '%".$goodsName."%')";
	 	$sql.="  order by goodsId desc";   
		//$m->exec
		return $m->pageQuery($sql);
	 }

	 /**
	  * 新增
	  */
	 public function insert(){
	 	$rd = array('status'=>-1);
	 	 
		$data = array();
		$data["lifeid"] = (int)I("lifeid");
		$data["goodsid"] = (int)I("goodsid");
		$data["sort"] = (int)I("sort");
		$data["lifecontent"] = I("lifecontent");
		$data["lifetitle"] = I("lifetitle");
		
		$data["createtime"] = date('Y-m-d H:i:s');
		$staff = session('RTC_STAFF');
		$data["createuser"] = $staff["staffId"];
		 
		$m = M('mall_lifegoods');
		$rs = $m->add($data);
		if(false !== $rs){
			$rd['status']= 1;
		}
		return $rd;
	 } 
     /**
	  * 修改
	  */
	 public function edit(){
	 	$rd = array('status'=>-1);
	 	$id = (int)I("lifegoodsid",0);
		
	 	$m = M('mall_lifegoods');
		$m->goodsid = (int)I("goodsid");
		$m->lifecontent =I("lifecontent");
		$m->lifetitle 	= I("lifetitle");
		$m->sort = (int)I("sort");
	    if($this->checkEmpty($data)){
			$rs = $m->where("lifegoodsid=".$id)->save();
			if(false !== $rs){
				$rd['status']= 1;
			}
		}
		return $rd;
	 } 
	 /**
	  * 获取指定对象
	  */
     public function get(){
	 	$m = M('mall_lifegoods');
		$id=(int)I('id');
		$sql = "select  ag.*,g.goodsName from __PREFIX__goods g inner join __PREFIX__mall_lifegoods ag on ag.goodsid=g.goodsid 
 	      where  ag.lifegoodsid=$id";			  
		return $m->query($sql)[0];
	 }
	   
	 /**
	  * 删除
	  */
	 public function lifeGoodsDel(){
	 	$rd = array('status'=>-1);
	    $m = M('mall_lifegoods');
	    $rs = $m->delete((int)I('id'));
		if(false !== $rs){
		   $rd['status']= 1;
		}
		return $rd;
	 }
	  
};
?>