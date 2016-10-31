<?php
 namespace Admin\Model;
/**
 * ============================================================================
 * 粗卡云:  
 * 联系方式:
 * ============================================================================
 * 抢购
 */
class GoodsGroupModel extends BaseModel {
	
	/**
	  * 分页列表[获取已审核列表]
	  */
     public function queryByPage(){
        $m = M('goods'); 
     	$goodsName = I('goodsName');     	 
	 	$sql = "select 
	 				g.*,gc.catName,snup.subtitle,snup.xiangoutype,snup.xiangou,snup.limituseshopId,snup.ticketId,snup.buyinfo
		 	from __PREFIX__goods g 
			left join __PREFIX__goods_cats gc on g.goodsCatId2=gc.catId			
			inner join __PREFIX__snappedup snup on snup.goodsId=g.goodsId 
			where goodsFlag=1  ";
			 
	 	if($goodsName!='')$sql.=" and (g.goodsName like '%".$goodsName."%' or g.goodsSn like '%".$goodsName."%')";
	 	$sql.="  order by goodsId desc";   
		return $m->pageQuery($sql);
	 }
 
	 
	 public function get(){
	 	$m = M('goods');
	 	$id = (int)I('id',0); 
		
		$sql = "select 
				g.*,gc.catName,snup.subtitle,snup.xiangoutype,snup.xiangou,snup.limituseshopId,snup.ticketId,snup.snappedupId
		 	from 
		 		__PREFIX__goods g 
			left join __PREFIX__goods_cats gc on g.goodsCatId2=gc.catId 			
			inner join __PREFIX__snappedup snup on snup.goodsId=g.goodsId  
			where g.goodsId=$id  order by goodsId desc";   
		$list = $m->query($sql);
		$goods = $list[0]; 
//		echo dump($list);
		
		$m = M('goods_gallerys');
		$goods['gallery'] = $m->where('goodsId='.$id)->select();
		return $goods;
	 }
	 
	/**
	 * 新增商品
	 */
	public function insert(){
	 	$rd = array('status'=>-1);

	    $shopId = -1; // 粗卡云平台
		$data = array();
		$data["goodsSn"] = I("goodsSn");
		$data["goodsName"] = I("goodsName");
		$data["goodsImg"] = I("goodsImg");
		$data["goodsThums"] = I("goodsThumbs");
		$data["shopId"] = $shopId;//session('RTC_USER.shopId');
		$data["marketPrice"] = (int)I("marketPrice");
		$data["shopPrice"] = (int)I("shopPrice");
		$data["goodsStock"] = (int)I("marketPrice");
		$data["isBook"] = (int)I("isBook");
		$data["bookQuantity"] = (int)I("bookQuantity");
		$data["warnStock"] = (int)I("warnStock");
		$data["goodsUnit"] = I("goodsUnit");
		
			
		$data["isBest"] = (int)I("isBest");
		$data["isBest"] = (int)I("isBest");
		$data["isRecomm"] = (int)I("isRecomm");
		$data["isNew"] = (int)I("isNew");
		$data["isHot"] = (int)I("isHot");
	    //如果商家状态不是已审核则所有商品只能在仓库中
 
		$data["isSale"] = 1;
		
		$data["goodsCatId1"] = (int)I("goodsCatId1");
		$data["goodsCatId2"] = (int)I("goodsCatId2");
		$data["goodsCatId3"] = (int)I("goodsCatId3");
		$data["shopCatId1"] = (int)I("shopCatId1");
		$data["shopCatId2"] = (int)I("shopCatId2");
		$data["goodsDesc"] = I("goodsDesc");
		$data["attrCatId"] = (int)I("attrCatId");
		$data["isShopRecomm"] = 0;
		$data["isIndexRecomm"] = 0;
		$data["isActivityRecomm"] = 0;
		$data["isInnerRecomm"] = 0;
		$data["goodsStatus"] =1;// ($GLOBALS['CONFIG']['isGoodsVerify']==1)?0:1;
		$data["goodsFlag"] = 1;
		$data["createTime"] = date('Y-m-d H:i:s');
		 
		 //抢购ID
		$snappedupId= newid();
		//子表
		$miaosha = array();
		$miaosha["snappedupId"] = $snappedupId;
		$miaosha["subtitle"] = I("subtitle");
		$miaosha["xiangoutype"] = (int)I("xiangoutype");		
		$miaosha["xiangou"] = (int)I("xiangou");
		$miaosha["limituseshopId"] = (int)I("limituseshopId");
		$miaosha["ticketId"] = I("ticketId");
		$miaosha["buyinfo"] = I("buyinfo");			//购买需知
 		
		if($this->checkEmpty($data,true)){
			$data["brandId"] = (int)I("brandId");
			$data["goodsSpec"] = I("goodsSpec");
			$data["goodsKeywords"] = I("goodsKeywords");
			
			$m = M('goods');			
			$goodsId = $m->add($data);
			if(false !== $goodsId){
				$rd['status']= 1;//$goodsId;
 
				
				$snappedup = M('snappedup');
				$miaosha["goodsId"] = $goodsId;
				$said=$snappedup->add($miaosha);
				//错误信息
				$rd["error"]=$snappedup->getError();
				$rd['snappedupId']=$said;
				
				//保存相册
				$gallery = I("gallery");
				if($gallery!=''){
					$str = explode(',',$gallery);
					foreach ($str as $k => $v){
						if($v=='')continue;
						$str1 = explode('@',$v);
						$data = array();
						$data['shopId'] = $shopId;// session('RTC_USER.shopId');
						$data['goodsId'] = $goodsId;
						$data['goodsImg'] = $str1[0];
						$data['goodsThumbs'] = $str1[1];
						$m = M('goods_gallerys');
						$m->add($data);
					}
				}
				
			}
			
		}//end if($this->checkEmpty($data,true))
		else
		{
			$rd['status']= -1000;
		}
		return $rd;
	}
	 
	/**
	 * 编辑商品信息
 	*/
	public function edit(){
		$rd = array('status'=>-1);
	  
	
		$shopId = -1; // 粗卡云平台
		$data = array();
		$data["goodsSn"] = I("goodsSn");
		$data["goodsName"] = I("goodsName");
		$data["goodsImg"] = I("goodsImg");
		$data["goodsThums"] = I("goodsThumbs");
		$data["shopId"] = $shopId;//session('RTC_USER.shopId');
		$data["marketPrice"] = (int)I("marketPrice");
		$data["shopPrice"] = (int)I("shopPrice");
		$data["goodsStock"] = (int)I("marketPrice");
		$data["isBook"] = (int)I("isBook");
		$data["bookQuantity"] = (int)I("bookQuantity");
		$data["warnStock"] = (int)I("warnStock");
		$data["goodsUnit"] = I("goodsUnit");
		
			
		$data["isBest"] = (int)I("isBest");
		$data["isBest"] = (int)I("isBest");
		$data["isRecomm"] = (int)I("isRecomm");
		$data["isNew"] = (int)I("isNew");
		$data["isHot"] = (int)I("isHot");
	    //如果商家状态不是已审核则所有商品只能在仓库中
 
		$data["isSale"] = 1;
		
		$data["goodsCatId1"] = (int)I("goodsCatId1");
		$data["goodsCatId2"] = (int)I("goodsCatId2");
		$data["goodsCatId3"] = (int)I("goodsCatId3");
		$data["shopCatId1"] = (int)I("shopCatId1");
		$data["shopCatId2"] = (int)I("shopCatId2");
		$data["goodsDesc"] = I("goodsDesc");
		$data["attrCatId"] = (int)I("attrCatId");
		$data["isShopRecomm"] = 0;
		$data["isIndexRecomm"] = 0;
		$data["isActivityRecomm"] = 0;
		$data["isInnerRecomm"] = 0;
		$data["goodsStatus"] =1;// ($GLOBALS['CONFIG']['isGoodsVerify']==1)?0:1;
		$data["goodsFlag"] = 1;
		$data["createTime"] = date('Y-m-d H:i:s');
		
				 
		//子表
		$miaosha = array();
		$miaosha["snappedupId"] = I("snappedupId");
		$miaosha["subtitle"] = I("subtitle");
		$miaosha["xiangoutype"] = (int)I("xiangoutype");		
		$miaosha["xiangou"] = (int)I("xiangou");
		$miaosha["limituseshopId"] = (int)I("limituseshopId");
		$miaosha["ticketId"] = I("ticketId");
		$miaosha["buyinfo"] = I("buyinfo");			//购买需知
		
		if($this->checkEmpty($data,true)){
			$data["goodsKeywords"] =  I("goodsKeywords");
			$data["brandId"] = (int)I("brandId");
			$data["goodsSpec"] = I("goodsSpec");
			$goodsId = (int)I("id");
			$m = M('goods');	
			$m->startTrans();			
			$rs = $m->where('goodsId='.$goodsId)->save($data);
			if($rs !== false && $rs !== null) {
				//秒杀明细
				$mMiaosha = M('snappedup');
				$snappedupId=$miaosha['snappedupId'];
				$filter="snappedupId='$snappedupId'";
				$rs = $mMiaosha->where($filter)->save($miaosha);

				if($rs !== false && $rs !== null) {
				    	//保存相册
					$gallery = I("gallery");
					if($gallery!=''){
						$str = explode(',',$gallery);
						$m = M('goods_gallerys');
						//删除相册信息
						$rs = $m->where('goodsId='.$goodsId)->delete();
						if($rs === false || $rs === null) {
							$m->rollback();
							$rd['key'] = "错误码504";
							return $rd;
						}
						//保存相册信息
						foreach ($str as $k => $v) {
							if($v=='')continue;
							$str1 = explode('@',$v);
							$data = array();
							$data['shopId'] = $goods['shopId'];
							$data['goodsId'] = $goods['goodsId'];
							$data['goodsImg'] = $str1[0];
							$data['goodsThumbs'] = $str1[1];
							$rs = $m->add($data);
							if($rs === false || $rs === null) {
								$m->rollback();
								$rd['key'] = "错误码503";
								return $rd;
							}
						}
					}
					
					 $rd['status']= 1;
					 $m->commit();
				} else {
					$m->rollback();
					$rd['key'] = "错误码502";
				}
			} else {
				$rd['status'] = -4000;
				$rd['key'] = "错误码501";
//				$rd['key'] = $m->getLastSql();
				$m->rollback();
			}
		}
		return $rd;
	}
	 
}