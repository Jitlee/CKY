<?php
 namespace M\Model;
/**
 * ============================================================================
 * 粗卡云:
  
 * 联系方式:
 * ============================================================================
 * 搜索服务类
 */
class SearchModel extends BaseModel {
    public function insert($keywords, $mod) {
    		$filter = array(
    			'searchKeywords'		=> $keywords,
    			'searchMod'			=> $mod,
		);
		
		$search = $this->where($filter)->find();
		$searchId = 0;
		$data = array();
		$data['updateTime'] = array('exp', 'now()');
		if($search) { // 编辑
			$data['searchCount'] = array('exp', '`searchCount` + 1');
			$data['updateTime'] = array('exp', 'now()');
			$this->where($filter)->save($data);
			$searchId = (int)$search['search'];
		} else { // 新增
			$data['searchKeywords'] = $keywords;
			$data['searchMod'] = $mod; 
			$searchId = (int)$this->add($data);
		}
		return $searchId;
    }
	
	public function top() {
		return $this->field('searchKeywords keywords')->order('searchCount desc, updateTime desc')->page(1, 15)->select();
	}
	
	public function suggest() {
		$keywords = I('keywords', "-");
		if($keywords != '-') {
			$keywordsArray = preg_split('/[\s,]+/', I('keywords'));
			$filter = array(
				'searchKeywords'		=> array('like', $keywordsArray[0].'%')
			);
			return $this->where($filter)->field('searchKeywords keywords')->order('searchCount desc, updateTime desc')->page(1, 30)->select();
		}
		return null;
	}
};
?>