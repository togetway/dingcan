<?php
include "../header.php";
require_once "../class/UserInfo.class.php";
require_once "../class/Order.class.php";
require_once "../class/BalanceLog.class.php";
$user = new UserInfo();
$order = new Order();
$balancelog = new BalanceLog();


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['order_id'])) {
        $order_id = $_POST['order_id'];
    
		$orderinfo = $order->get_order_info($order_id);
		$uid = $orderinfo[0]['user_id'];
		
		#减少金额
		$total_min = $orderinfo[0]['total']-$orderinfo[0]['subsidies'];
		if($total_min < 0){$total_min=0;}

		if($orderinof[0]['paystatus'] != 'paid' ){
				$re = $user->pay($uid,$total_min);
				if($re){
					$userinfo = $user->get_user_info($uid);
					$describe = '支付了 <a href="/shop.php?id='.$orderinfo[0]['shop_id'].'">'.$orderinfo[0]['shop_name'].'</a> 的订单 › <a href="#" onclick="look_order(\''.$orderinfo[0]['id'].'\')">查看订单详情</a>';
					$balancelog->add('pay',$total_min,$userinfo[0]['balance'],$describe,$uid);
					$order->modify_order_status($order_id,'paid');
					$result = 'success';
				}else{
					$result = 'error';
				}
		}else{
			$result = 'error';
		}
		$orderinfo = $order->get_order_info($order_id);
	}
}
?>
<div class="wap columns clearfix">
  <h1 class="page-title">付款结果</h1>
  <section class="main clearfix inner">
    <h3>
      <?php if($result==="success") {?>
      <span class="text-success">支付成功</span>，点击返回<a href="/today.php">今日订单</a>
      <?php }else if($result==="error"){?>
	  <span class="text-error">支付失败</span>，点击返回<a href="/today.php">今日订单</a>
	  <?php }?>
    </h3>

    <p>当前账户余额： <?php echo $userinfo[0]['balance'];?> 元</p>
  </section>
</div>
<?php
include "../footer.php";
?>