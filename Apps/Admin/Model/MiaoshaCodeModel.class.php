<?php
 namespace Admin\Model;
/**
 * ============================================================================
 * 粗卡云:
  
 * 联系方式:
 * ============================================================================
 * 云购码服务类
 */
class MiaoshaCodeModel extends BaseModel {
	
    public function createCodes($miaoshaId, $qishu, $count) {
		if(empty($miaoshaId)) {
			return -3;
		}
		
    		for ($i = 1; $i <= $count; $i++) {
    			$data = array(
				'miaoshaId'		=> $miaoshaId,
				'qishu'			=> $qishu,
				'miaoshaCode'	=> $i	
			);
    			if($this->add($data) === FALSE) {
    				return -2;
    			}
		}
		return 1;
    }
	
	public function del($miaoshaId, $qishu) {
		$map = array(
			'miaoshaId'		=> $miaoshaId,
			'qishu'			=> $qishu,
		);
		if($this->where($map)->delete() !== FALSE) {
			return 1;
		}
		return -1;
	}
};
?>