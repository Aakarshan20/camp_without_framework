

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




$conn = mysqli_connect($_CFG['host'],$_CFG['user'],$_CFG['passwd']);
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


if(isset($_GET['aid']) && (!isset($_GET['id']))){
	$aid = $_GET['aid']+0;
	
	if(!is_int($aid) || ($aid<=0)){
		// $status = "area";
		$msg = "編輯失敗 ( 請輸入大於零的數字 ) ";
	}else{
		$editor = "area";
		$sql = "select * from area where aid=" . $aid;

		
		$areaDatas = array();
		$areaDatas = getAll($conn,$sql)[0];
		// print_r($areaDatas);
		$msg = "";
	}
}else if(isset($_GET['id']) && (!isset($_GET['aid']))){
	$id = $_GET['id']+0;
	
	if(!is_int($id) || ($id<=0)){
		$msg = "編輯失敗";
	}else{
		$editor = "data";
		$sql = "select * from area order by aid";
		$areas = array();
		$areas = getAll($conn,$sql);
		// print_r($areas);
		
		// print_r($areaDatas);
		$sql = "select id,year,month,day,name,booked,orders.aid from orders left join area ";
		$sql .= " on area.aid=orders.aid where id = " . $_GET['id'];
		$datas = array();
		$datas = getAll($conn,$sql)[0];
		// print_r($datas);
		$msg = "";
		// $
	}
}else{
	$msg = "編輯失敗";
}




include('../view/header_admin.html');

include('../view/footer_admin.html');




}











?>