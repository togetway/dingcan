<?php
session_start();
require_once "../../class/UserInfo.class.php";
require_once "../../class/Order.class.php";
require_once "../../class/BalanceLog.class.php";
$user = new UserInfo();
$order = new Order();
$balancelog = new BalanceLog();
if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    if (isset($_GET['order_id'])) {
        $order_id = $_GET['order_id'];
		
		
		$orderinfo = $order->get_order_info($order_id);
		#减少金额
		$total_min = $orderinfo[0]['total']-$orderinfo[0]['subsidies'];
		if($total_min < 0){$total_min=0;}
		
		#如果定单不是支付状态，不处理
		if($orderinof[0]['paystatus'] != 'paid' ){
			$rs = $user->pay($orderinfo[0]['user_id'],$total_min);
			if($rs){
				$userinfo = $user->get_user_info($orderinfo[0]['user_id']);
				$describe = '支付了 <a href="/shop.php?id='.$orderinfo[0]['shop_id'].'">'.$orderinfo[0]['shop_name'].'</a> 的订单 › <a href="#" onclick="look_order(\''.$orderinfo[0]['id'].'\')">查看订单详情</a> 操作人员【'.$_SESSION['dish_realname'].'】';
				$balancelog->add('pay',$total_min,$userinfo[0]['balance'],$describe,$orderinfo[0]['user_id']);
				$order->modify_order_status($order_id,'paid');
				echo "支付定单成功";
			}else{
				echo "支付定单失败";
			}
		}
	}
}
?>