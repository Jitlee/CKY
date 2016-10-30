<?php
 namespace Admin\Model;
/**
 * ============================================================================
 * 粗卡云:
  
 * 联系方式:
 * ============================================================================
 * 商品服务类
 */
class SnappedupCatsActivityGoodsModel extends BaseModel {
    
	 
	 public function queryByPageGoods(){
        $m = M('goods'); 
     	$goodsName = I('goodsName');
     	$SUCatsActivityId = I('id');
	 	$sql = "
	 		select 
	 				g.*,gc.catName,snup.subtitle,snup.xiangoutype,snup.xiangou,snup.limituseshopId,snup.ticketId
		 	from  
				cky_snappedup_catsactivity_goods ag
			left join cky_goods g on g.goodsId= ag.goodsId
			left join cky_goods_cats gc on g.goodsCatId2=gc.catId			
			inner join cky_snappedup snup on snup.goodsId=g.goodsId 
			where 
				goodsFlag=1  
				and ag.SUCatsActivityId=$SUCatsActivityId			
";
			 
	 	if($goodsName!='')$sql.=" and (g.goodsName like '%".$goodsName."%' or g.goodsSn like '%".$goodsName."%')";
	 	$sql.="  order by goodsId desc";   
		return $m->pageQuery($sql);
	 }
	 
	 
	 /**
	  * 秒杀活动，查询商品
	  */
     public function queryByPageForActivity(){
        $m = M('goods'); 
     	$goodsName = I('goodsName');
     	$SUCatsActivityId = I('id');
	 	$sql = "select 
	 				g.*,gc.catName,snup.subtitle,snup.xiangoutype,snup.xiangou,snup.limituseshopId,snup.ticketId
		 	from __PREFIX__goods g 
			left join __PREFIX__goods_cats gc on g.goodsCatId2=gc.catId			
			inner join __PREFIX__snappedup snup on snup.goodsId=g.goodsId 
			where 
				goodsFlag=1  
				and g.goodsid not in(select img.goodsId from __PREFIX__snappedup_catsactivity_goods img where SUCatsActivityId =$SUCatsActivityId )				
";
			 
	 	if($goodsName!='')$sql.=" and (g.goodsName like '%".$goodsName."%' or g.goodsSn like '%".$goodsName."%')";
	 	$sql.="  order by goodsId desc";   
		return $m->pageQuery($sql);
	 }
	 
	 
	 
 
	 /**
	  * 向活动中添加商品
	  */
	 public function addGoods(){
	 	$rd = array('status'=>-1);
	 	$m = M('snappedup_catsactivity_goods');
		$id = I('id',0);
	 	$SUCatsActivityId	= I('SUCatsActivityId',0);
	 	$goodsIds= I('goodsIds');
		$sql="
	INSERT INTO  __PREFIX__snappedup_catsactivity_goods  (SUCatsActivityId,goodsId,addtime)
	select 
		$SUCatsActivityId,g.goodsId,now() 
	from 
		__PREFIX__goods g 
	inner join __PREFIX__snappedup snup on snup.goodsId=g.goodsId 
	where 
		goodsFlag=1
		and g.goodsid in($goodsIds)  
		and g.goodsid not in(select img.goodsId from __PREFIX__snappedup_catsactivity_goods img where SUCatsActivityId =$SUCatsActivityId )
";
		//echo $sql;
		
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
	    $m = M('snappedup_catsactivity_goods');
	    $rs = $m->delete((int)I('id'));
		if(false !== $rs){
		   $rd['status']= 1;
		}
		return $rd;
	 }
	  
};
?>