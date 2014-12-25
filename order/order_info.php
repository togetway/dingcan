<?php
session_start();
require_once "../class/Order.class.php";
require_once "../class/Config.class.php";
$order = new Order();
if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    if (isset($_GET['order_id'])) {

        $order_id = $_GET['order_id'];
		$orderinfo = $order->get_order_info($order_id);
    }
}



?>
    <div >
      <p><b>订单状态：</b>
      	<?php
		if($orderinfo[0]['canceled']){
		?>
      <span>已取消</span>
      <?php } else if($orderinfo[0]['paystatus'] === "paid") { ?>
      <span class="text-success">已支付</span>
      <?php } else {?>
      <span class="text-error">未支付</span>
      <?php } ?>
      </p>
      <p><b>消费时间：</b><?php echo $orderinfo[0]['stime'];?></p>
	  <p><b>消费总价:</b><?php echo $orderinfo[0]['total'];?></p>
	  <p><b>公司补贴:</b><?php echo $orderinfo[0]['subsidies'];?></p>
	  <p><b>食品列表:</b>
      <br>
      <ul>
	  <?php 
		$result=stripslashes($orderinfo[0]['order']);
		$result = json_decode($result);
		foreach($result as $key => $val){
	  ?>
        <li><a href="#">★  <?php echo $val->shop_name;?></a> : 
        	<?php echo $val->name;?> x <?php echo $val->num;?> 份 = <?php echo $val->price;?>元 <?php if($val->remark){echo '<span class="text-error">('.$val->remark.')</span>';}?></li>
        <?php } ?>
      </ul>
	  </p>
    </div>