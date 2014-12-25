<?php

require_once "userlist.php";


if ($_SERVER['REQUEST_METHOD'] == 'GET') {
	if (isset($_GET['username'])) {
		$username = $_GET["username"];
		$userlist = user_list();
		foreach($userlist as $key => $val){
			$name = $val['name'];
			$nickname = $val['nickname'];
			if($name == $username){
				
				$nickname = iconv("gb2312","utf-8", $nickname);
				echo $nickname;
				break;
			}
		}
		
	}

}

?>