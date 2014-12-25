<?php
include "../header.php";
require_once "../class/UserInfo.class.php";
$user = new UserInfo();
$uid = $_SESSION['dish_uid'];
$userinfo = $user->get_user_info($uid);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	if (isset($_POST['realname'])) {
		$realname = $_POST['realname'];
		$rs = $user->modify_realname($uid,$realname);
		if($rs ){
			$tip = "修改信息成功";
			$userinfo = $user->get_user_info($uid);
			$_SESSION['dish_realname'] = $userinfo[0]['realname'];
		}else{
			$tip = "修改信息失败";
		}
	}
}

?>
<div class="wap columns clearfix">
  <?php if ($tip){ ?>
  <p class="tip"><?php echo $tip; ?></p>
  <script type="text/javascript">
    $(".tip").delay(3000).slideUp();
  </script>
  <?php } ?>
  <div class="left">
    <h1 class="page-title">修改信息</h1>
    <section class="main clearfix inner">
      <form class="session_form" method="POST" id="account">
        <div class="item">
          <label for="setting-email">账号</label>
          <input name="username" id="setting-email" type="text" maxlength="32" placeholder="登陆账号" class="text-32"
                 value="<?php echo $userinfo[0]['username']; ?>" required="" disabled/><em>RTX账号</em>
        </div>
        <div class="item">
          <label for="setting-name">姓名</label>
          <input name="realname" id="setting-name" type="text" maxlength="32" placeholder="真实姓名" class="text-32"
                 value="<?php echo $userinfo[0]['realname']; ?>" required="" /><em>真实姓名</em>
        </div>
        <div class="item">
          <label for="setting-submit"></label>
          <button class="btn" id="setting-submit" type="submit">提交</button>
        </div>
      </form>
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
<?php
include "../footer.php";
?>