<?php
 namespace Admin\Model;
/**
 * ============================================================================
 * 粗卡云:  
 * 联系方式:
 * ============================================================================
 * 商品服务类
 */
class MiaoshaModel extends BaseModel {
	
	/**
	  * 分页列表[获取已审核列表]
	  */
     public function queryByPage($goodsCatId1=0){
        $m = M('goods');
//      $shopName = I('shopName');
     	$goodsName = I('goodsName');
//   	$areaId1 = (int)I('areaId1',0);
//   	$areaId2 = (int)I('areaId2',0);
		if($goodsCatId1==0)
		{
			$goodsCatId1 = (int)I('goodsCatId1',0);	
		}
     	$goodsCatId2 = (int)I('goodsCatId2',0);
     	$goodsCatId3 = (int)I('goodsCatId3',0);
     	$isAdminBest = (int)I('isAdminBest',-1);
     	$isAdminRecom = (int)I('isAdminRecom',-1);
	 	$sql = "select g.*,gc.catName,ms.jishijiexiao
	 		,ms.canyurenshu,ms.shengyurenshu,ms.maxqishu
		 	from __PREFIX__goods g 
			left join __PREFIX__goods_cats gc on g.goodsCatId2=gc.catId 			
			inner join __PREFIX__miaosha ms on ms.miaoshaid=g.miaoshaid 
			where goodsFlag=1  ";

	 	if($goodsCatId1>0)$sql.=" and g.goodsCatId1=".$goodsCatId1;
	 	if($goodsCatId2>0)$sql.=" and g.goodsCatId2=".$goodsCatId2;
 
	 	if($goodsName!='')$sql.=" and (g.goodsName like '%".$goodsName."%' or g.goodsSn like '%".$goodsName."%')";
	 	$sql.="  order by goodsId desc";   
		return $m->pageQuery($sql);
	 }
	 
	 public function get(){
	 	$m = M('goods');
	 	$id = (int)I('id',0); 
		
		$sql = "select g.*,gc.catName,ms.jishijiexiao
	 		,ms.canyurenshu,ms.shengyurenshu,ms.maxqishu,ms.subtitle,ms.xiangou
		 	from __PREFIX__goods g 
			left join __PREFIX__goods_cats gc on g.goodsCatId2=gc.catId 			
			inner join __PREFIX__miaosha ms on ms.miaoshaid=g.miaoshaid 
			where goodsId=$id  order by goodsId desc";   
		$goods = $m->query($sql)[0]; 
		
		$m = M('goods_gallerys');
		$goods['gallery'] = $m->where('goodsId='.$id)->select();
		return $goods;
	 }
	 
	/**
	 * 新增商品
	 */
	public function insert(){
	 	$rd = array('status'=>-1);
//		return $rd;
	 	//查询商家状态
//		$sql = "select shopStatus from __PREFIX__shops where shopFlag = 1 and shopId=".(int)session('RTC_USER.shopId');
//		$shopStatus = $this->query($sql);
//		if(empty($shopStatus)){
//			$rd['status'] = -2;
//			return $rd;
//		}
	    
		$data = array();
		$data["goodsSn"] = I("goodsSn");
		$data["goodsName"] = I("goodsName");
		$data["goodsImg"] = I("goodsImg");
		$data["goodsThums"] = I("goodsThumbs");
		$data["shopId"] = 0;//session('RTC_USER.shopId');
		$data["marketPrice"] = (int)I("marketPrice");
		$data["shopPrice"] = (int)I("shopPrice");
		$data["goodsStock"] = (int)I("goodsStock");
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
//	    if($shopStatus[0]['shopStatus']==1){
//			$data["isSale"] = (int)I("isSale");
//		}else{
//			$data["isSale"] = 0;
//		}
		$data["isSale"] = (int)I("isSale");
		
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
		 
		$miaoshaid= newid();
		$data["miaoshaid"] = $miaoshaid;
		
		//子表
		$miaosha = array();
		$miaosha["miaoshaid"] = $data["miaoshaid"];
		$miaosha["subtitle"] = I("subtitle");;
		$miaosha["maxqishu"] = (int)I("maxqishu");		//最大期数
		$miaosha["xiangou"] = (int)I("xiangou");
		
		$miaosha["miaoshastatus"] = (int)I("marketPrice");		//
		$miaosha["qishu"] = 1;		//期数',		 
		$miaosha["zongrenshu"] = (int)I("marketPrice");			//'总人数',
		$miaosha["canyurenshu"] = (int)I("marketPrice");		//'总需份数',
		$miaosha["shengyurenshu"] = (int)I("marketPrice");		// '剩余数',		
		$miaosha["jishijiexiao"] = (int)I("jishijiexiao");		// '即时揭晓',

		if($this->checkEmpty($data,true)){
			$data["brandId"] = (int)I("brandId");
			$data["goodsSpec"] = I("goodsSpec");
			$data["goodsKeywords"] = I("goodsKeywords");
			$m = M('goods');
			$mMiaosha = M('miaosha');
			$goodsId = $m->add($data);
			if(false !== $goodsId){
				$mMiaosha->add($miaosha);
				$rd['status']= 1;				
				 
				//保存相册
				$gallery = I("gallery");
				if($gallery!=''){
					$str = explode(',',$gallery);
					foreach ($str as $k => $v){
						if($v=='')continue;
						$str1 = explode('@',$v);
						$data = array();
						$data['shopId'] =0;// session('RTC_USER.shopId');
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
	 	$goodsId = (int)I("id",0);
		$shopId =0;
//	 	$shopId = (int)session('RTC_USER.shopId');
	    //查询商家状态
//		$sql = "select shopStatus from __PREFIX__shops where shopFlag = 1 and shopId=".$shopId;
//		$shopStatus = $this->query($sql);
//		if(empty($shopStatus)){
//			$rd['status'] = -2;
//			return $rd;
//		}
	 	//加载商品信息
	 	$m = M('goods');
	 	$goods = $m->where('goodsId='.$goodsId." and shopId=".$shopId)->find();
	 	if(empty($goods))return array();
		$data = array();
		
		$data["goodsSn"] = I("goodsSn");
		$data["goodsName"] = I("goodsName");
		$data["goodsImg"] = I("goodsImg");
		$data["goodsThums"] = I("goodsThumbs");
		$data["marketPrice"] = (float)I("marketPrice");
		$data["shopPrice"] = (float)I("shopPrice");
		$data["goodsStock"] = (int)I("goodsStock");
		$data["isBook"] = (int)I("isBook");
		$data["bookQuantity"] = (int)I("bookQuantity");
		$data["warnStock"] = (int)I("warnStock");
		$data["goodsUnit"] = I("goodsUnit");
		$data["isBest"] = (int)I("isBest");
		$data["isRecomm"] = (int)I("isRecomm");
		$data["isNew"] = (int)I("isNew");
		$data["isHot"] = (int)I("isHot");
		
	    //如果商家状态不是已审核则所有商品只能在仓库中
		$data["isSale"] = (int)I("isSale");
		
		$data["goodsCatId1"] = (int)I("goodsCatId1");
		$data["goodsCatId2"] = (int)I("goodsCatId2");
		$data["goodsCatId3"] = (int)I("goodsCatId3");
		$data["shopCatId1"] = (int)I("shopCatId1");
		$data["shopCatId2"] = (int)I("shopCatId2");
		$data["goodsDesc"] = I("goodsDesc");
		$data["goodsStatus"] = ($GLOBALS['CONFIG']['isGoodsVerify']['fieldValue']==1)?0:1;
		$data["attrCatId"] = (int)I("attrCatId");
		
		$data["miaoshaid"] = I("miaoshaid");
		
		//子表
		$miaosha = array();
//		$miaosha["miaoshaid"] = I("miaoshaid");
		$miaosha["subtitle"] = I("subtitle");;
		$miaosha["maxqishu"] = (int)I("maxqishu");		//最大期数
		$miaosha["xiangou"] = (int)I("xiangou");
		
		$miaosha["miaoshastatus"] = (int)I("marketPrice");		//
//		$miaosha["qishu"] = 1;		//期数',		 
		$miaosha["zongrenshu"] = (int)I("marketPrice");		//'总人数',
		$miaosha["canyurenshu"] = (int)I("marketPrice");		//'总需份数',
		$miaosha["shengyurenshu"] = (int)I("marketPrice");		// '剩余数',		
		$miaosha["jishijiexiao"] = (int)I("jishijiexiao");	// '即时揭晓',
		
		if($this->checkEmpty($data,true)){
			$data["goodsKeywords"] =  I("goodsKeywords");
			$data["brandId"] = (int)I("brandId");
			$data["goodsSpec"] = I("goodsSpec");
			
			
			$rs = $m->where('goodsId='.$goods['goodsId'])->save($data);
			//秒杀明细
			$mMiaosha = M('miaosha');
			$maioshaid=$miaosha['miaoshaid'];
			$filter="miaoshaid='$maioshaid'";
			$rs = $mMiaosha->where($filter)->save($miaosha);
			if(false !== $rs){
				 $rd['status']= 1;
			    //保存相册
				$gallery = I("gallery");
				if($gallery!=''){
					$str = explode(',',$gallery);
					$m = M('goods_gallerys');
					//删除相册信息
					$m->where('goodsId='.$goods['goodsId'])->delete();
					//保存相册信息
					foreach ($str as $k => $v){
						if($v=='')continue;
						$str1 = explode('@',$v);
						$data = array();
						$data['shopId'] = $goods['shopId'];
						$data['goodsId'] = $goods['goodsId'];
						$data['goodsImg'] = $str1[0];
						$data['goodsThumbs'] = $str1[1];
						$m->add($data);
					}
				}
			}
		}
		return $rd;
	}
		
	
}