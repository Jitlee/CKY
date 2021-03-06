<?php
 namespace Home\Model;
/**
 * ============================================================================
 * 粗卡云:
  
 * 联系方式:
 * ============================================================================
 * 平台信息服务类
 */
class MessagesModel extends BaseModel {
	 /**
	  * 删除
	  */
	 public function del(){
	 	$rd = array('status'=>-1);
	    $m = M('messages');
	    $map = array('id'=>(int)I('id'),'receiveUserId'=>(int)session('RTC_USER.userId'));
	    $rs = $m->where($map)->delete();
		if(false !== $rs){
		   $rd['status']= 1;
		}
		return $rd;
	 }
	/**
	 * 获取分页列表
	 */
	 public function queryByPage(){
	 	$userId=(int)session('RTC_USER.userId');
		$sql = "select * from __PREFIX__messages m where receiveUserId=".$userId;
		$sql.=" order by msgStatus asc,createTime desc ";
		return $this->pageQuery($sql);
	 }
	
	/**
	 * 获取消息
	 */
	public function get(){
		$id = (int)I('id');
        $map = array('id'=>$id,'receiveUserId'=>(int)session('RTC_USER.userId'));
        $info = $this->where($map)->find();
        if (!empty($info)) {
            if ($info['msgStatus'] == 0) {
                $this->where("id=".$id." and receiveUserId=".(int)session('RTC_USER.userId'))->save(array('msgStatus'=>1));
            }
        }
        return $info;
	}

	public function batchDel(){
		$ids = I('ids');
		$re = array();
        $map = array('id'=>array('in',$ids),'receiveUserId'=>(int)session('RTC_USER.userId'));
        $re['status'] = $this->where($map)->delete() === false ? -1 : 1 ;
        return $re;
	}
};
?>