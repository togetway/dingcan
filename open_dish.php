<?php
include "header.php";
if(!$_SESSION['dish_isadmin']){
	@header("location:/note.php");
	exit(0);
}
require_once "class/Config.class.php";
$config = new Config();

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    if (isset($_GET['open'])) {
        $open = $_GET['open'];
		
		if($open == 1){
			$config->open_dish();
		}
    }
}

$dish_status = $config->open_dish_status();

?>
<div class="wap">
  <section class="main inner">
	<p><b>每次需要点餐时请到此处开放点餐功能！</b></p>
	<p>00：00 ~ 14：00 为午餐时间</p>
	<p>14：00 ~ 00：00 为晚餐时间</p>
	
	<br>
	<?php if($dish_status){ ?>
		<b>当前状态：</b><span class="text text-success">已经开放点餐</span>
	<?php }else{?>
		<b>当前状态：</b><span class="text text-error">未开放点餐</span>
		<br>
		<br>
		点击 <a href="?open=1" id="open">【开放】</a>
	<?php } ?>
  </section>
</div>
<?php
include "footer.php";
?>