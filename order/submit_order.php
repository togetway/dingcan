<?php
session_start();
require_once "../class/Order.class.php";
require_once "../class/Config.class.php";
$order = new Order();
$config = new Config();
$subsidies = $config->get_subsidies();
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['list']) ) {

        $order_list = $_POST['list'];
		$user_id = $_SESSION['dish_uid'];
		$user_name = $_SESSION['dish_realname'];
		$paystatus = 'default';
		$result=stripslashes($order_list);
		$result = json_decode($result);
		$total = 0;
		foreach($result as $value){
			$price = $value->price;
			$num = $value->num;
			$total = $total + $price * $num;
		}
		//$order_list = base64_encode($order_list);
		$rs = $order->add($order_list,$paystatus,$total,$user_id,$user_name,$subsidies);
		if($rs == 'order_ok'){
			echo '{"result":"success"}';
		}else if ($rs == 'order_and_paid'){
			echo '{"result":"order_and_paid"}';
		}else if ($rs == 'order_not_paid'){
			echo '{"result":"order_not_paid"}';
		}else{
			echo '{"result":"error"}';
		}
		
		//$rs = $order->today_order_list();
		//print_r($rs);
		
    }
}
?>