<?php

   /*实现post 提交 发送和返回数据 前端实现在window.name特性上*/
   $flg = @empty($_POST['flg']) ? 0 : $_POST['flg'];
   $text = @empty($_POST['text']) ? 0 : $_POST['text'];
   //callback
   $callbackfun = @empty($_POST['fun']) ? 0 : $_POST['fun'];
   //返回
   if($text && $flg==0)
   {
       echo '<script type="text/javascript">'."\n";
       echo 'window.name="this is value";';
       echo '</script>'."\n";
   }else if($flg==1){
	   $value = "test value";
	   $v = "abc";
	   echo '<iframe src="http://www.test.com/cross_domain/02/10.html#'.$v.'"></iframe>';//变相方法   iframe返回值特性  
	   
   }else{
     $value = "callback value";
     echo '<script type="text/javascript">'."\n";
     //echo 'window.'.$callbackfun.'=function(v){console.log(v);}'."\n";
     //echo 'function '.$callbackfun.'(v){console.log(v);}'."\n";
     //echo $callbackfun."=top.".$callbackfun."\n";
     //echo "console.log(window.location.href);";
     echo $callbackfun.'("'.$value.'");'."\n";
     echo '</script>'."\n"; 
   }

?>