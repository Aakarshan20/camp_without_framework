<?php

define('ACC',true);
include('./Model/mysql.php');
include('./include/config.ini.php');
include('./include/init.inc.php');
header("Content-Type:text/html; charset=utf-8");
date_default_timezone_set('Asia/Taipei');
$msg = "已登出";
unset($_SESSION["usertoken"]);
include("./view/calender.html");

include("./view/message.html");
include("./view/footer.html");
?>