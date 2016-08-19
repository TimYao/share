<?php
    
	//header("Set-Cookie: cookiename=cookieValue; expires=" . gmstrftime("%A, %d-%b-%Y %H:%M:%S GMT", time() + (86400 * 365)) .  '; path=/; domain=netingcn.com');
	
	header("Set-Cookie: a=testa; path=/; domain=test.com");   //cookie服务器设置 //httpOnly设置前端是否可以看到操作后端cookie
	
	//setcookie也可以设置
	
?>
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=gb2312" />
		<title>设置cookie</title>
	</head>
	<body>
	      设置cookie1
	</body>
</html>