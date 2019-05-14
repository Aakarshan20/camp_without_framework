<?php
define('ACC',true);

header("Content-Type:text/html; charset=utf-8");
date_default_timezone_set('Asia/Taipei');
include('./Model/mysql.php');
include('./include/config.ini.php');
include('./include/init.inc.php');
$mysql = Mysql::get_instance();

if(!isset($_GET)){
	exit();
}

$cal_arr = explode('/',$_GET['m']);

if(count($cal_arr)!=3){
	exit();
}

if(!is_numeric($cal_arr[0])||!is_numeric($cal_arr[1])|| !is_numeric($cal_arr[2])){
	exit();
}

if($cal_arr[0]>2100 || $cal_arr[0]<2017 || ($cal_arr[1]+0)<1 || ($cal_arr[1]+0)>12
|| $cal_arr[2]<1 || $cal_arr[2]>32 ){
	exit();
}

list($year,$month,$day) = $cal_arr;

// $sql  = "select A.aid,name,booked,available,available-booked as remain,price";
// $sql .= " from orders A left join area B on A.aid=B.aid where year=:year and month=:month and day=:day and status=1";

$sql ="select aid,name,sum(booked)as booked,available,available-sum(booked) as remain,price from ";
$sql .="( select A.aid,name,booked,available,price from orders A ";
$sql .="left join area B on A.aid=B.aid where year=:year and month=:month and day=:day and status=1 ";
$sql .=" union all ";
 $sql .="select C.aid,name,booked,available,price from guestinfo C ";
 $sql .="left join area D on C.aid=D.aid where year=:year and month=:month and day=:day and status=1 ";
 $sql .=")as tmp group by aid ";

// echo $sql;
// exit();
$rs=$mysql->prepare($sql);

$rs->bindParam(':year', $year, PDO::PARAM_INT);
$rs->bindParam(':month', $month, PDO::PARAM_INT);
$rs->bindParam(':day', $day, PDO::PARAM_INT);

$rs->execute();


$orders_arr = $rs->fetchAll(PDO::FETCH_CLASS);

$sql = "select count(1) as count from area";
$rs = $mysql->query($sql);
$counts_of_area = $rs->fetchAll(PDO::FETCH_CLASS)[0]->count;


if(count($orders_arr)!=$counts_of_area){
	
	$sql  = "select * from area order by aid";
	$rs = $mysql->query($sql);
	$areas_arr = $rs->fetchAll(PDO::FETCH_CLASS);
	foreach($areas_arr as $k=>$v){
		foreach($orders_arr as $inner_k=>$inner_v){
			if($inner_v->aid == $v->aid){
				$areas_arr[$k] = $inner_v;
			}
		}
	}
	$orders_arr = $areas_arr;
}
// echo '<pre>';
// print_r($orders_arr);
// echo '</pre>';

// exit();
?>
		



		<div style="padding-bottom:10px">請選擇地區:&nbsp;&nbsp;
		
			<span>
				<select id="areaSelector" name ="area" onchange="getAvailableSite()">
					<option value=0 select>請選擇地區</option>
					<?php foreach($orders_arr as $k=> $v){ 
									if(!isset($v->remain)){?>
							<option value=<?php echo $v->aid?> ><?php echo $v->name?></option>
						<?php }else if(isset($v->remain) && $v->remain!=0){ ?>
							<option value=<?php echo $v->aid?> ><?php echo $v->name?></option>
						<?php  } ?>
					
	
					
					<?php } ?>
				</select>
				
			</span>
		</div>
		
		<table style="border:3px #cccccc solid;width:100%" cellpadding="10" border='1'>
				<tr>
					<td style="padding:5px;text-align:center;;width:33.3%;text-align:center;background:#7FFFD4;">地區</td>
					<td style="padding:5px;text-align:center;;width:33.3%;text-align:center;background:#7FFFD4;">價錢</td>
					<td style="padding:5px;text-align:center;;width:33.3%;text-align:center;background:#7FFFD4;">空位</td>
						</tr>
						<?php foreach($orders_arr as $v){ ?>
						<tr>
							<?php 
								if(isset($v->remain)){
										if($v->remain==0){
											$remain = "客滿";
											$color="red";
										}else{
											$remain =  "尚餘" . $v->remain . "帳";
											$color="black";
										}
									}else{
										$remain =   "尚餘" . $v->available . "帳";
										$color="black";
									}
							?>
							<td style="padding:5px;text-align:left;" id="<?php echo $v->aid?>_name"><?php echo $v->name?></td>
							<td style="padding:5px;text-align:right;" id="<?php echo $v->aid?>_price" ><?php echo $v->price?></td>
							<td style="padding:5px;text-align:left;color:<?php echo $color?>">
								<?php echo $remain?>
							</td>
						</tr>
						<?php } ?>
						
					
		</table>
		<div id="AvalibleSite" style="margin-top:10px"></div>

