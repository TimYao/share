<?php 

	header('Content-type: application/json;charset=utf-8');

	$requestTxtUrl = 'http://www.ylx.com/cross_domain/03/proxys.txt';
    
	$requestUrl = 'http://www.ylx.com/cross_domain/03/proxys.php';
 
    $valaue = $_SERVER['REQUEST_METHOD']=='GET' ? (isset($_GET['v']) ? $_GET['v'] : 0) : (isset($_POST['v']) ? $_POST['v'] : 0);
    
	$mod =  $_SERVER['REQUEST_METHOD']=='GET' ? (isset($_GET['mod']) ? $_GET['mod'] : 0) : (isset($_POST['mod']) ? $_POST['mod'] : 0);
	


	$header = array(
        "Host: www.test.com/",
        "Accept: application/json,text/html,application/xhtml+xml,application/xml;q=0.95,q=0.9,*/*;q=0.8",
        "Accept-Charset:utf-8;q=0.7",
        "Accept-Encoding: gzip,deflate,sdch",
        "Accept-Language: it-IT,it;q=0.8,en-US;q=0.6,en;q=0.4",
        "Cache-Control: max-age=0",
        "Connection: keep-alive",
		//'Content-Type: application/json',
    );

	 
	
	/*   请求拿到文件 */

    function getFileData($url,$mod)
    {
    	$ch = curl_init();
	    curl_setopt($ch, CURLOPT_URL, $url);
	    //curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
	    //curl_setopt($ch, CURLOPT_HEADER, 1);//设置是否让头信息一起输出（非false值）
	    //curl_setopt($ch, CURLINFO_HEADER_OUT, true);
	    //curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
	    
	    //curl_setopt($ch, CURLOPT_HEADER, 0);
	    //curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json;charset=utf-8'));
	    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		//curl_setopt($ch, CURLOPT_FOLLOWLOCATION,1);//如果返回的response 头部中存在Location值，就会递归请求   //$infors = curl_getinfo( $ch);

	    $data = curl_exec($ch);
	    curl_close($ch);
	    //var_dump($data);
	    echo $data."\n";
    }   

    /*get 获取数据*/
    function getData($url,$mod)
    {
        $ch = curl_init();
        $data = http_build_query(array("mod"=>$mod),'', '&');
        $url.= '?'.$data;
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
        $content=curl_exec($ch);
		curl_close($ch);
		echo $content;
    }

    /*post 提交拿数据*/ 
    function postData($url,$mod)
    {
    	$ch = curl_init();
		$data = http_build_query(array("mod"=>$mod,"title" => "Foo", "content" => "contents"),'', '&');

		curl_setopt($ch, CURLOPT_URL, $url);
        

        //这里我们也可以不设置，不设置，以默认有的发送，设置了保证发送请求格式是 application/x-www-form-urlencoded,以至后端POST可以读取到，注意如果设置了
        //下面的CURLOPT_HEADER设置为0，不然无法返回
		curl_setopt($ch, CURLOPT_HEADER, 0);//返回response头部信息 
		curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/x-www-form-urlencoded;charset=utf-8', 'Content-Length: ' . strlen($data))); 
		
		//curl_setopt($ch, CURLOPT_NOBODY, 0);//不返回response body内容  
		//curl_setopt($ch, CURLOPT_MAXREDIRS, 1);//设置请求最多重定向的次数  
		
		curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);//不直接输出response,以变量模式存储，控制输出  
		
		//curl_setopt($ch, CURLOPT_FOLLOWLOCATION,1);//如果返回的response 头部中存在Location值，跳转抓取   
		//curl_setopt($ch, CURLOPT_TIMEOUT,10); 

		//curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");//可以用于自定义请求方法，要求是知道服务器支持该方法
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $data);


		$content=curl_exec($ch);
		curl_close($ch);
        echo $content;
    }
    
    //根据标注来执行返回 0->post提交  1->get提交  其他为获取文件
    
    if($mod==0)
    {   
		postData($requestUrl,$mod); //调用post提交
    }else if($mod==1){
    	getData($requestUrl,$mod);  //调用get提交 
    }else{
        getFileData($requestTxtUrl,$mod);
    } 	
?>