<html>
<body>


<?php
echo "Plogg interview PHP test.<br>";

//Get the number of weekdays in range
// input start_day and end_day as date strings
// returns 0 if no weekdays exist 
function getNumWeekdays($start_day, $end_day)
{
	//use time stamp
	$start_day_ts = strtotime($start_day);
	$end_day_ts = strtotime($end_day);
	$num_weekdays = 0;
	while ($start_day_ts <= $end_day_ts ) {
		if (date("N", $start_day_ts) < 6){
			++$num_weekdays; 
		}
		$start_day_ts += 86400;//add one day of seconds
	}
	return $num_weekdays;
}

//Create an array of random numbers that is num weekdays long
//make sure we have normalized the array
//returns a normalized array
function getArrayRandNumbers($num_week_days){
	$total = 0;
	$rand_num = 0;
	$weekday_array = array();
	$norm_array = array();		
	for ($i= 0 ; $i < $num_week_days ; $i++) {
		//generate a random number with 3 significant figures.  
		//If we have a long interval of weekdays, we may need higher precision here.
		$rand_num = mt_rand(1,1000);
		//add it to the total
		$total += $rand_num;
		//put the random number into an array
		array_push($weekday_array , $rand_num);
	}
	//Normalize the array.  (make the sum of elements equal to one)
	//keep three digits for acuracy
	$total = floatval($total);
	foreach ( $weekday_array as $wd ) {	
		array_push( $norm_array, floatval($wd) / $total);		
	}
	return $norm_array;
}

//main function: Take the start and end date, fill an array with the values.
function dist_amount_randomly($amount, $baseline, $start_date, $end_date){
	//use time stamp
	$start_day_ts = strtotime($start_date);
	$end_day_ts = strtotime($end_date);
	//calculate num of weekdays
	$num_weekdays = getNumWeekdays($start_date, $end_date);
	//a unidimentional normalized array of size $num_weekdays
	$norm_array = getArrayRandNumbers($num_weekdays);
	$index_of_norm_array = 0;
	$output_array = array();
	//calculate the baseline number
	//min number in given weekday
	$min_in_days = floatval($amount)*floatval($baseline)/(floatval($num_weekdays)*100.0);
	$amount_to_be_dist = floatval(100-$baseline) *floatval($amount)/100.0;
	//fill the output array
	while ($start_day_ts <= $end_day_ts ) {		
		if (date("N", $start_day_ts) < 6){
			//make a non-zero array element
			$amount_for_weekday = $norm_array[$index_of_norm_array]*$amount_to_be_dist+$min_in_days;
			//convert to a 2 decimal number
			$formatted_amount = number_format($amount_for_weekday, 2,'.','');
			//increment the index of the norm array	
 			$index_of_norm_array++;
		} else {
			//make a zero array element
			$formatted_amount = "0.00";	
		}
		//conver start_day_ts to a string date
		$curr_date = date('Y-m-d',$start_day_ts);
		//add it to the end of the array.
		$output_array += [$curr_date => $formatted_amount];	
		//print out the values to check the math.				
		$start_day_ts += 86400;//add one day of seconds
	}
	return $output_array;
}
//Get the input from plogg_distribute.html
//print out the key value pairs of the array.
$final_output = dist_amount_randomly($_POST["total"], $_POST["baseline"], $_POST["start_date"], $_POST["end_date"]);
echo "The total to be distributed is: " . $_POST["total"]. "<br>";
echo "The number of workdays in the chosen interval is: " .$weekdays =getNumWeekdays( $_POST["start_date"], $_POST["end_date"]) . "<br>";
$min_amount_per_weekday = number_format(floatval($_POST["total"])*floatval($_POST["baseline"])/floatval($weekdays*100), 2,'.','');
echo "The minimum amount in each weekday is: ".$min_amount_per_weekday . "<br>";
$sum = 0;
foreach ($final_output  as $key => $val){
	echo $key."=>".$val. "<br>";
	$sum += $val;
}
echo "The sum of all the values is: ".$sum;
?> 

</body>
</html>




