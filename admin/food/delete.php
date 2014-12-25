<?php
require_once "../../class/Food.class.php";
$food = new Food();
if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    if (isset($_GET['id'])) {
        $food_id = $_GET['id'];
    }
	if($food_id){
		$rs = $food->food_del($food_id);
	}
}
?>