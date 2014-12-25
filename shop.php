<?php
include "header.php";
require_once "class/Shop.class.php";
require_once "class/Food.class.php";
require_once "class/Config.class.php";
$shop = new Shop();
$food = new Food();
$config = new Config();

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    if (isset($_GET['id'])) {
        $shop_id = $_GET['id'];
    }
	
	if($shop_id){
		$shopinfo = $shop->get_shop_info($shop_id);
    }
}

$dish_status = $config->open_dish_status();
?>
<?php if($shopinfo[0]['css']){ ?>
<style type="text/css">
  <?php echo $shopinfo[0]['css'];?>
</style>
<?php }?>


<?php if($shopinfo[0]['note'] and $shopinfo[0]['isshow'] ){ ?>
<script>
art.dialog({
	init: function () {
    	var that = this, i = 6;
        var fn = function () {
            that.title(i + '秒后关闭,【<?php echo $shopinfo[0]['name'];?>】 特殊说明');
            !i && that.close();
            i --;
        };
        timer = setInterval(fn, 1000);
        fn();
    },
    lock: true,
    //width: 400,// 必须指定一个像素宽度值或者百分比，否则浏览器窗口改变可能导致artDialog收缩
    content: '<span class="text-error"><?php echo $shopinfo[0]['note'];?></span>',
    icon: 'warning',
	cancelVal: '关闭',
	cancel: true
});
</script>
<?php } ?>

<div class="wap columns clearfix">
	<?php 
	if(!$dish_status){
		echo "<div class='tip'>亲！还没到点餐时间，请等待行政MM开放点餐功能！</div>";
	}
  if($shopinfo[0]['isshow']){
	?>
  <div class="left">
    <h1 class="page-title">
      <strong><?php echo $shopinfo[0]['name'];?></strong>
      <span>电话：<?php echo $shopinfo[0]['tel'];?></span>
      <span>地址：<?php echo $shopinfo[0]['address'];?></span>
      <input type="hidden" id="shop_id" value="<?php echo $shopinfo[0]['id'];?>">
      <input type="hidden" id="shop_name" value="<?php echo $shopinfo[0]['name'];?>">
    </h1>
	<h2 class="note-title">
	  <strong>备注：</strong><span><?php echo $shopinfo[0]['note'];?></span>
    </h1>
    <section class="main clearfix">
      <section class="food-list inner clearfix">
        <?php
		$group = $food->group_list($shop_id);
		if($group){
			foreach($group as $key => $val){
      $cid = $val['id'];
			$categories = $val['categories'];
        ?>
        <div>
          <div class="cat-title"><span><?php echo $categories;?></span></div>
          <ul>
			<?php 
			$groupfood = $food->group_food($shop_id,$cid);

			foreach($groupfood as $key => $val){
				$id = $val['id'];
				$name = $val['name'];
				$price = $val['price'];
			?>
            <li id="food-<?php echo $id;?>" data-id="<?php echo $id;?>" data-name="<?php echo $name;?>"
                data-price="<?php echo $price;?>">
              <?php echo $name;?><em><?php echo $price;?> 元</em></li>
			<?php } ?>
          </ul>
          <div class="clearfix"></div>
        </div>
        <?php }}else{?>
        <p>还未添加美食</p>
        <?php } ?>
      </section>
    </section>
  </div>
  <div class="right">
    <section id="cart">
      <h3>我的餐盒</h3>

      <div class="r_mycart">
        <table cellpadding="0" cellspacing="0">
          <tbody>
          <tr>
            <th class="ttl">名称</th>
            <th width="40">份数</th>
            <th width="30">单价</th>
            <th class="del" width="30">删除</th>
          </tr>
          </tbody>
        </table>
        <p id="noItemTips" class="ptt pbb" style="text-align: center; display: none; ">您还没有添加菜品哦~</p>
        <table id="cartTable">
          <tbody>
          </tbody>
        </table>
      </div>

	  <?php if($dish_status){?>
      <div class="buy" ><span class="buy-btn" >添加到购物车</span><strong class="buy-price">总价：<span id="cart_zongjia">0</span> 元</strong>
      </div>
	  <?php } ?>
    </section>
  </div>
  <div id="car-confirm" class="reveal-modal">
    <div id="confirm-list"></div>
  </div>

  <?php
  }else{
    echo "该商店已经被管理员关闭，请选择其它商店 <a href='/'>点餐</a> ！ ";
  }
  ?>
</div>
<script src="/static/js/jquery.reveal.js" type="text/javascript"></script>
<script type="text/javascript" src="/static/js/cart.js"></script>
<?php
include "footer.php";
?>