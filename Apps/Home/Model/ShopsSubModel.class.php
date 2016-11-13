<?php
namespace Home\Model;
/**
 * ============================================================================
 * 粗卡云:
  
 * 联系方式:
 * ============================================================================
 * 品牌服务类
 */
class ShopsSubModel extends BaseModel {
	
	/**
	  * 根据县区获取品牌列表
	  */
	public function queryByPage($shopId){
		
		$shopName = I("shopName");
		$sql = "SELECT * FROM __PREFIX__shopssub WHERE 1=1 and shopId=$shopId";
		 
		if($shopName!=""){
			$sql .= " AND shopName like '%$shopName%'";
		}
		$m = M('shopssub');
		return $m->pageQuery($sql);
	}
	/**
	  * 获取列表
	  */
	public function queryByList($shopId){
	     $m = M('shopssub');
		 $rs = $m->where("shopId=$shopId")->select();
		 return $rs;
	}
	  
	public function get(){
	 	$m = M('shopssub');
	 	$id = (int)I('id',0);
		$goods = $m->where("shopsubId=".$id)->find();
		return $goods;
	}
	/**
	 * 新增商品
	 */
	public function insert(){
	 	$rd = array('status'=>-1);
	 	
		$data = array();
		$data["shopId"] = session('RTC_USER.shopId');
		$data["shopName"] = I("shopName");
		$data["userName"] = I("userName");
		$data["shopImg"] = I("shopImg");
		$data["shopTel"] = I("shopTel");
		$data["shopAddress"] = I("shopAddress"); 
		
		$data["createTime"] = date('Y-m-d H:i:s');
		if($this->checkEmpty($data,true)){			
			$m = M('shopssub');
			$goodsId = $m->add($data);
			if(false !== $goodsId){
				$rd['status']= 1;
			}
		}
		return $rd;
	}
	
	public function edit(){
	 	$rd = array('status'=>-1);
	 	$shopsubId = (int)I("id",0);
	 	
		$data = array();
//		$data["shopId"] = session('RTC_USER.shopId');
		$data["shopName"] = I("shopName");
		$data["userName"] = I("userName");
		$data["shopImg"] = I("shopImg");
		$data["shopTel"] = I("shopTel");
		$data["shopAddress"] = I("shopAddress"); 
		
		$data["createTime"] = date('Y-m-d H:i:s');
		if($this->checkEmpty($data,true)){			
			$m = M('shopssub');
			$rs = $m->where('shopsubId='.$shopsubId)->save($data);
			if(false !== $rs){
				$rd['status']= 1;
			}
		}
		return $rd;
	}
	
//	public function del(){
//	 	$rd = array('status'=>-1);
//	 	$m = M('shopssub');
//	 	$shopId = (int)session('RTC_USER.shopId');
//	 	$data = array();
//		$data["goodsFlag"] = -1;
//	 	$rs = $m->where("shopId=".$shopId." and goodsId=".I('id'))->save($data);
//	    if(false !== $rs){
//			$rd['status']= 1;
//		}
//		return $rd;
//	 }

}