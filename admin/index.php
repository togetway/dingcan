<?php
include "header.php";
require_once "../class/Order.class.php";

$order = new Order();

$date=date("Y-m-d");
$HOUR = date("H");

$order_list = $order->admin_today_order_list();




?>
  <h2><?php echo $date; if($HOUR<14){echo "（午餐）";}else{echo "（晚餐）";}?> 定单</h1>
  <?php
 if($order_list){
  ?>
  <div class="alert alert-info">
        <strong>当前共有 <span class="label label-success" id='people'></span> 点餐, 其中 <span class="label label-important" id='no_pay'></span>未支付, 共花费 <span class="label label-info" id='all_total'></span></strong>
    </div>
    <table  class="table table-condensed table-hover">
          <thead>
          <tr>
            <th>序号</th>
            <th class="name">姓名</th>
            <th class="food">订单明细</th>
            <th class="total">总价</th>
            <th class="payStatus">订单状态</th>
			      <th class="action">操作</th>
          </tr>
          </thead>

          <tbody>
          <?php
          $user_count = 0;
          $all_total = 0;
          $no_pay = 0;
          foreach($order_list as $key => $val){
            $user_count += 1;
            $order_id = $val['id'];
            $user_id = $val['user_id'];
            $user_name = $val['user_name'];
          ?>
          <tr>
            <td><?php echo $user_count;?></td>
            <td><strong><?php echo $user_name;?></strong></td>
            <td>
     
              <?php
              $total = 0;
              $food_list = json_decode(stripslashes($val['order']));
              foreach($food_list as $key1 => $val1){
                $total += $val1->price*$val1->num;
                ?>

                <li><a href="#">★  <?php echo $val1->shop_name;?></a> => <?php echo $val1->name;?> - <?php echo $val1->price;?>元 - <?php echo $val1->num;?> 份 <?php if($val1->remark)echo '<span class="text-error">('.$val1->remark.')</span>';?></li>
              
              <?php
              }
              $all_total += $total;
              ?>
          
            </td>
            <td><?php echo $total;?></td>
            <td>
              <?php if($val['paystatus'] === "paid") { ?>
              <span class="text-success"><b>已支付</b></span>
              <?php } else {
                $no_pay +=1;
              ?>
              <span class="text-error">未支付</span>
              <?php } ?>
            </td>
            <td>
              <?php if($val['paystatus'] === "paid") { ?>
                <button class='btn btn-danger btn-small' onclick="order_canceled('<?php echo $order_id;?>','<?php echo $user_id;?>','<?php echo $user_name;?>')" >取消定单</button>
              <?php } else {?>
                <button class='btn btn-success btn-small' onclick="order_pay('<?php echo $order_id;?>','<?php echo $user_id;?>','<?php echo $user_name;?>')">帮忙支付</button>
              <?php } ?>
            </td>
          </tr>
          <?php
          }
          ?>
          </tbody>
     </table>
  <?php }else{ ?>
    <div class="alert alert-info">
        当前未发现任何定单
    </div>
  <?php }?>

<script>
$('#people').html("<?php echo $user_count;?>人");
$('#no_pay').html("<?php echo $no_pay;?>人");
$('#all_total').html("<?php echo $all_total;?>元");

function order_canceled(order_id,user_id,username) {
    art.dialog.confirm("确认取消【 "+username+" 】的定单吗？", function () {
    art.dialog.load("/admin/user/order_canceled.php?order_id=" + order_id, {title: '取消定单',lock:true,
        ok: function(){
            location.href = "/admin/";
        }
    });
    });
};


function order_pay(order_id,user_id,username) {
    art.dialog.confirm("确认帮【 "+username+" 】支付该定单吗？", function () {
    art.dialog.load("/admin/user/order_pay.php?order_id=" + order_id, {title: '取消定单',lock:true,
        ok: function(){
            location.href = "/admin/";
        }
    });
    });
};
</script>

<?php
include "footer.php";
?>
