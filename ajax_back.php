<?php 

define('ACC',true);
header("Content-Type:text/html; charset=utf-8");
date_default_timezone_set('Asia/Taipei');
include('./Model/mysql.php');
include('./include/config.ini.php');
include('./include/init.inc.php');
$mysql = mysql::get_instance();
$year = date("Y");
$month = $_GET['m'];
//

$sql = "select day,sum(booked) as sum from orders where year=:year and month=:month group by day  order by day ";
$rs= $mysql->prepare($sql);
$rs->bindParam(':year', $year, PDO::PARAM_INT);
$rs->bindParam(':month', $month, PDO::PARAM_INT);
$rs->execute();
$this_month_order = $rs->fetchAll(PDO::FETCH_CLASS);

$sql = "select sum(available) as sum from area";
$total_avalible = $mysql->query($sql)->fetchAll(PDO::FETCH_CLASS)[0]->sum;
$day_of_month = cal_days_in_month(CAL_GREGORIAN,$month,$year) ;

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

$day = ($month==date("m"))?date("d")+0:1;

// echo "day=" . $day;

$this_month_holiday = array();

foreach($holiday_calender as $v){
	if((substr($v->date,0,4) == $year) && (substr($v->date,5,(strrpos($v->date,'/')-5))==$month)){
		$this_month_holiday[substr($v->date,(strrpos($v->date,'/')+1))] = $v;
	}
}

// print_r($this_month_holiday);
fclose($myfile);

$day_of_month = cal_days_in_month(CAL_GREGORIAN,$month,$year);  
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
if($day!=1){
	for($i=$day-1;$i>0;$i--){
		
		$value = array();
		$value['day'] = $i;
		$value['color'] = "#B0C4DE";
		array_unshift($calender,$value);
	}
	
}







$weekday = date("w",mktime(0,0,0,$month,1,$year));

foreach($full_days_of_this_month as $v){
	$calender[($v->day)-1]['isFull'] = '<font color="red">(滿)</font>';
}

for($i=0;$i<$weekday;$i++){
	$value = array();
	$value['day'] = cal_days_in_month(CAL_GREGORIAN,($month-1),$year) -$i;
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
?>

<table style="border:3px #cccccc solid;width:100%" cellpadding="10" border='1'>
						
	<tr>
		<td style="padding:5px;text-align:center;;width:14.28%;text-align:center;background:#7FFFD4;">
		<?php if($month != date("m")){ ?>
			<input id="next" name="<?php echo ($month -1)?>" type="button" value="<" onclick="getValue(this)">
		<?php }else{ ?>
			_
		<?php } ?>
		</td>
		<td colspan=5 style="padding:5px;text-align:center;;width:71.42%;text-align:center;">
			<?php echo $year . "年 " . ($month+0) . "月"?>
		</td>
		<td style="padding:5px;text-align:center;;width:14.28%;text-align:center;background:#7FFFD4;">
		<?php if($month < 12 ){ ?>
			<input id="next" name="<?php echo ($month +1)?>" type="button" value=">" onclick="getValue(this)">
		<?php }else{ ?>
			_
		<?php } ?>
		</td>
	</tr>
	<tr>
		<td style="padding:5px;text-align:center;;width:14.28%;text-align:center;">日</td>
		<td style="padding:5px;text-align:center;;width:14.28%;text-align:center;">一</td>
		<td style="padding:5px;text-align:center;;width:14.28%;text-align:center;">二</td>
		<td style="padding:5px;text-align:center;;width:14.28%;text-align:center;">三</td>
		<td style="padding:5px;text-align:center;;width:14.28%;text-align:center;">四</td>
		<td style="padding:5px;text-align:center;;width:14.28%;text-align:center;">五</td>
		<td style="padding:5px;text-align:center;;width:14.28%;text-align:center;">六</td>
	</tr>
	<?php foreach($calender as $k=>$v){
			$first_remainder_for_tr = ($weekday==0)?1:0;
			$second_remainder_for_tr = ($weekday==0)?0:6;

			if($k%7 ==$first_remainder_for_tr){

				echo "<tr>";
			} 
			$id = (isset($v['day']))?$year . '/' . $month . '/' .  $v['day']:'';
			$id = (isset($v['another_month']))?"":$id;
	?>
		<td id="<?php 	echo $id;?>"style="padding:5px;text-align:center;;width:14.28%;text-align:center;
		color:		<?php 	echo $v['color']?>" onclick="insertArrive(this)"
		<?php echo isset($v['isFull'])?'class="full"':''?>
		>
						<?php	echo isset($v['day'])?$v['day']:'';
									echo isset($v['isFull'])?$v['isFull']:'';
									echo (isset($v['holidayCategory']))?"</br>" . $v['holidayCategory']:'';
			?>
		</td>
			<?php	if($k%7 ==6){

						echo "</tr>";
					}
			} ?>

</table>


