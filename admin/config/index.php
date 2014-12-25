<?php 
include "../header.php";
require_once "../../class/Config.class.php";
$config = new Config();
$configinfo = $config->config_info();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['lunch']) and isset($_POST['dinner']) and isset($_POST['weekend_lunch']) and isset($_POST['weekend_dinner'])) 
	 {
        $lunch = $_POST['lunch'];
		    $dinner = $_POST['dinner'];
        $weekend_lunch = $_POST['weekend_lunch'];
        $weekend_dinner = $_POST['weekend_dinner'];
		
		
    		$rs = $config->modify_config($lunch,$dinner,$weekend_lunch,$weekend_dinner);
    		if($rs){
    			$tip = "修改成功";
    			$configinfo = $config->config_info();
    		}else{
    			$tip = "修改失败";
    		}
    }
	
}

?>
<?php if ($tip){ ?>
  <div class="alert alert-info"><?php echo $tip; ?></div>
  <script type="text/javascript">
    $(".alert").delay(3000).slideUp();
  </script>
<?php } ?>
<h2>系统配置修改</h2>
<form id='modify_form' class="form-horizontal" method="POST">
        <div class='control-group'>
          <label class="control-label">周一至五 午餐补贴金额：</label>
          <div class='controls'>
            <input name="lunch" id="lunch" type="text" maxlength="3"  value="<?php echo $configinfo[0]['lunch_subsidies'];?>" required="" /> 元
          </div>
        </div>

        <div class='control-group'>
          <label class="control-label">周一至五 晚餐补贴金额：</label>
          <div class='controls'>
            <input name="dinner" id="dinner" type="text" maxlength="3"  value="<?php echo $configinfo[0]['dinner_subsidies'];?>" required="" /> 元
          </div>
        </div>

        <div class='control-group'>
          <label class="control-label">周六、天 午餐补贴金额：</label>
          <div class='controls'>
            <input name="weekend_lunch" id="weekend_lunch" type="text" maxlength="3"  value="<?php echo $configinfo[0]['weekend_lunch_subsidies'];?>" required="" /> 元
          </div>
        </div>

        <div class='control-group'>
          <label class="control-label">周六、天 晚餐补贴金额：</label>
          <div class='controls'>
            <input name="weekend_dinner" id="weekend_dinner" type="text" maxlength="3"  value="<?php echo $configinfo[0]['weekend_dinner_subsidies'];?>" required="" /> 元
          </div>
        </div>

        <div class="control-group">
          <div class="controls">
            <span id="work" class="btn btn-primary modify">修改</span>
          </div>
        </div>
</form>

<!-- Modal -->
<div id="myModal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-header">
    <button type="button" class="close cancel" data-dismiss="modal" aria-hidden="true">×</button>
    <h3 id="myModalLabel">系统配置</h3>
  </div>
  <div class="modal-body">
    <p>确定修改系统配置吗 ？</p>
  </div>
  <div class="modal-footer">
    <button class="btn btn-primary sure">确定</button>
    <button class="btn btn-danger cancel" data-dismiss="modal" aria-hidden="true">取消</button>
  </div>
</div>
<script type="text/javascript">
  $(".modify").unbind('click').live('click', function () {

    var _this = $(this);
    $('#lunch_m').text($('#lunch').val());
	$('#dinner_m').text($('#dinner').val());

    $('#myModal').modal({
      keyboard: true,
      show: true,
      backdrop: true
    });
    //管理员取消
    $(".cancel").unbind('click').live('click', function () {
      return;
    })
    //管理员确定则执行删除操作
    $(".sure").unbind('click').live('click', function () {
      $('#modify_form').submit();
    })
  });

  
</script>
<?php
include "../footer.php"; 
?>
