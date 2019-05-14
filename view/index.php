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
	// echo " :fail!<br/>";
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

/*
$ori_month = date("m");
*/
// if(isset($_GET['m']) && is_numeric($_GET['m'])){
	// if($_GET['m'] >0){
		// $month_shift=$_GET['m'];
		$day = 1;
	// }else{
		// $month_shift=0;
		// $day = date("d")+0;
	// }
// }else{
	// $month_shift=0;
	// $day = date("d")+0;
// }
// */

$year = date("Y");
// if(isset($_GET['mon'])){
	// $month=$_GET['mon'];

// }else{
	$month = date("m")+0;//+$month_shift;
// }

/*
if($month>12){
	$remainder = $month%12;
	$quotemeta = (int)($month/12);
	
	echo "remainder: " . $remainder ."<br/>";
	echo "quotemeta: " . $quotemeta ."<br/>";
	$month = $remainder;
	$year = $year + $quotemeta;
}
*/
// $calender = array();

$day_of_month = cal_days_in_month(CAL_GREGORIAN,$month,$year);  

/*

for($i=0;$i<$day_of_month;$i++){
	if($i+1<$day){
		$calender[]="";
	}else{
		$calender[]=$i+1;
	}
}
*/


//print_r($calender);
 // $weekday = date("w",mktime(0,0,0,$month,1,$year));

// for($i=0;$i<$weekday;$i++){
	// array_unshift($calender," ");
// }

// echo '<br/>';
// print_r($calender);
// if(!$calender[6]){
	// $calender = array_slice($calender,7);
// }
// echo '<br/>';

$sql = "select aid,name,available from area order by aid asc";
$areas = getAll($conn,$sql);
// print_r($areas);
// echo "month : " . $month , "<br/>";

// if(isset($_GET['da'])){
	// $day = $_GET['da'];
// }



// echo '<br/>';
$sql = "select * from orders ";
$sql .= " left join area on area.aid=orders.aid ";
$sql .= " where orders.aid in(select aid from area) ";
$sql .= " order by year,month,day,orders.aid asc;";
$datas = getAll($conn,$sql);




//echo '<pre>';
 // print_r($datas);
//echo '</pre>';
// exit();

// $sql = "select day,sum(booked)as total from orders where month=" . 
	// $month. "  group by day";
// $sum_of_each_day_arr = getAll($conn,$sql);
	
// print_r($sum_of_each_day_arr);

// $aid_arr = array();
// $total_avaliable = 0;
// foreach($areas as $v){
	// array_push($aid_arr,$v['aid']);
	// $total_avaliable += $v['available'];
// }
//print_r($aid_arr);

// $full_days_arr = array();

// foreach($sum_of_each_day_arr as $v){
	// if($v['total']==$total_avaliable){
		
		// $full_days_arr[$v['day']] = $v['total'];
	// }
// }
// print_r($full_days_arr);
/*
$aid_datas_arr = array();

for($i=0;$i<count($aid_arr);$i++){
	foreach($datas as $v){
		if($v['aid'] == $aid_arr[$i]){
			$aid_datas_arr[$aid_arr[$i]] = $v;
		}
	}
}
*/
 // echo $areas[0]['available'];
 $status = 'admin';
 // print_r($areas);

include("../view/header_admin.html");
include("../view/index_admin.html");
include("../view/footer_admin.html");
// echo "admin";
}

?>