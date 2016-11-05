<?php
 namespace Admin\Model;
/**
 * ============================================================================
 * 粗卡云:
  
 * 联系方式:
 * ============================================================================
 * 文章服务类
 */
class TicketModel extends BaseModel {
     /**
	  * 新增
	  */
	 public function insert(){
	 	$rd = array('status'=>-1);
	 	$id = $this->create_guid();
		$data = array();
		$data["ticketID"]=$id;
		$data["title"] = I("title");
		$data["typeName"] = I("typeName");
		 	 
		$data["imagePath"] = I("imagePath");
		$data["content"] = I("content");
		$data["efficacySDate"] = I("efficacySDate");
		$data["efficacyEDate"] = I("efficacyEDate");
		//$data["endDate"] = I("endDate");		
		$data["miniConsumption"] = (int)I("miniConsumption");
		$data["maxiConsumption"] = I("maxiConsumption");
		$data["needPoint"] = (int)I("needPoint");
		
		$data["limitDayUse"] = (int)I("limitDayUse");
		$data["limitDayGet"] = (int)I("limitDayGet");
		$data["limitGetnum"] = (int)I("limitGetnum");
		$data["onlyNewUser"] = (int)I("onlyNewUser");
		$data["totalCount"] = (int)I("totalCount");
		$data["ticketAmount"] = (double)I("ticketAmount");
		$data["limitUseShopID"] = (int)I("limitUseShopID");//限制商家
		$data["detail"] =I("detail");//卡券详情
		
		$data["createTime"] = date('Y-m-d H:i:s');
	    
	    if($this->checkEmpty($data,true)){
			$m = M('activity_ticket');
			$rs = $m->add($data);
		    if(false !== $rs){
				$rd['status']= 1;
			}
		}
		return $rd;
	 } 
     /**
	  * 修改
	  */
	 public function edit(){
	 	$rd = array('status'=>-1);
	 	$id = I("ticketID");
		$data = array();
		$data["title"] =I("title");
		$data["typeName"] = I("typeName");
		 
	 
		$data["imagePath"] = I("imagePath");
		$data["content"] = I("content");
		$data["efficacySDate"] = I("efficacySDate");
		$data["efficacyEDate"] = I("efficacyEDate");
		//$data["endDate"] = I("endDate");		
		$data["miniConsumption"] = (int)I("miniConsumption");
		$data["maxiConsumption"] = I("maxiConsumption");
		$data["needPoint"] = (int)I("needPoint");
		
		$data["limitDayUse"] = (int)I("limitDayUse");
		$data["limitDayGet"] = (int)I("limitDayGet");
		$data["limitGetnum"] = (int)I("limitGetnum");
		$data["onlyNewUser"] = (int)I("onlyNewUser");
		$data["totalCount"] = (int)I("totalCount");
		$data["ticketAmount"] = (double)I("ticketAmount");
		$data["limitUseShopID"] = (int)I("limitUseShopID");//限制商家
		$data["detail"] =I("detail");//卡券详情
		
	    if($this->checkEmpty($data,true)){	
			$m = M('activity_ticket');
		    $rs = $m->where("ticketID='".$id."'")->save($data);
			if(false !== $rs){
				$rd['status']= 1;
			}
		}
		return $rd;
	} 
	 
	public function create_guid($namespace = '') {     
	    static $guid = '';
	    $uid = uniqid("", true);
	    $data = $namespace;
	    $data .= $_SERVER['REQUEST_TIME'];
	    $data .= $_SERVER['HTTP_USER_AGENT'];
	    $data .= $_SERVER['LOCAL_ADDR'];
	    $data .= $_SERVER['LOCAL_PORT'];
	    $data .= $_SERVER['REMOTE_ADDR'];
	    $data .= $_SERVER['REMOTE_PORT'];
	    $hash = strtoupper(hash('ripemd128', $uid . $guid . md5($data)));
	    $guid = '' .   
	            substr($hash,  0,  8) . 
	            '-' .
	            substr($hash,  8,  4) .
	            '-' .
	            substr($hash, 12,  4) .
	            '-' .
	            substr($hash, 16,  4) .
	            '-' .
	            substr($hash, 20, 12) .
	            '';
	    return $guid;
	}
	 /**
	  * 获取指定对象
	  */
     public function get(){
	 	$m = M('activity_ticket');
		return $m->where("ticketID='".I('id')."'")->find();
	 }
	 /**
	  * 分页列表
	  */
     public function queryByPage(){
        $m = M('activity_ticket');
	 	$sql = "select a.*,s.shopName from __PREFIX__activity_ticket a  left join __PREFIX__shops s on s.shopid=a.limitUseShopID ";
	 	if(I('title')!='')$sql.=" and title like '%".I('title')."%'";
	 	$sql.=' order by createTime desc';
		return $m->pageQuery($sql);
	 }
	 /**
	  * 获取列表
	  */
	  public function queryByList(){
	     $m = M('activity_ticket');
	     $sql = "select * from __PREFIX__activity_ticket  order by createTime desc";
		 $rs = $m->query($sql);
		 return $rs;
	  }
	  
	 /**
	  * 删除
	  */
	 public function del(){
	 	$rd = array('status'=>-1);
	    $m = M('activity_ticket');
	    $rs = $m->delete(I('id'));
		if(false !== $rs){
		   $rd['status']= 1;
		}
		return $rd;
	 }
	 /**
	  * 显示分类是否显示/隐藏
	  */
	 public function editiIsShow(){
	 	$rd = array('status'=>-1);
	 	if(I('id',0)==0)return $rd;
	 	$m = M('activity_ticket');
	 	$m->isShowInShop = ((int)I('isShow')==1)?1:0;
	 	$rs = $m->where("ticketID='".I('id',0)."'")->save();
	    if(false !== $rs){
			$rd['status']= 1;
		}
	 	return $rd;
	 }
};
?>