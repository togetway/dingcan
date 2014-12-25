<?php
include "../header.php";
require_once "../../class/UserInfo.class.php";
require_once "../../class/BalanceLog.class.php";
$user = new UserInfo();
$balancelog = new BalanceLog();

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
	if (isset($_GET['user_id'])) {
       $user_id = $_GET['user_id'];
	}
	$userinfo = $user->get_user_info($user_id);
	$loglist = $balancelog->balancelog_list($user_id);
}

?>
<h2>【<?php echo $userinfo[0]['realname'];?>】余额变动记录 <small><a href="/admin/user/">返回用户列表</a></small></h2>
<table class="table table-striped">
  <thead>
  <tr>
    <th>时间</th>
    <th>类型</th>
    <th>数额</th>
    <th>余额</th>
    <th class="last">描述</th>
  </tr>
  </thead>

  <tbody>
  <?php 
	   foreach($loglist as $key => $val){
	   ?>
        <tr>
          <td><?php echo $val['stime'];?></td>
          <td>
            <?php if ($val['type'] === "recharge") { ?>
            <span class="text-info">充值</span>
            <?php } else if($val['type'] === "pay"){?>
            消费
            <?php } ?>
          </td>
          <td class="txtr type-<?php echo $val['tpye'];?>"><?php echo $val['amount'];?></td>
          <td class="txtr"><?php echo $val['balance'];?></td>
          <td class="last describe"><?php echo $val['describe'];?></td>
        </tr>
        <?php } ?>
  </tbody>
</table>
<script>
function look_order(order_id) {
	art.dialog.load("/order/order_info.php?order_id=" + order_id, {title: '定单详细信息',lock:true});
};
</script>
<?php
include "../footer.php" 
?>