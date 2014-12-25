<?php
session_start();
if(!$_SESSION['dish_uid']){
    @header("location:/user/login.php");
    exit(0);
}
$HOUR = date("H");
?>
<!DOCTYPE HTML>
<html lang="zh-cn">
<head>
  <meta charset="UTF-8">
  <title>点餐系统</title>
  <link href="/static/css/style.css" rel="stylesheet" type="text/css"/>
  <script type="text/javascript" src="/static/js/jquery.js"></script>
  <script src="/static/artDialog/artDialog.js?skin=default"></script>
  <script src="/static/artDialog/plugins/iframeTools.source.js"></script>
  

  <link rel="stylesheet" type="text/css" href="/static/tigra_calendar/tcal.css" />
  <script type="text/javascript" src="/static/tigra_calendar/tcal.js"></script> 

  <link rel="icon" href="/static/img/favicon.ico" type="image/x-icon" />
  <link rel="shortcut icon" href="/static/img/favicon.ico" type="image/x-icon" />
</head>
<body>
<?php
$agent = $_SERVER['HTTP_USER_AGENT'];
if(!preg_match('/FireFox\/([^\s]+)/i', $agent, $regs) and !preg_match('/Chrome\/([^\s]+)/i', $agent, $regs)){
	//echo "<script>alert('浏览器错误！请使用FireFoxe或者Chrome浏览器访问点餐系统！')</script>";
	echo "浏览器错误！请不要使用IE内核浏览器访问！建议使用FireFoxe或者Chrome浏览器！";
	exit();
}
?>
<header id="head">
  <nav class="nav">
    <div class="wap">
      <div title="点餐系统" class="logo"><a href="/"><span>YouEase-点餐系统</span></a></div>
      <ul>

		<?php if($HOUR < 14){?>
        <li><a href="/today.php">今日订单(午餐)</a></li>
		<?php }else{?>
		<li class="active"><a href="/today.php">今日订单(晚餐)</a></li>
		<?php }?>
    <li><a href="/shopcar.php" class='shop_count'>购物车(0)</a></li>
		<?php 
			if($_SESSION['dish_isadmin']){
		?>
		<li><a href="/notdish.php">未点餐同学</a></li>
		<li><a href="/open_dish.php">开放点餐</a></li>
		<?php } ?>
        <li class="nav_item_my">
          <a href='#'>(<?php echo $_SESSION['dish_realname'];?>)同学</a>
          <ul id="nav_my">
            <li><a href="/user/order.php">我的订单</a></li>
            <li><a href="/user/balance.php">账户余额</a></li>
			<li><a href="/user/account.php">账户信息</a></li>
            <li><a href="/user/logout.php">注销</a></li>
          </ul>
        </li>
        
		<?php 
			if($_SESSION['dish_isadmin']){
		?>
          <li><a href="/admin/" target="blank" >后台管理</a></li>
		<?php } ?>
		
      </ul>
    </div>
  </nav>
</header>
<script type="text/javascript" src="/static/js/count.js"></script>
