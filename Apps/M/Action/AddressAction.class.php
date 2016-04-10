<?php
namespace M\Action;
/**
 * ============================================================================
 * 粗卡云:
  
 * 联系方式:
 * ============================================================================
 * 首页（默认）控制器
 */
use Think\Controller;
class AddressAction extends Controller {

/*****地址管理******/
	public function addresslist()
	{		
		$this->assign('title', '收货地址');		
		//$this->assign("data", $result);
		$this->display();
						
	}
	
	public function getdefault() {
		$m= D('M/MemberAddress');
		$data = $m->getDefault(getuid());
//		echo $m->getLastSql();
		$this->ajaxReturn($data, 'JSON');
	}
	
	public function loadList()
	{
		$mMAdd= D('M/MemberAddress');
		$uid=session("uid");
		$result=$mMAdd->GetList($uid);
		$this->ajaxReturn($result,"JSON");
	}
	
	public function delete(){
		$result["status"]=0;
		$result["msg"]="失败。";
		$id=I('addressId',0);	 	
		$memberAre = D('M/MemberAddress');			 		
		$res = $memberAre->delete($id);
		if($res["status"] == 1) {
			$result["status"]=1;
		} 
		else 
		{
			$result["msg"]='删除地址出错啦。';
		}
		$this->ajaxReturn($result, "JSON");
	}
	public function setdefalut(){
		$result["status"]=1;
		$result["msg"]="失败。";
		$id=I('addressId',0);	 	
		$memberAre = D('M/MemberAddress');			 		
		$res = $memberAre->setdefalut($id,getuid());
//		if($res["status"] == 1) {
//			$result["status"]=1;
//		}
		$this->ajaxReturn($result, "JSON");
	}
	
	public function addressedit(){
		if(IS_POST) {
			$result["status"]=0;
			$result["msg"]="失败。";
			$memberAre = D('M/MemberAddress');
				 
			$id=I('addressId',0);	 		
			$add = $memberAre->GetByID($id);
			if(!$add) {				
				$_POST['userId']=  session("uid");
				$_POST["createTime"]=time();
				$res = $memberAre->Insert($_POST);
				if($res["status"] == 1) {
					$result["status"]=1;
				} 
				else 
				{
					$result["msg"]='数据错误'.$id;
				}
			}
			else
			{
				//$db->save($_POST);
				$add["userName"]=$_POST['userName'];
				$add["userPhone"]=$_POST['userPhone'];
				$add["areaId1Name"]=$_POST['areaId1Name'];
				$add["areaId2Name"]=$_POST['areaId2Name'];
				$add["areaId3Name"]=$_POST['areaId3Name'];
				$add["areaId1"]=$_POST['areaId1'];
				$add["areaId2"]=$_POST['areaId2'];
				$add["areaId3"]=$_POST['areaId3'];
				$add["address"]=$_POST['address'];				
				$add["postCode"]=$_POST['postCode'];				
								
				$res = $memberAre->Edit($add);				
				if($res["status"] != -1) {
					$result["status"]=1;
				} 
				else 
				{
					$result["msg"]='数据错误';
				}
			}
			$this->ajaxReturn($result, "JSON");
		}
		else
		{
			 $m = D('M/areas');
			 $addProvi=$m->GetProvince();
			 			  			 
			 if(I('addressId',0)>0){
			 	$memberAre = D('M/MemberAddress');	
				 $id=I('addressId',0);	 		
				$item = $memberAre->GetByID($id);
				 
				$cityarr = $m->getCityByProvince($item["areaId1"]);
				$xianarr = $m->getCountyByCity($item["areaId2"]);
				
				$this->assign("Item", $item);  
				$this->assign("cityarr", $cityarr);
				$this->assign("xianarr", $xianarr);	 
			 }
			$this->assign("dataProvi", $addProvi);
			$this->display();
		}
	}
	
	

}