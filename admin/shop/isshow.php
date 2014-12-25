<?php
require_once "../../class/Shop.class.php";
$shop = new Shop();
if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    if (isset($_GET['id'])) {
        $id = $_GET['id'];
    }
	if($id){
		$rs = $shop->change_isshow($id);
		if($rs){
			echo $rs;
		}
	}
}
?>