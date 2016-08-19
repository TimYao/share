function getWebSocket(url,message,fn)
{
    var websocketobj = new WebSocket(url);
    websocketobj.onopen = function(event){
        websocketobj.send(message);
    };
    websocketobj.onmessage = function(event){
        //console.log(event.data);
        fn(event);
    };
    websocketobj.onerror = function()
    {
        console.log("error");
    };
    websocketobj.onclose = function()
    {
        console.log("origin:"+url+"is closed!");
    }
}
getWebSocket("ws://www.ylx.com/test/cross_domain/1.html",'',getmain);

function getmain(ev)
{
   console.log(ev.data);
}