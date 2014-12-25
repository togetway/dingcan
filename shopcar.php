<?php
include "header.php";
require_once "class/Config.class.php";

$config = new Config();
$subsidies = $config->get_subsidies();
?>





<div class="wap columns clearfix">

  <h1 class="page-title">我的购物车</h1>
  <div class="r_mycart">
    <section class="main inner">
            <table cellpadding="0" cellspacing="0">
              <tbody>
              <tr style="background:#BEBEBE;">
                <th width="150">商店</th>
                <th class="ttl">食品</th>
                
                <th width="70">份数</th>
                <th width="70">单价</th>
                <th class="del" width="40">删除</th>
              </tr>
              </tbody>
            </table>
            <p id="noItemTips" class="ptt pbb" style="text-align: center; display: none; ">您还没有添加菜品哦~</p>
            <table id="cartTable">
              <tbody>
              </tbody>
            </table>
    
    <hr>
      <p class="buy-price">总价：<span id="cart_zongjia">0</span> 元</p><br>
      <p class="buy-price">公司补贴：<?php echo $subsidies;?> 元</p><br>
      <input type='hidden' id='subsidies' value="<?php echo $subsidies;?>">
      <p class="buy-price"><b>实付：<span id="shifu">0</span> 元 </b></p><br><hr>

      <button class="buy-btn buy-price"> 提交定单 </button>
    </section>
  </div>
</div>

  <!--显示对话框-->
  <div id="car-confirm" class="reveal-modal">
    <div id="confirm-list"></div>
    <a class="close-reveal-modal">&#215;</a>
  </div>

<script src="/static/js/jquery.reveal.js" type="text/javascript"></script>
<script type="text/javascript" src="/static/js/shop_cart.js"></script>


<?php
include "footer.php";
?>
