<?php

define('ACC',true);

header("Content-Type:text/html; charset=utf-8");
date_default_timezone_set('Asia/Taipei');
include('./Model/mysql.php');
include('./include/config.ini.php');
include('./include/init.inc.php');

$mysql = Mysql::get_instance();

$aid = isset($_GET['t'])?$_GET['t']:"";
if(!is_numeric($aid)){
	exit();
}

if($aid==0){
	echo "";
	exit();
}

$year = isset($_GET['yr'])?$_GET['yr']:date("Y");
if(!is_numeric($aid)){
	exit();
}

$month = isset($_GET['mon'])?$_GET['mon']:date('m')+0;
if(!is_numeric($aid)){
	exit();
}



//抓 orders 表
$table = "orders";

$sql  = "select day, area.aid, area.name, area.available, sum(booked) total_booked, area.available-sum(booked)";
$sql .= " from " . $table ;
$sql .= " inner join area";
$sql .= " on area.aid=" . $table . ".aid";
$sql .= " where month=" . $month . "   and status = 1 and is_delete=0";
$sql .= " and area.aid=:aid";
$sql .= " group by day";
$rs = $mysql->prepare($sql);

$rs->bindParam(':aid', $aid, PDO::PARAM_INT);
$rs->execute();
$datas_from_orders=$rs->fetchAll(PDO::FETCH_CLASS);

//抓guestinfo 表
$table = "guestinfo";

$sql  = "select day, area.aid, area.name, area.available, sum(booked) total_booked, area.available-sum(booked)";
$sql .= " from " . $table ;
$sql .= " inner join area";
$sql .= " on area.aid=" . $table . ".aid";
$sql .= " where month=" . $month . "   and status = 1 and is_delete=0";
$sql .= " and area.aid=:aid";
$sql .= " group by day";


$rs = $mysql->prepare($sql);

$rs->bindParam(':aid', $aid, PDO::PARAM_INT);
$rs->execute();
$datas_from_guestinfo=$rs->fetchAll(PDO::FETCH_CLASS);

$sql = " select available from area where aid=:aid" ;
$rs = $mysql->prepare($sql);

$rs->bindParam(':aid', $aid, PDO::PARAM_INT);
$rs->execute();
$available_of_this_aid=$rs->fetchAll(PDO::FETCH_CLASS)[0]->available;

//月份天數
$days_in_this_month  = cal_days_in_month(CAL_GREGORIAN,$month,$year);

//生成本月陣列備用，我先塞入個營位最大可容納數量，晚點塞入訂位數量
$calender_of_this_month = array();

for($i=1;$i<=$days_in_this_month;$i++){
	
	$calender_of_this_month[$i] = $available_of_this_aid;
}

//先用前端訂單比較日期後更新剩餘數量
foreach($datas_from_guestinfo as $v){
	$calender_of_this_month[$v->day] -= $v->total_booked;
}

//再比後端訂單
foreach($datas_from_orders as $v){
	$calender_of_this_month[$v->day] -= $v->total_booked;
}


$weekday = date("w",mktime(0,0,0,$month,1,$year));

$calender_of_thie_month_restructed = array();

$index=0;

foreach($calender_of_this_month as $k=>$v){

	$calender_of_thie_month_restructed[$index]['day'] = $k;
	$calender_of_thie_month_restructed[$index]['remains'] = $v;
	$calender_of_thie_month_restructed[$index]['color'] = "black";
	$index++;
}

// echo "<pre>";
// print_r($calender_of_thie_month_restructed);

// exit();

echo "\"( )\"內為剩餘數量";



for($j=0;$j<$weekday;$j++){
	$value = array();
	$last_month = ($month==1)?12:$month-1;
	$value['day'] = cal_days_in_month(CAL_GREGORIAN,$last_month,$year) -$j;
	$value['color'] = "#B0C4DE";
	$value['another_month'] = "true";
	array_unshift($calender_of_thie_month_restructed,$value);
	
}

$this_month_mod_7= count($calender_of_thie_month_restructed)%7;

$this_mohth_need_to_add = 7-$this_month_mod_7;

for($i=1;$i<=$this_mohth_need_to_add;$i++){
	$value = array();
	$value['day'] = $i;
	$value['color'] = "#B0C4DE";
	$value['another_month'] = "true";
	array_push($calender_of_thie_month_restructed,$value);
}


foreach($calender_of_thie_month_restructed as $k=>$v){
	if(isset($v['another_month'])){
		continue;
	}
	
	if($k%7==0 || $k%7==6){
		$calender_of_thie_month_restructed[$k]['color']="red";
	}
}


?>
<style>
	.get_remains_site_table{
		border:solid 1px;
		width:90%;
		border-color:black;
	}
	.get_remains_site_table th{
		border:solid 1px;
		text-align:center;
		padding:5px;
		width:14.3%;
		border-color:black;
	}
	
	.get_remains_site_table td{
		border:solid 1px;
		text-align:center;
		width:14.3%;	
		padding:5px;
		border-color:black;
	}
	
	.next_and_prev_month{
		width:80%;
	}
	.next_and_prev_month th{
		
		text-align:center;
		padding:5px;
		
		
	}
</style>
<center>
<table class="next_and_prev_month">
	<tr>
		<th style="width:25%">
			<?php if($month <= (date("m")) && $year==date("Y")){ ?>
						_
			<?php }else{ ?>
						<input type="button" value="<" onclick="get_remains_site_prev()">
			<?php } ?>
			
		</th>
		<th style="width:50%">
			<span id="get_remains_site_table_year"><?php echo $year?></span>年
			<span id="get_remains_site_table_month"><?php echo $month?></span>月
		</th>
		<th style="width:25%"><input type="button" value=">" onclick="get_remains_site_next()"></th>
	</tr>
</table>
<?php /* ?>

<table class="get_remains_site_table">
	<tr>
		<th>日期</th>
		<th>營位數量</th>
		<th>剩餘數量</th>
	</tr>
	<?php foreach($calender_of_this_month as $k=>$v){ ?>
	<tr>
		<td><?php echo $month . "/" . $k ?></td>
		<td><?php echo $available_of_this_aid ?></td>
		<td><?php echo ($v<=0)? "<span style=\"color:red\">本日已滿</span>":$v?></td>
	</tr>
	<?php } ?>
</table>
<?php*/ ?>

<table class="get_remains_site_table">
	<tr>
		<th>日</th>
		<th>一</th>
		<th>二</th>
		<th>三</th>
		<th>四</th>
		<th>五</th>
		<th>六</th>
	</tr>
	<?php foreach($calender_of_thie_month_restructed as $k=>$v){
			if($k%7 == 0 ){
				echo "<tr>";
			}
			echo "<td><font color=\"" . $v['color'] . "\">" . $v['day'] ;
			if(isset($v['remains'])){
				if($v['remains'] ==0){
					$v['remains']="滿";
				}
			}
			echo isset($v['remains'])?"<br/>(" . $v['remains']. ")":"";
			echo "</td>";
			
			if($k%7 == 6 ){
				echo "</tr>";
			}
	}?>
		
	
</table>
</center>





















