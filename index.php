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

$i = 0;
foreach($obj->getDat()->DV->Location->Period as $k => $v){
	$i++;
echo '
	<div class="vBlock" id="col'.$i.'">
		<div class="wrapper">';
echo '
			<h3>'.rtrim($v['value'], "Z").'</h3>';
			$obj->drawImg($v);
			
			foreach($v->Rep as $k2 => $v2){
				if($v2 != 'Night'){			
echo '
					<span class="nums">&#9651; '.$v2['Dm'].'</span><img src="media/ico/weather/c.png" ><br />
					<span class="nums">&#9661; '.$v2['FDm'].'</span><img src="media/ico/weather/c.png" ><br />';
				}
			}
echo '
		</div>
	</div>';
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