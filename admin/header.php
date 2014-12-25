<?php
session_start();
if(!$_SESSION['dish_uid']){
    @header("location:/user/login.php");
    exit(0);
}else{
	if(!$_SESSION['dish_isadmin']){
		@header("location:/note.php");
		exit(0);
	}
}
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <title>点餐系统-管理后台</title>
  <script type="text/javascript" src="/static/js/jquery.js"></script>
  <script type="text/javascript" src="/static/js/bootstrap.min.js"></script>
  <link rel="stylesheet" href="/static/bootstrap/css/bootstrap.css">
  <script src="/static/artDialog/artDialog.js?skin=default"></script>
  <script src="/static/artDialog/plugins/iframeTools.source.js"></script>
  <link rel="stylesheet" href="/static/css/admin.css">
  <link rel="icon" href="/static/img/favicon.ico" type="image/x-icon" />
  <link rel="shortcut icon" href="/static/img/favicon.ico" type="image/x-icon" />
</head>
<body>
<div class="navbar navbar-inverse navbar-fixed-top">
  <div class="navbar-inner">
    <div class="container">
      <a class="brand" href="/admin">点餐系统-管理后台</a>
      <div class="nav-collapse collapse">
        <ul class="nav">
          <li class="">
            <a href="/admin/user">用户列表</a>
          </li>
          <li>
            <a href="/admin/shop">店铺列表</a>
          </li>
		  <li>
            <a href="/admin/config">系统配置</a>
          </li>
          <li>
            <a href="/user/logout.php">注销</a>
          </li>
        </ul>
      </div>
    </div>
  </div>
</div>
<div class="container">