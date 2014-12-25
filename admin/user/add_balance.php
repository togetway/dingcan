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
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	if (isset($_POST['user_id']) and isset($_POST['amount'])) {
		$user_id = $_POST['user_id'];
        $amount = $_POST['amount'];
		$rs = $user->recharge($user_id,$amount);
		if($rs){
			$userinfo = $user->get_user_info($user_id);
			$describe = "充值 人民币".$amount."  操作人员【".$_SESSION['dish_realname']."】";
			$balancelog->add('recharge',$amount,$userinfo[0]['balance'],$describe,$user_id);
			$result = 'success';
			
		}else{
			$result = 'error';
		}
	    
		
		$loglist = $balancelog->balancelog_list($user_id);
	}
}

?>
<?php if($result === "success"){?>
<div class="alert alert-success">冲值成功</div>
<?php }else if ($result === "error"){?>
<div class="alert alert-error">冲值成功</div>
<?php }?>
<h2>为【<?php echo $userinfo[0]['realname'];?>】充值 <small><a href="/admin/user/">返回用户列表</a></small></h2>
<p>当前账户余额：￥<?php echo $userinfo[0]['balance'];?>元</p>
<br>
<form action="" method="post">
  <input type="hidden" name="user_id" value="<?php echo $user_id;?>">
  <input type="hidden" name="amount" value="50">
  <span class="btn btn-primary" data-amount="50">充值￥50 元</span>
</form>
<form action="" method="post">
  <input type="hidden" name="user_id" value="<?php echo $user_id;?>">
  <input type="hidden" name="amount" value="100">
  <span class="btn btn-primary" data-amount="100">充值￥100 元</span>
</form>
<form action="" method="post">
  <input type="hidden" name="user_id" value="<?php echo $user_id;?>">
  <input type="hidden" name="amount" value="200">
  <span class="btn btn-primary" data-amount="200">充值￥200 元</span>
</form>
<!-- Modal -->
<div id="myModal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-header">
    <button type="button" class="close cancel" data-dismiss="modal" aria-hidden="true">×</button>
    <h3 id="myModalLabel">充值</h3>
  </div>
  <div class="modal-body">
    <p>确认为【<?php echo $userinfo[0]['realname'];?>】充值￥<span id="amount"></span>元？</p>
  </div>
  <div class="modal-footer">
    <button class="btn btn-primary sure">确定</button>
    <button class="btn btn-danger cancel" data-dismiss="modal" aria-hidden="true">取消</button>
  </div>
</div>
<script type="text/javascript">
  $("form span.btn").unbind('click').live('click', function () {

    var _this = $(this);
    $('#amount').text($(this).attr('data-amount'));

    $('#myModal').modal({
      keyboard: true,
      show: true,
      backdrop: true
    });
    //管理员取消
    $(".cancel").unbind('click').live('click', function () {
      return;
    })
    //管理员确定则执行删除操作
    $(".sure").unbind('click').live('click', function () {
      _this.parent('form').submit();
    })
  });
</script>
<?php
include "../footer.php" 
?>
