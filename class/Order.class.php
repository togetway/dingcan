<?php
require_once 'DBConn.class.php';
//include_once '../conf.php';

# 定单信息类
class Order extends DBConn {

    function construct() {
        parent::__construct();
    }
    //获取定单
    function today_order_list_old(){
		$HOUR = date("H");
		$start_t = '';
		$end_t = '';
		if ($HOUR < 14) {
			$start_t = date("Y-m-d")." 00:00:00";
			$end_t = date("Y-m-d")." 14:00:00";
		} else {
			$start_t = date("Y-m-d")." 14:00:00";
			$end_t = date("Y-m-d",strtotime('+1 day'))." 00:00:00";
		}

		$shop_list = $this->select("select shop_id,shop_name from order_info where stime > '$start_t' and stime < '$end_t' group by shop_id");
		//print_r($shop_list);
		foreach($shop_list as $key => $val){
			$shop_id = $val['shop_id'];
			$shop_name = $val['shop_name'];
			$group[$shop_name]['shop_id'] = $val['shop_id'];
			$group[$shop_name]['shop_name'] = $val['shop_name'];
			#统计商店下的定单
			$sql = "select * from order_info where stime > '$start_t' and stime < '$end_t' and shop_id = $shop_id order by stime";
			$rs = $this->select($sql);
			$group[$shop_name]['values'] = $rs;
			#统计总额
			$all_total = $this->select("select sum(total) as all_total from order_info where stime > '$start_t' and stime < '$end_t' and shop_id = $shop_id and canceled = 0 ");
			$group[$shop_name]['all_total'] = $all_total[0]['all_total'];
		}
		//print_r($group);
        //$sql = "select * from order_info where stime > '$start_t' and stime < '$end_t'";
		//echo $sql;
        //$rs = $this->select($sql);
        return $group;
    }


    //获取定单
    function today_order_list(){
		$HOUR = date("H");
		$start_t = '';
		$end_t = '';
		if ($HOUR < 14) {
			$start_t = date("Y-m-d")." 00:00:00";
			$end_t = date("Y-m-d")." 14:00:00";
		} else {
			$start_t = date("Y-m-d")." 14:00:00";
			$end_t = date("Y-m-d",strtotime('+1 day'))." 00:00:00";
		}

		$order_list = $this->select("select * from order_info where stime > '$start_t' and stime < '$end_t' and canceled = 0");

		$food = array();
		foreach($order_list as $key => $val){
			$id = $val['id'];
			$order = $val['order'];
			$user_name = $val['user_name'];
			$user_id = $val['user_id'];
			$total = $val['total'];
			$canceled = $val['canceled'];
			$paystatus = $val['paystatus'];
			
			$result = json_decode(stripslashes($order));
			foreach($result as $key2 => $val2){

				$b['id'] = $val2->id;
				$b['name'] = $val2->name;
				$b['price'] = $val2->price;
				$b['num'] = $val2->num;
				$b['remark'] = $val2->remark;
				$food[$val2->shop_id][$user_name]['food'][] = $b;

				$food[$val2->shop_id][$user_name]['canceled'] = $canceled;
				$food[$val2->shop_id][$user_name]['paystatus'] = $paystatus;
				$food[$val2->shop_id][$user_name]['order_id'] = $id;
			}	
		}

        return $food;
    }


	
	//获取历史定单
    function history_order_list($date,$type){
		$date = addslashes(trim($date));
		$type = addslashes(trim($type));
		if ($type == 'dinner') {
			$start_t = $date." 00:00:00";
			$end_t = $date." 14:00:00";
		} else if($type == 'lunch') {
			$start_t = $date." 14:00:00";
			$end_t = date("Y-m-d",strtotime('+1 day',strtotime($date)))." 00:00:00";
		}else{
			return FALSE;
		}

		$order_list = $this->select("select * from order_info where stime > '$start_t' and stime < '$end_t' and canceled = 0");

		$food = array();
		foreach($order_list as $key => $val){
			$id = $val['id'];
			$order = $val['order'];
			$user_name = $val['user_name'];
			$user_id = $val['user_id'];
			$total = $val['total'];
			$canceled = $val['canceled'];
			$paystatus = $val['paystatus'];
			
			$result = json_decode(stripslashes($order));
			foreach($result as $key2 => $val2){
				$a['shop_id'] = $val2->shop_id;
				$a['shop_name'] = $val2->shop_name;

				$b['id'] = $val2->id;
				$b['name'] = $val2->name;
				$b['price'] = $val2->price;
				$b['num'] = $val2->num;
				$b['remark'] = $val2->remark;
				$food[$val2->shop_id][$user_name]['food'][] = $b;

				$food[$val2->shop_id][$user_name]['canceled'] = $canceled;
				$food[$val2->shop_id][$user_name]['paystatus'] = $paystatus;
				$food[$val2->shop_id][$user_name]['order_id'] = $id;
			}			
		}
        return $food;
    }
	
    //后台显示当前定单
    function admin_today_order_list(){
    	$HOUR = date("H");
		$start_t = '';
		$end_t = '';
		if ($HOUR < 14) {
			$start_t = date("Y-m-d")." 00:00:00";
			$end_t = date("Y-m-d")." 14:00:00";
		} else {
			$start_t = date("Y-m-d")." 14:00:00";
			$end_t = date("Y-m-d",strtotime('+1 day'))." 00:00:00";
		}

		$order_list = $this->select("select * from order_info where stime > '$start_t' and stime < '$end_t' and canceled = 0 order by paystatus,user_name");
		return $order_list;
    }


	//获取用户定单
	function user_order_list($uid){
		$uid = addslashes(trim($uid));
		$sql = "select * from order_info where user_id = $uid order by stime desc";
		$rs = $this->select($sql);
        return $rs;
	}
	
	//获取定单信息
	function get_order_info($id){
		$id = addslashes(trim($id));
		$sql = "select * from order_info where id = $id";
		$rs = $this->select($sql);
        return $rs;
	}
	

    //增加定单信息                                                                                       
    function add($orderlist,$paystatus,$total,$user_id,$user_name,$subsidies)
	{
		//判断时间
		$HOUR = date("H");
		$start_t = '';
		$end_t = '';
		if ($HOUR < 14) {
			$start_t = date("Y-m-d")." 00:00:00";
			$end_t = date("Y-m-d")." 14:00:00";
		} else {
			$start_t = date("Y-m-d")." 14:00:00";
			$end_t = date("Y-m-d",strtotime('+1 day'))." 00:00:00";
		}
		
        $orderlist = addslashes(trim($orderlist));
        $paystatus = addslashes(trim($paystatus));
		$stime = date("Y-m-d H:i:s");
		$total = addslashes(trim($total));
		$user_id = addslashes(trim($user_id));
		$user_name = addslashes(trim($user_name));
		$subsidies = addslashes(trim($subsidies));
		$sel_sql = "select * from order_info where stime > '$start_t' and stime < '$end_t' and user_id = $user_id and canceled = 0 limit 1";
		$sel_rs = $this->select($sel_sql);
		if ($sel_rs){
			if($sel_rs[0]['paystatus'] == 'paid'){
				return 'order_and_paid';
			}else{
				return 'order_not_paid';
			}
		}else{
			$sql = "insert into order_info values(0,'$orderlist','$paystatus',0,'$stime','$total',$user_id,'$user_name',$subsidies)";
			$rs = $this->insert($sql);
			if ($rs) { 
				return 'order_ok';
			}else {
				return 'order_no';                                                                                         
			}
		}		
    }
	
	#修改定单付款状态
	function modify_order_status($id,$status){
		$id = addslashes(trim($id));
		$status = addslashes(trim($status));
		$sql = "update order_info set paystatus = '$status' where id = $id";
		$rs2 = $this->update($sql);
		return $rs2;
	}
	
	#取消定单
	function order_canceled($id){
		$id = addslashes(trim($id));
		$sql = "update order_info set canceled = 1 where id = $id";
		$rs2 = $this->update($sql);
		return $rs2;
	}
	
	// 修改食品信息
    function modify_food($id, $name, $price, $week,$category) {
        $id = addslashes(trim($id));
        $name = addslashes(trim($name));
        $price = addslashes(trim($price));
        $week = addslashes(trim($week));
		$category = addslashes(trim($category));
        $sql = "select name from food where id=$id";
        $rs = $this->select($sql);
        if ($rs) {
            $sql_update = "update food set name='$name',price='$price',week='$week',category='$category' where id=$id";
            $rs2 = $this->update($sql_update);
            if ($rs2) {
                return "update_food_success";
            } else {
                return "update_food_fail";
            }
        } else {
            return "not_food_id";
        }
    }
	
	//未点餐账号列表
	function not_dish_userlist(){
		//判断时间
		$HOUR = date("H");
		$start_t = '';
		$end_t = '';
		if ($HOUR < 14) {
			$start_t = date("Y-m-d")." 00:00:00";
			$end_t = date("Y-m-d")." 14:00:00";
		} else {
			$start_t = date("Y-m-d")." 14:00:00";
			$end_t = date("Y-m-d",strtotime('+1 day'))." 00:00:00";
		}
		$sql = "select * from user_info where id not in(select user_id from order_info where stime > '$start_t' and stime < '$end_t' and canceled = 0)";
		
		$rs = $this->select($sql);
		return $rs;
	}

    //判断日期是否显示取消订单链接
    function is_canceled_order($stime){
        $b_date = strtotime('-1 day');
        $n_date = strtotime($stime);
        if($n_date < $b_date)
            return False;
        else
            return True;
    }

}                                                                                                                  
?>
