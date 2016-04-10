<?php
 namespace Admin\Model;
/**
 * ============================================================================
 * 粗卡云:
  
 * 联系方式:
 * ============================================================================
 * 商城商品分类服务类
 */
class MallGoodsModel extends BaseModel {
	public function insert() {
		$result = array('status', -1);
		
		$mgId = (int)I('mgId', 0);
		$data['mod1'] = I('mod1', 0);
		$data['mod2'] = I('mod2', 0);
		$data['shopId'] = I('shopId', 0);
		$data['goodsId'] = I('goodsId', 0);
		$data['mgSort'] = I('mgSort', 0);
		
		$map = array(
			'mod1'		=> $data['mod1'],
			'goodsId'	=> $data['goodsId'],
		);
		if($mgId > 0) { // 编辑
			if($this->save($data)) {
				$result['status'] = 1;
			}
		} else {
			$existstData = $this->field('mgId')->where($map)->find();
			if(!empty($existstData)) { // 编辑
				$mgId = $existstData['mgId'];
				if($this->save($data)) {
					$result['status'] = 1;
				}
			} else {
				$mgId = $this->add($data); // 新增
				if($mgId > 0) {
					$result['status'] = 1;
				}
			}
			
		}
		$result['mgId'] = $mgId; 
		$this->ajaxReturn($result, 'JSON');
	}
};
?>