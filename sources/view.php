<?php
setcookie("location", $_POST['locID'], strtotime( '+320 days' ), '/');
require('cl_weather.php');

$height = $_POST['height'];
$obj = new weather;

$obj->locID = $_POST['locID'];
try{
	$obj->printDat($height);
}
catch(Exception $e){
	echo '<div id="error">Sorry, we could not find any information on this location</div>';
}
?>