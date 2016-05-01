<?php
 namespace M\Model;
/**
 * ============================================================================
 * 粗卡云:
  
 * 联系方式:
 * ============================================================================
 * 用户搜索服务类
 */
class MemberSearchModel extends BaseModel {
    public function insert($seachId, $mod, $uid) {
    		if($seachId > 0 && $uid > 0) {
	    		$data = array();
			$data['searchId'] = $seachId;
			$data['searchMod'] = $mod;
			$data['uid'] = $uid;
			$this->save($data);
		}
    }
};
?>