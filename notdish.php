<?php
include "header.php";
include "class/UserInfo.class.php";
include "class/Order.class.php";
$user = new UserInfo();
$order = new Order();
$userlist = $order->not_dish_userlist();
?>




<div class="wap columns clearfix">
<div class="left2">
<h1 class="page-title">未点餐同学</h1>
<section class="main clearfix">
<?php
if($userlist){
?>
<table  class="data" width="100%" border="0" cellspacing="0" cellpadding="5"">
  <thead>
  <tr>
    <th>真实姓名</th>
    <th>RTX账号</th>
	<th>通知点餐</th>
  </tr>
  </thead>
  <tbody>
  <?php
	foreach($userlist as $key => $val){
		$id = $val['id'];
		$username = $val['username'];
		$realname = $val['realname'];
	?>
  <tr id="<?php echo $id;?>" username="<?php echo $username;?>" realname="<?php echo $realname;?>">
    <td><?php echo $realname;?></td>
    <td><?php echo $username;?></td>
    <td>
		<a href="#" onclick="yes_or_no('<?php echo $username;?>','<?php echo $realname;?>')"><img src="/static/img/rtx.jpg"></a>
    </td>
  </tr>
  <?php }?>
  </tbody>
</table>
<?php }else{ ?>
	很好！同学们都已经点餐！
 <?php } ?>
</section>
</div>
</div>

<script>
function yes_or_no(username,realname) {
    art.dialog.confirm("您确认RTX通知【"+realname+"】同学点餐吗？", function () {
        art.dialog.open("http://192.168.1.2:8900/dish/msg.php?username=" + username+"&realname="+realname, {title: '发送结果',lock:true});
    });
};
</script>

<?php
include "footer.php";
?>
