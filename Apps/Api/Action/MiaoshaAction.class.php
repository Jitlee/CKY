<?php
namespace Api\Action;
/**
 * ============================================================================
 * 粗卡云:
  
 * 联系方式:
 * ============================================================================
 * 首页（默认）控制器
 */

class MiaoshaAction extends BaseAction {
	
	public function _empty() {
		$this->top();
	}
	
	public function top() {
		$data = array(
			array(
				'id' => 1,
				'thumb' => 'Upload/ads/2016-10/5815809264d8e.jpg',
				'title' => '100元现金券钻卡专享',
				'shopPrice'	=> 100.0,
				'price'	=> 30.0,
				'sale'	=> 0,
			),
			array(
				'id' => 2,
				'thumb' => 'Upload/ads/2016-10/5815809264d8e.jpg',
				'title' => '100元现金券钻卡专享',
				'shopPrice'	=> 100.0,
				'price'	=> 40.0,
				'sale'	=> 0,
			),
			array(
				'id' => 3,
				'thumb' => 'Upload/ads/2016-10/5815809264d8e.jpg',
				'title' => '100元现金券钻卡专享',
				'shopPrice'	=> 100.0,
				'price'	=> 20.0,
				'sale'	=> 0,
			),
			array(
				'id' => 4,
				'thumb' => 'Upload/ads/2016-10/5815809264d8e.jpg',
				'title' => '100元现金券钻卡专享',
				'shopPrice'	=> 100.0,
				'price'	=> 50.0,
				'sale'	=> 0,
			),
			array(
				'id' => 5,
				'thumb' => 'Upload/ads/2016-10/5815809264d8e.jpg',
				'title' => '100元现金券钻卡专享',
				'shopPrice'	=> 100.0,
				'price'	=> 80.0,
				'sale'	=> 0,
			),
			array(
				'id' => 6,
				'thumb' => 'Upload/ads/2016-10/5815809264d8e.jpg',
				'title' => '100元现金券钻卡专享',
				'shopPrice'	=> 100.0,
				'price'	=> 75.0,
				'sale'	=> 0,
			),
		);
		$this->json($data);
	}
}
?>