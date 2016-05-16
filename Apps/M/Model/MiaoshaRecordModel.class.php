<?php
 namespace M\Model;
/**
 * ============================================================================
 * 粗卡云:
  
 * 联系方式:
 * ============================================================================
 * 开奖结果服务类
 */
class MiaoshaRecordModel extends BaseModel {
    public function lst() {
    		$miaoshaId = I("id");
		$qishu = (int)I('qishu', 0);
    		$filter = array(
			'r.prize_miaoshaId'		=> $miaoshaId,
			'r.prize_qishu'			=> $qishu
		);
		$list = $this->field("r.miaoshaId,r.qishu,r.mmid,ms.createTime,ms.miaoshaCount,g.goodsName,ms.ms,
			(HOUR(ms.createTime)*10000000+MINUTE(ms.createTime)*100000+SECOND(ms.createTime)*1000 + ms.ms) prizeNo
			,INSERT(u.TrueName,ROUND(CHAR_LENGTH(u.TrueName) / 2),ROUND(CHAR_LENGTH(u.TrueName) / 4),'****') username, u.imagePath userimg")
			->join("r inner join __MEMBER_MIAOSHA__ ms on ms.mmid = r.mmid")
			->join("inner join __GOODS__ g on g.miaoshaId = ms.miaoshaId")
			->join("inner join __MEMBER__ u on u.uid = ms.uid")
			->where($filter)->order('ms.createTime desc')->select();
//		echo $this->getLastSql();
		return $list;
    }
};
?>