<?php
namespace Api\Action;
/**
 * ============================================================================
 * 粗卡云:
  
 * 联系方式:
 * ============================================================================
 * 首页（默认）控制器
 */

use Think\Controller;
class BaseAction extends Controller {
	public function json($data){
		$response = array(
			'msg'	=> "",
			'code'	=> 200,
			'rst'	=> $data
		);
		$this->ajaxReturn($response);
	}
	
	public function error($code = 500, $msg = "") {
		$response = array(
			'msg'	=> $msg,
			'code'	=> $code,
			'rst'	=> null
		);
		$this->ajaxReturn($response);
	}
}
?>