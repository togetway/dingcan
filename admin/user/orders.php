<?php
include "../header.php";
require_once "../../class/UserInfo.class.php";
require_once "../../class/Order.class.php";
$user = new UserInfo();
$order = new Order();

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
	if (isset($_GET['user_id'])) {
       $user_id = $_GET['user_id'];
	}
	$userinfo = $user->get_user_info($user_id);
	$orderlist = $order->user_order_list($user_id);
}
?>
<h2>用户【<?php echo $userinfo[0]['realname'];?>】的历史订单 <small><a href="/admin/user/">返回用户列表</a></small></h2>
	<?php
		foreach($orderlist as $key => $val){
			$id = $val['id'];
			$user_id = $val['user_id'];
			$user_name = $val['user_name'];
			$shop_id = $val['shop_id'];
			$shop_name = $val['shop_name'];
			$stime = $val['stime'];
			$total = $val['total'];
			$subsidies = $val['subsidies'];
	  ?>
<table  class="table table-bordered">
  <tbody>
  <tr class="hd-row">
    <td colspan="4">
      <?php echo $stime;?> 的购买清单
      <span>，共计：<?php echo $total;?> 元</span>
	  <span>，实付：<?php echo $total;?>-<?php echo $subsidies ;?>=
			<?php
				$pay = $total - $subsidies;
				if($pay < 0){
					echo 0;
				}else{
					echo $pay;
				}
			?>元，</span>
      支付状态:
	    <?php
		if($val['canceled']){
		?>
      <span>已取消</span>
      <?php } else if($val['paystatus'] === "paid") { ?>
      <span class="text-success">已支付</span>
        <!--只允许取消当天定单-->
        <?php if($order->is_canceled_order($stime)){?>
        <a href="#" onclick="order_canceled('<?php echo $id;?>','<?php echo $user_id;?>')">【取消定单】</a>
        <?php }?>
      <?php } else {?>
      <span class="text-error">未支付</span>
      <a href="#" onclick="order_pay('<?php echo $id;?>','<?php echo $user_id;?>')">【支付】</a>
      <?php } ?></td>
    </td>
  </tr>
  <?php
	$result=stripslashes($val['order']);
	$result = json_decode($result);
	foreach($result as $key2 => $val2){
  ?>
  <tr>
    <td class="shop_name-td"><?php echo $val2->shop_name;?></td>
    <td class="shop_name-td"><?php echo $val2->name;?> <?php if($val2->remark)echo '<span class="text-error">('.$val2->remark.')</span>';?></td>
    <td class="price-td"><?php echo $val2->price;?>元</td>
    <td class="num-td"><?php echo $val2->num;?>份</td>
  </tr>
  <?php }?>
  </tbody>
</table>
<?php } ?>
<!--<div class="actions">
  <a class="btn btn-primary" href="/admin/shop/add">添加店铺</a>
</div>-->
<script>

function order_canceled(order_id,user_id) {
    art.dialog.confirm("确认取消该定单吗？", function () {
		art.dialog.load("/admin/user/order_canceled.php?order_id=" + order_id, {title: '取消定单',lock:true,
        ok: function(){
            location.href = "/admin/user/orders.php?user_id=" + user_id;
        }
		});
    });
};


function order_pay(order_id,user_id) {
    art.dialog.confirm("确认支付该定单吗？", function () {
		art.dialog.load("/admin/user/order_pay.php?order_id=" + order_id, {title: '取消定单',lock:true,
        ok: function(){
            location.href = "/admin/user/orders.php?user_id=" + user_id;
        }
		});
    });
};

</script>
<?php
include "../footer.php" 
?>
