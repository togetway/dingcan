<?php
include "../header.php";
require_once "../../class/Shop.class.php";
require_once "../../class/Food.class.php";
$shop = new Shop();
$food = new Food();
if ($_SERVER['REQUEST_METHOD'] == 'GET' ) {
    if (isset($_GET['shop_id'])) {
        $shop_id = $_GET['shop_id'];
    }

    if (isset($_GET['categories_id'])) {
        $s_categories_id = $_GET['categories_id'];
    }
	
	if($shop_id){
		$shopinfo = $shop->get_shop_info($shop_id);
    }
}



if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  if( isset($_POST['shop_id']))
    $shop_id = $_POST['shop_id'];
  if (isset($_POST['name']) and isset($_POST['price']) and isset($_POST['week']) and isset($_POST['categories_id'])) 
	{
		$name = $_POST['name'];
		$price = $_POST['price'];
		$week = $_POST['week'];
		$s_categories_id = $_POST['categories_id'];
		
		if($name != "" and $price != "" and $week != ""){
      echo $name;
			$rs = $food->check_food_name($shop_id,$name);
			if(!$rs){
				$rs2 = $food->add($name,$price,$shop_id,$week,$s_categories_id);
				if($rs2){
					$tip = "添加美食【".$name."】成功！";
				}else{
					$tip = "添加美食【".$name."】失败！";
				}
			}else{
				$tip = "美食名【".$name."】已经存在！";
			}
		}else{
			$tip = "供应时间不能为空！";
		}
  }else{
    $tip = "错误：传递参数不全！";
  }
	if($shop_id){
		$shopinfo = $shop->get_shop_info($shop_id);
  }
}

$categorieslist = $shop->list_categories($shop_id);
if(!$categorieslist){
  echo "<script language='javascript'>window.history.go(-1);alert('未添加食品分类，无法添加食品，请先添加食品分类');</script>";
}

?>
<?php if ($tip){ ?>
  <div class="alert alert-info"><?php echo $tip; ?></div>
<?php } ?>

<script>
function load(url,title) {
  art.dialog.open(url, {title: title,lock:true});
};
</script>

<h2>在【<a href="/shop.php?id=<?php echo $shopinfo[0]['id'] ?>" target="_blank"><?php echo $shopinfo[0]['name'] ?></a>】中添加美食  <small><a href="/admin/shop/">返回店铺列表</a></small></h2>
<div class="">
  <div class="row">
    <div class="span10">
      <table class="table table-bordered">
        <thead>
        <tr>
          <th>分类</th>
          <th>菜名</th>
          <th>价格</th>
		  <th>供应时间</th>
          <th>操作</th>
        </tr>
        </thead>
        <tbody>
		<?php
		$foodlist = $food->food_list($shop_id,$s_categories_id);
    $lsname = $shop->list_categories_name($shop_id);
		foreach($foodlist as $key => $val){
			$id = $val['id'];
			$name = $val['name'];
			$price = $val['price'];
			$categories_id = $val['categories_id'];
			$week = $val['week'];

			//$weekname = $food->num_change_week($week);
      $weekname = $food->show_week($week);
		?>
        <tr>
          <td><?php echo $lsname[$categories_id];?></td>
          <td><?php echo $name;?></td>
          <td><?php echo $price;?> 元</td>
		      <td><?php echo $weekname;?></td>
          <td><a href="#"  onclick="load('/admin/food/edit.php?food_id=<?php echo $id;?>','修改美食')">编辑</a>|<a food-id="<?php echo $id;?>" id="deleteFood" href="javascript:void(0)" >删除</a></td>
        </tr>
        <?php }?>
        </tbody>
      </table>
    </div>
    <div class="span4 well">
      <form method="POST">
        <fieldset>
          <legend>添加美食</legend>

          <label for="categories_id">分类</label>
          <select name="categories_id" id="categories_id" required>
            <option value="">--选择食品分类--</option>
            <?php
            foreach ($lsname as $key => $value){
              if($s_categories_id == $key){
            ?>
            <option value="<?php echo $key;?>" selected = "selected"><?php echo $value;?></option>
            <?php
            }else{
            ?>
            <option value="<?php echo $key;?>"><?php echo $value;?></option>
            <?php }} ?>
          </select>(必选)

          <label for="name">名称</label>
          <input type="text" name="name" id="name" required >(必填)

          <label for="price">价格</label>
          <input type="text" name="price" id="price" required>(必填)

        

          <label for="week">供应时间 <input type="checkbox" name="chkAll" onclick="CheckAll(this.form)" checked='checked'/>全选</label>
          
          <input type="checkbox" name="week[]" value="1" checked='checked'/>一
          <input type="checkbox" name="week[]" value="2" checked='checked'/>二
          <input type="checkbox" name="week[]" value="3" checked='checked'/>三
          <input type="checkbox" name="week[]" value="4" checked='checked'/>四
          <input type="checkbox" name="week[]" value="5" checked='checked'/>五
          <input type="checkbox" name="week[]" value="6" checked='checked'/>六
          <input type="checkbox" name="week[]" value="0" checked='checked'/>日

          <label>&nbsp;</label>
          <button type="submit" class="btn btn-primary">添加美食</button>

          <input type="hidden" id='shop_id'name="shop_id" value="<?php echo $shopinfo[0]['id'];?>">

      </fieldset>
      </form>
    </div>
  </div>
</div>
<!-- Modal -->
  <div id="myModal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-header">
      <button type="button" class="close cancel" data-dismiss="modal" aria-hidden="true">×</button>
      <h3 id="myModalLabel">删除菜谱</h3>
    </div>
    <div class="modal-body">
      <p>确定删除这盘菜？</p>
    </div>
    <div class="modal-footer">
      <button class="btn btn-primary sure">确定</button>
      <button class="btn btn-danger cancel" data-dismiss="modal" aria-hidden="true">取消</button>
      
    </div>
  </div>
  <script>

  $("#categories_id").change(function(){
      var categories_id = $('#categories_id').val();
      var shop_id = $('#shop_id').val();
      location.href ="?shop_id="+shop_id+'&categories_id='+categories_id;
    });

  //监听删除食谱的按钮
  $("#deleteFood").unbind('click').live('click', function() {
    var id = $(this).attr("food-id");
    var that = $(this);
    //弹出选择是否删除窗口
    $('#myModal').modal({
          keyboard: true,
          show: true,
          backdrop: true
        });
    //管理员取消
    $(".cancel").unbind('click').live('click',function() {
        return ;
    })
    //管理员确定则执行删除操作
    $(".sure").unbind('click').live('click',function() {
      $("#myModal").modal('hide');
      $.ajax({
        url: '/admin/food/delete.php?id=' + id,
        type: 'GET',
        data: { timeStamp:new Date().getTime() },
        error: function(){
          alert('网络错误，请联系管理员');
        },
        success: function(data){
          that.closest("tr").remove();
        }
      })
    })     
  })
    
   //全选    
    function CheckAll(thisform){
      for (var i = 0; i < thisform.elements.length; i++) {
        var e = thisform.elements[i];
        if (e.Name != "chkAll" && e.disabled != true)
          e.checked = thisform.chkAll.checked;
      }
    }
  </script>
<?php
include "../footer.php";
?>
