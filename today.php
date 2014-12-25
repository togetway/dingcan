<?php
include "header.php";
require_once "class/UserInfo.class.php";
require_once "class/Order.class.php";
require_once "class/Shop.class.php";
require_once "class/Config.class.php";
$user = new UserInfo();
$order = new Order();
$shop = new Shop();
$config = new Config();

$uid = $_SESSION['dish_uid'];
$isadmin = $_SESSION['dish_isadmin'];
$userinfo = $user->get_user_info($uid);
$subsidies = $config->get_subsidies();

$HOUR = date("H");
if ($HOUR < 14 )
    $type = 'dinner';
else
    $type = 'lunch';

if($_SERVER['REQUEST_METHOD'] == 'POST'){
		if (isset($_POST['date']) and isset($_POST['type'])){
			$date = $_POST['date'];
			$type = $_POST['type'];
			
			if($date != "" and $type != ""){
				$order_list = $order->history_order_list($date,$type);
			}
		}
}else{
	$order_list = $order->today_order_list();
    $date=date("Y-m-d");
}



?>

<div class="wap">
	<form action="" method="POST">
	<h1 class="page-title">
    <span>历史订单查询：</span><input type="text" name="date" class="tcal" value="<?php echo $date;?>" />
	<select name="type">
		<option value="dinner" <?php if($type == 'dinner') echo 'selected="selected"';?>>午餐</option>
		<option value="lunch" <?php if($type == 'lunch') echo 'selected="selected"';?>>晚餐</option>
	</select>
	<input type="submit" class="buy-btn" value="查询">
	</h1>
	</form>
  <?php
 if($order_list){
  //店铺分类
  foreach($order_list as $key => $val){
	$shop_id = $key;
	$shopinfo = $shop->get_shop_info($shop_id);
	
  ?>
  <div id="shop-<?php echo $shopinfo[0]['id'];?>">
    <h1 class="page-title">
      <strong><a href="/shop.php?id=<?php echo $shopinfo[0]['id'];?>"><?php echo $shopinfo[0]['name'];?></a> </strong>
      <span class="shop_tel">电话：<?php echo $shopinfo[0]['tel'];?></span>
      <span class="shop_address">地址：<?php echo $shopinfo[0]['address'];?></span>
    </h1>
    <section class="main clearfix" style="margin-bottom: 20px">
      <section class="inner">
        <table id="today-list" class="today-tb data" cellpadding="0" cellspacing="0">
          <thead>
          <tr>
            <th class="name">姓名</th>
            <th class="food">订单明细</th>
            <th class="total">总价</th>
            <th class="payStatus">订单状态</th>
			
          </tr>
          </thead>
          <tbody>
          <?php
      
      //按名字分类
      $all_total = 0;
			foreach($val as $key2 => $val2){
				//$result=stripslashes($val2['order']);
				//$result = json_decode($result);
				
		  ?>
          <tr data-id="<?php echo $val2['id'];?>" <?php if($val2['canceled']){?>style="text-decoration:line-through;"<?php } ?>>
            <td class="name user-<?php echo $key2;?>">
			<?php
				if($val2['more'] > 1){
					echo "<span class='text-error'>".$key2."</span>";
				}else if ($key2 == $_SESSION['dish_realname']){
					echo "<span class='text-info'>".$key2."</span>";
				}else{
          echo $key2;
        }
			
			?>
			</td>
            <td class="food">
              <ul>
                <?php 
          $total = 0;
          //显示个人食物列表
					foreach($val2['food'] as $key3 => $val3){


						#统计各个餐品份数,已经取消定单不统计
						if(!$val2['canceled']){
							$analytics[$shop_id][$val3['name']]['num'] +=$val3['num'];
              $total_price[$shop_id]['price'] +=$val3['price'];
              $total += $val3['price']*$val3['num'];
              $all_total += $val3['price']*$val3['num'];
						}
				?>
                <li><?php echo $val3['name'];?> - <?php echo $val3['price'];?>元 - <?php if($val3['num']>1){echo '<span class="text-error">'.$val3['num'].' 份</span>';}else{echo $val3['num'].' 份';}?> <?php if($val3['remark'])echo '<span class="text-error">('.$val3['remark'].')</span>';?></li>
                <?php }?>
              </ul>
            </td>
            <td class="total"><?php echo $total; ?></td>
            <td>
			  <?php
				if($val2['canceled']){
				?>
              <span><b>已取消</b></span>
              <?php } else if($val2['paystatus'] === "paid") { ?>
              <span class="text-success"><b>已支付</b></span>
              <?php } else if($key2 == $_SESSION['dish_realname']){?>
              <a href="/pay/item.php?order_id=<?php echo $val2['order_id'];?>" class="text-info">支付</a> |
              <a href="#" onclick="order_canceled('<?php echo $val2['order_id'];?>','<?php echo $shopinfo[0]['name'];?>')" class="text-info">取消</a>
              <?php } else {?>
              <span class="text-error">未支付</span>
              <?php } ?>
            </td>
			
          </tr>
          <?php } ?>
          </tbody>
          
        </table>
      
        <table id="today-analytics" class="today-tb data">
          <thead>
          <tr>
            <th>订餐统计清单</th>
          </tr>
          </thead>
          <tbody>
		  <?php
			$totalNum = 0;
			if($analytics[$shop_id]){
			foreach($analytics[$shop_id] as $key4 => $val4){
				$totalNum += $val4['num'];

		  ?>
          <tr>
            <td><?php echo $key4 ; ?> - <?php echo $val4['num'];?>份</td>
          </tr>
          <?php }}?>
          </tbody>
          <tfoot>
          <tr style="text-align: right;background: #f9f9f9;">
            <td colspan="3">
              <p>共计 <b><?php echo $totalNum;?></b> 份， <b><?php echo $all_total;?></b> 元</p>
            </td>
          </tr>
          </tfoot>
        </table>

        <div class="clearfix"></div>
      </section>
    </section>
  </div>
  <?php }}else{ ?>
  <div class="main inner today_null">
    <div class="txt">
      <h3>大家还没点餐</h3>

      <p>可能大伙太忙了，赶紧去群里吼一声，大家快来点餐啊！</p>
    </div>
  </div>
  <?php }?>
</div>

<script>
function order_canceled(order_id, shop_name) {
    art.dialog.confirm("<b>人是铁，饭是钢。</b><br>确认取消您在【"+shop_name+"】中的定单吗？", function () {
		window.location.href="/user/order_canceled.php?order_id=" + order_id;
        //art.dialog.open("/user/order_canceled.php?order_id=" + order_id, {title: '结果',lock:true});
    });
};
</script>

<?php
include "footer.php";
?>
