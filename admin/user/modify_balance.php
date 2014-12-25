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
	if (isset($_POST['type']) and isset($_POST['user_id']) and isset($_POST['amount']) and isset($_POST['note'])) {
		$type = $_POST['type'];
		$user_id = $_POST['user_id'];
        $amount = $_POST['amount'];
		$note = $_POST['note'];
		if($amount !="" and $note != ""){
			if($type == 'add'){
				$rs = $user->recharge($user_id,$amount);
				if($rs){
					$userinfo = $user->get_user_info($user_id);
					$describe = "增加人民币".$amount."  操作人员【".$_SESSION['dish_realname']."】,原因：".$note;
					$balancelog->add('recharge',$amount,$userinfo[0]['balance'],$describe,$user_id);
					$result = 'success';
					$tip = "增加金额成功";
				}else{
					$result = 'error';
					$tip = "增加金额失败";
				}
			}else if ($type == 'min'){
				$rs = $user->pay($user_id,$amount);
				if($rs){
					$userinfo = $user->get_user_info($user_id);
					$describe = "扣除人民币".$amount."  操作人员【".$_SESSION['dish_realname']."】,原因：".$note;
					$balancelog->add('pay',$amount,$userinfo[0]['balance'],$describe,$user_id);
					$result = 'success';
					$tip = "扣除金额成功";
				}else{
					$result = 'error';
					$tip = "扣除金额失败";
				}
			}else{
        $result = 'error';
        $tip = "坏孩子，你还没选择操作类型！";
      }
		}else{
			$userinfo = $user->get_user_info($user_id);
			$result = 'error';
			$tip = "坏孩子，金额和原因不允许留空！";
		}
	}
}

?>
<h2>调整【<?php echo $userinfo[0]['realname'];?>】同学余额数目 <small><a href="/admin/user/">返回用户列表</a></small></h2>
<p>当前账户余额：￥<?php echo $userinfo[0]['balance'];?>元</p>
<br>
<?php if($result === "success"){?>
<div class="alert alert-success"><?php echo $tip;?></div>
<?php }else if ($result === "error"){?>
<div class="alert alert-error"><?php echo $tip;?></div>
<?php }?>




<div class="well">
  <form action="" id='modify_form' class="form-horizontal" method="POST">
        <div class='control-group'>
          <label class="control-label">操作：</label>
          <div class='controls'>
            <select id='type' name='type' required>
              <option value="">==选择操作==</option>
              <option value='add'>+ 添加金额</option>
              <option value='min'>- 减少金额</option>
            </select> *(必选)

            <input type="hidden" name="user_id" value="<?php echo $user_id;?>">
          </div>
        </div>

        <div class='control-group'>
          <label class="control-label">调整金额：</label>
          <div class='controls'>
            <input type="text" id='amount' name="amount" placeholder="金额" value="" required> *(必填)
          </div>
        </div>

        <div class='control-group'>
          <label class="control-label">调整原因说明：</label>
          <div class='controls'>
            <input type="text" id='note' name="note" placeholder="增加原因" value="" required> *(必填)
          </div>
        </div>

        <div class="control-group">
          <div class="controls">
            <span id="modify" class="btn btn-primary modify">调整</span>
          </div>
        </div>
</form>


<!-- Modal -->
<div id="myModal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-header">
    <button type="button" class="close cancel" data-dismiss="modal" aria-hidden="true">×</button>
    <h3 id="myModalLabel">金额调整</h3>
  </div>
  <div class="modal-body">
    <p>确认调整【<?php echo $userinfo[0]['realname'];?>】金额吗？</p>
  </div>
  <div class="modal-footer">
    <button class="btn btn-primary sure">确定</button>
    <button class="btn btn-danger cancel" data-dismiss="modal" aria-hidden="true">取消</button>
  </div>
</div>
<script type="text/javascript">
  $("#modify").unbind('click').live('click', function () {

    var _this = $(this);
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
      $("#modify_form").submit();
    })
  });
</script>
<?php
include "../footer.php" 
?>
