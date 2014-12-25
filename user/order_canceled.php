<?php
include "../header.php";
require_once "../class/UserInfo.class.php";
require_once "../class/Order.class.php";
$user = new UserInfo();
$order = new Order();


if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    if (isset($_GET['order_id'])) {
        $order_id = $_GET['order_id'];
		
		$order->order_canceled($order_id);
		$orderinfo = $order->get_order_info($order_id);
		
	}
	
}
?>
<div class="wap columns clearfix">

  <section class="main clearfix inner">
    <div class="today_order">

      <?php if($orderinfo[0]['canceled'] == 1) {?>
      <div class='alert alert-success'><span class="text-success">取消定单成功</span>，<a href="/index.php">重新点餐</a></div>
      <?php }else{?>
	  <span class="text-error">取消定单失败</span>，点击返回<a href="/today.php">今日订单</a>
	  <?php }?>
	  <br>
  
	  <p>取消定单内容：</p>
	  <br>
      <p><b><?php echo $orderinfo[0]['stime'];?></b> 在 <a target="_blank" href="/shop.php?<?php echo $orderinfo[0]['shop_id'];?>"><?php echo $orderinfo[0]['shop_name'];?></a> 购买了总价为
        <?php echo $orderinfo[0]['total'];?>元的美食。</p>
      <br>
      <ul style="margin-left: 30px;">
	  <?php 
		$result=stripslashes($orderinfo[0]['order']);
		$result = json_decode($result);
		foreach($result as $key => $val){
	  ?>
        <li><a target="_blank" href="/shop.php?id=<?php echo $val->shop_id;?>">★  <?php echo $val->shop_name;?></a> : 
        	<?php echo $val->name;?> x <?php echo $val->num;?> 份 = <?php echo $val->price;?>元 <?php if($val->remark){echo '<span class="text-error">('.$val->remark.')</span>';}?></li>
        <?php } ?>
      </ul>
    </div>
  </section>
</div>
<?php
include "../footer.php";
?>