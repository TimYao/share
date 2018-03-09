
(function($d3){

  var doc = document,
    _initData,
    parentId,
    infoDiv,
    layerRel,
    textBox,
    btnClose,
    infoTexts = {},
    graph,
    gt,
    _oDoc,
    _oW,
    _oH,
    _oSvg,
    _oColors,
    _simulation,
    radius = 30,
    defColors = {
      'china' : 'rgb(111, 141, 162)',
      'province' : 'rgb(40, 111, 189)',
      'city' : 'rgb(255, 187, 120)',
      'county' : 'rgb(255, 127, 14)',
      'agency_list' : 'rgb(214, 39, 40)',
      'branch' : 'rgb(255, 152, 150)',
      'tourist_administration' : 'rgb(44, 160, 44)',
      'firm_pri_person_info' : 'rgb(148, 103, 189)',
      'user_guide' : 'rgb(152, 223, 138)',
      'firm_LY_AO_SFC_OPANOMALY' : 'rgb(255, 152, 150)',
      'firm_LY_CASE_CF_PARTYINFO' : 'rgb(255, 187, 120)',
      'firm_LY_CASE_CF_BASEINFO' : 'rgb(196, 156, 148)',
      'firm_LY_E_LI_ILLDISHONESTY' : 'rgb(127, 127, 127)',
      'firm_inv_info' : 'rgb(140, 86, 75)'
    };


  try {
    if(!('event' in $d3)){
      throw new Error('d3 is not undefined ! please check');
    }
  }catch (e){
    console.log('this is error: ',e.message);
  }finally{
    console.log('......');
  }

  //nameSpace = typeof nameSpace === 'string' && (nameSpace!='false' && nameSpace!='true' && nameSpace!='null' && nameSpace!='undefined') ? nameSpace : 'forceMap';

  infoDiv = doc.getElementById('infoDiv');
  textBox = doc.getElementById('text-info');
  btnClose = doc.getElementById('closed');
  layerRel = doc.getElementById('layerRel');
  _oDoc = doc.documentElement;
  _oW = _oDoc.clientWidth;
  _oH = _oDoc.clientHeight;
  _oSvg = $d3.select('svg');
  _oSvg.attr('width', _oW).attr('height', _oH);
  _oColors = $d3.scaleOrdinal($d3.schemeCategory20);

  // 队列存储
  layerRel.pushStack = [];

  // 折起关闭
  btnClose.onclick = function(event){
    var ev = event || window.event;
    selectClick(ev,'close');
    return false;
  }

  //组织弹窗冒泡
  layerRel.onclick = function(event){
    var ev = event || window.event;
    var target = ev.target || ev.srcElement;
    var index = target.dataset.index;
    var id = target.getAttribute('data-parent-id');
    var text;
    // 查看
    if(target.className === 'see'){
      if(id){
        text = target.parentNode.getElementsByTagName('span')[0].innerHTML;
        docClick();

        if('relations' in infoTexts[id] && infoTexts[id]['relations'][index]['isStatus']){
          return false;
        }
        getAjax({id:id,type:'id',relationships:text},function(){
          var relations = 'relations' in infoTexts[id] ? infoTexts[id]['relations'] : undefined;
          if(!('isOpen' in infoTexts[id])){
            infoTexts[id].isOpen = true;
          }
          target.style.cursor = 'default';
          target.innerHTML = '已展开';
          if(relations){
            relations[index].isStatus = true;
          }
        });
      }
    }
    if(ev.cancelBubble){
      ev.cancelBubble = true;
    }
    if(ev.stopPropagation){
      ev.stopPropagation();
    }
  };
  layerRel.onmouseenter = function(){
    var parentId = this.dataset.parentId;
    var curNode = $d3.select('g[data-id="'+parentId+'"]');
    clearTimeout(gt);
    curNode.selectAll('.cp').style('display','block');
    curNode.selectAll('.iText').style('display','block');
    clearTimeout(layerRel.time);
    delete layerRel.time;
  }
  layerRel.onmouseleave = function(){
    var parentId = this.dataset.parentId;
    var curNode = $d3.select('g[data-id="'+parentId+'"]');
    gt = setTimeout(function(){
      curNode.selectAll('.cp').style('display','none');
      curNode.selectAll('.iText').style('display','none');
    },500);
    clearTimeout(layerRel.time);
    delete layerRel.time;
    layerRel.className = 'layerRelation hide';
  }

  // document 关闭弹出
  doc.onclick = docClick;

  // 初始化图
  _initData = function(data,status){
    var _isRoot,
        root,
        links,
        link,
        edgelabels,
        edgepaths,
        def,
        marker,
        node,
        circle,
        text,
        rangSplit=[0.32,0.32,0.32];

    if (!data) throw new Error('数据请求错误！');

    if(status === 'update'){
      graph = data;
    }else{

      if(graph && graph.nodes.length>0){
        if(data.parentId){
          graph.parentId = data.parentId;
        }

        if(!('links' in graph)){
          graph.links = [];
        }
        graph.links = graph.links.concat(data.links);
        graph.nodes = graph.nodes.concat(data.nodes);
      }else{
        graph = data;
      }
    }

    _isRoot = (!('links' in data) || data['links'].length<=0) ? (parentId = -1,true) : false;

    // 根节点
    root = _oSvg.select('.roots')._groups[0][0] ? _oSvg.select('.roots') : _oSvg.append('g').attr('class','roots');

    /*
     节点模式
     var xAxisForce = $d3.forceX().x(_oW/2);
     var yAxisForce = $d3.forceY().y(_oH/2);
     */

    _simulation = $d3.forceSimulation()
      .force('link', $d3.forceLink().id(function(d) { return d.id; }).distance(function(d) {return d.distance*1.2 || 100}).strength(function(d){
        return  0.1;
      }))
      .force('collide', d3.forceCollide(-10).radius(function() {return 50 }).iterations(55))
      .force('charge', $d3.forceManyBody().strength(-2))
      //.force("xAxis",xAxisForce).force("yAxis",yAxisForce);
      .force('centeringForce',d3.forceCenter(_oW/2,_oH/2));

    // 连接线
    if(_isRoot === false){
      links = root.append('g')
        .attr('class', 'links');
      // 连接箭头
      def = links.append('defs');
      marker = def.append('marker')
        .attrs({'id':'arrowhead',
          'viewBox':'-0 -6 10 20',
          'refX':130,
          'refY':3,
          'orient':'auto',
          'markerWidth':10,
          'markerHeight':12
        })
        .append('path')
        .attr('d', 'M0,0 L0,8 L12,3 z')
        .attr('fill', '#666')
        .style('stroke','none');

      link = links
        .selectAll('line')
        .data(graph.links)
        .enter()
        .append('line')
        .attr('class',function(d){
          return 'line line-'+(d.source.id || d.source);
        })
        .attr('marker-end', 'url(#arrowhead)');

      edgepaths = root.selectAll(".edgepath")
        .data(graph.links)
        .enter()
        .append('path')
        .attrs({
          'class': 'edgepath',
          'fill-opacity': 0,
          'stroke-opacity': 0,
          'id': function (d, i) {return 'edgepath' + i}
        })
        .style("pointer-events", "none");

      edgelabels = root.selectAll(".edgelabel")
        .data(graph.links)
        .enter()
        .append('text')
        .style("pointer-events", "none")
        .attrs({
          'class': 'edgelabel',
          'id': function (d, i) {return 'edgelabel' + i},
          'font-size': 12,
          'fill': '#999'
        });
      edgelabels.append('textPath')
        .style("text-anchor", "middle")
        .style("pointer-events", "none")
        .attrs({
          'xlink:href': function (d, i) {return '#edgepath' + i},
          "startOffset": "50%"
        })
        .text(function (d) {return d.type});
    }

    // 节点外层
    node = root.selectAll('g.node')
      .data(graph.nodes)
      .enter()
      .append('g')
      .attr('data-id',function(d){
        return d.id;
      })
      .classed('node', true)
      .call($d3.drag()
        .on('start', dragStarted)
        .on('drag', dragged)
        .on('end', dragEnded))
      .on("click", selectClick,false);

    node
      .on('mouseenter',function(d){
        var id = 'id' in d ? d.id : '';
        var curNode = $d3.select(this);
        if(id === ''){
          return false;
        }
        $d3.selectAll('.line-'+id).style('stroke','#fff');
        clearTimeout(gt);
        curNode.selectAll('.cp')
          .style('display','block');
        curNode.selectAll('.iText')
          .style('display','block')
      })
      .on('mouseleave',function(d){
        var id = d.id || '',infoNode = $d3.select(this).selectAll('.cp');
        var curNode = $d3.select(this);
        $d3.selectAll('.line-'+id).style('stroke','');
        infoNode
          .style('display','none');
        curNode.selectAll('.iText')
          .style('display','none')
        layerRel.time = setTimeout(function(){
          layerRel.className = 'layerRelation hide';
          layerRel.time = null;
          delete layerRel['time'];
        },500);
      });


    // 信息节点
    ck();
    // 圆节点
    circle = node.append('circle')
      .attr('class', 'circle')
      .attr('r', radius)
      .style('fill', function(d) { return defColors[d.label] || _oColors[randomColors(1,20)]});

    // 文本节点
    text = node.append('text')
      .classed('text', true)
      .text(function(d) {
        var text;
        if(d.name.length>4){
          text = d.name.substring(0,4);
        }else{
          text = d.name;
        }
        return text;
      });

    _simulation
      .nodes(graph.nodes)
      .on('tick', ticked);

    if(_isRoot === false){
      _simulation.force("link")
        .links(graph.links);
    }
    // 定义函数
    function ticked() {
      if(_isRoot === false){
        link
          .attr("x1", function(d) {return d.source.x; })
          .attr("y1", function(d) { return d.source.y; })
          .attr("x2", function(d) { return d.target.x; })
          .attr("y2", function(d) { return d.target.y; });
        edgepaths.attr('d', function (d) {
          return 'M ' + d.source.x + ' ' + d.source.y + ' L ' + d.target.x + ' ' + d.target.y;
        });
        edgelabels.attr('transform', function () {
          return 'translate(0,12)';
        });
      }
      node.attr('transform', function(d) {
        return 'translate(' + [d.x, d.y] + ')';
      });
    }

    function dragStarted(d) {
      if($d3.select(this).attr('class') === 'node'){
        if (!$d3.event.active) _simulation.alphaTarget(0.01).restart();
      }
      d.fx = d.x;
      d.fy = d.y;
    }

    function dragged(d) {
      d.fx = $d3.event.x;
      d.fy = $d3.event.y;
    }

    function dragEnded(d) {
      if($d3.select(this).attr('class') === 'node') {
        if (!d3.event.active) _simulation.alphaTarget(0);
      }
      //记录拖拽结束节点
      //d.fx = null;
      //d.fy = null;
    }

    function ck(){
      var r = 50,
          total = 360,
          startDeg = 0,
          startDegrees=0,
          endDegrees=0,
          endDeg=0,
          arc,
          arcPath,
          rotate,
          delNode,
          infoNode,
          listNode,
          colors = ['rgb(176, 183, 122)','rgb(122, 165, 183)','rgb(180, 31, 132)'];

      arc = $d3.arc().innerRadius(0);

      if(rangSplit.length>0){
        rangSplit.forEach(function(n,i){
          endDeg += n * total;
          rotate = i==0?0:rotate+5.1;
          endDegrees = endDeg * (Math.PI / 180);
          arcPath = arc.outerRadius(r).startAngle(startDegrees).endAngle(endDegrees);
          startDeg = endDeg;
          startDegrees = endDegrees;
          node
            .append('path')
            .attr('d', arcPath())
            .attr('class','cp cp'+i)
            .style('fill', colors[i])
            .style('display','none')
            .style('transform','rotate('+(rotate)+'deg)');
          // 图标节点
          if(i===0){
            infoNode = node.append('text')
              .classed('iText', true)
              .text('i')
              .style('transform','translate(34px,-10px)');
          }else if(i===1){
            listNode = node.append('image')
              .classed('iText icon-eye-open', true)
              .attr('src', './images/eyes.png')
              .attr('xlink:href','./images/eyes.png')
              .attr('width', 18)
              .attr('height', 13)
              .style('transform','translate(-8px,32px)');
          }else if(i===2) {
            delNode = node.append('text')
              .classed('iText', true)
              .text('×')
              .style('transform','translate(-42px,-10px)');
          }

          // 查看节点信息
          node.selectAll('.cp0')
            .on('click',cp0);
          if(infoNode){
            infoNode.on('click',cp0);
          }
          // 查看关系
          node.selectAll('.cp1')
            .on('click',cp1);
          if(listNode){
            listNode.on('click',cp1);
          }
          // 关闭所有关系
          node.selectAll('.cp2')
            .on('click',cp2);
          if(delNode){
            delNode.on('click',cp2);
          }
          function cp0(){
            var oClass;
            var ev = $d3.event;
            var id = this.parentNode.dataset.id;
            oClass = 'info-div info-move-show';
            infoDiv.className = oClass;
            texts({id:id});
            ev.stopPropagation();
          }
          function cp1(){
            var ev = $d3.event;
            var id = this.parentNode.dataset.id;
            if(infoTexts[id] && 'relations' in infoTexts[id]){
              clearTimeout(gt);
              clearTimeout(layerRel.time);
              gt = null;
              layerRel.time = null;
              delete layerRel.time;
              layerRelation({id:id});
              updatelayerRelation({id:id});
              return false;
            }
            getAjax({id:id,relation:'relation'},function(){
              layerRelation({id:id});
              updatelayerRelation({id:id});
            });
            ev.stopPropagation();
          }
          function cp2(){
            var infoNodes;
            var ev = $d3.event;
            var id = this.parentNode.dataset.id;
            infoNodes = infoTexts[id];
            if('isOpen' in infoNodes){
              updateData({id:id});
              delete infoNodes['isOpen'];
              if('relations' in infoNodes){
                infoNodes['relations'].forEach(function(info){
                  delete info['isStatus'];
                })
              }
              $d3.select('.roots').remove();
              _initData(graph,'update');
              return false;
            }
            //delete infoTexts[id]['isOpen'] = true;
            ev.stopPropagation();
          }
        })
      }
    }
  };

  function selectClick(event, flg){
    var ev;
    ev = $d3.event || event || window.event;
    if(flg == 'close'){
      infoDiv.className = 'info-div info-move-hide';
      setTimeout(function(){
        infoDiv.className = 'info-div hide';
      },500);
    }
    if(ev.cancelBubble){
      ev.cancelBubble = true;
    }
    if(ev.stopPropagation){
      ev.stopPropagation();
    }
  }
  // 随机函数
  function randomColors(min, max){
    return Math.random()+(max-min)+min;
  }

  function getAjax(opt,callback){
    var id = 'id' in opt ? opt['id'] : -1;
    var url =  'http://172.16.128.86:9000/search/node' || 'data.php';
    var text = opt.relationships || '';
    var datas = {};
    datas['id'] = id;
    if('type' in opt){
      datas['type'] = opt['type'];
    }else{
      datas['type'] = 'id';
    }
    if('relation' in opt){
      datas['type'] = 'relation';
    }
    if(text){
      datas['relationships'] = text;
    }
    $.ajax({
      url: url,
      type: 'get',
      dataType: 'json',
      data:datas,
      success: function(res){
        var data;
        if(!res){
          return false;
        }
        if(('data' in res) && !res['data']){
          return false;
        }
        data = 'data' in res ? res.data : res;
        if('nodes' in data){
          $d3.select('.roots').remove();
          data.nodes.forEach(function(info){
            infoTexts[info.id] = info;
          });
          _initData(data);
          // 文本展示
          //texts({id:id});
        }

        if('relation' in opt){
          if(!('parentId' in data)){
            return false;
          }
          if(!('relations' in infoTexts[data.parentId])){
            if(data.relations){
              infoTexts[data.parentId]['relations'] = data.relations;
            }else{
              infoTexts[data.parentId]['isRelations'] = false;
            }
          }
        }
        callback ? callback() : void(0);
      },
      error: function(error){
      }
    });
  }

  function layerRelation(opt){
    var i=0, l, id, olderId, relationText, x, y;
    var nodesData = graph.nodes;
    l = nodesData.length;
    id = opt.id;
    for(i;i<l;i++){
      if(nodesData[i].id == id){
        relationText = nodesData[i].relations;
        x = nodesData[i].x;
        y = nodesData[i].y;
        break;
      }
    }
    if(relationText){
      if(!isNaN(+(layerRel.sid)) && +(layerRel.sid) === +id){
        layerRel.x = x;
        layerRel.y = y;
      }else{
        olderId = layerRel.sid;
        layerRel.sid = id;
        layerRel.x = x;
        layerRel.y = y;
        relationNodes(relationText,id);
      }
      if('time' in layerRel){
        delete layerRel.time;
      }

      if(layerRel.isShow){
        if(olderId === id){
          return false;
        }
      }

      layerRel.style.left = x+(radius+15)+'px';
      layerRel.style.top = y+'px';
      if(layerRel.isShow){
        if(!isNaN(olderId) && (olderId !== id)){
          layerRel.className = 'layerRelation';
          setTimeout(function(){
            layerRel.className = 'layerRelation layerFadeIn';
          },30);
          return false;
        }
      }

      layerRel.className = 'layerRelation layerFadeIn';
      layerRel.isShow = true;
    }
  }

  function texts(opt){
    var currentNode, text, i, l, fragment, oSpan, oEm, k, n, oTextBox = $(textBox), mCSB_container = oTextBox.find('.mCSB_container');
    if('id' in opt){
      currentNode = infoTexts[opt.id];
    }

    mCSB_container.get(0).innerHTML = '';

    if(currentNode){
      text = 'text' in currentNode ? currentNode['text'] : undefined;
      if(text){
        text = text.split('^@^');
        l = text.length;
        i = 0;
      }
    }else{
      text = ['当前没有消息'];
      i = 0;
      l = 1;
    }
    if(!text){
      text = ['当前没有消息'];
      i = 0;
      l = 1;
    }
    if(i!=undefined){
      fragment = doc.createDocumentFragment();
      while(i<l){
        n = 0;
        oSpan = doc.createElement('span');
        oSpan.setAttribute('class','label-text');
        if(text[i].indexOf('^^')){
          k = text[i].split('^^');
          while(n<2){
            oEm = doc.createElement('em');
            oEm.innerHTML = k[n];
            n++;
            oSpan.appendChild(oEm);
          }
        }
        if(!oEm){
          oSpan.innerText = text[i];
        }
        fragment.appendChild(oSpan);
        i++;
      }
    }

    if(fragment){
      mCSB_container.get(0).appendChild(fragment);
      oTextBox.mCustomScrollbar({
        theme:"dark"
      });
    }
  }

  function updateData(opt){
    var id = +(opt.id), nodes, links, i=0, n, j, arrNodes, curid;
    if(isNaN(id)){
      return false;
    }
    nodes = graph.nodes;
    links = graph.links;
    if(nodes && links){

      arrNodes = treeNodes([id],links);

      while(arrNodes[i] && arrNodes[i]>=0){
        curid = +arrNodes[i];
        j = 0;
        n = 0;
        while(links[j]){
          if(+(links[j]['target']['id']) === curid){
            links.splice(j,1);
            j = j;
          }else{
            j++;
          }
        }
        while(nodes[n]){
          if(+(nodes[n]['id']) === curid){
            nodes.splice(n,1);
            n = n;
          }else{
            n++;
          }
        }
        i++;
      }
      //console.log(graph);
    }
  }

  function treeNodes(nodeId, links){
    var checkNodes, arrNodes = [];
    if(!nodeId || !links || links.length===0){
      return false;
    }

    checkNodes = function(nodeId, links){
      var i=0, j=0, l, k, nodes=[], curId;
      l = links.length;
      k = nodeId.length;
      if(k<0){
        return [];
      }
      for(j;j<k;j++){
        curId = nodeId[j];
        i = 0;
        for(i;i<l;i++){
          if(+links[i]['source']['id'] === +curId){
            nodes.push(links[i]['target']['id']);
          }
        }
      }

      if(nodes.length>0){
        arrNodes = arrNodes.concat(nodes);
        checkNodes(nodes,links);
      }else{
        return nodes;
      }
    };

    checkNodes(nodeId, links);
    return arrNodes;
  }

  function updatelayerRelation(opt){
    var id, domA, text, status;
    if(!'id' in opt){
      return false;
    }
    id = opt.id;
    domA = layerRel.querySelectorAll('.see') || $(layerRel).find('.see');
    if(domA.length<=0){
      domA = null;
    }
    if(infoTexts[id] !== undefined){
      if(('isOpen' in infoTexts[id])){
        status = true;
      }
      if('relations' in infoTexts[id]){
        infoTexts[id]['relations'].forEach(function(info, i){

          if(status){
            if(('isStatus' in info) && info['isStatus'] === true){
              text = '已展开';
              domA[i].style.cursor = 'default';
            }else{
              text = '查看';
              domA[i].style.cursor = 'pointer';
            }
          }else{
            delete info.isStatus;
            text = '查看';
            if(domA){
              domA[i].style.cursor = 'pointer';
            }
          }
          if(domA) {
            domA[i].innerHTML = text;
          }
        })
      }
    }
  }

  function relationNodes(relationText, parentId){
    var i=0,l=relationText.length;
    var tagDiv, tagSpan, tagAsee, delDom;
    var fragments = document.createDocumentFragment();


    if(layerRel.pushStack.length>0){
      while(delDom = layerRel.pushStack.pop()){
        layerRel.removeChild(delDom);
      }
    }

    for(i;i<l;i++){
      tagDiv = document.createElement('div');
      tagSpan = document.createElement('span');
      tagAsee = document.createElement('a');
      tagDiv.className = 'relationList';
      tagAsee.setAttribute('class','see');
      tagAsee.setAttribute('data-parent-id', parentId);
      tagAsee.setAttribute('data-index', i);
      tagAsee.innerHTML = '查看';
      tagSpan.innerHTML = relationText[i].title;
      tagDiv.appendChild(tagSpan);
      tagDiv.appendChild(tagAsee);
      layerRel.pushStack.push(tagDiv);
      fragments.appendChild(tagDiv);
    }
    layerRel.dataset.parentId = parentId;
    layerRel.appendChild(fragments);
    return layerRel;
  }

  function docClick(){
    if(layerRel && layerRel.isShow){
      layerRel.className = 'layerRelation layerFadeOut';
      clearTimeout(layerRel.time);
      delete layerRel.time;
      layerRel.time = setTimeout(function(){
        layerRel.className = 'layerRelation hide';
        delete layerRel.isShow;
      },500);
    }
  }

  // init
  getAjax({id:-1});

})(d3,this);