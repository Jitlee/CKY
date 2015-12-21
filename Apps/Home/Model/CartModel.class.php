<?php
namespace Home\Model;
/**
 * ============================================================================
 * 粗卡云:
  
 * 联系方式:
 * ============================================================================
 * 购物车服务类
 */
class CartModel extends BaseModel {
	protected $tableName = 'goods'; 
	
	/**
	 * 添加[正常]商品到购物车
	 */
	public function addToCart(){
		//判断一下该商品是否正常	出售
		$goodsInfo = self::getGoodsInfo((int)I("goodsId"),(int)I("goodsAttrId"));
		if($goodsInfo['goodsId']=='')return array();
		$goodsInfo["cnt"] = ((int)I("gcount")>0)?(int)I("gcount"):1;
		$goodsInfo["ischk"] = 1;
		$cartgoods = array();
		$totalMoney = 0;
		$RTC_CART = session("RTC_CART");
		//如果 购物车为空则放入购物车中
		if(empty($RTC_CART)){
			$shopcat = array();
			$shopcat[$goodsInfo["goodsId"]."_".$goodsInfo["goodsAttrId"]] = $goodsInfo;
			$totalMoney += $goodsInfo["cnt"]*$goodsInfo["shopPrice"];
			$cartgoods[] = $goodsInfo;		
			session("RTC_CART",$shopcat);
		}else{
			//如果购物车不为空则要看下该商品是否原来就存在了。
			$shopcat = $RTC_CART;
			//如果已经存在则要把数量相加
			if(!empty($shopcat[$goodsInfo['goodsId']."_".$goodsInfo["goodsAttrId"]])){
				$goodsInfo["cnt"]=$RTC_CART[$goodsInfo["goodsId"]."_".$goodsInfo["goodsAttrId"]]["cnt"]+$goodsInfo["cnt"];
			}			
			$shopcat[$goodsInfo["goodsId"]."_".$goodsInfo["goodsAttrId"]] = $goodsInfo;				
            //重新把购物车内的数据拿到外边
			foreach($shopcat as $key=>$cgoods){	
				$totalMoney += $cgoods["cnt"]*$cgoods["shopPrice"];
				$cartgoods[] = $cgoods;
			}		
			session("RTC_CART",$shopcat);
		}
		$cartInfo = array();
		$cartInfo["cartgoods"] = $cartgoods;
		$cartInfo["totalMoney"] = $totalMoney;
		return $cartInfo;
	}
	
	/**
	 * 获取商品信息
	 */
	public function getGoodsInfo($goodsId,$goodsAttrId = 0){
		$sql = "SELECT g.attrCatId,g.goodsId,g.goodsSn,g.goodsName,g.goodsThums,g.shopId,g.marketPrice,g.shopPrice,g.goodsStock,g.bookQuantity,g.isBook,sp.shopName
				FROM __PREFIX__goods g ,__PREFIX__shops sp WHERE g.shopId=sp.shopId AND goodsFlag=1 and isSale=1 and goodsStatus=1 and g.goodsId = $goodsId";
		$goodslist = $this->query($sql);
		//如果商品有价格属性的话则获取其价格属性
		if(!empty($goodslist) && $goodslist[0]['attrCatId']>0){
			$sql = "select ga.id,ga.attrPrice,ga.attrStock,a.attrName,ga.attrVal,ga.attrId from __PREFIX__attributes a,__PREFIX__goods_attributes ga
			        where a.attrId=ga.attrId and a.catId=".$goodslist[0]['attrCatId']." and a.isPriceAttr=1 
			        and ga.goodsId=".$goodslist[0]['goodsId']." and id=".$goodsAttrId;
			
			$priceAttrs = $this->query($sql);
			if(!empty($priceAttrs)){
				$goodslist[0]['attrId'] = $priceAttrs[0]['attrId'];
				$goodslist[0]['goodsAttrId'] = $priceAttrs[0]['id'];
				$goodslist[0]['attrName'] = $priceAttrs[0]['attrName'];
				$goodslist[0]['attrVal'] = $priceAttrs[0]['attrVal'];
				$goodslist[0]['shopPrice'] = $priceAttrs[0]['attrPrice'];
				$goodslist[0]['goodsStock'] = $priceAttrs[0]['attrStock'];
			}
		}else{
			$goodslist[0]['goodsAttrId'] = 0;
		}
		return $goodslist[0];
	}
	
	/**
	 * 获取购物车信息
	 */
	public function getCartInfo(){
		$mgoods = D('Home/Goods');
		$totalMoney = 0;
		$shopcart = session("RTC_CART")?session("RTC_CART"):array();
		$cartgoods = array();
		foreach($shopcart as $goodsId=>$cgoods){
			$temp = explode('_',$goodsId);
			$goodsId = (int)$temp[0];
			$goodsAttrId = (int)$temp[1];
			$sql = "SELECT  g.goodsThums,g.goodsId,g.shopPrice,g.isBook,g.goodsName,g.shopId,g.goodsStock,g.shopPrice,shop.shopName,shop.qqNo,shop.deliveryType,shop.shopAtive,
					shop.shopTel,shop.shopAddress,shop.deliveryTime,shop.isInvoice, shop.deliveryStartMoney,
					shop.deliveryFreeMoney,shop.deliveryMoney ,g.goodsSn,shop.serviceStartTime,shop.serviceEndTime
					FROM __PREFIX__goods g, __PREFIX__shops shop
					WHERE g.goodsId = $goodsId AND g.shopId = shop.shopId AND g.goodsFlag = 1 and g.isSale=1 and g.goodsStatus=1 ";
			$goods = self::queryRow($sql);
		    //如果商品有价格属性的话则获取其价格属性
		    if(!empty($goods) && $cgoods['attrCatId']>0){
			    $sql = "select ga.id,ga.attrPrice,ga.attrStock,a.attrName,ga.attrVal,ga.attrId from __PREFIX__attributes a,__PREFIX__goods_attributes ga
			             where a.attrId=ga.attrId and a.catId=".$cgoods['attrCatId']." and a.isPriceAttr=1 and ga.attrId=".$cgoods['attrId']." 
			             and ga.goodsId=".$goodsId." and id=".$goodsAttrId;
				$priceAttrs = $this->query($sql);
				if(!empty($priceAttrs)){
					$goods['goodsAttrId'] = $priceAttrs[0]['id'];
					$goods['attrName'] = $priceAttrs[0]['attrName'];
					$goods['attrVal'] = $priceAttrs[0]['attrVal'];
					$goods['shopPrice'] = $priceAttrs[0]['attrPrice'];
					$goods['goodsStock'] = $priceAttrs[0]['attrStock'];
				}
			}else{
				$goods['goodsAttrId'] = 0;
			}
			if($goods["isBook"]==1){
				$goods["goodsStock"] = $goods["goodsStock"]+$goods["bookQuantity"];
			}
			$goods["cnt"] = $cgoods["cnt"];
			$goods["ischk"] = $cgoods["ischk"];
			if($goods["ischk"]==1)$totalMoney += $goods["cnt"]*$goods["shopPrice"];

			if($startTime<$goods["startTime"]){
				$startTime = $goods["startTime"];
			}
			if($endTime>$goods["endTime"]){
				$endTime = $goods["endTime"];
			}
		
			$cartgoods[$goods["shopId"]]["shopgoods"][] = $goods;
			$cartgoods[$goods["shopId"]]["deliveryFreeMoney"] = $goods["deliveryFreeMoney"];//店铺免运费最低金额
			$cartgoods[$goods["shopId"]]["deliveryMoney"] = $goods["deliveryMoney"];//店铺配送费
			$cartgoods[$goods["shopId"]]["deliveryStartMoney"] = $goods["deliveryStartMoney"];//店铺配送费
			$cartgoods[$goods["shopId"]]["totalCnt"] = $cartgoods[$goods["shopId"]]["totalCnt"]+$cgoods["cnt"];
			$cartgoods[$goods["shopId"]]["totalMoney"] = $cartgoods[$goods["shopId"]]["totalMoney"]+(($goods["ischk"]==1)?$goods["cnt"]*$goods["shopPrice"]:0);
		}
		
		foreach($catgoods as $key=> $cshop){
			if($cshop["totalMoney"]<$cshop["deliveryFreeMoney"]){
				$totalMoney = $totalMoney + $cshop["deliveryMoney"];
			}
		}
		
		$cartInfo = array();
		$cartInfo["totalMoney"] = $totalMoney;
		$cartInfo["cartgoods"] = $cartgoods;
		return $cartInfo;
		
	}
   
	/**
	 * 检测购物车中商品库存
	 */
	public function checkCatGoodsStock(){
		$shopcart = session("RTC_CART")?session("RTC_CART"):array();	
		$mgoods = D('Home/Goods');
		$cartgoods = array();		
		foreach($shopcart as $key=>$cgoods){
			$temp = explode('_',$key);
			$goodsId = (int)$temp[0];
			$goodsAttrId = (int)$temp[1];
			$obj = array();
			$obj["goodsId"] = $goodsId;
			$obj["goodsAttrId"] = $goodsAttrId;
			$goods = $mgoods->getGoodsStock($obj);
			if($goods["isBook"]==1){
				$goods["goodsStock"] = $goods["goodsStock"]+$goods["bookQuantity"];
			}
			$goods['goodsAttrId'] = $goodsAttrId;
			$goods["cnt"] = $cgoods["cnt"];
			$goods["stockStatus"] = ($goods["goodsStock"]>=$goods["cnt"])?1:0;		
			$cartgoods[] = $goods;
		}
		
		return $cartgoods;
		
	}
	
	/**
	 * 删除购物车中的商品
	 */
	public function delCartGoods(){
		$goodsKey = (int)I("goodsId")."_".(int)I("goodsAttrId");
		$shopcart = session("RTC_CART")?session("RTC_CART"):array();
		session("RTC_CART");
		$newShopcat = array();
		foreach($shopcart as $key=>$cgoods){	
			if($goodsKey != $key){
				$newShopcat[$key] = $cgoods;
			}			
		}
		session("RTC_CART",$newShopcat);
		return 1;
		
	}
	
	/**
	 * 修改购物车中的商品数量
	 * 
	 */
	public function changeCartGoodsnum(){
		$goodsKey = (int)I("goodsId")."_".(int)I("goodsAttrId");
		$num = abs((int)I("num"));
		$ischk = (int)I("ischk",0);
		$shopcart = session("RTC_CART")?session("RTC_CART"):array();
		session("RTC_CART",null);
		$newShopcart = array();
		foreach($shopcart as $key=>$cgoods){	
			$cartgoods = $shopcart[$key];
			if($goodsKey == $key){
				$cartgoods["cnt"] = $num;
				$cartgoods["ischk"] = $ischk;
			}
			$newShopcart[$key] = $cartgoods;	
		}
		session("RTC_CART",$newShopcart);
		return 1;
	}
	
}