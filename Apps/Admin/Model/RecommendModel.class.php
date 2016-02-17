<?php
 namespace Admin\Model;
/**
 * ============================================================================
 * 粗卡云:
  
 * 联系方式:
 * ============================================================================
 * 推荐餐厅务类
 */
class RecommendModel extends BaseModel {
      
	public function insert(){
	 	$rd = array('status'=>-1);
		$m = M('recommend');
		$recCount = $m->where("shopsid=".(int)I('shopsid',0))->count();;
		if($recCount==0)
		{
		 	$id = (int)I("recommid",0);		
			$data["shopsid"] = (int)I("shopsid");
			$data["sort"] = (int)I("sort");
			$data["EfficacySData"] = I("EfficacySData");
			$data["EfficacyEDate"] = I("EfficacyEDate");
			$data["CreateTime"] =date('Y-m-d H:i:s');
			$data["RecommStatus"] = I("RecommStatus");
		    if($this->checkEmpty($data,true)){
			    $rs = $m->add($data);
				if(false !== $rs){
					$rd['status']= 1;
				}
			}
		}
		else
		{
			$rd['msg']= "商家已设置推荐餐厅。";//dump($data);	
		}
		return $rd;
	 } 
	 
     /**
	  * 修改
	  */
	 public function edit(){
	 	$rd = array('status'=>-1);
	 	$id = (int)I("recommid",0);

		$data["sort"] = (int)I("sort");
		$data["EfficacySData"] = I("EfficacySData");
		$data["EfficacyEDate"] = I("EfficacyEDate");
		$data["RecommStatus"] = (int)I("RecommStatus");
	    if($this->checkEmpty($data,true)){ 
			$m = M('recommend');
		    $rs = $m->where("recommid=".(int)I('recommid',0))->save($data);
			if(false !== $rs){
				$rd['status']= 1;
			}
		}
		//$rd['msg']=dump($data);
		return $rd;
	 }
	 
	 /**
	  * 获取指定对象
	  */
     public function get(){
	 	$m = M('recommend');
		$rs = $m->where("recommid=".(int)I('recommid'))->find(); 
		return $rs;
	 } 
	 
	 /**
	  * 分页列表
	  */
     public function queryByPage(){
        $m = M('recommend');
        $areaId1 = (int)I('areaId1',0);
     	$areaId2 = (int)I('areaId2',0);
	 	$sql = "select rc.*,shopId,shopSn,shopName,u.userName,shopAtive,shopStatus,gc.catName from __PREFIX__shops s
	 		left join __PREFIX__users u on s.userId=u.userId   
	 		left join __PREFIX__goods_cats gc on gc.catId=s.goodsCatId1 
	 		inner join __PREFIX__recommend rc  on s.shopid=rc.shopsid  
	 	     where  shopFlag=1 ";
	 	if(I('shopName')!='')$sql.=" and shopName like '%".I('shopName')."%'";
	 	if(I('shopSn')!='')$sql.=" and shopSn like '%".I('shopSn')."%'";
	 	if($areaId1>0)$sql.=" and areaId1=".$areaId1;
	 	if($areaId2>0)$sql.=" and areaId2=".$areaId2;
	 	$sql.=" order by sort desc";
		return $m->pageQuery($sql);
	 }
	 
	public function del(){
		$rd = array('status'=>-1);
	    $m = M('recommend');
	    $rs = $m->delete((int)I('recommid'));
		if(false !== $rs){
		   $rd['status']= 1;
		}
		return $rd;
	 }
	 
};
?>