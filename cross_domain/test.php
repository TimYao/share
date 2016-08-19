<?php

   //设置允许访问的地址
   //header("Access-Control-Allow-Origin:http://www.test.com");
   // 响应类型 header('Access-Control-Allow-Methods:POST');
   // 响应头设置  header('Access-Control-Allow-Headers:x-requested-with,content-type');
   //header("Content-Type:application/json; charset=utf-8");

   /*实现post 提交 发送和返回数据 前端实现在window.name特性上*/
   $text = @empty($_POST['text']) ? 0 : $_POST['text'];
   //callback
   $fun = @empty($_POST['fun']) ? 0 : $_POST['fun'];

   /**test4**/
   $callback = @empty($_GET['callback']) ? 0 : $_GET['callback'];
   $value = "数据返回成功！";


   /**test5**/
   $access_value = @empty($_GET['access_value'])?0:$_GET['access_value']; //实现跨资源共享的跨域
   //返回
   if($text) //window.name
   {

       echo '<script type="text/javascript">'."\n";
       //echo 'alert("hello");'."\n";
       //echo "funtest (name){alert(name)};";
       echo 'window.name="12222";';
       //echo $callback.'('.$name.')';
       //echo "alert(window.name);";
       echo '</script>'."\n";
   }else if($fun){
	   $name = "values";
	   echo '<script type="text/javascript">'."\n";
	   echo $fun."('".$name."')";
	   echo '</script>'."\n";
   }else if($callback)    //jsonp数据返回方法
   {
       echo $callback."("."'".$value."'".");";
   }else if($access_value)  //cors 跨资源共享方法
   {
       $arrs = array("status"=>"true");
       echo json_encode($arrs);
   }



?>