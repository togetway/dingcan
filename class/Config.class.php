<?php
require_once 'DBConn.class.php';
//include_once '../conf.php';

# 活动记录目录信息类
class Config extends DBConn {

    function construct() {
        parent::__construct();
    }
    //获取配置信息
    function config_info(){
        $sql = "select * from config limit 1";
        $rs = $this->select($sql);
        return $rs;
    }
	
	//获取是不开放点餐
	function open_dish_status(){
		$sql = "select open_dish from config limit 1";
		$rs = $this->select($sql);
		$set_time = $rs[0]['open_dish'];
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
		
		if($set_time > $start_t and $set_time < $end_t){
			return TRUE;
		}else{
			return FALSE;
		}
	}
	
	#开放点餐
	function open_dish()
	{
		$stime = date("Y-m-d H:i:s");
		$sql = "update config set open_dish = '$stime'";
		$rs = $this->update($sql);
		return $rs;
	}
	
	#修改系统配置
	function modify_config($lunch,$dinner,$weekend_lunch,$weekend_dinner)
	{
		$lunch = addslashes(trim($lunch));
		$dinner = addslashes(trim($dinner));
		$weekend_lunch = addslashes(trim($weekend_lunch));
		$weekend_dinner = addslashes(trim($weekend_dinner));
		$sql = "update config set lunch_subsidies = $lunch, dinner_subsidies = $dinner, weekend_lunch_subsidies = $weekend_lunch, weekend_dinner_subsidies = $weekend_dinner";
		$this->begintransaction();
        $rs = $this->update($sql); 
        if ($rs) {
            $this->commit();
            return TRUE;
        } else {
            $this->rollback();
            return FALSE;
        }
	}

	
	#获取补贴金额
	function get_subsidies()
	{
		$sql = "select * from config limit 1";
		$rs = $this->select($sql);

		$HOUR = date("H");
		$WEEK = date('w');
		if ($WEEK >=1 and $WEEK <=5){
			if ($HOUR < 14) {
				return $rs[0]['lunch_subsidies'];
			}else{
				return $rs[0]['dinner_subsidies'];
			}
		}else{
			if ($HOUR < 14) {
				return $rs[0]['weekend_lunch_subsidies'];
			}else{
				return $rs[0]['weekend_dinner_subsidies'];
			}
		}
        
	}
	
}                                                                                                                  
?>
