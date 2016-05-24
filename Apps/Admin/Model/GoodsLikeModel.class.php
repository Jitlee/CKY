<?php
 namespace Admin\Model;
/**
 * ============================================================================
 * 粗卡云:
  
 * 联系方式:
 * ============================================================================
 * 猜你喜欢
 */
class GoodsLikeModel extends BaseModel {
      
	public function insert(){
		try
		{
		 	$rd = array('status'=>-1);
			$m = M('goods_like');
			$recCount = $m->where("goodsId=".(int)I('goodsId',0))->count();;
			if($recCount==0)
			{
			 	$id = (int)I("likeid",0);		
				$data["goodsId"] = (int)I("goodsId");
				$data["sort"] = (int)I("sort");
				$data["efficacysdate"] = I("efficacysdate");
				$data["efficacyedate"] = I("efficacyedate");
				$data["createtime"] =date('Y-m-d H:i:s');
				$data["likestatus"] = (int)I("likestatus");
			    if($this->checkEmpty($data,true)){
				    $rs = $m->add($data);
					if(false !== $rs){
						$rd['status']= 1;
					}
				}
			}
			else
			{
				$rd['msg']= "商品已设置猜你喜欢。";//dump($data);	
			}
		}
	 	catch(Exception $e)
		{
			$rd["msg"]=$e->getMessage();   
		}
		return $rd;
	 } 
	 
     /**
	  * 修改
	  */
	 public function edit(){
		try
		{
		 	$rd = array('status'=>-1);
		 	//$id = (int)I("likeid",0);
			
			$data["goodsId"] = (int)I("goodsId");
			$data["sort"] = (int)I("sort");
			$data["efficacysdate"] = I("efficacysdate");
			$data["efficacysdate"] = I("efficacysdate");
			 
			$data["likestatus"] = (int)I("likestatus");
		    if($this->checkEmpty($data,true)){ 
				$m = M('goods_like');
			    $rs = $m->where("likeid=".(int)I('likeid',0))->save($data);
				if(false !== $rs){
					$rd['status']= 1;
				}
			}
			//$rd['msg']=dump($data);
		}
	 	catch(Exception $e)
		{
			$rd["msg"]=$e->getMessage();   
		}
		return $rd;
	 }
	 
	 /**
	  * 获取指定对象
	  */
     public function get(){
	 	$m = M('goods_like');
		$rs = $m->where("likeid=".(int)I('likeid'))->find(); 
		return $rs;
	 } 
	 
	 public function getbygoodsid(){
	 	$m = M('goods_like');
		$rs = $m->where("goodsId=".(int)I('id'))->find(); 
		return $rs;
	 } 
	 
	 /**
	  * 分页列表
	  */
     public function queryByPage(){
        $m = M('goods_like');
//      $areaId1 = (int)I('areaId1',0);
//   	$areaId2 = (int)I('areaId2',0);
	 	$sql = "select rc.*,g.goodsSn,g.goodsName,g.shopPrice,s.shopname from __PREFIX__goods g
	 		inner join __SHOPS__ s on s.shopId = g.shopId
	 		inner join __PREFIX__goods_like rc  on g.goodsId=rc.goodsId
	 	     where 1=1 ";
	 	if(I('goodsName')!='')$sql.=" and goodsName like '%".I('goodsName')."%'";
	 	if(I('goodsSn')!='')$sql.=" and goodsSn like '%".I('goodsSn')."%'";
//	 	if($areaId1>0)$sql.=" and areaId1=".$areaId1;
//	 	if($areaId2>0)$sql.=" and areaId2=".$areaId2;
	 	$sql.=" order by sort desc";
		return $m->pageQuery($sql);
	 }
	 
	public function del(){
		$rd = array('status'=>-1);
	    $m = M('goods_like');
	    $rs = $m->delete((int)I('likeid'));
		if(false !== $rs){
		   $rd['status']= 1;
		}
		return $rd;
	 }
	 
};
?>