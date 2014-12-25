<?php
include "../header.php";
require_once "../../class/Shop.class.php";
$shop = new Shop();
if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    if (isset($_GET['id'])) {
        $shop_id = $_GET['id'];
    }
	$shopinfo = $shop->get_shop_info($shop_id);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['shop_id']) and isset($_POST['name']) and isset($_POST['address']) and isset($_POST['tel']) and isset($_POST['css']) and isset($_POST['note']) and isset($_POST['delivery_time'])) 
	{
    $shop_id = $_POST['shop_id'];
		$name = $_POST['name'];
		$address = $_POST['address'];
		$tel = $_POST['tel'];
		$css = $_POST['css'];
		$note = $_POST['note'];
		$delivery_time = $_POST['delivery_time'];
		
		if($name != ""){
			$rs = $shop->modify_shop($shop_id,$name,$address,$tel,$css,$note,$delivery_time);
			if($rs == 'update_shop_success'){
				$tip = "修改商家信息成功";
			}else if($rs == 'update_shop_fail'){
				$tip = "修改商家信息失败";
			}else if($rs == 'not_shop_id'){
				$tip = "商家ID不存在";
			}
			$shopinfo = $shop->get_shop_info($shop_id);
		}else{
			$tip = "商家名不能为空";
		}
    }
}

?>
<h2>修改店铺信息 <small><a href="/admin/shop/">返回店铺列表</a></small></h2>
  <?php if ($tip){ ?>
  <div class="alert alert-info"><?php echo $tip; ?></div>
  <?php } ?>
<form method="POST" class="form-horizontal well">
  <div class="control-group">
    <label class="control-label" for="name">名称</label>
    <div class="controls">
      <input type="text" name="name" id="name" value="<?php echo $shopinfo[0]['name'];?>">(必填)
    </div>
  </div>
  <div class="control-group">
    <label class="control-label" for="address">地址</label>
    <div class="controls">
      <input type="text" name="address" id="address" value="<?php echo $shopinfo[0]['address'];?>">
    </div>
  </div>
  <div class="control-group">
    <label class="control-label" for="tel">电话</label>
    <div class="controls">
      <input type="text" name="tel" id="tel" value="<?php echo $shopinfo[0]['tel'];?>">
    </div>
  </div>
  <div class="control-group">
      <label class="control-label" for="tel">时间</label>

      <div class="controls">
        <input name="delivery_time" type="radio" value="all" <?php if($shopinfo[0]['delivery_time'] == 'all'){echo 'checked';}?>/>全天
		<input name="delivery_time" type="radio" value="lunch" <?php if($shopinfo[0]['delivery_time'] == 'lunch'){echo 'checked';}?>/>午餐
		<input name="delivery_time" type="radio" value="dinner" <?php if($shopinfo[0]['delivery_time'] == 'dinner'){echo 'checked';}?>/>晚餐
		（送餐时间）
      </div>
    </div>
  <div class="control-group">
    <label class="control-label" for="address">备注</label>
    <div class="controls">
      <input type="text" name="note" id="note" value="<?php echo $shopinfo[0]['note'];?>">
    </div>
	
  </div>
  <div class="control-group">
    <label class="control-label" for="tel">样式</label>
    <div class="controls">

      <input type="radio" name="css" id="css" value=".food-list li {width: 580px;} .food-list li:nth-child(even) {margin-right: 0; }" >一行一列
      <input type="radio" name="css" id="css" value=".food-list li {width: 269px;} .food-list li:nth-child(even) {margin-right: 0; float:right;}" checked>一行两列
      <input type="radio" name="css" id="css" value=".food-list li:nth-child(3n) {margin-right: 0;}" >一行三列
    </div>
  </div>
  <div class="control-group">
    <input type="hidden" name="shop_id" id="shop_id" value="<?php echo $shopinfo[0]['id'];?>">
  </div>
  <div class="control-group">
    <div class="controls ">
      <button class="btn btn-primary" type="submit">编辑店铺</button>
    </div>
  </div>
</form>
<?php
include "../footer.php";
?>