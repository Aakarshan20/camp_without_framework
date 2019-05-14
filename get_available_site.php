<?php
define('ACC',true);

header("Content-Type:text/html; charset=utf-8");
date_default_timezone_set('Asia/Taipei');
include('./Model/mysql.php');
include('./include/config.ini.php');
include('./include/init.inc.php');
$mysql = Mysql::get_instance();
$_GET['ad'] = explode('/',$_GET['ad']);

$month = $_GET['ad'][1];
$day = $_GET['ad'][2];
$year = $_GET['ad'][0];
$stay_day = $_GET['sd'];
$aid = $_GET['aid'];

if($aid==0){
	echo "";
	exit();
}

$res_date = date("Y/m/d",mktime(0,0,0,$month,$day+$stay_day,$year));

$res_date_arr = explode('/',$res_date);

$year_to_leave = $res_date_arr[0];
$month_to_leave = $res_date_arr[1];
$day_to_leave = $res_date_arr[2];


$cross_month = false;
$cross_year = false;

if($month_to_leave!=$month){
	$cross_month=true;
}

if($year_to_leave!=$year){
	$cross_year=true;
}

if($cross_year){
	
	
	$sql = "select year,month,day,name,(available-sum(booked)) as remain from ";
	$sql .="( select year,month,day,name,booked,available from orders ";
	$sql .=" left join area on area.aid=orders.aid ";
	$sql .=" where year=:year and month=:month and day between :day and  31 and orders.aid=:aid and status=1 ";
	$sql .=" union all ";
	$sql .=" select year,month,day,name,booked,available from guestinfo ";
	$sql .=" left join area on area.aid=guestinfo.aid ";
	$sql .=" where year=:year and month=:month and day between :day and 31 and guestinfo.aid=:aid and status=1 ";
	$sql .="  ) as tmp group by day";
	
	
	// echo "cross_year<br/>";
	// echo $sql;
	// exit();
	$rs=$mysql->prepare($sql);
	$rs->bindParam(':year', $year, PDO::PARAM_INT);
	$rs->bindParam(':month', $month, PDO::PARAM_INT);
	$rs->bindParam(':day', $day, PDO::PARAM_INT);
	
	$rs->bindParam(':aid', $aid, PDO::PARAM_INT);
	$rs->execute();
	$res_date_arr_1 = $rs->fetchAll(PDO::FETCH_CLASS);
	
	$sql = "select year,month,day,name,(available-sum(booked)) as remain from ";
	$sql .="( select year,month,day,name,booked,available from orders ";
	$sql .=" left join area on area.aid=orders.aid ";
	$sql .=" where year=:year and month=:month_to_leave and day between 1 and  :day_to_leave and orders.aid=:aid and status=1 ";
	$sql .=" union all ";
	$sql .=" select year,month,day,name,booked,available from guestinfo ";
	$sql .=" left join area on area.aid=guestinfo.aid ";
	$sql .=" where year=:year and month=:month_to_leave and day between 1 and :day_to_leave and guestinfo.aid=:aid and status=1 ";
	$sql .="  ) as tmp group by day";
	
	/*
	$sql = "select year,month,day,name,available-booked  as remain from orders ";
	$sql.="left join area on area.aid=orders.aid where year=:year and month=:month_to_leave and day between 1 and :day_to_leave and orders.aid=:aid";
	$sql.=" and status=1 order by year ,month,day";*/
	$rs=$mysql->prepare($sql);
	$rs->bindParam(':year', $year_to_leave, PDO::PARAM_INT);
	$rs->bindParam(':month_to_leave', $month_to_leave, PDO::PARAM_INT);
	// $rs->bindParam(':day', $day, PDO::PARAM_INT);
	$rs->bindParam(':day_to_leave', $day_to_leave, PDO::PARAM_INT);
	$rs->bindParam(':aid', $aid, PDO::PARAM_INT);
	$rs->execute();
	$res_date_arr_2 = $rs->fetchAll(PDO::FETCH_CLASS);
	$res_date_arr = array();
	$res_date_arr = array_merge($res_date_arr_1, $res_date_arr_2);
	
}else if($cross_month){
	$sql = "select year,month,day,name,(available-sum(booked)) as remain from ";
	$sql .="( select year,month,day,name,booked,available from orders ";
	$sql .=" left join area on area.aid=orders.aid ";
	$sql .=" where year=:year and month=:month and day between :day and  31 and orders.aid=:aid and status=1 ";
	$sql .=" union all ";
	$sql .=" select year,month,day,name,booked,available from guestinfo ";
	$sql .=" left join area on area.aid=guestinfo.aid ";
	$sql .=" where year=:year and month=:month and day between :day and 31 and guestinfo.aid=:aid and status=1 ";
	$sql .="  ) as tmp group by day";
	
	$rs=$mysql->prepare($sql);
	$rs->bindParam(':year', $year, PDO::PARAM_INT);
	$rs->bindParam(':month', $month, PDO::PARAM_INT);
	$rs->bindParam(':day', $day, PDO::PARAM_INT);
	
	$rs->bindParam(':aid', $aid, PDO::PARAM_INT);
	$rs->execute();
	$res_date_arr_1 = $rs->fetchAll(PDO::FETCH_CLASS);
	
	$sql = "select year,month,day,name,(available-sum(booked)) as remain from ";
	$sql .="( select year,month,day,name,booked,available from orders ";
	$sql .=" left join area on area.aid=orders.aid ";
	$sql .=" where year=:year and month=:month_to_leave and day between 1 and  :day_to_leave and orders.aid=:aid and status=1 ";
	$sql .=" union all ";
	$sql .=" select year,month,day,name,booked,available from guestinfo ";
	$sql .=" left join area on area.aid=guestinfo.aid ";
	$sql .=" where year=:year and month=:month_to_leave and day between 1 and :day_to_leave and guestinfo.aid=:aid and status=1 ";
	$sql .="  ) as tmp group by day";
	
	
	$rs=$mysql->prepare($sql);
	$rs->bindParam(':year', $year, PDO::PARAM_INT);
	$rs->bindParam(':month_to_leave', $month_to_leave, PDO::PARAM_INT);
	
	$rs->bindParam(':day_to_leave', $day_to_leave, PDO::PARAM_INT);
	$rs->bindParam(':aid', $aid, PDO::PARAM_INT);
	$rs->execute();
	$res_date_arr_2 = $rs->fetchAll(PDO::FETCH_CLASS);
	$res_date_arr = array();
	$res_date_arr = array_merge($res_date_arr_1, $res_date_arr_2);
	
}else{

	$sql = "select year,month,day,name,(available-sum(booked)) as remain from ";
	$sql .="( select year,month,day,name,booked,available from orders ";
	$sql .=" left join area on area.aid=orders.aid ";
	$sql .=" where year=:year and month=:month and day between :day and :day_to_leave and orders.aid=:aid and status=1 ";
	$sql .=" union all ";
	$sql .=" select year,month,day,name,booked,available from guestinfo ";
	$sql .=" left join area on area.aid=guestinfo.aid ";
	$sql .=" where year=:year and month=:month and day between :day and :day_to_leave and guestinfo.aid=:aid and status=1 ";
	$sql .="  ) as tmp group by day";

	
	$rs=$mysql->prepare($sql);
	$rs->bindParam(':year', $year, PDO::PARAM_INT);
	$rs->bindParam(':month', $month, PDO::PARAM_INT);
	$rs->bindParam(':day', $day, PDO::PARAM_INT);
	$rs->bindParam(':day_to_leave', $day_to_leave, PDO::PARAM_INT);
	$rs->bindParam(':aid', $aid, PDO::PARAM_INT);
	$rs->execute();
	$res_date_arr = $rs->fetchAll(PDO::FETCH_CLASS);
	
}

$sql = "select * from area where aid=:aid";
$rs=$mysql->prepare($sql);
$rs->bindParam(':aid', $aid, PDO::PARAM_INT);
$rs->execute();
$this_area = $rs->fetchAll(PDO::FETCH_CLASS)[0];


$max_available = $this_area->available;

$res_date_arr_to_print = array();
$full_days_arr = array();
foreach($res_date_arr as $v){
	
	$res_date_arr_to_print[mktime(0,0,0,$v->month,$v->day,$v->year)] = $v->remain;
	if($v->remain < $max_available){
		$max_available = $v->remain;
		if($v->remain ==0){
			$date = $v->year . "/" . $v->month . "/" . $v->day;
			array_push($full_days_arr,$date);
		}
	}
}



?>

<table style="border:3px #cccccc solid;width:100%" cellpadding="10" border='1'>
	<tr>
		<td style="padding:5px;text-align:center;;width:33.3%;text-align:center;background:#7FFFD4;">日期</td>
		<td style="padding:5px;text-align:center;;width:33.3%;text-align:center;background:#7FFFD4;">空位</td>
		
	</tr>
		<?php for($i=0;$i<$stay_day;$i++){ ?>
		<tr>
			<?php $timestamp = mktime(0,0,0,$month,$day+$i,$year);?>
			<td style="padding:5px;text-align:center;"><?php echo  $date = date("Y/m/d",$timestamp)	?></td>
			<td style="padding:5px;text-align:center;">
				<?php if(empty($res_date_arr)){  
									echo $this_area->available; 
								}else if(array_key_exists ( $timestamp ,  $res_date_arr_to_print )){
									echo ($res_date_arr_to_print[$timestamp]==0)?"<font color=\"red\">0 (已滿)</font>": $res_date_arr_to_print[$timestamp];
								}else{
									echo $this_area->available; 
								}?>
				
			
			</td>
			
		</tr>
		<?php } ?>
</table>
<div id="availableMsg" style="margin:10px">
	<?php echo ($max_available==0)?"<font color=\"red\">所選日期中，" . $full_days_arr[0] . "已無空位；請重新選擇日期或重新選擇露營區</font>":'';?>
	
</div>
<?php if($max_available>0){ ?>

	<div style="margin:10px" >請選擇帳數:&nbsp;&nbsp;
		<select id="seatSelector<?php echo $aid?>" name = "<?php echo $aid?>" onchange="createOrder(this)">
			<option value=0 selected>請選擇帳數</option>
			<?php for($i=1;$i<=$max_available;$i++){?>
			<option value=<?php echo $i?>><?php echo $i?></option>
			<?php } ?>
		</select>
	</div>
	<div id="showOrder">
	</div>

<?php }?>