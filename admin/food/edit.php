<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <script type="text/javascript" src="/static/js/jquery.js"></script>
  <script type="text/javascript" src="/static/js/bootstrap.min.js"></script>
  <link rel="stylesheet" href="/static/bootstrap/css/bootstrap.css">
</head>
<body>
<?php
require_once "../../class/Shop.class.php";
require_once "../../class/Food.class.php";
$shop = new Shop();
$food = new Food();
if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    if (isset($_GET['food_id'])) {
        $food_id = $_GET['food_id'];
    }
	$foodinfo = $food->get_food_info($food_id);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['food_id']) and isset($_POST['name']) and isset($_POST['price']) and isset($_POST['week']) and isset($_POST['categories_id']) and isset($_POST['shop_id'])) 
	{
    $id = $_POST['food_id'];
		$name = $_POST['name'];
		$price = $_POST['price'];
		$week = $_POST['week'];
		$categories_id = $_POST['categories_id'];
		$shop_id = $_POST['shop_id'];
		
		if($name != "" and $price != ""){
			$rs = $food->modify_food($id,$name,$price,$week,$categories_id);
			if($rs == 'update_food_success'){
				$tip = "修改美食成功";
			}else if($rs == 'update_food_fail'){
				$tip = "修改美食失败";
			}else if($rs == 'not_food_id'){
				$tip = "美食IP不存在";
			}
			$foodinfo = $food->get_food_info($id);
		}else{
			$tip = "美食名价格不能为空";
		}
    }
	
}

$num_week = array(1=>'一',2=>'二',3=>'三',4=>'四',5=>'五',6=>'六',0=>'日');


?>
<?php if ($tip){ ?>
  <div class="alert alert-info"><?php echo $tip; ?></div>
<?php } ?>
<div class="well">
  <form method="POST">
      <label for="categories_id">分类</label>
      <select name="categories_id" id="categories_id" required>
        <?php
       $shop_id = $foodinfo[0]['shop_id'];
       $lsname = $shop->list_categories_name($shop_id);
            foreach ($lsname as $key => $value){
            ?>
            <option value="<?php echo $key;?>"><?php echo $value;?></option>
            <?php } ?>
      </select>

      <label for="name">名称</label>
      <input type="text" name="name" id="name" value="<?php echo $foodinfo[0]['name'];?>" required>

      <label for="price">价格</label>
      <input type="text" name="price" id="price" value="<?php echo $foodinfo[0]['price'];?>" required>


      <label for="price">供应时间 <input type="checkbox" name="chkAll" onclick="CheckAll(this.form)" />全选</label>
        <?php
          $week = $foodinfo[0]['week'];
          $week = split(',', $week);
          foreach($num_week as $key => $value){
              if(in_array($key,$week))
                echo '<input type="checkbox" name="week[]" value='.$key.' checked="checked" />'.$value ;
              else
                echo '<input type="checkbox" name="week[]" value='.$key.' />'.$value ;
          }
        ?>


      


      <label>&nbsp;</label>
      <button type="submit" class="btn btn-primary">修改美食</button>

      <input type="hidden" name="food_id" value="<?php echo $foodinfo[0]['id'];?>">
      <input type="hidden" name="shop_id" value="<?php echo $shop_id;?>">

  </form>
  </ul>
</div>
<script type="text/javascript">
  $(document).ready(function(){
    $('#week').val('<?php echo $foodinfo[0]['week'];?>');
    $('#categories_id option[value="<?php echo $foodinfo[0]['categories_id'];?>"]').attr("selected",true);
  });

  //全选    
    function CheckAll(thisform){
      for (var i = 0; i < thisform.elements.length; i++) {
        var e = thisform.elements[i];
        if (e.Name != "chkAll" && e.disabled != true)
          e.checked = thisform.chkAll.checked;
      }
    }
</script>

</body>
</html>
