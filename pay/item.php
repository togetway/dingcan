<?php
include "../header.php";
require_once "../class/UserInfo.class.php";
require_once "../class/Order.class.php";
$user = new UserInfo();
$order = new Order();

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    if (isset($_GET['order_id'])) {
        $order_id = $_GET['order_id'];
    }
	$orderinfo = $order->get_order_info($order_id);
	$userinfo = $user->get_user_info($orderinfo[0]['user_id']);
}
?>
<div class="wap columns clearfix">
  <h1 class="page-title">付款</h1>
  <section class="main clearfix inner">
    <div class="today_order">
      <p>当前账户余额： <?php echo $userinfo[0]['balance'];?> 元</p>
      <br>

      <p>你于 <b><?php echo $orderinfo[0]['stime'];?></b> 购买了总价为
        <?php echo $orderinfo[0]['total'];?>元的美食。</p>
      <br>
      <ul style="margin-left: 30px;">
	  <?php 
		$result=stripslashes($orderinfo[0]['order']);
		$result = json_decode($result);
		foreach($result as $key => $val){
	  ?>
        <li><a target="_blank" href="/shop.php?id=<?php echo $val->shop_id;?>">
          ★  <?php echo $val->shop_name;?></a> : <?php echo $val->name;?> x <?php echo $val->num;?> 份 = <?php echo $val->price;?>元 <?php if($val->remark){echo '<span class="text-error">('.$val->remark.')</span>';}?></li>
        <?php } ?>
      </ul>
		<br>
		<p>公司餐费补贴 <b><?php echo $orderinfo[0]['subsidies'];?></b> 元，实付<b> 
		<?php
			$pay = $orderinfo[0]['total'] - $orderinfo[0]['subsidies'];
				if($pay < 0){
					echo 0;
				}else{
					echo $pay;
				}
		?>
		</b> 元。 </p>
		
      <form action="/pay/submit_pay.php" method="post" style="margin: 20px 0;">
        <input type="hidden" name="order_id" value="<?php echo $order_id;?>"/>
        <input type="submit" class="btn btn-mini" value="现在付款">
      </form>
    </div>
  </section>
</div>
<?php
include "../footer.php";
?>