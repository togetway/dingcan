<?php
require_once 'DBConn.class.php';
//include_once '../conf.php';

# 消费记录类
class BalanceLog extends DBConn {

    function construct() {
        parent::__construct();
    }
    //获取对应账号消费记录
    function balancelog_list($uid){
		$uid = addslashes(trim($uid));
        $sql = "select * from balance_log where user_id = $uid order by stime desc";
        $rs = $this->select($sql);
        return $rs;
    }
	
    //增加消费记录                                                                                      
    function add($type, $amount, $balance,$describe,$user_id) {
        $type = addslashes(trim($type));
        $amount = addslashes(trim($amount));
		$balance = addslashes(trim($balance));
		$describe = addslashes(trim($describe));
		$user_id = addslashes(trim($user_id));
		$date = date("Y-m-d H:i:s");
        $sql = "insert into balance_log values(0,'$type',$amount,$balance,'$describe',$user_id,'$date') ";
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

}                                                                                                                  
?>
