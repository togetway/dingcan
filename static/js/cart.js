/**
 * 购物车功能
 * User: willerce
 * Date: 9/18/12
 * Time: 12:56 PM
 */

(function () {

  $(window).scroll(function () {
    var top = $(this).scrollTop();
    var top = 66 - top;
    if (top < 0) top = 0;
    $('#cart').css('top', top + "px");
  });



  var storage = window.localStorage;
  var shop_id = $('#shop_id').val();
  var shop_name = $('#shop_name').val();
  
  //购物车对象
  //从localstorage中取出已经点的美食
  var shopping_cart = [];
  if (storage.getItem(shop_id) != null) {
    shopping_cart = JSON.parse(storage.getItem(shop_id));
  }

  //遍历美食列表
  for (var i in shopping_cart) {
    $('#food-' + shopping_cart[i].id).addClass('checked');

    //创建购物篮
    var dom = $(create_car_item(shopping_cart[i]));
    //dom.find("option[value='"+shopping_cart[i].num+"']").attr("seleted", "true");
    dom.find('select').val(shopping_cart[i].num);
    dom.appendTo($('#cartTable tbody'));
  }

  //计算总价
  $("#cart_zongjia").text(get_total());

  //绑定份数修改事件
  $('.cart_o_num').change(changeNum);
  $('.del_btn').click(del_food);


  $('.food-list ul li').click(function () {
    var el = $(this);
    if (el.hasClass('checked')) {//已经选中-> 取消
      el.removeClass('checked');
      for (var i in shopping_cart) {
        if (shopping_cart[i].id == el.attr('data-id')) {
          $('#car-' + shopping_cart[i].id).remove();
          shopping_cart.splice(i, 1);
          //重设购物车
          storage.setItem(shop_id, JSON.stringify(shopping_cart));
        }
      }
    } else {//未选中
      el.addClass('checked');
      //构建对象
      var food = {
        id: el.attr('data-id'),
        name: el.attr('data-name'),
        price: el.attr('data-price'),
        num: 1,
        shop_id:shop_id,
        shop_name:shop_name,
      };
      //向数组添加
      shopping_cart.push(food);
      //向storage中保存
      storage.setItem(shop_id, JSON.stringify(shopping_cart));
      //向购物篮添加
      $(create_car_item(food)).appendTo($('#cartTable tbody'));
      //绑定份数修改事件
      $('.cart_o_num').change(changeNum);
      $('.del_btn').click(del_food);
    }

    //重新计算
    $("#cart_zongjia").text(get_total());
  });

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
      $('#confirm-list').empty().html("亲，不要着急，您还木有选菜呢！");
    } else {

      //禁用掉按钮，防止重复提交
        $(this).attr('disabled', 'disabled');
          


        var shop_cat = JSON.parse(storage.getItem('youease'));
        if(!shop_cat){shop_cat = [];}

        var food_id_list = [];
        for( var j in shop_cat){
          food_id_list.push(shop_cat[j]['id']);
        }


        for (var i in shopping_cart) {
          var key = $.inArray(shopping_cart[i]['id'], food_id_list)
          if( key >= 0){
            shop_cat[key]['num'] += shopping_cart[i]['num'];
          }else{
            shop_cat.push(shopping_cart[i]);
          }
        }
        storage.setItem('youease', JSON.stringify(shop_cat));
        storage.removeItem(shop_id);

        //显示购物车中食品数量
        var count = shop_cat.length;
        var str = "购物车("+count+")";
        $(".shop_count").html(str);
        
        $('#confirm-list').empty().html('<h3>添加购物车成功</h3><p><a href="/shopcar.php"><img src="/static/img/jiesuan.png"></a> &nbsp;&nbsp;&nbsp;&nbsp;<a href="/"><img src="/static/img/xuancan.png"></a></p>');

    }
  });
  // 加载完咱才知道图片大小啊
  window.onload = function(){
    $('#picmenu').click(function(e){ 
      var x;
      var y;
      if (e.pageX || e.pageY) { 
        x = e.pageX;
        y = e.pageY;
      }
      else { 
        x = e.clientX + document.body.scrollLeft + document.documentElement.scrollLeft; 
        y = e.clientY + document.body.scrollTop + document.documentElement.scrollTop; 
      } 
      y -= $(this).offset().top;
      x -= $(this).offset().left;
      if(x + 100 < $(this).width() && y + 100 < $(this).height()){
        $('<div class=""><span class="close">X</span></div>').css({
          width: 100,
          height: 100,
          top: y,
          left: x,
          border: 'solid 1px black',
          position: 'absolute'
        }).draggable({
          containment: "#picmenu"
        }).resizable({
          containment: "#picmenu"
        })
        .appendTo('#picmenu').children('.close').css({
          float: 'right'
        }).click(function(){
          $(this).parent().remove();
          return false;
        })
      }
    });
  };

  function create_car_item(food) {
    return '<tr id="car-' + food.id + '" data-id="' + food.id + '"><td class="ttl">' + food.name + '</td><td width="40"><select class="cart_o_num">' +
      '<option value="1">1</option><option value="2">2</option><option value="3">3</option><option value="4">4</option>' +
      '<option value="5">5</option><option value="6">6</option><option value="7">7</option><optionvalue="8">8</option></select></td><td class="del" width="30">' + food.price + '</td>' +
      '<td width="30"><a id="cart_del_' + food.id + '" class="del_btn" href="javascript:void(0);" title="不要">删除</a></td></tr>';
  }


  function get_total() {
    var price = 0.0;
    for (var i in shopping_cart) {
      price += (parseFloat(shopping_cart[i].price) * parseInt(shopping_cart[i].num));
    }

    if(shopping_cart && shopping_cart.length > 0 ){
      $(".buy").show();
    }else{
      $(".buy").hide();
    }
    
    return price;
  }

  function changeNum() {
    var food_id = $(this).parents('tr').attr('data-id');

    for (var i in shopping_cart) {
      if (shopping_cart[i].id == food_id) {
        shopping_cart[i].num = $(this).val();
        //重设购物车
        storage.setItem(shop_id, JSON.stringify(shopping_cart));
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
        storage.setItem(shop_id, JSON.stringify(shopping_cart));
        $(this).parents('tr').remove();
        $('#food-' + food_id).removeClass('checked');
        //重新计算
        $("#cart_zongjia").text(get_total());
      }
    }
  }

})();

