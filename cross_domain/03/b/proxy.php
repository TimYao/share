<?php

  $v = @empty($_GET['v']) ? 0 : $_GET['v'];
  $arrs = array("status"=>"success","msg"=>"success");
  echo json_encode($arrs);

?>