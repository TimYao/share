<?php
  
   $data = array();  
   $value = @empty($_GET["access_value"]) ? 0 : $_GET["access_value"];
   if($value)
   {
	   //$data["status"] = true;
	   $data = array_merge($data,array("status"=>true,"msg"=>"成功"));
   }else{
	   $data = array_merge($data,array("status"=>false,"msg"=>"失败"));
	   //$data["status"] = false;
   }
   //echo json_encode($data);
?>