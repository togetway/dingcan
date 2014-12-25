<?php
require_once "../../class/UserInfo.class.php";
$user = new UserInfo();
if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    if (isset($_GET['id'])) {
        $uid = $_GET['id'];
    }
	if($uid){
		$rs = $user->change_isadmin($uid);
		if($rs){
			echo "YES";
		}
	}
}
?>