<?php
include "../header.php";
require_once "../../class/Shop.class.php";
$shop = new Shop();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['name']) and isset($_POST['address']) and isset($_POST['tel'])  and isset($_POST['css']) and isset($_POST['note']) and isset($_POST['delivery_time'])) 
	{
		$name = $_POST['name'];
		$address = $_POST['address'];
		$tel = $_POST['tel'];
		$css = $_POST['css'];
		$note = $_POST['note'];
		$delivery_time = $_POST['delivery_time'];
		if($name != ""){
			$rs = $shop->check_shop_name($name);
			if(!$rs){
				$rs2 = $shop->add($name,$address,$tel,$css,$note,$delivery_time);
				if($rs2){
					$tip = "添加商店【".$name."】成功！";
				}else{
					$tip = "添加商店【".$name."】失败！";
				}
			}else{
				$tip = "商店【".$name."】已经存在！";
			}
		}else{
			$tip = "商店名不能为空";
		}
    }
}
?>
<h2>添加店铺 <small><a href="/admin/shop/">返回店铺列表</a></small></h2>
<?php if ($tip){ ?>
  <div class="alert alert-info"><?php echo $tip; ?></div>
<?php } ?>
<div class="well">
  <form method="POST" class="form-horizontal" >
    <div class="control-group">
      <label class="control-label" for="name">名称</label>

      <div class="controls">
        <input type="text" name="name" id="name" required>(必填)
      </div>
    </div>
    <div class="control-group">
      <label class="control-label" for="address">地址</label>

      <div class="controls">
        <input type="text" name="address" id="address">
      </div>
    </div>
    <div class="control-group">
      <label class="control-label" for="tel">电话</label>

      <div class="controls">
        <input type="text" name="tel" id="tel">
      </div>
    </div>
	<div class="control-group">
      <label class="control-label" for="tel">时间</label>

      <div class="controls">
        <input name="delivery_time" type="radio" value="all" checked />全天
		<input name="delivery_time" type="radio" value="lunch" />午餐
		<input name="delivery_time" type="radio" value="dinner" />晚餐
		（送餐时间）
      </div>
    </div>
	<div class="control-group">
      <label class="control-label" for="tel">备注</label>

      <div class="controls">
        <input type="text" name="note" id="note">
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
      <div class="controls">
        <button class="btn btn-primary" type="submit">添加店铺</button>
      </div>
    </div>
  </form>
</div>
<?php
include "../footer.php";
?>