<?php

function sendMSG($send_to,$title,$body,$time=0){
    $RootObj= new COM("RTXSAPIRootObj.RTXSAPIRootObj");
    $RootObj->ServerIP ='127.0.0.1';
    $RootObj->ServerPort ='8006';
    $status = $RootObj->SendNotify($send_to,$title,$time,$body);
    return $status;
}


if ($_SERVER['REQUEST_METHOD'] == 'GET') {
   
    if (isset($_GET['username']) and isset($_GET['realname'])) {
        $username=$_GET['username'];
	$realname=$_GET['realname'];
	$realname = iconv("utf-8","gb2312", $realname);
	
	$nowtime=date("Y年m月d日 H:i:s");  //获取当前时间
        $title = "★★点餐提示★★";
        $messages =  $realname."同学 \n请尽快登陆系统进行点餐\n点餐地址:http://192.168.1.9:8081 \n提示时间:".$nowtime;
        $status = sendMSG("$username" , "$title" , "$messages");
	if($status == 0){
		echo "OK";
	}else{
		echo "NO";
	}
    }
}

?>