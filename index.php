<?php
require('sources/cl_weather.php');
$obj = new weather;
if(isset($_COOKIE['location'])){
	$obj->locID = $_COOKIE['location'];
} else {
	$obj->locID = 3772;
}
weather::nightTime(21);
?>
<html>
<head>
<meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no" />
<script src="js/jquery.min.js"></script>
<script src="js/rain_ui.js"></script>
<link rel="stylesheet" type="text/css" href="css/style.css">

</head>
<body>
<div id="weather_cols">
<?php
try{
	$obj->printDat();
}
catch(Exception $e){
	echo '<div id="error">Sorry, we could not find any information on this location</div>';
}
?>
</div>
<div id="locSelect">
	<form>
		
		<select  id="selArea" name="area" data-mini="true">
			<option class="sel_def" selected>Select Area</option>
			<?php $obj->areas(); ?>
		</select>
			<br />
		<select id="selLoc" name="loc">
			<option>Select Location</option>
		</select>
		
		<a id="subLoc">Go!</a>
	</form>
</div>
</body>
</html>