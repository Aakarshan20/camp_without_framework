<?php
define('ACC',true);
include('../include/init.inc.php');
$_SESSION = array();
$bannerMsg = "成功登出";
$buttonMsg = "重新登入";
include("../view/header_admin.html");
// include("../view/message_admin.html");
include("../view/footer_admin.html");
?>