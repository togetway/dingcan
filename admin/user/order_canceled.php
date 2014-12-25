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
		#补回金额
		$total_add = $orderinfo[0]['total']-$orderinfo[0]['subsidies'];
		if($total_add < 0){$total_add=0;}
		
		#如果定单不是支付状态，不处理
		if($orderinfo[0]['paystatus'] == 'paid' and  $orderinfo[0]['canceled'] == 0){
			$rs1 = $order->order_canceled($order_id);
			$rs2 = $user->recharge($orderinfo[0]['user_id'],$total_add);
			if($rs1 and $rs2){
				$userinfo = $user->get_user_info($orderinfo[0]['user_id']);
				$describe = '已支付定单取消返还 <a href="#" onclick="look_order(\''.$orderinfo[0]['id'].'\')">查看订单详情</a> 操作人员【'.$_SESSION['dish_realname'].'】';
				$balancelog->add('recharge',$total_add,$userinfo[0]['balance'],$describe,$orderinfo[0]['user_id']);
				echo "取消定单成功<br>返还 $total_add 元。";
			}else{
				echo "取消定单失败";
			}
		}
	}
}
?>