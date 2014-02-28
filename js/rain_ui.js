$(document).ready(function(){
	$('form').each(function(){this.reset()});
	$('#error').remove();
	
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
				if(msg != '<div id="error">Sorry, we could not find any information on this location</div>'){
					$('#weather_cols').html(msg)
					$('.vBlock').animate({top: '0px'}, 900);
				}
			});
		});
		moveY = 0;
	});
	//When the user selects an area, update the list of locations from the server//
	$('select[name=area]').on('change', function(e){
		var area = $('#selArea').find(":selected").text();
		$.ajax({
			type: "POST",
			url: "sources/cl_weather.php",
			data: { AjaxAction: 'getLocs', data: area}
		})
		.done(function( msg ) {
			if(msg.length != 0){
				$('#selLoc').html(msg);
			} else {
				$('#selLoc').html('<option>Select Location</option>');
			}
		});
	});
});