<?php
require_once "../../class/Shop.class.php";
$shop = new Shop();
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  if (isset($_POST['id']) and isset($_POST['name'])) {
    $id = $_POST['id'];
		$name = $_POST['name'];
		
		if($id != "" and $name != ""){
			$rs = $shop->modify_categories($id,$name);
			if($rs == 'update_categories_success'){
				$tip = "修改食品分类信息成功";
			}else if($rs == 'update_categories_fail'){
				$tip = "修改食品分类信息失败";
			}else if($rs == 'not_categories_id'){
				$tip = "食品分类ID不存在";
			}
			$shopinfo = $shop->get_shop_info($shop_id);
		}else{
			$tip = "名称不能为空";
		}
    echo $tip;
  }
}

?>