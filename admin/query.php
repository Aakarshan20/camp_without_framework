<?php


define('ACC',true);
include("../include/mysql_connect.php");
include("../include/init.inc.php");


// $sql = "show tables";




// $sql = "insert into users(name,passwd)values('";
// $sql .= "manager3','" . md5('manager31970B51999') ."')";

// $sql = "delete from users where uid=3";


$sql = "update users set passwd='" . md5('manager31970B51999') . "'";
$sql .=" where name='manager3'";

mysqli_query($conn,$sql);


$sql = "select * from users";

$tables = array();

$tables = getAll($conn,$sql);

print_r($tables);


?>