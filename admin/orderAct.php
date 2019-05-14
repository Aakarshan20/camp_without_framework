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
<<<<<<< HEAD
	echo " :fail!<br/>";
=======
	// echo " :fail!<br/>";
>>>>>>> update
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

<<<<<<< HEAD
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

=======

	$sql = "insert into orders (year,month,day,aid,booked,guest_name,phone,bank_account,stayDays)values(".
	$_POST['year'].",".$_POST['month'].",".$_POST['day'].",".$_POST['aid'].",".
	$_POST['booked'].",'".$_POST['guestName']."',".$_POST['guestPhone'].",".$_POST['bank_account'].",".$_POST['stayDays'] . ")";
	
$rs = sqlexe($conn,$sql);

if($rs){
	$msg ="資料新增成功";
}else{
	$msg ="資料新增失敗 ";
}
if($_POST['aid']==0){
	$msg .=" 請選擇區域";
}
// Array ( [year] => 2017 [month] => 6 [day] => 17 [aid] => 0 [guestName] => [guestPhone] => [stayDays] => [bank_account] => )
if(empty($_POST['booked'])){
	$msg .=" 預訂數量未填";
}

if(empty($_POST['guestName'])){
	$msg .=" 客戶姓名未填";
}

if(empty($_POST['guestPhone'])){
	
	$msg .=" 連絡電話未填";
}else if(!is_numeric($_POST['guestPhone'])){
	$msg .=" 連絡電話請填入數字";
}

if(empty($_POST['stayDays'])){
	$msg .=" 預訂天數未填";
}else if(!is_numeric($_POST['stayDays'])){
	$msg .=" 預訂天數請填入數字";
}

if(empty($_POST['bank_account'])){
	$msg .=" 匯款帳戶未填";
}else if(!is_numeric($_POST['bank_account'])){
	$msg .=" 匯款帳戶請填入數字";
}

if($_POST['stayDays']>1){//多於一天開始循環添加
	$sql = "SELECT LAST_INSERT_ID() AS `id`";
	$last_id = getAll($conn,$sql)[0]['id'];//找父id
	
	$year = $_POST['year'];
	$month = $_POST['month'];
	$day = $_POST['day'];
	
	for($i=1;$i<$_POST['stayDays'];$i++){
			// d-m-y
			$new_year = date("Y",strtotime($day."-".$month."-".$year) + ($i*86400));
			$new_month = date("m",strtotime($day."-".$month."-".$year) + ($i*86400));
			$new_day = date("d",strtotime($day."-".$month."-".$year) + ($i*86400));
			
			$sql = "insert into orders (year,month,day,aid,booked,guest_name,phone,bank_account,stayDays,parent_id)values(".
			$new_year.",".$new_month.",".$new_day.",".$_POST['aid'].",".
			$_POST['booked'].",'".$_POST['guestName']."',".$_POST['guestPhone'].",".$_POST['bank_account'].",".
			$_POST['stayDays'] . "," . $last_id .")";
			// echo $sql . "<br/>";
			
			 sqlexe($conn,$sql);
	}
	
}

// exit();

// print_r($id);



>>>>>>> update

$status="order";
$sql = "delete from orders where aid not in (select aid from area)";
		sqlexe($conn,$sql);

<<<<<<< HEAD
$sql = "select year,month,day,name,booked from orders left join area on ";
$sql .=" area.aid=orders.aid order by year,month,day,orders.aid";
=======
$sql = "select year,month,day,name,booked,guest_name,phone,bank_account,status,orders.createtime,stayDays,price from orders left join area on ";
$sql .=" area.aid=orders.aid where parent_id=0 order by year,month,day,orders.aid ";
>>>>>>> update
$datas =getAll($conn,$sql);

include("../view/header_admin.html");
include("../view/message_admin.html");
include("../view/footer_admin.html");
}
?>