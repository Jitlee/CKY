<?php
namespace M\Action;
/**
 * ============================================================================
 * 粗卡云:
  
 * 联系方式:
 * ============================================================================
 * 商家 控制器
 */
use Think\Controller;
class GoodsAction extends BaseAction {
//	public function page() {
//		$m = D('M/Goods');
//		$list = $m->goods();
////		echo $m->getLastSql();
//		$this->ajaxReturn($list, 'JSON');
//	}
	// 获取所有商品
	public function ptg() {
		$db = D('Goods');
		$list = $db->pageTop();
		$this->ajaxReturn($list, 'JSON');
	}
	
	// 获取所有商品的数量
	public function ctg() {
		$db = D('Goods');
		$count = $db->countTop();
		$this->ajaxReturn($count, 'JSON');
	}
	
	// 商品详情
	public function detail() {		
		$m = D('M/Goods');
		$data = $m->detail();
		$data['goodsDesc'] = htmlspecialchars_decode(html_entity_decode($data['goodsDesc']));
		$this->assign('data', $data);
		$this->assign('title', $data['goodsName']);
		if((int)$data['goodsCatId1'] == 1) {
			$this->display('fast');
		} else {
			$this->display();	
		}
	}
	
	public function detailtst() {
//		try_login();
//		$openid = get_user_open_id();
//		$openid=''.$openid;	
//		echo $openid.'
//		';
//		
		$user_agent = $_SERVER['HTTP_USER_AGENT'];
		echo $user_agent.'
		'.strpos($user_agent, 'MicroMessenger');
		if (strpos($user_agent, 'MicroMessenger') >0) {
			echo'try_login()
			';
		}
		else
		{
			echo '未输出';
		}
		
		
		$this->display('fast');
	}
	public function gallerys() {
		$m = D('M/GoodsGallerys');
		$list = $m->lst();
//		echo $m->getLastSql();
		$this->ajaxReturn($list, 'JSON');
	}
	
	public function record() {
		$this->assign('title', '购买记录');
		$this->display();
	}
	
	public function records() {
		$m = D('M/OrderGoods');
		$list = $m->records();
		$this->ajaxReturn($list, 'JSON');
	}
}