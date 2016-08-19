<?php 
	header('Content-type: application/json;charset=utf-8'); 
    //header('Content-type: text/plain;charset=utf-8'); 
	$arr = array("status"=>"true");

	$title = isset($_REQUEST['title']) ? $_REQUEST['title'] : -1;  
	$content = isset($_REQUEST['content']) ? $_REQUEST['content'] : -1; 
	$mod = isset($_REQUEST['mod']) ? $_REQUEST['mod'] : -1; 
    
	if($mod<0)
	{
        echo "404";
        exit();
	}

	if($mod==0)
	{
        if($title && $content)
	    {
	    	$arr["msg"] = "post request is success!"; 
			echo json_encode($arr);
	    } 
	}else if($mod==1)
	{
		$arr["msg"] = "get request is success!";
        echo json_encode($arr);
	}else{
		$arr["msg"] = "get other file content!";
        echo json_encode($arr);
	}

			
?>