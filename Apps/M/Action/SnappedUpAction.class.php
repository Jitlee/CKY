<?php
namespace M\Action;
use Think\Controller;
use P\Model;

class SnappedUpAction extends BaseAction {
	
	
	public function _initialize(){
		$this->assign('tabid', "member");
	}
	
	public function index() {
		$m = D('M/SnappedUp');
		$data = $m->GetMostSnappedUpCats();

		$CName	=$data["CName"];
 		$this->assign('CName', $CName);
		$SUCatsId=(int)$data["SUCatsId"];
 		//活动标题
 		$Activitys=$m->GetActivity($SUCatsId);
 		$this->assign('Activitys', $Activitys);
		//主页商品
		$s=$m->GetGoodsTop($SUCatsId);
		$this->assign('goods', $s);
		 
		$this->display();
	}
	
	/***
	 * 主页-秒杀数据获取
	 * **/
	public function IndexSnapp() {
		$m = D('M/SnappedUp');
		$obj = $m->GetMostSnappedUpCats();		
		$SUCatsId=(int)$obj["SUCatsId"];
		$CName	=$obj["CName"];
		
		$data = array();
		$data["SUCatsId"]=$SUCatsId;
		$data["CName"] =$CName;
 		
		if($SUCatsId > 0)//主页商品
		{
			$s=$m->GetGoodsTop($SUCatsId);
			$data["Goods"]=$s;
		}
		$this->ajaxReturn($data, 'JSON');
	}
	
	public function queryActivityGoods() {
		$m = D('M/SnappedUp');
		$data = $m->queryActivityGoods();
		$this->ajaxReturn($data, 'JSON');
	}
 	
 	public function detail() {
 		$goodsid=I("id");
 		// 商品详情
	 
		$m = D('M/SnappedUp');
		$data = $m->detail();
		$data['goodsDesc'] = htmlspecialchars_decode(html_entity_decode($data['goodsDesc']));
		$data['buyinfo'] = htmlspecialchars_decode(html_entity_decode($data['buyinfo']));
		$limituseshopId=$data['limituseshopId'];
		$this->assign('data', $data);
		$this->assign('title', $data['goodsName']);
//		  echo dump($data);
		 //商家信息
		 $mshop = D('M/Shops');
		$dataShop = $mshop->detail($limituseshopId);
		$this->assign('dataShop', $dataShop);
		$this->assign('shopName', $dataShop['shopName']);
		
	
 		$this->display();
	}
	
	public function detailinfo() {
 		$goodsid=I("id");
 		// 商品详情 
		$m = D('M/SnappedUp');
		$data = $m->detail();
		$data['goodsDesc'] = htmlspecialchars_decode(html_entity_decode($data['goodsDesc'])); 
		$this->assign('goodsDesc', $data['goodsDesc'] );
		  
 		$this->display();
	}
	
	
	
	
}