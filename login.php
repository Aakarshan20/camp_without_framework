<?php

define('ACC',true);
include('./Model/mysql.php');
include('./include/config.ini.php');
include('./include/init.inc.php');
header("Content-Type:text/html; charset=utf-8");
date_default_timezone_set('Asia/Taipei');


include("./view/calender.html");

include("./view/login.html");
include("./view/footer.html");
?>





