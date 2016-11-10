<?php
 namespace M\Model;
/**
 * ============================================================================
 * 粗卡云:
  
 * 联系方式:
 * ============================================================================
 * 拼团商品服务类
 */
class GoodsGroupModel extends BaseModel {
	public function goods($groupGoodsId = 0) {
		 $uid = getuid();
		 $where = array('groupGoodsId' => $groupGoodsId);
		 $goods = $this
		 	->field('g.goodsId, g.goodsSn, g.goodsName, g.goodsImg, g.goodsThums, g.shopId, g.goodsStock, g.shopPrice, g.goodsUnit, g.saleCount,g.goodsDesc,
		 		gg.subtitle,gg.groupGoodsId, gg.groupPrice, gg.groupNumbers, gg.groupPreNumbers, unix_timestamp() * 1000 now,
		 		unix_timestamp(gg.groupEndTime)*1000 endTime, unix_timestamp() * 1000 now,
			 	unix_timestamp(gg.groupStartTime) * 1000 AS groupStartTime, unix_timestamp(gg.groupEndTime) * 1000 AS groupEndTime')
		 	->join('gg inner join __GOODS__ g on g.goodsId = gg.goodsId')
		 	->where($where)
		 	->find();
//		echo $this->getLastSql();
		
		$group = $this->field('g.groupId, gg.groupPreNumbers, g.groupNumbers, gd.isPay,gd.groupDetailId,o.orderId,g.groupStatus,gd.isCaptain,
			unix_timestamp(g.createTime) * 1000 AS createTime,  unix_timestamp(date_add(g.createTime, interval gg.groupLimitHours hour))*1000 endTime, unix_timestamp() * 1000 now')
			->join('gg inner join __GROUP__ g on gg.groupGoodsId = g.groupGoodsId and g.groupGoodsId = '.$groupGoodsId)
			->join('inner join __GROUP_DETAIL__ gd on g.groupId = gd.groupId and gd.uid='.$uid)
			->join('inner join __ORDERS__ o on o.mmid = gd.groupDetailId and o.orderType=-1')
			->where('g.groupStatus not in(-1,-2)')
			->find();
			
//		echo $this->getLastSql();
			
		return array(
			'goods'	=> $goods,
			'group'	=> $group
		);
	}
	
	public function group($groupId = 0) {
		$group = $this->field('g.groupId, gg.groupGoodsId, gg.groupPreNumbers, g.groupNumbers,g.groupStatus,
			unix_timestamp(g.createTime) * 1000 AS createTime,  unix_timestamp(date_add(g.createTime, interval gg.groupLimitHours hour))*1000 endTime, unix_timestamp() * 1000 now')
			->join('gg inner join __GROUP__ g on gg.groupGoodsId = g.groupGoodsId and g.groupStatus=1 and g.groupId = '.$groupId)
			->where('now()<date_add(g.createTime,interval gg.groupLimitHours hour) and g.groupNumbers < gg.groupPreNumbers')
			->find();
			
		return $group;
	}
	
	public function detail($groupDetailId = 0) {
		$group = $this->field('g.groupId, gg.groupGoodsId')
			->join('gg inner join __GROUP__ g on gg.groupGoodsId = g.groupGoodsId')
			->join('inner join __GROUP_DETAIL__ gd on g.groupId = gd.groupId')
			->where(array('gd.groupDetailId'=> $groupDetailId))
			->find();
		return $group;
	}
	
	// 团员列表
	public function members($groupId = 0) {
		$m = M('GroupDetail');
		$where = array(
			'groupId'	=> $groupId,
		);
		$members = $m->field('gd.isCaptain, gd.groupDetailId, gd.groupId, gd.uid, gd.createTime,gd.isPay,
			u.ImagePath userImg,
			INSERT(u.trueName,ROUND(CHAR_LENGTH(u.trueName) / 2),ROUND(CHAR_LENGTH(u.trueName) / 4),\'****\') userName')
			->join('gd inner join __MEMBER__ u on u.uid = gd.uid')
			->where($where)->order('gd.createTime asc')->select();
		
//		echo $m->getLastSql();
			
		return $members;
	}
	
	public function checkOrder($groupGoodsId = 0, $groupId = 0) {
		$uid = getuid();
		$rst = array('status' => 1);
		
		$now = date('y-m-d-H-i-s');
     	$where = array(
     		'groupGoodsId'	=> $groupGoodsId,
     		'groupStartTime' => array('elt', $now),
     		'groupEndTime' => array('egt', $now)
     	);
     	
		$groupGoodsCount = $this->where($where)->count();
		if($groupGoodsCount != 1) { // 数据异常
			$rst['status'] = -44;
			$rst['data'] = '该商品拼团活动已经过期或输入的信息不正确';
		} else {
			if($groupId > 0) { // 查看是否已经参加过本次拼团（非本商品）
				$groupCount = $this->join('gg inner join __GROUP__ g on g.groupGoodsId=gg.groupGoodsId and g.groupId='.$groupId)
					->join('inner join __GROUP_DETAIL__ gd on gd.groupId=g.groupId and gd.uid='.$uid)
					->where(array('gg.groupGoodsId'	=> $groupGoodsId))
					->count();
				
				if($groupCount == 0) { // 参团
					$group = $this->field('gg.groupPrice, g.groupNumbers, gg.groupPreNumbers,unix_timestamp(date_add(g.createTime, interval gg.groupLimitHours hour)) endTime, unix_timestamp() now')
						->join('gg inner join __GROUP__ g on g.groupGoodsId=gg.groupGoodsId and g.groupId='.$groupId)
						->where(array('gg.groupGoodsId'	=> $groupGoodsId))
						->find();
					if(empty($group)) {
						$rst['status'] = -46;
						$rst['data'] = '你要参加的拼团不存在';
					} else if(!((int)$group['groupNumbers'] < (int)$group['groupPreNumbers'])) {
						$rst['status'] = -47;
						$rst['data'] = '真不好意思，你要参加的拼团已满员了';
					} else if(!((int)$group['now'] < (int)$group['endTime'])) {
						$rst['status'] = -48;
						$rst['data'] = '真不好意思，你来晚了，组团失败';
					} else {
						$rst['groupPrice'] = (float)$group['groupPrice'];
					}
				} else {
					$rst['status'] = -45;
					$rst['data'] = '你已经参加此次拼团活动';
				}
			} else {
				$group = $this->field('groupPrice,unix_timestamp(groupEndTime) endTime, unix_timestamp() now')
						->where(array('groupGoodsId'	=> $groupGoodsId))
						->find();
				if(empty($group)) {
					$rst['status'] = -46;
					$rst['data'] = '你要开的拼团不存在';
				} else if(!((int)$group['now'] < (int)$group['endTime'])) {
					$rst['status'] = -48;
					$rst['data'] = '真不好意思，你来晚了，该商品拼团活动已经结束了';
				} else {
					$rst['groupPrice'] = (float)$group['groupPrice'];
				}
			}
		}
		return $rst;
	}
	
	// 开团
	public function open($groupGoodsId = 0) {
		$uid = getuid();
		$m = M('Group');
		$rst = array('status' => -1, 'data' => '开团失败');
		$data['groupGoodsId'] = $groupGoodsId;
		$data['groupNumbers'] = 1;
		$data['groupStatus'] = 0;
		$data['groupHeadId'] = $uid;
		$groupId = $m->add($data);
		if($groupId > 0) {
			$gdm = M('GroupDetail');
			$gddata['groupId'] = $groupId;
			$gddata['isCaptain'] = 1;
			$gddata['uid'] = $uid;
			$gddata['isPay'] = 0;
			$groupDetailId = $gdm->add($gddata);
			
			if($groupDetailId > 0) {
				$rst['status'] = 1;
				$rst['groupDetailId'] = $groupDetailId;
			}
		}
		return $rst;
	}
	
	// 参团
	public function participate($groupId) {
		$uid = getuid();
//		$m = M('Group');
		$rst = array('status' => -1, 'data' => '参团失败');
//		$data['groupNumbers'] = array('exp', '`groupNumbers` + 1');
//		if($m->where($groupId)->save($data)) {
			$gdm = M('GroupDetail');
			$gddata['groupId'] = $groupId;
			$gddata['isCaptain'] = 0;
			$gddata['uid'] = $uid;
			$gddata['isPay'] = 0;
			$groupDetailId = $gdm->add($gddata);
			
			if($groupDetailId > 0) {
				$rst['status'] = 1;
				$rst['groupDetailId'] = $groupDetailId;
			}
//		}
		return $rst;
	}
};
?>