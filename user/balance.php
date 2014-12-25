<?php
include "../header.php";
require_once "../class/UserInfo.class.php";
require_once "../class/BalanceLog.class.php";
$user = new UserInfo();
$balancelog = new BalanceLog();
$uid = $_SESSION['dish_uid'];
$userinfo = $user->get_user_info($uid);

$loglist = $balancelog->balancelog_list($uid);



?>
<div class="wap columns clearfix">
  <div class="left">
    <h1 class="page-title">帐户余额</h1>
    <section class="main clearfix">
      <div class="inner">
        <p>当前账户余额: <?php echo $userinfo[0]['balance'];?>元</p>
      </div>
      <table id="balance-log" class="data" width="100%" border="0" cellspacing="0" cellpadding="5">
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
      <div></div>
    </section>
  </div>
  <div class="right">
    <div class="block">
      <ul class="setting">
        <li><a href="/user/order.php">› 我的订单</a></li>
        <li><a href="/user/balance.php">› 账户余额</a></li>
        <li><a href="/user/account.php">› 账户信息</a></li>
      </ul>
    </div>
  </div>
</div>
<script>
function look_order(order_id) {
	art.dialog.load("/order/order_info.php?order_id=" + order_id, {title: '定单详细信息',lock:true});
};
</script>
<?php
include "../footer.php";
?>