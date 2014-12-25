(function () {

  var cart = 'youease';
  var storage = window.localStorage;

  
  //购物车对象
  //从localstorage中取出已经点的美食
  var shopping_cart = [];
  shopping_cart = JSON.parse(storage.getItem(cart));

  if(shopping_cart && shopping_cart.length > 0){
    var str = "购物车("+shopping_cart.length+")";
    $(".shop_count").html(str);
  }
  
})();

