<?php
 namespace Admin\Model;
/**
 * ============================================================================
 * 粗卡云:
  
 * 联系方式:
 * ============================================================================
 * 商品服务类
 */
class MallActivityGoodsModel extends BaseModel {
    
	 
	 
	 /**
	  * 分页列表-商场活动，分类查询[获取已审核列表]
	  */
     public function queryByPageForActivityClass(){
        $m = M('goods');
        $shopName = I('shopName');
     	$goodsName = I('goodsName');


	 	$sql = "select g.*,gc.catName,sc.catName shopCatName,p.shopName
	 		,ag.actgoodsid,ma.mactid,ma.mactname,mam.mactmid,mam.mactmname
		 from __PREFIX__goods g
	 		  inner join __PREFIX__mall_activitygoods ag on ag.goodsid=g.goodsid 
	 		  inner join __PREFIX__mall_activity  ma on ma.mactid=ag.mactid
	 		  inner join __PREFIX__mall_activitym mam on ag.mactmid = mam.mactmid
	 	      left join  __PREFIX__goods_cats gc on g.goodsCatId3=gc.catId 
	 	      left join  __PREFIX__shops_cats sc on sc.catId=g.shopCatId2,__PREFIX__shops p 
	 	      where goodsStatus=1 and goodsFlag=1 and p.shopId=g.shopId and g.isSale=1";

	 	if($shopName!='')$sql.=" and (p.shopName like '%".$shopName."%' or p.shopSn like '%".$shopName."%')";
	 	if($goodsName!='')$sql.=" and (g.goodsName like '%".$goodsName."%' or g.goodsSn like '%".$goodsName."%')";
	 	$sql.="  order by mam.mactmid desc";   
		return $m->pageQuery($sql);
	 }
	 
	 /**
	  * 分页列表[获取已审核列表]
	  */
     public function queryByPageForAddGoods(){
     	$mactid	= I('mactid',0);
        $m = M('goods');
        $shopName = I('shopName');
     	$goodsName = I('goodsName');
     	$areaId1 = (int)I('areaId1',0);
     	$areaId2 = (int)I('areaId2',0);
     	$goodsCatId1 = (int)I('goodsCatId1',0);
     	$goodsCatId2 = (int)I('goodsCatId2',0);
     	$goodsCatId3 = (int)I('goodsCatId3',0);
     	$isAdminBest = (int)I('isAdminBest',-1);
     	$isAdminRecom = (int)I('isAdminRecom',-1);
	 	$sql = "select g.*,gc.catName,sc.catName shopCatName,p.shopName from __PREFIX__goods g 
	 	left join cky_mall_activitygoods ag on ag.goodsid=g.goodsid  and ag.mactid=$mactid
	 	      left join __PREFIX__goods_cats gc on g.goodsCatId3=gc.catId 
	 	      left join __PREFIX__shops_cats sc on sc.catId=g.shopCatId2,__PREFIX__shops p 
	 	      where goodsStatus=1 and goodsFlag=1 and p.shopId=g.shopId and g.isSale=1 and ISNULL(ag.actgoodsid)";
	 	

//	 	if($goodsCatId2>0)$sql.=" and g.goodsCatId2=".$goodsCatId2;
	 	
	 	
	 	if($shopName!='')$sql.=" and (p.shopName like '%".$shopName."%' or p.shopSn like '%".$shopName."%')";
	 	if($goodsName!='')$sql.=" and (g.goodsName like '%".$goodsName."%' or g.goodsSn like '%".$goodsName."%')";
	 	$sql.="  order by goodsId desc";   
		//$m->exec
		return $m->pageQuery($sql);
	 }

	 /**
	  * 向活动中添加商品
	  */
	 public function addGoods(){
	 	$rd = array('status'=>-1);
	 	$m = M('mall_activitygoods');
		$id = I('id',0);
	 	$mactid	= I('mactid',0);
	 	$mactmid= I('mactmid',0);
		$sql="
	INSERT INTO  __PREFIX__mall_activitygoods  (mactid,mactmid,goodsId,applytime)
	select 
		$mactid,$mactmid,goodsId,now() 
	from 
		cky_goods g 
	where 
		g.goodsStatus=1 and g.goodsFlag=1  and g.isSale=1
		and g.goodsid in($id)  
		and goodsid not in(select img.goodsid from cky_mall_activitygoods img where  img.mactid=$mactid)
";
		
		$rs = $m->execute($sql);
		if(false !== $rs){
			$rd['status'] = 1;
		}
		return $rd;
	 }
	  
	 /**
	  * 删除
	  */
	 public function activityGoodsDel(){
	 	$rd = array('status'=>-1);
	    $m = M('mall_activitygoods');
	    $rs = $m->delete((int)I('id'));
		if(false !== $rs){
		   $rd['status']= 1;
		}
		return $rd;
	 }
	  
};
?>