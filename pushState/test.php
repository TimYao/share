<?php
   $a = $_GET['a'];
   if(isset($a)){
       $html = "测试成功!".$a;
   }
   $arr = array("status"=>1000,"msg"=>$html);
   echo json_encode($arr);
?>