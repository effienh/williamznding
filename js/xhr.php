

<script type="text/javascript">

var refreshDelay = 10000;

/* Creates the XMLHTTPRequest object depending on the browser */
function createRequestObject() {
    var ro;
    if(navigator.appName == "Microsoft Internet Explorer"){
        ro = new ActiveXObject("Microsoft.XMLHTTP");
    }else{
        ro = new XMLHttpRequest();
    }
    return ro;
}
var http = createRequestObject();

/* Makes the request back to /div.php ... change the URL to whatever
   script you put on the server side to return the contents of the div only */
function sndReq() {
    http.open('get', '/wwi/js/temp.php');
    http.onreadystatechange = handleResponse;

    http.send();
}

/* Does the work of replacing the contents of the div id="target" when
   the XMLHTTPRequest is received, and schedules next update */
function handleResponse() {
    if(http.readyState == 4 && http.status == 200){
        var response = http.responseText;
        document.getElementById('temp').innerHTML = response;
        setTimeout(sndReq, 6000);
    }
}

/* Schedules the first request back to the server. Subsequent refreshes
   are scheduled in handleResponse() */
setTimeout(sndReq, 5);
</script>
