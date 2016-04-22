<?php
namespace M\Action;
/**
 * ============================================================================
 * 粗卡云:
  
 * 联系方式:
 * ============================================================================
 * 秒杀控制器
 */
use Think\Controller;

class MiaoshaAction extends BaseAction {

	public function index() {
		$this->assign('title', '热门秒杀');
		
		$this->assign('tabId', 0);
		
		$this->display();
	}
	
	public function jiexiao() {
		$this->assign('title', '即将揭晓');
		
		$this->assign('category', $category);
		$this->assign('categoryName', $categoryName);
		
		$this->assign('tabId', 1);
		
		$this->display();
	}
	
	// 查询热门秒杀和即将揭晓
	public function ms() {
		$db = D('M/Miaosha');
		$list = $db->queryMiaosha();
//		echo $db->getLastSql();
		$this->ajaxReturn($list, 'JSON');
	}
	
	public function history() {
		$this->assign('title', '最新揭晓');
		
		$this->assign('tabId', 2);
		
		$this->display();
	}
	
	// 查询历史
	public function hs() {
		$db = D('M/Miaosha');
		$list = $db->queryHistory();
//		echo $db->getLastSql();
		$this->ajaxReturn($list, 'JSON');
	}
	
	public function view() {
		
		$db = M('Miaosha');
		$data = $db->field('qishu, miaoshaStatus')->find(I('id'));
		$this->assign('data', $data);
		
		$this->assign('title', '商品详情');
		$this->display();
	}
	
	public function m() {
		$db = D('M/Miaosha');
		$data = $db->get();
		if((int)$data['miaoshaStatus'] < 2) {
			$db = D('M/GoodsGallery');
			$galleries = $db->query();
			if(empty($galleries)) {
				$galleries[] = array('goodsImg'	=> $data['goodsImg']);
			}
			$data['galleries'] = $galleries;
		}
		$this->ajaxReturn($data, 'JSON');
	}
	
	public function detail() {
		$this->assign('title', '商品图文详情');
		
		$miaoshaId = I('id');
		$db = M('goods');
		$data = $db->field('goodsDesc')->where(array('miaoshaId' => $miaoshaId))->find();
		$data['goodsDesc'] = htmlspecialchars_decode(html_entity_decode($data['goodsDesc']));
		$this->assign('data', $data);
		
		$this->display();
	}
	
	// 显示参与纪录
	public function mm() {
		$this->assign('title', '参与纪录');
		$this->display("Miaosha/member_miaosha");
	}
	
	// 分页参与纪录
	public function pmm() {
		$mmdb = D('M/MemberMiaosha');
		$list = $mmdb->lst();
		$this->ajaxReturn($list, 'JSON');
	}
	
	// 显示购买云购码纪录
	public function mc() {
		$uid = (int)I('uid', 0);
		$user = null;
		if($uid > 0) { // 中奖用户购本期所有的云购码
			$db = D('M/Member');
			$user = $db->findByUid($uid);
		} else { // 当前纪录的云购码
			$db = D('M/MemberMiaosha');
			$user = $db->findUserByMmid($mmid);
		}
		
		$prizeCode = C('PRIZE_CODE') + (int)I('code');
		$this->assign('code', $prizeCode);
		$this->assign('member', $user);
		
//		echo $db->getLastSql();
//		echo dump($user);
		
		$mcdb = D('M/MiaoshaCode');
		$cnt = $mcdb->cnt();
		$this->assign('count', $cnt);
		
		$this->assign('title', '购买云购码');
		$this->display("Miaosha/miaosha_code");
	}
	
	// 分页云购码
	public function pmc() {
		$mcdb = D('M/MiaoshaCode');
		$list = $mcdb->lst();
		$this->ajaxReturn($list, 'JSON');
	}
}