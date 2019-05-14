<?php

define('ACC',true);
include('./Model/mysql.php');
include('./include/config.ini.php');
include('./include/init.inc.php');
$mysql = mysql::get_instance();


$month = date("m");
// $month=7;
$year = date("Y");
// $sql = "desc area";
$sql ="alter table area add column price smallint";
$sql .=" unsigned not null defaul 0";
// $sql = "select day,sum(booked) as sum from orders";
// $sql .=" where year=:year and month=:month ";
// $sql .=" group by day  order by day ";
// $rs= $mysql->prepare($sql);
// $rs->bindParam(':year', $year, PDO::PARAM_INT);
// $rs->bindParam(':month', $month, PDO::PARAM_INT);
// $rs->execute();
$rs=$mysql->query($sql);

// $sql = "desc area";
// $rs=$mysql->query($sql);
// $datas = $rs->fetchAll(PDO::FETCH_CLASS);

print_r($rs);
// print_r($datas);
?>