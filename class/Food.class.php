<?php
require_once 'DBConn.class.php';
//include_once '../conf.php';

# 活动记录目录信息类
class Food extends DBConn {

    function construct() {
        parent::__construct();
    }
    //获取对应商家的食品
    function food_list($shop_id,$categories_id){
		$shop_id = addslashes(trim($shop_id));
        $categories_id = addslashes(trim($categories_id));
        if($categories_id)
            $sql = "select * from food where shop_id = $shop_id and categories_id = $categories_id order by week,name";
        else
            $sql = "select * from food where shop_id = $shop_id order by categories_id,week,name";
        $rs = $this->select($sql);
        return $rs;
    }
	
	//获取分组
	function group_list($shop_id){
		$shop_id = addslashes(trim($shop_id));
		$sql = "select id,categories from shop_categories where id in (select categories_id from food where shop_id = $shop_id group by categories_id) order by id";
		$rs = $this->select($sql);
        return $rs;
		
	}
	
	//根据分组获取食品列表
	function group_food($shop_id,$categories_id){
		$D = date('w');
		$shop_id = addslashes(trim($shop_id));
		$categories_id = addslashes(trim($categories_id));
		$sql = "select * from food where shop_id = $shop_id and categories_id = $categories_id and week like '%$D%' order by price";
		$rs = $this->select($sql);
        return $rs;
	}

    //获取食品信息
    function get_food_info($id){
        $id = addslashes(trim($id));
        $sql = "select * from food where id = $id";
        $rs = $this->select($sql);
        return $rs;
    }

    //增加食品信息                                                                                       
    function add($name, $price, $shop_id,$week,$categories_id) {
        $name = addslashes(trim($name));
        $price = addslashes(trim($price));
		$shop_id = addslashes(trim($shop_id));
		//$week = addslashes(trim($week));
        $week = join(',',$week);
		$categories_id = addslashes(trim($categories_id));
        $sql = "insert into food values(0,'$name','$price','$shop_id','$week',$categories_id) ";
        $this->begintransaction();                                                                                
        $rs = $this->insert($sql);
        if ($rs) {                                                                                                
            $this->commit();                                                                                      
            return TRUE;                                                                                          
        } else {                                                                                                  
            $this->rollback();                                                                                    
            return FALSE;                                                                                         
        }                                                                                                         
    }
	
	// 修改食品信息
    function modify_food($id, $name, $price, $week,$categories_id) {
        $id = addslashes(trim($id));
        $name = addslashes(trim($name));
        $price = addslashes(trim($price));
        //$week = addslashes(trim($week));
        $week = join(',',$week);
		$categories_id = addslashes(trim($categories_id));
        $sql = "select name from food where id=$id";
        $rs = $this->select($sql);
        if ($rs) {
            $sql_update = "update food set name='$name',price='$price',week='$week',categories_id=$categories_id where id=$id";
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
	
	//数字转换为星期
	function num_change_week($num){
		$num = addslashes(trim($num));
		switch ($num)
		{
		case 1:
			return "一";
		case 2:
			return "二";
		case 3:
			return "三";
		case 4:
			return "四";
		case 5:
			return "五";
		case 6:
			return "六";
		case 0:
			return "日";
		case -1:
			return "全部";
		default:
			echo "num error";
		}
	}
	
    #把数定转换为星期
    function show_week($week){
        $week = addslashes(trim($week));
        if($week=="")
            return "<b>关闭</b>";
        $week_list = split(',',$week);
        $count = count($week_list);

        $num_week = array(1=>'一',2=>'二',3=>'三',4=>'四',5=>'五',6=>'六',0=>'日');
        if($count == 7 or $count == 0){
            return '全周';
        }else{
            $week_str = "";
            for($i=0;$i<$count;$i++){
                $num = $week_list[$i];
                $w=$num_week[$num];
                if($week_str)
                    $week_str = $week_str.'、'.$w;
                else
                    $week_str = $w;
            }
            return '周'.$week_str;
        }
    }


    //删除食品                                                                                                 
    function food_del($id){                                                                                       
        $id = addslashes(trim($id));                                                                              
        $sql = "delete from food where id=$id";                                                              
        $this->delete($sql);                                                                                      
    }

    //检查食品是否已经添加
    function check_food_name($shop_id,$name) {                                                                          
        $name = addslashes(trim($name));
        $sql = "select id from food where shop_id = $shop_id and name='$name' ";
        $chrs = $this->select($sql);
        return $chrs;                                                                                             
    }   

}                                                                                                                  
?>
