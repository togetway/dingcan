<?php
include "../header.php";
require_once "../class/UserInfo.class.php";
require_once "../class/Order.class.php";
$user = new UserInfo();
$order = new Order();
$uid = $_SESSION['dish_uid'];
$userinfo = $user->get_user_info($uid);

$orderlist = $order->user_order_list($uid);

?>
<div class="wap columns clearfix">
  <div class="left">
    <section class="fav clearfix">
      <?php
      	if($orderlist){
		foreach($orderlist as $key => $val){
			$id = $val['id'];
			$user_id = $val['user_id'];
			$user_name = $val['user_name'];
			$shop_id = $val['shop_id'];
			$shop_name = $val['shop_name'];
			$stime = $val['stime'];
			$total = $val['total'];
			$remark = $val['remark'];
			$subsidies = $val['subsidies'];
	  ?>
      <table class="myorder" cellpadding="0" cellspacing="0" id="order-<?php echo $id;?>">
        <tbody>
        <tr class="hd-row">
          <td colspan="6"><?php echo $stime;?> 的购买清单。
				
			  支付状态:
	    <?php
		if($val['canceled']){
		?>
      <span>已取消</span>
      <?php } else if($val['paystatus'] === "paid") { ?>
      <span class="text-success">已支付</span>
      <?php } else {?>
      <span class="text-error">未支付</span>
      <?php } ?>
          </td>
        </tr>
		<?php
			$i = 0;
			$result=stripslashes($val['order']);
			$result = json_decode($result);
			foreach($result as $key2 => $val2){
			$i += 1;
		?>
        <tr>
          <td class="shop_name-td"><?php echo $val2->shop_name;?></td>
          <td class="shop_name-td"><?php echo $val2->name;?> - <?php echo $val2->price;?> 元 - <?php echo $val2->num;?> 份 
          	<?php if($val2->remark)echo '<span class="text-error">('.$val2->remark.')</span>';?>
          </td>
          <?php if($i==1&&count($result)==1){?>
          <td class="total-td"><?php echo $total;?>-<?php echo $subsidies ;?>=
			<?php
				$pay = $total - $subsidies;
				if($pay < 0){
					echo 0;
				}else{
					echo $pay;
				}
			?>元
		  </td>

          <?php }else if($i==1&&count($result)>1){?>
          <td rowspan="<?php echo count($result);?>" class="total-td">
		  <?php echo $total;?>-<?php echo $subsidies ;?> =
			<?php
				$pay = $total - $subsidies;
				if($pay < 0){
					echo 0;
				}else{
					echo $pay;
				}
			?>元
		  </td>

          <?php } ?>
        </tr>
        <?php }?>
        </tbody>
      </table>
      <?php } 

      }else{
      	echo "无任何订单";
      }
      ?>
    </section>
  </div>
  <div class="right">
    <div class="block">
      <ul class="setting">
        <li><a href="/user/order.php">› 我的订单</a></li>
        <li><a href="/user/balance.php">› 账户余额</a></li>
        <li><a href="/user/account.php">› 账户信息</a></li>
      </ul>
    </div>
  </div>
</div>
<?php
include "../footer.php";
?>