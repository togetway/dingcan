<?php
include "../header.php";
include "../../class/UserInfo.class.php";
$user = new UserInfo();
?>
<h2>用户列表</h2>
<table class="table table-striped">
  <thead class="hd-row">
  <tr>
	<th>序号</th>
    <th>真实姓名</th>
    <th>账号</th>
    <th>余额</th>
    <th>用户类型</th>
    <th>操作</th>
    <th>删除用户</th>
  </tr>
  </thead>
  <tbody>
  <?php
	$userlist = $user->user_list();
	$all_balance = 0;
	$user_count = 0;
	$min_user = 0;
	$min_balance = 0;
	foreach($userlist as $key => $val){
		$id = $val['id'];
		$username = $val['username'];
		$realname = $val['realname'];
		$balance = $val['balance'];
		$isadmin = $val['isadmin'];
		$all_balance += $balance;
		$user_count += 1;
		if($balance<0){$min_user += 1;$min_balance += $balance;}
	?>
  <tr id="<?php echo $id;?>">
	<td><?php echo $key+1;?></td>
    <td><?php echo $realname;?></td>
    <td><?php echo $username;?></td>
    <td>
	<?php 
		if($balance <= 0){
			echo "<span class='text-error'>".$balance." 元</span>";
		}else{
			echo $balance." 元";
		}
	?> 
	</td>
    <td>
      <?php if($isadmin) {?>
      <a class="btn btn-success btn-small Admin">超级管理</a>
      <?php }else{ ?>
      <a class="btn btn-small Admin">普通用户</a>
      <?php } ?>
    </td>
    <td>
      <a href="/admin/user/orders.php?user_id=<?php echo $id ;?>" class="btn btn-info btn-small" >查看订单</a>
      <a href="/admin/user/balance.php?user_id=<?php echo $id ;?>" class="btn btn-info btn-small" >变动记录</a>
      <a href="/admin/user/add_balance.php?user_id=<?php echo $id ;?>" class="btn btn-info btn-small" >充值</a>
	  <a href="/admin/user/modify_balance.php?user_id=<?php echo $id ;?>" class="btn btn-info btn-small" >余额微调</a>
    </td>
    <td>
      <a class="btn btn-danger btn-small" id="deleteUser" >删除用户</a>
    </td>
  </tr>
  <?php } ?>
  </tbody>
</table>
<table class="table">
<tr><th>总人数：</th><td><?php echo $user_count;?> 人</td>
<th>总金额：</th><td><?php echo $all_balance;?> 元</td>
<th>欠费人数：</th><td><?php echo $min_user;?> 人</td>
<th>欠费总金额：</th><td><?php echo $min_balance;?> 元</td>
</tr>
</table>
<!-- Modal -->
<div id="myModal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-header">
    <button type="button" class="close cancel" data-dismiss="modal" aria-hidden="true">×</button>
    <h3 id="myModalLabel">Modal header</h3>
  </div>
  <div class="modal-body">
    <p>确定删除此用户？</p>
  </div>
  <div class="modal-footer">
    <button class="btn btn-primary sure">确定</button>
    <button class="btn btn-danger cancel" data-dismiss="modal" aria-hidden="true">取消</button>
  </div>
</div>
<script type="text/javascript">
  $(function(){
    //监听超级管理员按钮
    $(".Admin").unbind('click').live('click',function(){
      var id = $(this).closest("tr").attr("id");
      var that = $(this);
      $.ajax({
        url: '/admin/user/isadmin.php?id=' + id,
        type: 'GET',
        data: { timeStamp:new Date().getTime() },//解决ie不能兼容问题
        error: function(){
          alert('网络错误，请联系管理员');
        },
        success: function(data){
          if(data){
            that.prev().text("true");
            that.addClass("btn-success");
            that.text("超级管理");
          }else{
            that.prev().text("false");
            that.removeClass("btn-success");
            that.text("普通用户");
          }
        }
      })
    })
    //监听删除用户的按钮
    $("#deleteUser").unbind('click').live('click', function(){
      var id = $(this).closest("tr").attr("id");
      var that = $(this);
      //弹出选择是否删除窗口
      $('#myModal').modal({
        keyboard: true,
        show: true,
        backdrop: true
      });
      //管理员取消
      $(".cancel").unbind('click').live('click',function(){
        return ;
      })
      //管理员确定则执行删除操作
      $(".sure").unbind('click').live('click',function(){
        $("#myModal").modal('hide');
        $.ajax({
          url: '/admin/user/delete.php?id=' + id,
          type: 'GET',
          data: {timeStamp:new Date().getTime()},
          error: function(){
            alert('网络错误，请联系管理员');
          },
          success: function(data){
            that.closest("tr").remove();
          }
        })
      })
    })

  })
</script>
<?php
include "../footer.php";
?>
