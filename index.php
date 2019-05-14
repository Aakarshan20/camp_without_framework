<?php 
define('ACC',true);
// exit();
//include('./include/config.ini.php');
include('./Model/mysql.php');
include('./include/init.inc.php');
header("Content-Type:text/html; charset=utf-8");
date_default_timezone_set('Asia/Taipei');
// exit();
$mysql = Mysql::get_instance();


$first_link_title = "行車動線";
$first_link = "map.php";
$second_link_title = "立即訂位";
$second_link = "order.php";

//設定首頁縮圖
$area_covers=array(
"./view/images/a_cover.jpg","./view/images/b_cover.jpg","./view/images/c_cover.jpg","./view/images/d_cover.jpg",
"./view/images/ian1_cover.jpg","./view/images/ian2_cover.jpg","./view/images/ian3_cover.jpg","./view/images/ian4_cover.jpg",);


//公用函式區
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

//結束函式





$ori_month = date("m");

if(isset($_GET['m']) && is_numeric($_GET['m'])){
	if($_GET['m'] >0){
		$month_shift=$_GET['m'];
		$day = 1;
	}else{
		$month_shift=0;
		$day = date("d")+0;
	}
}else{
	$month_shift=0;
	$day = date("d")+0;
}

$year = date("Y");

$month = date("m")+$month_shift;



if($month>12){
	$remainder = $month%12;
	$quotemeta = (int)($month/12);

	$month = $remainder;
	$year = $year + $quotemeta;
}

$calender = array();

$day_of_month = cal_days_in_month(CAL_GREGORIAN,$month,$year);  


for($i=0;$i<$day_of_month;$i++){
	if($i+1<$day){
		$calender[]="";
	}else{
		$calender[]=$i+1;
	}
}



// print_r($calender);
 $weekday = date("w",mktime(0,0,0,$month,1,$year));

for($i=0;$i<$weekday;$i++){
	array_unshift($calender," ");
}

// echo '<br/>';
// print_r($calender);
while(isset($calender[6]) && (!$calender[6])){
	$calender = array_slice($calender,7);
}
// echo '<br/>';
// print_r($calender);



$sql = "select aid,name,available from area order by aid";

$areas = $mysql->query($sql)->fetchAll();



// echo '<br/>';
$sql = "select * from orders where year=" . $year . " and month=" . $month ;
$sql .= " and day>=" . $day;
// $datas = getAll($conn,$sql);

$datas=$mysql->query($sql)->fetchAll();


// echo '<pre>';
 // print_r($datas);
// echo '</pre>';

$sql = "select day,sum(booked)as total from orders where month=" . 
	$month. " and year=".$year ."  group by day";
// $sum_of_each_day_arr = getAll($conn,$sql);

$sum_of_each_day_arr = $mysql->fetchAll($sql);
	
// print_r($sum_of_each_day_arr);

$aid_arr = array();
$total_avaliable = 0;
foreach($areas as $v){
	array_push($aid_arr,$v['aid']);
	$total_avaliable += $v['available'];
}
// print_r($aid_arr);
// print_r($total_avaliable);

$full_days_arr = array();

foreach($sum_of_each_day_arr as $v){
	if($v['total']>=$total_avaliable){
		$full_days_arr[$v['day']] = $v['total'];
	}
}
// print_r($full_days_arr);

$aid_datas_arr = array();

for($i=0;$i<count($aid_arr);$i++){
	foreach($datas as $v){
		if($v['aid'] == $aid_arr[$i]){
			$aid_datas_arr[$aid_arr[$i]][] = $v;
		}
	}
}



include("./view/calender.html");
include("./view/index_front.html");
include("./view/footer.html");

?>
















