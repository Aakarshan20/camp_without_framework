<?php
define('ACC',true);
include('./Model/mysql.php');
include('./include/config.ini.php');
include('./include/init.inc.php');
header("Content-Type:text/html; charset=utf-8");
date_default_timezone_set('Asia/Taipei');

if(empty($_SESSION['usertoken'])){
$msg = "請登入以繼續查詢";
include("./view/calender.html");

include("./view/message.html");
include("./view/footer.html");
//sleep(3);
//header("login.php");

}else{
$mysql = mysql::get_instance();

$passwd = $_SESSION['usertoken'];
$sql = "select guest_name,phone,email,year,month,day,area.name,booked,stayDays,status,price,guestinfo.createtime,bank_account  from guestinfo left join area on guestinfo.aid=area.aid where passwd=:passwd and parent_id=0";
// $sql = "select count(1)  from guestinfo left join area on guestinfo.aid=area.aid where passwd=:passwd";

$rs = $mysql->prepare($sql);
$rs->bindParam(':passwd',$passwd,PDO::PARAM_STR);
$rs->execute();
$datas = $rs->fetchAll(PDO::FETCH_CLASS);

 include("./view/calender.html");
 include("./view/guestOrder.html");
 include("./view/footer.html");
	
}

?>
