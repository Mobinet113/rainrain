<?php 
require('sources/cl_weather.php');
$obj = new weather;
if(isset($_GET['loc'])){
	$obj->locID = $_GET['loc'];
} else {
	$obj->locID = 3772;
}
?>
<html>
<head>
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
<link rel="stylesheet" type="text/css" href="css/style.css">

<script>
$(document).ready(function(){
	$('.vBlock').mouseover(function(){
		$(this).stop();
		$(this).animate({
			top: "-20px",
			opacity: 0.9,
		}, 300, function(){
			$(this).animate({
				top: "0px",
				opacity: 1,
			}, 1000);
		});		
		
	});
});
</script>
</head>
<body>
<?php
weather::nightTime(21);


try{
	$obj->printDat();
}
catch(Exception $e){
	echo '<div id="error">Sorry, we could not find any information on this location</div>';
}
?>
<div id="locSelect">
	<form action="index.php" method="get">
		<select name="loc">
			<?php
				foreach($obj->locations()->Location as $k => $v){
					echo '<option value="'.$v['id'].'">'.$v['name'].'</option>';
				}
			?>
		</select>
		<input type="submit" value="Go!">
	</form>
</div>
</body>
</html>