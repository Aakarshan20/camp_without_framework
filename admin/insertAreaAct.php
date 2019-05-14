

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

$_POST['available'] +=0;
<<<<<<< HEAD

=======
$_POST['price']+=0;
print_r($_POST);
>>>>>>> update
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
if(!is_int($_POST['available']) || ($_POST['available']<=0)){
	$msg = " 新增失敗，請輸入正確的數字";
	$areastatus = 'view';
	
}else{

	$sql = "insert into area (name,available)values('" . $_POST['name'] . "','" . $_POST['available'] . "')";

=======
if(empty($_POST['name'])){
	$msg = " 新增失敗，名稱為空白";
	$areastatus = 'view';
}else if(!is_int($_POST['available']) || ($_POST['available']<=0) || !is_int($_POST['price']) || ($_POST['price']<=0)){
	
	$msg = " 新增失敗，請輸入正確的數字";
	$areastatus = 'view';
}else{

	$sql = "insert into area (name,available,price)values('" . $_POST['name'] . "','" . $_POST['available'] ."','". $_POST['price'] .  "')";
		// echo $sql;
		// exit();
>>>>>>> update
	$areastatus = 'view';
	$rs = sqlexe($conn,$sql);

	if($rs){
		$msg = "新增成功";
	}else{
		$msg = "新增失敗";
	}
}

$sql = "select * from area order by aid " ;
// $sql .= " left join area on area.aid=orders.aid order by day,name asc";
$datas = getAll($conn,$sql);

// print_r($datas);

include('../view/header_admin.html');
include('../view/message_admin.html');
include('../view/footer_admin.html');


}













?>