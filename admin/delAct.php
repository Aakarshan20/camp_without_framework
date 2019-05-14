

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

$id = $_GET['id'] +0;

// echo $id;

<<<<<<< HEAD

$sql = "delete from orders where id=" .$id;

$rs = sqlexe($conn,$sql);

=======
$table = (!isset($_GET['table']) )?"orders":$_GET['table'];

$sql = "delete from " . $table ." where id=" .$id . " or parent_id=" .$id;

$rs = sqlexe($conn,$sql);

// echo $sql;

>>>>>>> update
if($rs){
	$msg = "刪除成功";
}else{
	$msg = "刪除失敗";
}

<<<<<<< HEAD
$sql = "select * from orders " ;
$sql .= " left join area on area.aid=orders.aid order by day,name asc";
=======
$sql = "select * from  " . $table ;
$sql .= " left join area on area.aid= " . $table . ".aid where parent_id=0 order by day,name asc";
// echo $sql;
>>>>>>> update
$datas = getAll($conn,$sql);

$status = "admin";

include('../view/header_admin.html');
include('../view/message_admin.html');
include('../view/footer_admin.html');





}










?>