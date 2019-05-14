<?php

define('ACC',true);
include('./Model/mysql.php');
include('./include/config.ini.php');
include('./include/init.inc.php');
header("Content-Type:text/html; charset=utf-8");
date_default_timezone_set('Asia/Taipei');

$mysql = mysql::get_instance();

$sql = "select count(1) as count from guestinfo where email=:email and passwd = :passwd"; 
$email = $_POST['email'];
$passwd = md5($email."1970". $_POST['passwd']);

$rs = $mysql->prepare($sql);
$rs->bindParam(':passwd',$passwd,PDO::PARAM_STR);
$rs->bindParam(':email',$email,PDO::PARAM_STR);
$rs->execute();
$datas = $rs->fetchAll(PDO::FETCH_CLASS)[0]->count;



if($datas==0){
	$msg="帳號密碼錯誤";
}else{
	$_SESSION['usertoken'] = $passwd;
	$msg="登入成功";
}

include("./view/calender.html");

include("./view/message.html");
include("./view/footer.html");


?>