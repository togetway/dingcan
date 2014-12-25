<?php
include "../header.php";
require_once "../../class/Shop.class.php";
$shop = new Shop();
if ($_SERVER['REQUEST_METHOD'] == 'GET' ) {
    if (isset($_GET['shop_id'])) {
        $shop_id = $_GET['shop_id'];
    }
	
	if($shop_id){
		$shopinfo = $shop->get_shop_info($shop_id);
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['categories']) and isset($_POST['shop_id'])) 
	{
		$categories = $_POST['categories'];
		$shop_id = $_POST['shop_id'];
		
		if($categories != ""){
			$rs = $shop->check_categories($shop_id,$categories);
			if(!$rs){
				$rs2 = $shop->add_categories($shop_id,$categories);
				if($rs2){
					$tip = "添加美食分类【".$categories."】成功！";
				}else{
					$tip = "添加美食分类【".$categories."】失败！";
				}
			}else{
				$tip = "美食分类【".$categories."】已经存在！";
			}
		}else{
			$tip = "名字不能为空";
		}
    }
	if($shop_id){
		$shopinfo = $shop->get_shop_info($shop_id);
    }
}

?>
<?php if ($tip){ ?>
  <div class="alert alert-info"><?php echo $tip; ?></div>
<?php } ?>
<h2>在【<a href="/shop.php?id=<?php echo $shopinfo[0]['id'] ?>" target="_blank"><?php echo $shopinfo[0]['name'] ?></a>】中添加食品分类  <small><a href="/admin/shop/">返回店铺列表</a></small></h2>
<div class="">
  <div class="row">
    <div class="span10">
      <table class="table table-bordered">
        <thead>
        <tr>
          <th>店铺</th>
          <th>食品分类</th>
          <th>操作</th>
        </tr>
        </thead>
        <tbody>
		<?php
		$categorieslist = $shop->list_categories($shop_id);
		foreach($categorieslist as $key => $val){
			$id = $val['id'];
			$shop_id = $val['shop_id'];
			$categories = $val['categories'];
		?>
        <tr>
          <td><?php echo $shopinfo[0]['name'];?></td>
          <td><?php echo $categories;?></td>
          <td><a categories-id='<?php echo $id;?>' categories='<?php echo $categories;?>'   id='modifyCategories' href="javascript:void(0)">编辑</a>
            | <a food-id="<?php echo $id;?>" id="deleteCategories" href="javascript:void(0)" >删除</a></td>
        </tr>
        <?php }?>
        </tbody>
      </table>
    </div>
    <div class="span4 well">
      <form method="POST">
        <div class="item">
          <label for="name">食品分类名称</label>
          <input type="text" name="categories" id="categories" required>(必填)
        </div>
        <div class="item">
          <label>&nbsp;</label>
          <button type="submit" class="btn btn-primary">添加</button>
        </div>
        <div class="item">
          <input type="hidden" name="shop_id" value="<?php echo $shopinfo[0]['id'];?>">
        </div>
      </form>
    </div>
  </div>
</div>
<!-- Modal -->
  <div id="myModal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-header">
      <button type="button" class="close cancel" data-dismiss="modal" aria-hidden="true">×</button>
      <h3 id="myModalLabel">删除食品分类</h3>
    </div>
    <div class="modal-body">
      <p>确定删除这食品分类吗？</p>
      <p>删除食品分类会连同分类下的食品一并删除</p>
    </div>
    <div class="modal-footer">
      <button class="btn btn-primary sure">确定</button>
      <button class="btn btn-danger cancel" data-dismiss="modal" aria-hidden="true">取消</button>
      
    </div>
  </div>

  <div id="Modify" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="ModifyLabel" aria-hidden="true">
    <div class="modal-header">
      <button type="button" class="close cancel" data-dismiss="modal" aria-hidden="true">×</button>
      <h3 id="ModifyLabel">修改食品分类名称</h3>
    </div>
    <div class="modal-body">
      食品分类名称:<input type='text' id='s_categories' name='s_categories'>
    </div>
    <div class="modal-footer">
      <button class="btn btn-primary sure">确定</button>
      <button class="btn btn-danger cancel" data-dismiss="modal" aria-hidden="true">取消</button>
      
    </div>
  </div>
  <script>
  //监听删除食谱的按钮
  $("#deleteCategories").unbind('click').live('click', function() {
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
        url: '/admin/shop/delete_categories.php?id=' + id,
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

  $("#modifyCategories").unbind('click').live('click', function() {
      var categories = $(this).attr("categories");
      var categories_id = $(this).attr("categories-id");

      $('#s_categories').val(categories);
      var that = $(this);

      $('#Modify').modal({
        keyboard: true,
        show: true,
        backdrop: true
      });

      $(".cancel").unbind('click').live('click', function() {
        return ;
      })
      $(".sure").unbind('click').live('click', function() {
        var s_categories = $('#s_categories').val();
        $('#Modify').modal('hide');
        $.ajax({
          url: '/admin/shop/edit_categories.php',
          type: 'POST',
          data: {'id':categories_id, 'name':s_categories, 'timeStamp':new Date().getTime()},
          success: function(data){
            alert(data);
            window.location.reload();
          },
          error: function(){
            alert('修改失败');
          }
          
        })
      })
    })

  </script>
<?php
include "../footer.php";
?>