<?php
 namespace Home\Action;;
/**
 * ============================================================================
 * 粗卡云:
  
 * 联系方式:
 * ============================================================================
 * 卡券控制器
 */
class TicketAction extends BaseAction{
	public function index(){ 
		$this->assign("umark","Ticketindex");
		$this->display("default/shops/ticket/index");
    }
    /**
     * 卡券验证
     */
    public function access(){
    	$tick = D('Home/Ticket');
    	$res=$tick->check();
		$this->ajaxReturn($res);
    }
}
	