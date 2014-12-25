<?php
include 'header.php';
require_once "class/UserInfo.class.php";
require_once "class/Shop.class.php";
require_once "class/Config.class.php";
$user = new UserInfo();
$shop = new Shop();
$config = new Config();
$uid = $_SESSION['dish_uid'];
$userinfo = $user->get_user_info($uid);
$dish_status = $config->open_dish_status();
$subsidies = $config->get_subsidies();

$dish_show_note = $_SESSION['dish_show_note'];
?>

<?php if($dish_show_note){?>
<script>
<?php if($userinfo[0]['balance']<10){ ?>
art.dialog.notice({
    title: '余额不足',
    width: 400,// 必须指定一个像素宽度值或者百分比，否则浏览器窗口改变可能导致artDialog收缩
    content: '亲，您的余额不足10元，请移步行政MM处进行充值！',
    icon: 'face-sad',
    time: 10
});
<?php } ?>

art.dialog({
	title: '餐费补贴提醒',
	content: '<p>欢迎进入点餐系统！</p><p>您的余额为：<b><?php echo $userinfo[0]['balance'];?></b> 元</p><p><span class="text-error">当前餐费补贴金额：<b><?php echo $subsidies;?></b> 元</span></p>',
	icon: 'face-smile',
	time: 5,
});

</script>
<? $_SESSION['dish_show_note'] = 0; }?>


<div class="wap">
  <section class="main clearfix" style="background: none;">
  <?php 
	if(!$dish_status){
		echo "<div class='tip'>亲！还没到点餐时间，请等待行政MM开放点餐功能！</div>";
	}
	?>
    <section class="fav clearfix" >
	<?php
	$shoplist = $shop->show_shop_list();
	if($shoplist){
	foreach($shoplist as $key => $val){
		$id = $val['id'];
		$name = $val['name'];
		$address = $val['address'];
		$tel = $val['tel'];
		$note = $val['note'];
	?>
      <div class="food-item">
        <h3><a href="/shop.php?id=<?php echo $id;?>"><?php echo $name;?></a></h3>
        <p><em>地址：</em><?php echo $address;?><br/><em>电话：</em><?php echo $tel;?> </p>
        <a href="/shop.php?id=<?php echo $id;?>"><img src="static/images/shop/1.png"/></a>
      </div>
    <?php
		}
	}else{
		echo '<span class="text-error">店铺已全部关闭或者不提供点餐功能</span>';
	}

     ?>
    </section>
  </section>
</div>
<?php include 'footer.php';?>

