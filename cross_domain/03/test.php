<?php

   //设置允许访问的地址
   header("Access-Control-Allow-Origin:http://www.test.com");
   // 响应类型 header('Access-Control-Allow-Methods:POST');
   // 响应头设置  header('Access-Control-Allow-Headers:x-requested-with,content-type');
   //header("Content-Type:application/json; charset=utf-8");

   
   $access_value = @empty($_GET['access_value'])?0:$_GET['access_value']; //实现跨资源共享的跨域
   //返回
    if($access_value)  //cors 跨资源共享方法
   {
       $arrs = array("status"=>"true");
       echo json_encode($arrs);
   }



?>