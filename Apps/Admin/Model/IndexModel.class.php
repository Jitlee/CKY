<?php
namespace Admin\Model;
/**
 * ============================================================================
 * 粗卡云:
  
 * 联系方式:
 * ============================================================================
 * 首页服务类
 */
class IndexModel extends BaseModel {
	/**
	 * 获取商品配置分类信息
	 */
    public function loadConfigsForParent(){
		$sql = "select * from ".$this->tablePrefix."sys_configs where fieldType!='hidden' order by parentId asc,fieldSort asc";
		$rs = $this->query($sql);
		$configs = array();
		if(count($rs)>0){
			foreach ($rs as $key=>$v){
				if($v['fieldType']=='radio' || $v['fieldType']=='select'){
					$v['txt'] = explode('||',$v['valueRangeTxt']);
					$v['val'] = explode(',',$v['valueRange']);
				}
				$configs[$v['parentId']][] = $v;
			}
		}
		unset($rs);
		return $configs;
	}
	/**
	 * 保存平台配置信息
	 */
	public function saveConfigsForCode(){
		$rd = array('status'=>-1);
		$sql = "select * from ".$this->tablePrefix."sys_configs where fieldType!='hidden' order by parentId asc,fieldSort asc";
		$rs = $this->query($sql);
		if(!empty($rs)){
			$m = M('sys_configs');
			foreach ($rs as $key => $v){
				$result = $m-> where('fieldCode="'.$v['fieldCode'].'"')->setField('fieldValue',I($v['fieldCode']));
				if(false === $result){
				    $rd['status']= -1;
				}
			}
			$rd['status'] = 1;
			RTCDataFile("mall_config",'',null);
		}
		return $rd;
	}
	/**
	 * 保存授权码
	 */
	public function saveLicense(){
		$rd = array('status'=>-1);
		$m = M('sys_configs');
	    $result = $m-> where('fieldCode="mallLicense"')->setField('fieldValue',I('license'));
		if(false !== $result){
			$rd['status']= 1;
			RTCDataFile("mall_config",'',null);
		}
		return $rd;
	}

	/**
	 * 一周动态
	 * @return [type] [description]
	 */
	public function getWeekInfo(){
		$ret = array();
		//用户
		$weekDate = date('Y-m-d 00:00:00',time()-604800);//一周内
		$ret['userNew'] = M('member')->where(' RegisterTime>"'.$weekDate.'"')->count();//新增用户
		
		//申请商家
		$ret['shopApply'] = M('Shops')->where('shopStatus >= 0 and shopFlag=1 and createTime>"'.$weekDate.'"')->count();
		
		//新增商品
		$ret['goodsNew'] = M('goods')->where('goodsFlag=1 and createTime>"'.$weekDate.'"')->count();
		//新增订单
		$ret['ordersNew'] = M('orders')->where('orderFlag=1 and orderStatus >=0 and createTime>"'.$weekDate.'"')->count();
		//新增商家
		$map['shopStatus'] = 1;
		$ret['shopNew'] = M('Shops')->where('shopStatus = 1 and shopFlag=1 and createTime>"'.$weekDate.'"')->count();
		return $ret;
	}

	/**
	 * 统计信息
	 * @return array 统计信息的数组
	 */
	public function getSumInfo(){
		$ret = array();
		$ret['userSum'] = M('member')->count();//新增用户
		//申请商家
		$ret['shopApplySum'] = M('Shops')->where('shopStatus = 0 and shopFlag=1')->count();
		//商品
		$ret['goodsSum'] = M('goods')->where('goodsFlag=1')->count();
		//订单
		$ret['ordersSum'] = M('orders')->where('orderFlag=1 and orderStatus >=0')->count();
		//订单总金额
		$ret['moneySum'] = M('orders')->where('orderFlag=1 and orderStatus >=0')->sum('totalMoney');
		
		//商家
		$ret['shopSum'] = M('Shops')->where('shopStatus = 1 and shopFlag=1')->count();
		return $ret;
	}
}