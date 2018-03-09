<?php
   header('content-type:application/json;charset=utf8');

   $id = (int)$_GET['id'];
   $type = $_GET['type'];
   $relationships = $_GET['relationships'];

   if($type==='id'){

        /* 主节点 */
        if($id === -1){
          $data = array(
             "parentId"=>0,
             "nodes"=>array(
               array("id"=>0,"name"=>"中国","text"=>"中国",label=>"china"),
             )
          );
        }
        if($id === 0){
              $data = array(
                "parentId"=>0,
                "nodes"=>array(
                    array("id"=>1,"name"=>"北京","text"=>"北京信息",label=>"city"),
                   /*array("id"=>1,"name"=>"北京","text"=>"北京信息",label=>"city","relations"=>array(
                     array("type"=>"node","id"=>"181","title"=>"北京标题1"),
                     array("type"=>"node","id"=>"182","title"=>"北京标题2")
                   )),*/
                   array("id"=>2,"name"=>"上海","text"=>"上海信息",label=>"city"),
                   /*array("id"=>2,"name"=>"上海","text"=>"上海信息",label=>"city","relations"=>array(
                    array("type"=>"node","id"=>"2181","title"=>"上海标题1")
                   )),*/
                   array("id"=>3,"name"=>"广州","text"=>"广州信息",label=>"city"),
                   array("id"=>4,"name"=>"深圳","text"=>"深圳信息",label=>"city"),
                   array("id"=>5,"name"=>"天津","text"=>"天津信息",label=>"city"),
                   array("id"=>6,"name"=>"山西","text"=>"山西信息",label=>"province"),
                   array("id"=>7,"name"=>"山东","text"=>"山东信息",label=>"province"),
                   array("id"=>8,"name"=>"四川","text"=>"四川信息",label=>"province"),
                   array("id"=>9,"name"=>"河北","text"=>"河北信息",label=>"province"),
                   array("id"=>10,"name"=>"河南","text"=>"河南信息",label=>"province")
                ),
                "links"=>array(
                   array("source"=>"1", "target"=>"0", "type"=>"文本"),
                   array("source"=>"2", "target"=>"0", "type"=>"文本"),
                   array("source"=>"3", "target"=>"0", "type"=>"文本"),
                   array("source"=>"4", "target"=>"0", "type"=>"文本"),
                   array("source"=>"5", "target"=>"0", "type"=>"文本"),
                   array("source"=>"6", "target"=>"0", "type"=>"文本"),
                   array("source"=>"7", "target"=>"0", "type"=>"文本"),
                   array("source"=>"8", "target"=>"0", "type"=>"文本"),
                   array("source"=>"9", "target"=>"0", "type"=>"文本"),
                   array("source"=>"10", "target"=>"0", "type"=>"文本")
                )
              );
           }

       /* 北京点 */
          if($id === 1){
             $data = array(
               "parentId"=>1,
               "nodes"=>array(
                  /*array("id"=>1,"name"=>"北京","text"=>"北京信息",label=>"city"),*/
                  array("id"=>11,"name"=>"海淀",label=>"county"),
                  array("id"=>12,"name"=>"丰台",label=>"county"),
                  array("id"=>13,"name"=>"朝阳",label=>"county"),
                  array("id"=>14,"name"=>"大兴",label=>"county"),
                  array("id"=>15,"name"=>"通州","text"=>"通州信息",label=>"county"),
                  array("id"=>16,"name"=>"西城",label=>"county")
               ),
               "links"=>array(
                  array("source"=>"11", "target"=>"1", "type"=>"文本","distance"=>20),
                  array("source"=>"12", "target"=>"1", "type"=>"文本","distance"=>20),
                  array("source"=>"13", "target"=>"1", "type"=>"文本","distance"=>20),
                  array("source"=>"14", "target"=>"1", "type"=>"文本","distance"=>20),
                  array("source"=>"15", "target"=>"1", "type"=>"文本","distance"=>20),
                  array("source"=>"16", "target"=>"1", "type"=>"文本","distance"=>20)
               )
             );

          }
          /* 上海点 */
          if($id === 2){
            $data = array(
              "parentId"=>2,
              "nodes"=>array(
                 /*array("id"=>2,"name"=>"上海","text"=>"上海信息",label=>"city"),*/
                 array("id"=>21,"name"=>"闸北","text"=>"闸北信息",label=>"county"),
                 array("id"=>22,"name"=>"徐汇",labels=>"county",label=>"county")
              ),
              "links"=>array(
                 array("source"=>"21", "target"=>"2", "type"=>"文本"),
                 array("source"=>"22", "target"=>"2", "type"=>"文本")
              )
            );
         }

          /*通州数据 列出旅行社*/
          if($id === 15){
            $data = array(
                "parentId"=>15,
                "nodes"=>array(
                   /*array("id"=>15,"name"=>"通州",label=>"county"),*/
                   array("id"=>151,"name"=>"青旅",label=>"agency_list"),
                   array("id"=>152,"name"=>"a旅行社","text"=>"通州a旅行社",label=>"agency_list"),
                   array("id"=>153,"name"=>"b旅行社",label=>"agency_list"),
                   array("id"=>154,"name"=>"c旅行社",label=>"agency_list"),
                   array("id"=>155,"name"=>"d旅行社",label=>"agency_list"),
                   array("id"=>156,"name"=>"e旅行社",label=>"agency_list")
                ),
                "links"=>array(
                   array("source"=>"151", "target"=>"15", "type"=>"文本"),
                   array("source"=>"152", "target"=>"15", "type"=>"文本"),
                   array("source"=>"153", "target"=>"15", "type"=>"文本"),
                   array("source"=>"154", "target"=>"15", "type"=>"文本"),
                   array("source"=>"155", "target"=>"15", "type"=>"文本"),
                   array("source"=>"156", "target"=>"15", "type"=>"文本")
                )
              );
          }

          /*闸北数据 列出旅行社*/
          if($id === 21){
            $data = array(
               "parentId"=>21,
               "nodes"=>array(
                  /*array("id"=>21,"name"=>"闸北",label=>"county"),*/
                  array("id"=>211,"name"=>"青旅",label=>"agency_list"),
                  array("id"=>212,"name"=>"a旅行社","text"=>"通州a旅行社",label=>"agency_list"),
                  array("id"=>213,"name"=>"b旅行社",label=>"agency_list"),
                  array("id"=>214,"name"=>"c旅行社",label=>"agency_list")
               ),
               "links"=>array(
                  array("source"=>"211", "target"=>"21", "type"=>"文本"),
                  array("source"=>"212", "target"=>"21", "type"=>"文本"),
                  array("source"=>"213", "target"=>"21", "type"=>"文本"),
                  array("source"=>"214", "target"=>"21", "type"=>"文本")
               )
            );
          }

          /*通州数据 a旅行社 */
          if($id === 152){
            $data = array(
               "parentId"=>152,
               "nodes"=>array(
                  /*array("id"=>15,"name"=>"通州",label=>"county"),*/
                  array("id"=>151,"name"=>"青旅",label=>"agency_list"),
                  array("id"=>152,"name"=>"a旅行社",label=>"agency_list"),
                  array("id"=>153,"name"=>"b旅行社",label=>"agency_list"),
                  array("id"=>154,"name"=>"c旅行社",label=>"agency_list"),
                  array("id"=>155,"name"=>"d旅行社",label=>"agency_list"),
                  array("id"=>156,"name"=>"e旅行社",label=>"agency_list"),
                  array("id"=>1521,"name"=>"法人",label=>"firm_pri_person_info"),
                  array("id"=>1522,"name"=>"投资人",label=>"firm_inv_info")
               ),
               "links"=>array(
                  array("source"=>"151", "target"=>"15", "type"=>"文本"),
                  array("source"=>"152", "target"=>"15", "type"=>"文本"),
                  array("source"=>"153", "target"=>"15", "type"=>"文本"),
                  array("source"=>"154", "target"=>"15", "type"=>"文本"),
                  array("source"=>"155", "target"=>"15", "type"=>"文本"),
                  array("source"=>"156", "target"=>"15", "type"=>"文本"),
                  array("source"=>"1521", "target"=>"152", "type"=>"文本"),
                  array("source"=>"1522", "target"=>"152", "type"=>"文本")
               )
             );
          }

          /*通州数据 c旅行社 */
          if($id === 154){
           $data = array(
              "parentId"=>154,
              "nodes"=>array(
                 /*array("id"=>15,"name"=>"通州",label=>"county"),*/
                 array("id"=>151,"name"=>"青旅",label=>"agency_list"),
                 array("id"=>152,"name"=>"a旅行社",label=>"agency_list"),
                 array("id"=>153,"name"=>"b旅行社",label=>"agency_list"),
                 array("id"=>154,"name"=>"c旅行社",label=>"agency_list"),
                 array("id"=>155,"name"=>"d旅行社",label=>"agency_list"),
                 array("id"=>156,"name"=>"e旅行社",label=>"agency_list"),
                 array("id"=>1521,"name"=>"法人",label=>"firm_pri_person_info"),
                 array("id"=>1522,"name"=>"投资人",label=>"firm_inv_info")
              ),
              "links"=>array(
                 array("source"=>"151", "target"=>"15", "type"=>"文本"),
                 array("source"=>"152", "target"=>"15", "type"=>"文本"),
                 array("source"=>"153", "target"=>"15", "type"=>"文本"),
                 array("source"=>"154", "target"=>"15", "type"=>"文本"),
                 array("source"=>"155", "target"=>"15", "type"=>"文本"),
                 array("source"=>"156", "target"=>"15", "type"=>"文本"),
                 array("source"=>"1521", "target"=>"152", "type"=>"文本"),
                 array("source"=>"1522", "target"=>"152", "type"=>"文本"),
                 array("source"=>"1521", "target"=>"154", "type"=>"文本"),
                 array("source"=>"1522", "target"=>"154", "type"=>"文本")
              )
            );
          }

          if($id===1811){
             $data = array(
                "parentId"=>1811,
                "nodes"=>array(
                   array("id"=>1851,"name"=>"青旅",label=>"agency_list")
                ),
                "links"=>array(
                   array("source"=>"1851", "target"=>"1811", "type"=>"文本")
                )
             );
          }




          if($relationships==='relationships'){
                  if($id === 0){
                    $data = array(
                      "parentId"=>0,
                      "nodes"=>array(
                          array("id"=>1,"name"=>"北京","text"=>"北京信息",label=>"city"),
                         /*array("id"=>1,"name"=>"北京","text"=>"北京信息",label=>"city","relations"=>array(
                           array("type"=>"node","id"=>"181","title"=>"北京标题1"),
                           array("type"=>"node","id"=>"182","title"=>"北京标题2")
                         )),*/
                         array("id"=>2,"name"=>"上海","text"=>"上海信息",label=>"city"),
                         /*array("id"=>2,"name"=>"上海","text"=>"上海信息",label=>"city","relations"=>array(
                          array("type"=>"node","id"=>"2181","title"=>"上海标题1")
                         )),*/
                         array("id"=>3,"name"=>"广州","text"=>"广州信息",label=>"city"),
                         array("id"=>4,"name"=>"深圳","text"=>"深圳信息",label=>"city"),
                         array("id"=>5,"name"=>"天津","text"=>"天津信息",label=>"city"),
                         array("id"=>6,"name"=>"山西","text"=>"山西信息",label=>"province"),
                         array("id"=>7,"name"=>"山东","text"=>"山东信息",label=>"province"),
                         array("id"=>8,"name"=>"四川","text"=>"四川信息",label=>"province"),
                         array("id"=>9,"name"=>"河北","text"=>"河北信息",label=>"province"),
                         array("id"=>10,"name"=>"河南","text"=>"河南信息",label=>"province")
                      ),
                      "links"=>array(
                         array("source"=>"1", "target"=>"0", "type"=>"文本","distance"=>10),
                         array("source"=>"2", "target"=>"0", "type"=>"文本","distance"=>10),
                         array("source"=>"3", "target"=>"0", "type"=>"文本","distance"=>10),
                         array("source"=>"4", "target"=>"0", "type"=>"文本","distance"=>10),
                         array("source"=>"5", "target"=>"0", "type"=>"文本","distance"=>10),
                         array("source"=>"6", "target"=>"0", "type"=>"文本","distance"=>10),
                         array("source"=>"7", "target"=>"0", "type"=>"文本","distance"=>10),
                         array("source"=>"8", "target"=>"0", "type"=>"文本","distance"=>10),
                         array("source"=>"9", "target"=>"0", "type"=>"文本","distance"=>10),
                         array("source"=>"10", "target"=>"0", "type"=>"文本","distance"=>10)
                      )
                    );
                 }
                  if($id === 1){
                    $data = array(
                       "parentId"=>181,
                       "nodes"=>array(
                          /*array("id"=>1,"name"=>"北京","text"=>"北京信息",label=>"city"),*/
                          array("id"=>1811,"name"=>"关系一",label=>"agency_list")
                       ),
                       "links"=>array(
                          array("source"=>1811, "target"=>"1", "type"=>"文本")
                       )
                     );
                  }
             }


   }



   if($type==='relation'){

      if($id === 0){
                $data = array(
                    "parentId"=>0,
                    "relations"=>array(
                       array("id"=>"1","title"=>"北京"),
                       array("id"=>"2","title"=>"上海")
                ));
             }
      if($id === 1){
        $data = array(
            "parentId"=>1,
            "relations"=>array(
               array("id"=>"181","title"=>"北京标题1"),
               array("id"=>"182","title"=>"北京标题2")
        ));
      }
      if($id === 3){
          $data = array(
              "parentId"=>3,
              "relations"=>array(
                 array("id"=>"1381","title"=>"广州标题1"),
                 array("id"=>"1382","title"=>"广州标题2")
          ));
      }

      if($id != 1 && $id != 3 && $id != 0){
           $data = array("parentId"=>$id);
      }
   }

   $data = json_encode($data);
   echo $data;



?>
