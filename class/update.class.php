<?php
require_once 'DBConn.class.php';
//include_once '../conf.php';

# 活动记录目录信息类
class Update extends DBConn {

    function construct() {
        parent::__construct();
    }
    //获取所有商家信息
    function shop_list(){
        $sql = "select * from shop";
        $rs = $this->select($sql);
        return $rs;
    }
	
    #更新食品分类表
    function update_categories($shop_id,$categories){
		$categories = addslashes(trim($categories));
        $categorys = explode('|', $categories);
        foreach ($categorys as $value){
            $show_category = explode('#', $value);
            $a = $show_category[1];

            #插入分类
            $sql = "insert into shop_categories values(0, $shop_id, '$a')";
            $this->begintransaction();
            $rs = $this->insert($sql);
            if ($rs) {
                $sql2 = "update food set categories_id=$rs where shop_id = $shop_id and category = '$value'";
                $rs2 = $this->update($sql2);
                if($rs2){
                    $this->commit();
                    echo "OK $shop_id $a and all food<br>";
                }else{
                    $this->rollback();
                    echo "NO $shop_id $a<br>";
                }
            } else {
                $this->rollback();
                echo "NO $shop_id $a<br>";
            }

            #更新食品列表

        }
    }


}

$update = new Update();
$shoplist = $update->shop_list();
foreach($shoplist as $key => $val){
    $id = $val['id'];
    $name = $val['name'];
    $categories = $val['categories'];
    echo "<h3>$name</h3>";
    $update->update_categories($id, $categories);
}
?>


