<?php
 namespace M\Model;
/**
 * ============================================================================
 * 粗卡云:
 * ============================================================================
 * 秒杀-实体类
 */
class SnappedUpModel extends BaseModel {
     
	 
	 /**
	  * 获取最新的活动名称、活动节点 
	  */
     public function GetMostSnappedUpCats(){        
		$sql="
			select 
				sc.SUCatsId,cat.CName
			from 
				cky_snappedup_catsactivity sc
			inner join cky_snappedup_cats cat on cat.SUCatsId=sc.SUCatsId
			where
				cat.isShow=1 and sc.isShow=1
				AND SYSDATE() BETWEEN DATE_ADD(CAdATE,INTERVAL StartTime Hour) AND DATE_ADD(CAdATE,INTERVAL StartTime+ActivityLen+1 Hour) 
			order by 
				DATE_ADD(CAdATE,INTERVAL StartTime Hour) 
			 limit 1
		 ";
//		 echo $sql;
//		 $m = M('snappedup_catsactivity_goods');
		return $this->query($sql)[0];
	 }
	 
	 
	 
	 /**
	  * 根据活动节点查询商品
	  * */
//	 public function querySUCatsActivityGoods($SUCatsActivityId) {
//		$pageNo = 1;
//		$pageSize = 20;
//		$map = array(
//			"g.goodsFlag"=>1
//			,"ag.SUCatsActivityId"=>$SUCatsActivityId
//		);
//		$m = M('snappedup_catsactivity_goods');
//		$list = $m->field('g.goodsId, g.goodsSn, g.goodsName, g.goodsStock, g.goodsThums, g.marketPrice, g.shopPrice, g.goodsUnit, g.saleCount, g.shopCatId1, g.goodsSpec')
//				->join('ag inner join cky_goods g on g.goodsId= ag.goodsId')
//				->join('cky_snappedup snup on snup.goodsId=g.goodsId ')			
//				->where($map)
//				->order('g.goodsId')
//				->page($pageNo, $pageSize)->select();
//
//		return $list;
//	}
	
	
	/**
	  * 根据活动获取活动节点 
	  */
     public function GetActivity($SUCatsIdId){        
		$sql="
			select 
				sc.SUCatsId,cat.CName,sc.*
				,case when SYSDATE() <DATE_ADD(CAdATE,INTERVAL StartTime Hour) then  -1  -- 未开始
				 when SYSDATE() >DATE_ADD(CAdATE,INTERVAL StartTime+ActivityLen Hour) then  2		-- 结束
				 when SYSDATE() >DATE_ADD(CAdATE,INTERVAL StartTime Hour) and SYSDATE() <DATE_ADD(CAdATE,INTERVAL StartTime+ActivityLen Hour) then 1	-- 进行中				
				 end  timestatus  
				,unix_timestamp(DATE_ADD(CAdATE,INTERVAL StartTime+ActivityLen Hour))*1000 endTime
			from 
				cky_snappedup_catsactivity sc
			inner join cky_snappedup_cats cat on cat.SUCatsId=sc.SUCatsId
			where
				cat.isShow=1 and sc.isShow=1
				AND cat.SUCatsId=$SUCatsIdId
			order by 
				DATE_ADD(CAdATE,INTERVAL StartTime Hour)  asc
		 ";
//		 echo $sql;
		$rs = $this->query($sql);
		return $rs;
	 }
	 
     public function GetGoodsTop($SUCatsId){        
		$sql="
SELECT	 
	*
FROM(
	SELECT		
		heyf_tmp.*,			
		IF (
			@pdept = heyf_tmp.SUCatsActivityId ,@rank :=@rank + 1 ,@rank := 1
		) AS rank,
		@pdept := heyf_tmp.SUCatsActivityId
	FROM
	(
			SELECT 
				ga.SUCatsId,ag.SUCatsActivityId
				,g.goodsId,g.goodsSn,g.goodsName,g.goodsStock,g.saleCount,g.goodsThums,g.marketPrice,g.shopPrice,g.goodsUnit,g.shopCatId1,g.goodsSpec
				,floor((g.saleCount/g.goodsStock) *100) 'salerate'
			FROM 
				`cky_snappedup_catsactivity_goods` ag
			inner join cky_snappedup_catsactivity ga on ga.SUCatsActivityId= ag.SUCatsActivityId 
			inner join cky_goods g on g.goodsId= ag.goodsId 
			INNER JOIN cky_snappedup snup on snup.goodsId=g.goodsId 
			WHERE 1=1
				 and g.goodsFlag = 1 
				 AND ga.SUCatsId = $SUCatsId
			ORDER BY ag.SUCatsActivityId,g.goodsId asc
			LIMIT 0,200
	) 
	heyf_tmp,
	(
		SELECT @pdept := NULL ,@rank := 0
	) a
) result
where rank <5
		 ";
//		 echo $sql;
		$rs = $this->query($sql);
		return $rs;
	 }
	 
	 
	/****
	 * 根据活动节点，查询商品
	 * ****/
 public function queryActivityGoods() {
 		$ActivityId	=I("ActivityId",0);
 		$goodsId	=I("goodsId",0);
 		$pageSize	=I("pageSize",10);
		$sql=" 
			SELECT 
				ga.SUCatsId,ag.SUCatsActivityId,
				g.goodsId,g.goodsSn,g.goodsName,g.goodsStock,g.goodsThums,g.marketPrice,g.shopPrice,g.goodsUnit,g.saleCount,g.shopCatId1,g.goodsSpec
				,floor((g.saleCount/g.goodsStock) *100) 'salerate'
				,case when SYSDATE() <DATE_ADD(CAdATE,INTERVAL StartTime Hour) then  -1  -- 未开始
				 when SYSDATE() >DATE_ADD(CAdATE,INTERVAL StartTime+ActivityLen Hour) then  2		-- 结束
				 when SYSDATE() >DATE_ADD(CAdATE,INTERVAL StartTime Hour) and SYSDATE() <DATE_ADD(CAdATE,INTERVAL StartTime+ActivityLen Hour) then 1	-- 进行中				
				 end  timestatus   
			FROM 
				`cky_snappedup_catsactivity_goods` ag
			inner join cky_snappedup_catsactivity ga on ga.SUCatsActivityId= ag.SUCatsActivityId 
			inner join cky_goods g on g.goodsId= ag.goodsId 
			INNER JOIN cky_snappedup snup on snup.goodsId=g.goodsId 
			WHERE 1=1
				 and g.goodsFlag = 1 
				 AND ag.SUCatsActivityId = $ActivityId
				 and g.goodsId>$goodsId
			ORDER BY ag.SUCatsActivityId,g.goodsId asc
			LIMIT 0,$pageSize
 
		 ";
		$rs = $this->query($sql);
//		echo $this->getLastSql();
		return $rs;
	}
	 
	 
	 public function detail() {
	 	$m=M('goods');
		$goodsId = I('id');
		$field = 'g.goodsId, g.shopId, goodsSn, goodsName, shopCatId1, g.goodsCatId1, goodsImg, goodsThums, shopPrice,marketPrice, goodsStock, saleCount, goodsDesc,goodsSpec, shopName, deliveryStartMoney, deliveryFreeMoney, deliveryMoney, deliveryCostTime, serviceStartTime, serviceEndTime'
			.", cg.subtitle,cg.xiangoutype,cg.xiangou,cg.limituseshopId,cg.ticketId,cg.buyinfo";
		$join = 'g left join __SHOPS__ s on s.shopId = g.shopId';
		$leftJoin1 = 'left join __MALL_ACTIVITYGOODS__ mag on mag.goodsId = g.goodsId';
		$leftJoin2 = 'left join cky_snappedup cg on cg.goodsId = g.goodsId';
		$data = $m->field($field)->join($join)->join($leftJoin1)->join($leftJoin2)->where(array('g.goodsId'=> $goodsId))->find();
	
//		echo $m->getLastSql();
		return $data;
	}
	 
}