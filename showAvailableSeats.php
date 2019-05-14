<?php

//echo "for test";

define('ACC',true);
include('./Model/mysql.php');
include('./include/config.ini.php');
include('./include/init.inc.php');
header("Content-Type:text/html; charset=utf-8");
date_default_timezone_set('Asia/Taipei');

$mysql = mysql::get_instance();


$year = $_GET['year'];
$month = $_GET['month'];
$day = $_GET['day'];
$aid = $_GET['aid'];

$sql ="select sum(booked) as booked from (";
$sql .= "select booked from orders ";
$sql .=" where year=:year ";
// $sql .=" where year=" . $year;
$sql .=" and month = :month and day=:day and orders.aid=:aid and status=1";
// $sql .=" and month =".$month." and day=" .$day." and orders.aid=" . $aid . " and status=1";

$sql .=" union all ";

$sql .= "select booked from guestinfo ";
$sql .=" where year=:year ";
// $sql .=" where year=" . $year;
$sql .=" and month = :month and day=:day and guestinfo.aid=:aid and status=1";
// $sql .=" and month =".$month." and day=" .$day." and guestinfo.aid=" . $aid . " and status=1";
$sql .=")as tmp ";



// echo $sql ;
// exit();

$rs = $mysql ->prepare($sql);

$rs->bindParam(':year',$year,PDO::PARAM_INT);
$rs->bindParam(':month',$month,PDO::PARAM_INT);
$rs->bindParam(':day',$day,PDO::PARAM_INT);
$rs->bindParam(':aid',$aid,PDO::PARAM_INT);
$rs->execute();
$datas = $rs->fetchAll(PDO::FETCH_CLASS);

$booked = empty($datas)?0:$datas[0]->booked;

$sql = "select available from area where aid=:aid";

$rs = $mysql ->prepare($sql);
$rs->bindParam(':aid',$aid,PDO::PARAM_INT);
$rs->execute();
$available = $rs->fetchAll(PDO::FETCH_CLASS)[0]->available;

echo $available-$booked;

?>