<?php
 namespace M\Model;
/**
 * ============================================================================
 * 粗卡云:
  
 * 联系方式:
 * ============================================================================
 * 商城活动类
 */
class MallActivityModel extends BaseModel {
    public function queryTop6() {
    		return $this->field("mactid, logothums")->where('now() between `starttime` and `endtime`')->order('sort desc, createTime')->page(1, 6)->select();
    }
	
	/**
	  * 获取指定对象
	  */
     public function getActivity(){
	 	$m = M('mall_activity');
		return $m->where("mactid=".(int)I('mactid'))->find();
	 }
	 
	 public function activityPage(){
        $pageSize = 8;
		$pageNo = intval(I('pageNo', 1));
		$time= strftime("%Y-%m-%d");
		$map = array(
			'status'				=> 1,
			'starttime'		=> array('ELT', $time),
			'endtime'		=> array('EGT', $time),
		);
		$m = M('mall_activity');
		return $m->field('mactid, mactname, adpaththums') ->order('createtime desc') ->where($map)->page($pageNo, $pageSize)->select();
	 }
	 
	 /**
	  * 分页列表
	  */
     public function getActivityClass(){
        $mactid	= I('mactid',0);
		$m = M('mall_activitym');
		$map = array(
			'mactid' => $mactid
		);
		return $m->order('sort desc')->where($map)->select();
	 }
	 
	  /**
	  * 分页列表[获取已审核列表]
	  */
     public function getActivityGoods($pageSize,$pageNum){
     	$mactid	= I('mactid',0);
        $m = M('goods');
		 
	 	$sql = "
select 
	ag.mactid,ag.mactmid,ag.actgoodsid,mam.mactmname,mam.mlogothums,mam.lineshownum,p.shopName
	,g.*,mam.mactId activeId, mam.priceMode activeType,mam.amount activeAmount
from cky_goods g 
inner join cky_mall_activitygoods ag on ag.goodsid=g.goodsid  
inner join cky_mall_activitym mam on ag.mactmid = mam.mactmid
left join  cky_shops p on p.shopId=g.shopId 
where goodsStatus=1 and goodsFlag=1  and g.isSale=1  and ag.mactid=$mactid
order by mam.sort desc,ag.mactmid limit $pageSize,$pageNum";    
		 
		return $m->query($sql);
	 }
	
};
?>