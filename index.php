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
<meta name="viewport" content="width=device-width, initial-scale=1" />
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
	$('#subLoc').click(function(){
		$('.vBlock').stop();
		var locID = $('#selLoc').val();
		var winY  = $('html').height();
		var moveY = 600;
		for(var i = 0;i <= 3; i++){
			$('.vBlock:eq('+i+')').animate({top: -winY+'px'}, moveY+i*100);
		}
		
		$('.vBlock:eq(4)').animate({top: -winY+'px'}, moveY+400, function(){
			$.ajax({
				type: "POST",
				url: "sources/view.php",
				data: { locID: locID, height: winY}
			})
			.done(function( msg ) {
				$('#weather_cols').html(msg)
				$('.vBlock').animate({top: '0px'}, 900);
		
			});
		});
		moveY = 0;
	});
});
</script>
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
		<select id="selLoc" name="loc">
			<?php
				$obj->locations();
			?>
		</select>
		<a id="subLoc">Go!</a>
	</form>
</div>
</body>
</html>