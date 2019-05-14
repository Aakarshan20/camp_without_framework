<?php
defined('ACC')||exit('ACC Denied');

include('../include/config.ini.php');

// $_CFG= array();

// print_r($_CFG);
// $_CFG['host'] = 'localhost';
// $_CFG['db'] = 'camp';
// $_CFG['user'] = 'hisql0726';
// $_CFG['passwd'] = 'hisql0726sqlhi';
// $_CFG['char'] = 'utf8';


// exit();
if(!($conn = mysqli_connect($_CFG['host'], $_CFG['user'], $_CFG['passwd'])))
        die("無法對資料庫連線");

//資料庫連線採UTF8
mysqli_query($conn,"SET NAMES utf8");

//選擇資料庫
if(!mysqli_select_db($conn,$_CFG['db']))
        die("無法使用資料庫");

	
function getAll($conn,$sql){
	$rs = mysqli_query($conn,$sql);
	$datas = array();
	while($rows=mysqli_fetch_assoc($rs)){
		$datas[]=$rows;
	}	
	return $datas;
}


function getOne($conn,$sql){
	$rs = mysqli_query($conn,$sql);
	$row = mysqli_fetch_row($rs);
	return $row;
}
	
?>  



