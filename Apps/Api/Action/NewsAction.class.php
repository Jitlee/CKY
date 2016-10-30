<?php
namespace Api\Action;
/**
 * ============================================================================
 * 粗卡云:
  
 * 联系方式:
 * ============================================================================
 * 首页（默认）控制器
 */

class NewsAction extends BaseAction {
	
	public function _empty() {
		$this->top();
	}
	
	public function top() {
		$data = array(
			'top' => array(
				'id'	 => 1,
				'title' => '陕西省2016年统一考试录用公务员公告发布'
			),
			'list' => array(
				array(
					'id' => 2,
					'thumb' => 'Upload/ads/2016-10/5815809264d8e.jpg',
					'title' => '省工商联高级资讯委员主任、原副主席刘某某视察某某公司并发表重要讲话',
					'date'	=> '2016-10-15'
				),
				array(
					'id' => 4,
					'thumb' => 'Upload/ads/2016-10/581580f74c808.jpg',
					'title' => '定边售价商业管理公司承接定边各类商业管理相关业务',
					'date'	=> '2016-10-15'
				),
				array(
					'id' => 5,
					'thumb' => 'Upload/ads/2016-10/581580e0b7fc3.jpg',
					'title' => '平困村庄变迁记--定边县白泥边县白泥边县白泥边县白泥边县白泥',
					'date'	=> '2016-9-22'
				),
			)
		);
		$this->json($data);
	}
}
?>