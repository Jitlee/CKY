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
   			$this->display("default/shops/ticket/index");
// 		}
		
    }
    /**
     * 广告记数
     */
    public function access(){
    	$ads = D('Home/Ads');
    	$ads->statistics((int)I('id'));
    }
}
	