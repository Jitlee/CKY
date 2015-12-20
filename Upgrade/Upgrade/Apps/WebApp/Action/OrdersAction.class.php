<?php
namespace WebApp\Action;
/**
 * ============================================================================ 
 * ============================================================================
 * 基础控制器
 */
class OrdersAction extends BaseAction {

	/**
	 * 订单管理
	 */
  public function index(){
    $orders = D('WebApp/Orders');
    $ordersList = $orders->getOrderList();
    // echo '<pre>';
    // var_dump($ordersList);
    // exit;
    $this->assign('ordersList',$ordersList);
    $this->display("default/orders_management");
  }

}