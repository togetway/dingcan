<?php 


//header('Content-Type: text/html; charset=utf-8');

//require_once "IPLimit.php";
function user_list(){
$connstr = "Driver={Microsoft access Driver (*.mdb)};DBQ=e:/Program Files/Tencent/RTXServer/db/rtxdb.mdb";



$conn = @new COM("ADODB.Connection") or die ("ADOÁ¬½ÓÊ§°Ü!");

$conn->Open($connstr);

$rs = @new COM("ADODB.RecordSet");

$sql ="select ID,UserName,Name from Sys_user where AccountState<>1 or AccountState is null order by Name";

$rs->Open($sql,$conn,1,3);



$rs->MoveFirst();



$result = array();  



while(!$rs->EOF)

{

  $idField = $rs->Fields(0);

  $id = $idField->value;

  $nameField = $rs->Fields(1);

  $name = $nameField->value;

  $nicknameField = $rs->Fields(2);

  $nickname = $nicknameField->value;
  
  

  //$name = iconv("gb2312","utf-8", $name);
  //$nickname = iconv("gb2312","utf-8", $nickname);

  array_push($result, array('id'=>$id,'name'=>$name,'nickname'=>$nickname));

	$rs->MoveNext();

}





$rs->close(); 



return $result;



//echo json_encode($result);

}

//$users = user_list();
//print_r($users);
?>