<?php
namespace M\Action;
/**
 * ============================================================================
 * 粗卡云:
  
 * 联系方式:
 * ============================================================================
 * 搜索控制器
 */
use Think\Controller;
class SearchAction extends Controller {
	public function s() {
		if(IS_POST) {
			$keywordsArray = preg_split('/[\s,]+/', I('keywords'));
			$mod = (int)I('mod', 0);
			echo dump($keywordsArray);
			if(!empty($keywordsArray) && $mod > 0) {
				$uid = (int)session('uid');
				$sdb = D('Search');
				$msdb = D('MemberSearch');
				foreach($keywordsArray as $keywords) {
					$searchId = $sdb->insert(substr($keywords, 0, 20), $mod);
					echo $sdb->getLastSql();
					$msdb->insert($searchId, $mod, $uid);
				}
			}
		}
	}
	
	public function t() {
		$sdb = D('Search');
		$list = $sdb->top();
		$this->ajaxReturn($list, 'JSON');
	}
	
	public function suggest() {
		$sdb = D('Search');
		$list = $sdb->suggest();
		$this->ajaxReturn($list, 'JSON');
	}
}