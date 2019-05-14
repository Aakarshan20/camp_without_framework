<?php


define('ACC',true);
include('./Model/mysql.php');
include('./include/config.ini.php');
include('./include/init.inc.php');
header("Content-Type:text/html; charset=utf-8");
date_default_timezone_set('Asia/Taipei');


$first_link_title = "回首頁";
$first_link = "index.php";
$second_link_title = "行車動線";
$second_link = "map.php";

$mysql = mysql::get_instance();
$month = date("m");
// $month=7;
$year = date("Y");
$sql  = "select day,sum(sum) as sum from";
$sql .= "(select day,sum(booked) as sum from orders where year=:year and month=:month and status=1 group by day   ";
// $sql .= "(select day,sum(booked) as sum from orders where year=2017 and month=6 and status=1 group by day   ";
$sql .=" union all ";
$sql .= "select day,sum(booked) as sum from guestinfo where year=:year and month=:month and status=1 group by day)    as tmp group by day";
// $sql .= "select day,sum(booked) as sum from guestinfo where year=2017 and month=6 and status=1 group by day )   as tmp group by day";
// echo $sql;



//select day,sum(sum) from ( select day,sum(booked) as sum from orders 
// where year=2017 and month=6 and status=1 group by day union all select day,sum(booked) as sum from 
// guestinfo where year=2017 and month=6 and status=1 group by day )as tmp group by day

$rs= $mysql->prepare($sql);
$rs->bindParam(':year', $year, PDO::PARAM_INT);
$rs->bindParam(':month', $month, PDO::PARAM_INT);
$rs->execute();
$this_month_order = $rs->fetchAll(PDO::FETCH_CLASS);

$sql = "select * from area order by aid";
$rs= $mysql->query($sql);
$area = $rs->fetchAll(PDO::FETCH_CLASS);

// print_r($this_month_order);
// exit();
$sql = "select sum(available) as sum,count(1) as camp_counts from area";
$camp_counts = $mysql->query($sql)->fetchAll(PDO::FETCH_CLASS)[0]->camp_counts;


$total_avalible = $mysql->query($sql)->fetchAll(PDO::FETCH_CLASS)[0]->sum;
$day_of_month = cal_days_in_month(CAL_GREGORIAN,$month,$year) ;

$sql = "select aid,name,available,price from area order by aid";

$areas = $mysql->query($sql)->fetchAll();

$full_days_of_this_month = array();



// echo '<pre>';

foreach($this_month_order as $v){
	if($v->sum==$total_avalible){
		array_push($full_days_of_this_month,$v);
	}
}
	



$myfile = fopen("calender.txt", "r") or die("Unable to open file!");
$file_json =  fread($myfile,filesize("calender.txt"));


$holiday_calender = json_decode($file_json)->result->records;

$this_month_holiday = array();


$year = date("Y");
$month = date("m");
// $month=10;




foreach($holiday_calender as $v){
	if((substr($v->date,0,4) == $year) && (substr($v->date,5,(strrpos($v->date,'/')-5))==$month)){
		$this_month_holiday[substr($v->date,(strrpos($v->date,'/')+1))] = $v;
	}
}


fclose($myfile);

$day = ($month==date("m"))?date("d")+0:1;

$day_of_month = cal_days_in_month(CAL_GREGORIAN,$month,$year);  
$calender = array();
for($i=$day;$i<=$day_of_month;$i++){
		

		$calender[$i-1]['day']=$i;
		if(array_key_exists($i,$this_month_holiday)){
			if (empty($this_month_holiday[$i]->name)){
				if(($this_month_holiday[$i]->holidayCategory)=="星期六、星期日"){
					$calender[$i-1]['color'] ="red";
					// $calender[$i]['holidayCategory']="星期六、星期日";
				}else{
					if(($this_month_holiday[$i]->isHoliday)=="是"){
						$calender[$i-1]['color'] ="red";
						$calender[$i-1]['holidayCategory']=$this_month_holiday[$i]->holidayCategory;
					}else{
						$calender[$i-1]['color'] ="black";
						$calender[$i-1]['holidayCategory']=$this_month_holiday[$i]->holidayCategory;
					}
				}
			}else{
				$calender[$i-1]['color'] ="red";
				$calender[$i-1]['holidayCategory']=$this_month_holiday[$i]->name;
			}
		}else{
			$calender[$i-1]['color'] ="black";
		}
}


$weekday = date("w",mktime(0,0,0,$month,1,$year));


if($day!=1){
	for($i=$day-1;$i>0;$i--){
		
		$value = array();
		$value['day'] = $i;
		$value['color'] = "#B0C4DE";
		array_unshift($calender,$value);
	}
	
}




foreach($full_days_of_this_month as $v){
	$calender[($v->day)-1]['isFull'] = '<font color="red">(滿)</font>';
}


for($j=0;$j<$weekday;$j++){
>>>>>>> update
=======
for($j=1;$j<=$weekday;$j++){
>>>>>>> 2ef6ed96ca8bf393507b6e5cd939354f456564fc
	$value = array();
	$value['day'] = cal_days_in_month(CAL_GREGORIAN,($month-1),$year) -$j;
	$value['color'] = "#B0C4DE";
	$value['another_month'] = "true";
	array_unshift($calender,$value);
}



$num_of_calender_array_before = count($calender);

if($num_of_calender_array_before%7!=0){
	for($i=1;$i<=(7-($num_of_calender_array_before%7));$i++){
		
		$value = array();
		$value['day'] = $i;
		$value['color'] = "#B0C4DE";
		$value['another_month'] = "true";
		array_push($calender,$value);
		
	}	
}



//include("./view/calender.html");
 include("./view/order.html");
include("./view/footer.html");


include("./view/calender.html");
 include("./view/order.html");
include("./view/footer.html");

?>