<?php
require_once 'DBConn.class.php';
//include_once '../conf.php';

# 活动记录目录信息类
class Shop extends DBConn {

    function construct() {
        parent::__construct();
    }
    //获取所有商家信息
    function shop_list(){
        $sql = "select * from shop";
        $rs = $this->select($sql);
        return $rs;
    }
	
	//获取所有商家信息
    function show_shop_list(){
		//判断时间
		$HOUR = date("H");
		if ($HOUR < 14) {
			$delivery_time = 'lunch';
		} else {
			$delivery_time = 'dinner';
		}
		
        $sql = "select * from shop where isshow = 1 and  delivery_time in ('all','$delivery_time')";
        $rs = $this->select($sql);
        return $rs;
    }

    //获取商家信息
    function get_shop_info($id){
        $id = addslashes(trim($id));
        $sql = "select * from shop where id = $id";
        $rs = $this->select($sql);
        return $rs;
    }

    //增加商家信息                                                                                             
    function add($name, $address, $tel,$css,$note,$delivery_time) {                                                     
        $name = addslashes(trim($name));                                                                  
        $address = addslashes(trim($address));
		$tel = addslashes(trim($tel));
		$css = addslashes(trim($css));
		$note = addslashes(trim($note));
		$delivery_time = addslashes(trim($delivery_time));
        $sql = "insert into shop values(0,'$name','$address','$tel','$css','$note','$delivery_time',1)";
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
    //删除商家                                                                                                
    function shop_del($id){                                                                                       
        $id = addslashes(trim($id));                                                                              
        $sql = "delete from shop where id=$id";
		$sql2 = "delete from food where shop_id = $id";
        $sql3 = "delete from shop_categories where shop_id = $id";
		$this->begintransaction();
        $rs= $this->delete($sql);
		$rs2= $this->delete($sql2);
        $rs3= $this->delete($sql3);
		if ($rs and $rs2 and $rs3){
			$this->commit();                                                                                      
            return TRUE;
		} else {
			$this->rollback();                                                                                    
            return FALSE;  
		}
    }

    //检查商家是否已经添加
    function check_shop_name($name) {
        $name = addslashes(trim($name));
        $sql = "select id from shop where name='$name' ";                                            
        $chrs = $this->select($sql);
        return $chrs;
    }
	
	// 修改商家信息
    function modify_shop($id, $name, $address, $tel,$css,$note,$delivery_time) {
        $id = addslashes(trim($id));
        $name = addslashes(trim($name));
        $address = addslashes(trim($address));
        $tel = addslashes(trim($tel));
		$css = addslashes(trim($css));
		$note = addslashes(trim($note));
		$delivery_time = addslashes(trim($delivery_time));
        $sql = "select name from shop where id=$id";
        $rs = $this->select($sql);
        if ($rs) {
            $sql_update = "update shop set name='$name',address='$address',tel='$tel',css='$css',note='$note',delivery_time='$delivery_time' where id=$id";
            $rs2 = $this->update($sql_update);
            if ($rs2) {
                return "update_shop_success";
            } else {
                return "update_shop_fail";
            }
        } else {
            return "not_shop_id";
        }
      }
	  
	//转换文字
	function delivery_time_change($delivery_time){
		$delivery_time = addslashes(trim($delivery_time));
		switch ($delivery_time)
		{
		case 'all':
			return "全天";
		case 'lunch':
			return "午餐";
		case 'dinner':
			return "晚餐";
		default:
			return "错误";
		}
	}

    //是否显示店铺
    function change_isshow($id){ 
        $uid = addslashes(trim($id));
        $sql= "select isshow from shop where id=$id"; 
        $rs = $this->select($sql);
        $isshow = $rs[0]['isshow'];
        if($isshow == 1){
            $sql_update = "update shop set isshow=0 where id=$id ";
        }else{
            $sql_update = "update shop set isshow=1 where id=$id ";
        }
        $this->update($sql_update);
        $rs = $this->select($sql);
        return $rs[0]['isshow'];
    }

    //添加店铺食品分类
    function add_categories($shop_id, $categories){
        $shop_id = addslashes(trim($shop_id)); 
        $categories = addslashes(trim($categories));
        $sql = "insert into shop_categories values(0, $shop_id, '$categories')";
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

    //删除店铺食品分类
    function del_categories($id){
         $id = addslashes(trim($id));
         $sql = "delete from shop_categories where id=$id";
         $sql2 = "delete from food where categories_id=$id";
         $rs= $this->delete($sql);
         $rs2= $this->delete($sql2);
         if ($rs and $rs2){
            return TRUE;
        } else {
            return FALSE;  
        }
    }

    //修改店铺食品分类
    function modify_categories($id, $categories){
        $id = addslashes(trim($id));
        $categories = addslashes(trim($categories));
        $sql = "select id from shop_categories where id=$id";
        $rs = $this->select($sql);
        if ($rs) {
            $sql_update = "update shop_categories set categories='$categories' where id=$id";
            $rs2 = $this->update($sql_update);
            if ($rs2) {
                return "update_categories_success";
            } else {
                return "update_categories_fail";
            }
        } else {
            return "not_categories_id";
        }
    }

    //获取店铺食品分类列表
    function list_categories($shop_id){
        $shop_id = addslashes(trim($shop_id)); 
        $sql = "select * from shop_categories where shop_id=$shop_id";
        $rs = $this->select($sql);
        return $rs;
    }

    //获取店铺食品分类名字
    function list_categories_name($shop_id){
        $shop_id = addslashes(trim($shop_id)); 
        $sql = "select * from shop_categories where shop_id=$shop_id";
        $rs = $this->select($sql);
        foreach($rs as $key => $val){
            $id = $val['id'];
            $categories = $val['categories'];
            $a[$id] = $categories;
        }
        return $a;
    }

    //检查食品分类是否存在
    function check_categories($shop_id,$categories){
        $shop_id = addslashes(trim($shop_id)); 
        $categories = addslashes(trim($categories));
        $sql = "select id from shop_categories where shop_id = $shop_id and categories='$categories' ";
        $chrs = $this->select($sql);
        return $chrs;
    }

    //获取食品分类名称
    function get_categories($cid){
        $cid = addslashes(trim($cid)); 
        $sql = "select categories from shop_categories where id = $cid";
        $rs = $this->select($sql);
        return $rs[0]['categories'];
    }
}
?>
