<?php
namespace M\Action;
use Think\Controller;
 

class PayBaseAction extends BaseUserAction {
	 
	public function PayNotify($parameter)
	{
			$payres = array( "status"     => -1 ); 
		
			$orderNo=$parameter['OrderNo'];
			$transaction_id=$parameter['transaction_id'];
			$fee_type='RMB';//货币类型
			 
			$mMPay = D('M/MemberPay');
			$dataInfo=$mMPay->GetByPayNo($orderNo);
			
			if($dataInfo && $dataInfo["PayType"]=="recharge" && $dataInfo["Status"]==0)	
			{
				 $dataInfo["ChangeTime"]=date('Y-m-d H:i:s');
				 $dataInfo["result_code"]=$result_code.'';
				 $dataInfo["fee_type"]=$fee_type.'';
				 $dataInfo["transaction_id"]=$transaction_id.'';
				 $dataInfo["cash_fee"]=$cash_fee.'';
				 $dataInfo["Status"]=99;
				 
				 $cardid=$dataInfo["cardid"];
				 $result=$mMPay->UpdateRechange($dataInfo,$cardid);
				 
				if($result["status"] == 1)//订单支付状态
				{
					$payres["status"]=1;
				}
			}
			else if($dataInfo && $dataInfo["PayType"]=="order" && $dataInfo["Status"]==0)	
			{
				 $dataInfo["ChangeTime"]=date('Y-m-d H:i:s');
				 $dataInfo["result_code"]=$result_code.'';
				 $dataInfo["fee_type"]=$fee_type.'';
				 $dataInfo["transaction_id"]=$transaction_id.'';
				 $dataInfo["cash_fee"]=$cash_fee.'';
				 $dataInfo["Status"]=99;
				 
				 
				$tfee=$dataInfo['amount'];
				$accountmoney=$dataInfo['accountmoney'];					
				$accountscore=$dataInfo['accountscore'];
				//扣余额
				$orderid=$dataInfo["extendid"]; 
				if($accountmoney >0 || $accountscore>0)
				{
					$cardid=$dataInfo["cardid"];
					$res=$mMPay->OrderValuePay($dataInfo,$cardid);//更新订单状态
					if($res["status"] == 0)//返回状态
					{
						$wxm= new WxNotify();
						$wxm->SendOrderNotifyToShops($orderid);
						
						$payres["status"]=1;		 
					}
				}
				else //直接在线支付
				{
					$cardid=$dataInfo["cardid"];
				 	$result=$mMPay->UpdatePayOrder($dataInfo);	
					if($result["status"] == 1)//订单支付状态
					{
						$wxm= new WxNotify();
						$wxm->SendOrderNotifyToShops($orderid);
						
						$payres["status"]=1;	 
					}
				}
			}
			else if($dataInfo  && $dataInfo["Status"]==99) //已经处理过，状态成功。
			{
				$payres["status"]=1;
			}
			else
			{
				$content="-----------------未处理的对象-----------------";
				$content=$content."orderNo=$orderNo===支付宝交易号=$transaction_id";
				logger($content);
				
				$payres["status"]=1;
			}
			return $payres;
	}


}
	