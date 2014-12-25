<?php
function login($user,$pwd){
	$RootObj= new COM("RTXSAPIRootObj.RTXSAPIRootObj");
	$RootObj->ServerIP ='127.0.0.1';
       $RootObj->ServerPort ='8006';
	$status= $RootObj->Login($user, $pwd);
	return $status;
}

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
	$error_num = 0;
	if (isset($_GET['user'])) {
		$user = $_GET["user"];
		if ($user == ""){
			$error_num =1;		}
	}else{
		$error_num = 1;
	}
	
	if (isset($_GET['pwd'])) {
		$pwd = $_GET["pwd"];
		if ($pwd == ""){
			$error_num = 1;
		}
	}else{
		$error_num = 1;
	}
	
	if ($error_num == 0){
		$re = login($user, $pwd);
		if ($re == 0){
			echo 1;
		}else{
			echo 0;
		}
	}else{
		echo 0;
	}
}
?>