/**
 * 购物车功能
 * User: willerce
 * Date: 9/18/12
 * Time: 12:56 PM
 */

(function () {

  
  var storage = window.localStorage;
  var cart = 'youease';

  //购物车对象
  //从localstorage中取出已经点的美食
  var shopping_cart = [];
  shopping_cart = JSON.parse(storage.getItem(cart));

  //获取补贴金额
  var subsidies = $("#subsidies").attr("value");



  //遍历显示美食列表
  for (var i in shopping_cart) {
    //创建购物篮
    //alert(shopping_cart[i].name);
    var dom = $(show_food(shopping_cart[i]));
    dom.find('select').val(shopping_cart[i].num);
    dom.appendTo($('#cartTable tbody'));
  }

  //计算总价
  $("#cart_zongjia").text(get_total());

  //绑定份数修改事件
  $('.cart_o_num').change(changeNum);
  $('.del_btn').click(del_food);

  

  $('.buy-btn').click(function (e) {
	//获取备注信息
	var remark = $('#remark').val();
    $('#car-confirm').reveal({
      animation: 'fadeAndPop',
      animationspeed: 300,
      closeonbackgroundclick: false,
      dismissmodalclass: 'close-reveal-modal'
    });

    if (shopping_cart.length <= 0) {
      
      $('#confirm-list').empty().html("亲，您的购物车啥都没有！");
    } else {

		    //获取备注信息
		    shopping_cart2 = remark_shopping_cart();
        //禁用掉按钮，防止重复提交
        $(this).attr('disabled', 'disabled');

        //向后台提交订单
        $.ajax({
          type: "POST",
          url: "/order/submit_order.php",
          data: "list=" + encodeURIComponent(JSON.stringify(shopping_cart2)),
          dataType: 'json',
          success: function (data) {
            if (data.result == "success") {
              //清空localstorage
              storage.removeItem(cart);
              $('#confirm-list').empty().html('<div style="text-align:center;"><h3>订单提交成功</h3><p>倒计时 <span class="timeout">6</span> 秒后</p><p>跳转到 <a href="/today.php">今日订单</a> 进行支付</p></div>');

              var totaltime = 0;

              setInterval(function () {
                if (totaltime < 5) {
                  totaltime++;
                  $('#confirm-list .timeout').text(parseInt($('#confirm-list .timeout').text()) - 1);
                } else {
                  location.href = "/today.php";
                }
              }, 1000)

            }else if (data.result == "order_and_paid"){
				    $('#confirm-list').empty().html('<div style="text-align:center;"><h3>订单提交失败</h3><p>您今天已经下过定单，并已经支付！</p><p>可转至 <a href="/today.php">今日订单</a> 查看</p></div>');
			     }else if (data.result == "order_not_paid"){
				      $('#confirm-list').empty().html('<div style="text-align:center;"><h3>订单提交失败</h3><p>您今天已经在下过定单，但未支付！</p><p>请跳转至 <a href="/user/order.php">我的订单</a> 进行支付</p></div>');
			     }
          },
          error: function () {
            alert('下单出错了');
          }
        });

    }
  });


  function show_food(food) {
    return '<tr id="car-' + food.id + '" data-id="' + food.id + '"><td width="150">' + food.shop_name + '</td><td class="ttl">' + food.name + '</td>'+
      '<td width="50"><input type="text" id="remark-' + food.id + '" name="remark"  placeholder="备注说明（选填）" title="备注信息" required=""></td><td width="70"><select class="cart_o_num">' +
      '<option value="1">1</option><option value="2">2</option><option value="3">3</option><option value="4">4</option>' +
      '</select></td><td class="del" width="70">' + food.price + '</td>' +
      '<td width="40"><a id="cart_del_' + food.id + '" class="del_btn" href="javascript:void(0);" title="不要">删除</a></td></tr>';
  }


  function get_total() {
    var price = 0.0;
    for (var i in shopping_cart) {
      price += (parseFloat(shopping_cart[i].price) * parseInt(shopping_cart[i].num));
    }

    if(price > subsidies){
      var shifu = price - subsidies;
      $('#shifu').text(shifu);
    }else{
      $('#shifu').text('0');
    }
    

    if(!shopping_cart || shopping_cart.length == 0){
      $(".r_mycart").empty().html('购物为空，请前往选购食品');
    }

    return price;
  }

  function changeNum() {
    var food_id = $(this).parents('tr').attr('data-id');

    for (var i in shopping_cart) {
      if (shopping_cart[i].id == food_id) {
        shopping_cart[i].num = $(this).val();
        //重设购物车
        storage.setItem(cart, JSON.stringify(shopping_cart));
        //重新计算
        $("#cart_zongjia").text(get_total());
      }
    }
  };

  function del_food() {
    var food_id = $(this).parents('tr').attr('data-id');
    for (var i in shopping_cart) {
      if (shopping_cart[i].id == food_id) {
        shopping_cart.splice(i, 1);
        //重设购物车
        storage.setItem(cart, JSON.stringify(shopping_cart));
        $(this).parents('tr').remove();
        $('#food-' + food_id).removeClass('checked');
        //重新计算
        $("#cart_zongjia").text(get_total());
      }
    }
    //var a = JSON.parse(storage.getItem(cart));
    var count = shopping_cart.length;
    var str = "购物车("+count+")";
    $(".shop_count").html(str);
  }


  function remark_shopping_cart(){
    for (var i in shopping_cart){
      var food_id = shopping_cart[i].id;
      var remark = $('#remark-' + food_id).attr('value');
      
      shopping_cart[i]['remark'] = remark;
    }
    return shopping_cart;
  }

})();

