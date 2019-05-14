<?php 
define('ACC',true);
include('../include/init.inc.php');


if(!isset($_SESSION['username'])){
	$login=true;
	$buttonMsg="送出";
	
	
	include("../view/header_admin.html");
	
	include("../view/footer_admin.html");
}else{

include('../include/config.ini.php');
header("Content-Type:text/html; charset=utf-8");
date_default_timezone_set('Asia/Taipei');

//進出資料庫
$conn = mysqli_connect($_CFG['host'],$_CFG['user'],$_CFG['passwd']);
// echo "connect";
if($conn){
	// echo " :success!<br/>";
}else{
	echo " :fail!<br/>";
}



function sqlexe($conn,$sql){
	$rs = mysqli_query($conn,$sql);
	if($rs){
		// echo $sql ." :success!<br/>";
	}else{
		// echo $sql ." :fail!<br/>";
	}
	return $rs;
}

function getAll($conn,$sql){
	$rs = sqlexe($conn,$sql);
	$data = array();
	while($rows = mysqli_fetch_assoc($rs)){
		$data[]=$rows;
	}
	return $data;
}

$sql = "use " . $_CFG['db'];

sqlexe($conn,$sql);


$sql = "set names utf8";

sqlexe($conn,$sql);

$sql = "select count(*)as count from orders where year=" . $_POST['year'] . " and month=" . 
$_POST['month'] . " and day=" .$_POST['day'] ." and aid=" . $_POST['aid'];


$exist = getAll($conn,$sql)[0]['count'];

// print_r($exist);


if($exist==0){
	$sql = "insert into orders (year,month,day,aid,booked)values(".
	$_POST['year'].",".$_POST['month'].",".$_POST['day'].",".$_POST['aid'].",".
	$_POST['booked'].")";
}else{
	$sql = "update orders set booked=" . $_POST['booked'] ." where year=" 
	. $_POST['year'] . " and month=" . $_POST['month'] . " and day=" 
	.$_POST['day'] ." and aid=" . $_POST['aid'];
}

// echo $sql;

$rs = sqlexe($conn,$sql);

if($rs){
	$msg ="資料新增成功";
}else{
	$msg ="資料新增失敗";
}


$status="order";
$sql = "delete from orders where aid not in (select aid from area)";
		sqlexe($conn,$sql);

$sql = "select year,month,day,name,booked from orders left join area on ";
$sql .=" area.aid=orders.aid order by year,month,day,orders.aid";
$datas =getAll($conn,$sql);

include("../view/header_admin.html");
include("../view/message_admin.html");
include("../view/footer_admin.html");
}
?>