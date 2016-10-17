<?php
 namespace Admin\Model;
/**
 * ============================================================================
 * 粗卡云:  
 * 联系方式:
 * ============================================================================
 * 抢购
 */
class SnappedUpModel extends BaseModel {
	
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
     	 
	 	$sql = "select g.*,gc.catName,ms.jishijiexiao,ms.qishu,ms.miaoshaStatus,ms.zongrenshu
	 		,ms.canyurenshu,ms.shengyurenshu,ms.maxqishu
		 	from __PREFIX__goods g 
			left join __PREFIX__goods_cats gc on g.goodsCatId2=gc.catId			
			inner join __PREFIX__miaosha ms on ms.miaoshaId=g.miaoshaId 
			where goodsFlag=1  ";

	 	if($goodsCatId1>0)$sql.=" and g.goodsCatId1=".$goodsCatId1;
	 	if($goodsCatId2>0)$sql.=" and g.goodsCatId2=".$goodsCatId2;
 
	 	if($goodsName!='')$sql.=" and (g.goodsName like '%".$goodsName."%' or g.goodsSn like '%".$goodsName."%')";
	 	$sql.="  order by goodsId desc";   
		return $m->pageQuery($sql);
	 }

	public function queryHistoryByPage($goodsCatId1=0){
        $m = M('goods');
     	$goodsId = I('goodsId');
	 	$sql = "select g.*,gc.catName,ms.jishijiexiao,ms.qishu,ms.miaoshaStatus,ms.zongrenshu
	 		,ms.canyurenshu,ms.shengyurenshu,ms.maxqishu
		 	from __PREFIX__goods g 
			left join __PREFIX__goods_cats gc on g.goodsCatId2=gc.catId 			
			inner join __PREFIX__miaosha_history ms on ms.miaoshaId=g.miaoshaId 
			where goodsFlag=1  and goodsId=$goodsId"; 

	 	$sql.="  order by ms.qishu desc";   
		return $m->pageQuery($sql);
	} 
	
	public function queryOrderByPage($goodsCatId1=0){
        $m = M('goods');
     	$goodsId = I('goodsId');
     	$qishu = I('qishu');
	 	$sql = "
select 
	mm.mmid, mm.createTime, mm.miaoshaCount,go.goodsname, mm.uid,mm.qishu,  u.trueName userName,g.*
from  
	cky_member_miaosha mm
inner join cky_member  u on mm.uid = u.uid
left join cky_miaosha_history g on g.miaoshaId=mm.miaoshaId and mm.qishu=g.qishu
left join cky_goods go on go.miaoshaId=g.miaoshaId
where  goodsId=$goodsId";
 

	 	$sql.="  order by g.qishu desc";   
		return $m->pageQuery($sql);
	}
	 
	 
	 public function get(){
	 	$m = M('goods');
	 	$id = (int)I('id',0); 
		
		$sql = "select g.*,gc.catName,ms.jishijiexiao
	 		,ms.canyurenshu,ms.shengyurenshu,ms.maxqishu,ms.subtitle,ms.xiangou
		 	from __PREFIX__goods g 
			left join __PREFIX__goods_cats gc on g.goodsCatId2=gc.catId 			
			inner join __PREFIX__miaosha ms on ms.miaoshaId=g.miaoshaId 
			where goodsId=$id  order by goodsId desc";   
		$list = $m->query($sql);
		$goods = $list[0]; 
		
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
		 
		$miaoshaId= newid();
		$data["miaoshaId"] = $miaoshaId;
		
		//子表
		$miaosha = array();
		$miaosha["miaoshaId"] = $data["miaoshaId"];
		$miaosha["subtitle"] = I("subtitle");;
		$miaosha["maxqishu"] = (int)I("maxqishu");		//最大期数
		$miaosha["xiangou"] = (int)I("xiangou");
		
		$zongrenshu =(int)I("marketPrice");
		$miaosha["miaoshaStatus"] = 0;		//
		$miaosha["qishu"] = 1;		//期数',		 
		$miaosha["zongrenshu"] = $zongrenshu;			//'总人数',
		$miaosha["canyurenshu"] = 0;		//'参与人数',
		$miaosha["shengyurenshu"] = $zongrenshu;		// '剩余数',		
		$miaosha["jishijiexiao"] = (int)I("jishijiexiao");		// '即时揭晓',
		if($miaosha["jishijiexiao"] >0)
		{
			$addTime=(int)$miaosha["jishijiexiao"];
			$miaosha["lastTime"] = date('Y-m-d H:i:s',strtotime("+$addTime hour"));
		}
		if($zongrenshu > 100000) {
			$rd['status']= -2000;
			$rd['key']= "商品秒杀人次不能大于十万次";
			return $rd;
		}
		
		
		$rd['data'] = array(
			'miaoshaId'		=> $miaoshaId,
			'qishu'			=> $miaosha["qishu"],
			'count'			=> $miaosha["zongrenshu"],
		);

		if($this->checkEmpty($data,true)){
			$data["brandId"] = (int)I("brandId");
			$data["goodsSpec"] = I("goodsSpec");
			$data["goodsKeywords"] = I("goodsKeywords");
			$m = M('goods');
			$goodsId = $m->add($data);
			if(false !== $goodsId){
				$this->add($miaosha);
				$rd['status']= 1;				
				 
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
	 	$goodsId = (int)I("id",0);
		$shopId =0;
 
	 	//加载商品信息
	 	$m = M('goods');
	 	$goods = $m->field("g.miaoshaId, shopId, goodsId, zongrenshu,canyurenshu,shengyurenshu")->join("g inner join __MIAOSHA__ m on g.miaoshaId=m.miaoshaId")->where("goodsId=$goodsId")->find();
	 	if(empty($goods))return array();
		
		
		$zongrenshu = (int)I("marketPrice");
		$canyurenshu = (int)$goods['canyurenshu'];
		
		if($zongrenshu < (int)$goods['canyurenshu']) {
			$rd['status'] = -3000;
			$rd['key']= "总需人数不能低于已购买次数<r style='color:red'>$canyurenshu</r>";
			return $rd;
		}
		
		if($zongrenshu > 100000) {
			$rd['status']= -2000;
			$rd['key']= "商品秒杀人次不能大于十万次";
			return $rd;
		}
	
		$data = array();
		
		$data["goodsSn"] = I("goodsSn");
		$data["goodsName"] = I("goodsName");
		$data["goodsImg"] = I("goodsImg");
		$data["goodsThums"] = I("goodsThumbs");
		$data["marketPrice"] = (float)I("marketPrice");
//		$data["shopPrice"] = (float)I("shopPrice");
		$data["goodsStock"] = $zongrenshu - $canyurenshu;
		$data["isBook"] = (int)I("isBook");
		$data["bookQuantity"] = (int)I("bookQuantity");
		$data["warnStock"] = (int)I("warnStock");
		$data["goodsUnit"] = I("goodsUnit");
		$data["isBest"] = (int)I("isBest");
		$data["isRecomm"] = (int)I("isRecomm");
		$data["isNew"] = (int)I("isNew");
		$data["isHot"] = (int)I("isHot");
		
	    //如果商家状态不是已审核则所有商品只能在仓库中
		//$data["isSale"] = (int)I("isSale");
		
		$data["goodsCatId1"] = (int)I("goodsCatId1");
		$data["goodsCatId2"] = (int)I("goodsCatId2");
		$data["goodsCatId3"] = (int)I("goodsCatId3");
		$data["shopCatId1"] = (int)I("shopCatId1");
		$data["shopCatId2"] = (int)I("shopCatId2");
		$data["goodsDesc"] = I("goodsDesc");
		$data["goodsStatus"] = ($GLOBALS['CONFIG']['isGoodsVerify']['fieldValue']==1)?0:1;
		$data["attrCatId"] = (int)I("attrCatId");
		
		$data["miaoshaId"] = I("miaoshaId");
		
		//子表
		$miaosha = array();
		$miaosha["subtitle"] = I("subtitle");;
		$miaosha["maxqishu"] = (int)I("maxqishu");		//最大期数
		$miaosha["xiangou"] = (int)I("xiangou");
		
		$miaosha["zongrenshu"] = $zongrenshu;		//'总人数',
		$miaosha["shengyurenshu"] = $zongrenshu - $canyurenshu;		// '剩余数',		
		$miaosha["jishijiexiao"] = (int)I("jishijiexiao");	// '即时揭晓',
		if($miaosha["jishijiexiao"] >0)
		{
			$addTime=(int)$miaosha["jishijiexiao"];
			$miaosha["lastTime"] = date('Y-m-d H:i:s',strtotime("+$addTime hour"));
			$miaosha["createTime"] = date('Y-m-d H:i:s');
		}
		
		if($this->checkEmpty($data,true)){
			$data["goodsKeywords"] =  I("goodsKeywords");
			$data["brandId"] = (int)I("brandId");
			$data["goodsSpec"] = I("goodsSpec");
			
			$m->startTrans();
			
			$rs = $m->where('goodsId='.$goodsId)->save($data);
			if($rs !== false && $rs !== null) {
				//秒杀明细
				$mMiaosha = M('miaosha');
				$maioshaid=$goods['miaoshaId'];
				$filter="miaoshaId='$maioshaid'";
				$rs = $mMiaosha->where($filter)->save($miaosha);
//				$rt['key'] = $mMiaosha->getLastSql();
//				$rt['status'] = -5000;
//				return $rt;
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
	
	public function del($miaoshaId) {
		$rst['status'] = -7;
		$data = $this->field('miaoshaStatus,qishu')->find($miaoshaId);
		if(!empty($data) && (int)$data['qishu'] == 1 && (int)$data['miaoshaStatus'] == 0) {
			$rst['status'] = -1;
			if($this->delete($miaoshaId) !== FALSE) {
				$rst['status'] = 1;
			}
		}
		return $rst;
	}
	
}