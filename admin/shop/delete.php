<?php
require_once "../../class/Shop.class.php";
$shop = new Shop();
if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    if (isset($_GET['id'])) {
        $shop_id = $_GET['id'];
    }
	if($shop_id){
		$rs = $shop->shop_del($shop_id);
	}
}
?>