<?php
include "../header.php";
require_once "../../class/Shop.class.php";
$shop = new Shop();
?>
  <h2>店铺列表<a style="margin-left: 50px;" class="btn btn-inverse" href="/admin/shop/add.php">添加店铺</a></h2>
  <table class="table table-striped">
    <thead>
      <tr>
		<th>序号</th>
        <th>名称</th>
		<th>送餐时间</th>
        <th>电话</th>
        <th>地址</th>
        <th>显示店铺</th>
        <th>操作</th>
      </tr>
    </thead>
    <tbody>
    <?php
	$shoplist = $shop->shop_list();
	foreach($shoplist as $key => $val){
		$id = $val['id'];
		$name = $val['name'];
		$delivery_time = $val['delivery_time'];
		$address = $val['address'];
		$tel = $val['tel'];
    $isshow = $val['isshow'];
    $categorieslist = $shop->list_categories($id);
	?>
      <tr>
		<td><?php echo $key+1;?></td>
        <td><a  href="/admin/shop/edit.php?id=<?php echo $id;?>" title="编辑店铺"><?php echo $name;?></a></td>
		<td><?php echo $shop->delivery_time_change($delivery_time);?></td>
        <td><?php echo $tel;?></td>
        <td><?php echo $address;?></td>
        <td>
          <?php if($isshow){?>
          <a class="btn btn-success btn-small isshow" shop-id="<?php echo $id;?>">已显示</a>
          <?php }else{?>
          <a class="btn btn-small isshow" shop-id="<?php echo $id;?>">已关闭</a>
          <?php }?>
        </td>
        <td>
          <a class="btn btn-info btn-small" href="/admin/shop/add_categories.php?shop_id=<?php echo $id;?>">编辑美食分类</a>
          <?php if($categorieslist){?>
          <a class="btn btn-info btn-small" href="/admin/food/add.php?shop_id=<?php echo $id;?>">编辑美食菜单</a>
          <?php }else{ ?>
          <a class="btn btn-info btn-small" title="请先添加美食分类" disabled>编辑美食菜单</a>
          <?php }?>
          <a class="btn btn-danger btn-small" id="deleteShop" shop-id="<?php echo $id;?>">删除店铺</a>
        </td>
      </tr>
    <?php } ?>
    </tbody>
  </table>
  <!-- Modal -->
  <div id="myModal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-header">
      <button type="button" class="close cancel" data-dismiss="modal" aria-hidden="true">×</button>
      <h3 id="myModalLabel">删除店铺</h3>
    </div>
    <div class="modal-body">
      <p>确定删除此店铺？</p>
    </div>
    <div class="modal-footer">
      <button class="btn btn-primary sure">确定</button>
      <button class="btn btn-danger cancel" data-dismiss="modal" aria-hidden="true">取消</button>
      
    </div>
  </div>
  <script>
  //监听删除用户的按钮
  $("#deleteShop").unbind('click').live('click', function(){
    var id = $(this).attr("shop-id");
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
        url: '/admin/shop/delete.php?id=' + id,
        type: 'GET',
        data: { timeStamp:new Date().getTime() },
        error: function() {
          alert('网络错误，请联系管理员');
        },
        success: function(data) {
          that.closest("tr").remove();
        }
      });
    });    
  });


  $(".isshow").unbind('click').live('click',function(){
      var id = $(this).attr("shop-id");
      var that = $(this);
      $.ajax({
        url: '/admin/shop/isshow.php?id=' + id,
        type: 'GET',
        data: { timeStamp:new Date().getTime() },//解决ie不能兼容问题
        error: function(){
          alert('网络错误，请联系管理员');
        },
        success: function(data){
          if(data){
            that.prev().text("true");
            that.addClass("btn-success");
            that.text("已显示");
          }else{
            that.prev().text("false");
            that.removeClass("btn-success");
            that.text("已关闭");
          }
        }
      })
    })

  </script>

<?php
include "../footer.php";
?>
