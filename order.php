<?php


define('ACC',true);

include('./include/config.ini.php');
include('./include/init.inc.php');
header("Content-Type:text/html; charset=utf-8");
date_default_timezone_set('Asia/Taipei');

$first_link_title = "回首頁";
$first_link = "index.php";
$second_link_title = "行車動線";
$second_link = "map.php";
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
		
		$calender[$i]['day']=$i;
		if(array_key_exists($i,$this_month_holiday)){
			if (empty($this_month_holiday[$i]->name)){
				if(($this_month_holiday[$i]->holidayCategory)=="星期六、星期日"){
					$calender[$i]['color'] ="red";
					// $calender[$i]['holidayCategory']="星期六、星期日";
				}else{
					if(($this_month_holiday[$i]->isHoliday)=="是"){
						$calender[$i]['color'] ="red";
						$calender[$i]['holidayCategory']=$this_month_holiday[$i]->holidayCategory;
					}else{
						$calender[$i]['color'] ="black";
						$calender[$i]['holidayCategory']=$this_month_holiday[$i]->holidayCategory;
					}
				}
			}else{
				$calender[$i]['color'] ="red";
				$calender[$i]['holidayCategory']=$this_month_holiday[$i]->name;
			}
		}else{
			$calender[$i]['color'] ="black";
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


for($j=1;$j<=$weekday;$j++){
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


// print_r($calender);
// exit();
include("./view/calender.html");
 include("./view/order.html");
include("./view/footer.html");
?>