

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

// print_r($_POST);
$mode = '';
if(isset($_GET['m'])){
	if($_GET['m']=='area'){
		$mode='area';
		$_POST['available'] +=0;
	}else if($_GET['m']=='data'){
		$mode='data';
		$_POST['booked'] +=0;
	}
}


// exit();

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


switch($mode){
	case "area":
	// print_r($_POST);
	if(!is_int($_POST['available']) || ($_POST['available']<=0)){
		$msg = "編輯失敗，請輸入正確的數字" . $_POST['available'];
		$areastatus='view';
		$sql ="select * from area";
		$datas = array();
		$datas = getAll($conn,$sql);
	}else{

		$sql = "update area set name = '" . $_POST['name'] . "',available='";
		$sql .= $_POST['available'] . "' where aid='" . $_POST['aid'] . "'";
		// echo $sql;
		// exit();
		$rs = sqlexe($conn,$sql);
		if($rs){
			$msg = "編輯成功";
		}else{
			$msg = "編輯失敗";
		}
		$areastatus='view';
		$sql ="select * from area";
		$datas = array();
		$datas = getAll($conn,$sql);
	}
	break;
	case "data":
	// print_r($_POST);
	
	if(!is_numeric($_POST['year']) || ($_POST['year']<=0)||($_POST['year']>2100)){
		$msg = "編輯失敗，請輸入正確的年份: 您輸入了" . $_POST['year'];
		$areastatus='view';
		$sql ="select * from area";
		$datas = array();
		$datas = getAll($conn,$sql);
	}else if(!is_numeric($_POST['month']) || ($_POST['month']<=0) || ($_POST['month']>12)){
		$msg = "編輯失敗，請輸入正確的月份: 您輸入了" . $_POST['month'];
		$areastatus='view';
		$sql ="select * from area";
		$datas = array();
		$datas = getAll($conn,$sql);
		
	}else if(!is_numeric($_POST['day']) || ($_POST['day']<=0) || ($_POST['day']>31)){
		$msg = "編輯失敗，請輸入正確的日期: 您輸入了" . $_POST['day'];
		$areastatus='view';
		$sql ="select * from area";
		$datas = array();
		$datas = getAll($conn,$sql);
	}else if(!is_numeric($_POST['booked']) || ($_POST['booked']<=0)){
		$msg = "編輯失敗，請輸入正確的數字" . $_POST['booked'];
		$areastatus='view';
		$sql ="select * from area";
		$datas = array();
		$datas = getAll($conn,$sql);
	}else if($_POST['day'] > cal_days_in_month(CAL_GREGORIAN, $_POST['month'], $_POST['year'])){
		$msg = "編輯失敗，請輸入正確的日期: 您輸入了" . 
		$_POST['year'] . "年 " . $_POST['month'] . "月 " . $_POST['day'] . "日";
		$areastatus='view';
		$sql ="select * from area";
		$datas = array();
		$datas = getAll($conn,$sql);
	}else{
		$sql = "update orders set year = '" . $_POST['year'] . "',month='";
		$sql .= $_POST['month'] . "',day='" . $_POST['day'] ."',aid='";
		$sql .= $_POST['aid'] . "' ,booked='". $_POST['booked'] ."' where id='" . $_POST['id'] . "'";
		
		// echo $sql;
		// exit();
		$rs = sqlexe($conn,$sql);
		if($rs){
			$msg = "編輯成功";
		}else{
			$msg = "編輯失敗";
		}
		$status='view';
		$sql ="select year,month,day,name,booked from orders ";
		$sql .= " left join area on area.aid=orders.aid order by year,month,day,orders.aid";
		$datas = array();
		$datas = getAll($conn,$sql);
		$sql = "delete from orders where aid not in (select aid from area)";
		sqlexe($conn,$sql);
	}
	break;
	
	
}


// $sql = "select * from area";
// $sql = "select * from orders " ;
// $sql .= " left join area on area.aid=orders.aid order by day,name asc";
// $datas = getAll($conn,$sql);

// $status = "area";

include('../view/header_admin.html');
include('../view/message_admin.html');
include('../view/footer_admin.html');


}













?>