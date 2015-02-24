<?php


header("HTTP/1.1 509 Bandwidth Limit Exceeded");
echo('<h1>Bandwidth Limit Exceeded</h1>
The server is temporarily unable to service your request due to the site owner reaching his/her bandwidth limit. Please try again later.
<hr>
<i>Apache/2.2.4 Server at tothebestof.com Port 80</i>');


?>