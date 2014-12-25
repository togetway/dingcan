<?php
session_start();
include "../class/UserInfo.class.php";
$user = new UserInfo();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	$num = 0;
    if (isset($_POST['username'])) {
        $username = $_POST['username'];
		if($username == ""){
			$num += 1;
			$tip = "用户名不能为空";
		}
		
    } else {
        $tip = "用户名不能为空";
		$num += 1;
    }
    if (isset($_POST['password'])) {
        $password = $_POST['password'];
		if($password == ""){
			$num += 1;
			$tip = "密码不能为空";
		}
    } else {
        $tip = "密码不能为空";
		$num += 1;
    }
	
	if($num == 0){
    //解决中文账号无法登陆问题
    $nickname = iconv("utf-8","gb2312", $username);
		$url="http://192.168.1.2:8900/dish/login.php?user=$nickname&pwd=$password";
		$re = file_get_contents($url);
		if($re == 1){
			$rs = $user->check_user($username);
			$uid = $rs[0]['id'];
			$username = $rs[0]['username'];
			$realname = $rs[0]['realname'];
			$isadmin = $rs[0]['isadmin'];
			$_SESSION['dish_username'] = $username;                                                                               
			$_SESSION['dish_uid'] = $uid;                                                               
			$_SESSION['dish_realname'] = $realname;
			$_SESSION['dish_isadmin'] = $isadmin;
			$_SESSION['dish_show_note'] = 1;
			echo "<script language='javascript'>window.location= '/';</script>";
            exit(0);
		}else{
			$tip = "用户名或者密码错误！";
		}
	}
}

$agent_ok = 1;
$agent = $_SERVER['HTTP_USER_AGENT'];
if(!preg_match('/FireFox\/([^\s]+)/i', $agent, $regs) and !preg_match('/Chrome\/([^\s]+)/i', $agent, $regs)){
  //echo "<script>alert('浏览器错误！请使用FireFoxe或者Chrome浏览器访问点餐系统！')</script>";
  //echo "浏览器错误！请使用FireFoxe或者Chrome浏览器访问点餐系统！";
  //exit();
  $agent_ok = 0;
}

?>

<!DOCTYPE HTML>
<html lang="zh-cn" style="<?php if($agent_ok ==1){echo "background: url('/static/img/bg2.jpg') repeat center 0 fixed;";}?>">
<head>
  <meta charset="UTF-8">
  <title>点餐系统 › 请先登录</title>
  <link href="/static/css/style.css" rel="stylesheet" type="text/css"/>
  <script src="/static/js/jquery.js" type="text/javascript"></script>
</head>
<body>
  <?php if($agent_ok == 1){?>
  <div class="auth_form">
      <form method="post" class="session_form">
          <h1>点餐系统 › 登录</h1>
          <div class="item">
            <input type="text" id="username" name="username" class="input" placeholder="账号" title="你的帐号" required=""/>
          </div>
          <div class="item">
            <input type="password" id="password" name="password" class="input" placeholder="密码" title="你的帐号密码" required=""/>
          </div>
          <div class="item clearfix">
            <span>请用RTX账号登陆</span>&nbsp;&nbsp;
            <button type="submit" id="sub" class="btn">登录</button>
          </div>
          <?php if($tip){?>
          <p class="tip"><?php echo $tip;?></p>
          <script type="text/javascript">
            $(".tip").delay(2000).slideUp();
          </script>
          <?php }?>
      </form>
  </div>
  <?php }else{echo "浏览器错误！请不要使用IE内核浏览器访问！建议使用FireFoxe或者Chrome浏览器！";}?>
</body>
</html>