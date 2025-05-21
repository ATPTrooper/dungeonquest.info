<?

	$hero_map = $_REQUEST["hero_map"];
	
	echo $hero_map;
	echo count($hero_map);

?>
<html>
<head>

	<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
	<script type="text/javascript">


	var hero_map = <?=$hero_map?>;

	$(document).ready(function() {
		
		alert("hero_map[101].id: " + hero_map[101].id);
			
		// Close Map Modal
		$('#map_modal').on('hidden.bs.modal', function () {
			$('.mini_dice_placeholder').append($('.mini_dice_sheet'));
			$('.mini_dice_sheet').show();
			$('#main_screen').show();
		})
		
		$('#placeholder').append("<div class='print' style=\"visibility: visible; width: 200px; height: 200px; background-image: url('/dungeonquest/images/second_edition_friendlybombs/empty/24.jpg'\"></div>");
	})
		
	function show_map() {
		$('#map_modal').modal("show");
		$('.mini_dice_sheet').hide();
	}
	function print_board(){
		$('#main_screen').hide();
		window.print();
		return false;
	}
</script>
</head>
<body>

	<div id="placeholder" class="print" style="visibility: visible;"></div>
	<img src="/dungeonquest/images/second_edition_friendlybombs/empty/24.jpg"/>
	<div style="background-image: url('/dungeonquest/images/second_edition_friendlybombs/empty/24.jpg')">dd</div>
	<div id="main_screen">
		<input class="btn btn-primary map_button" type="button" name="map_button" id="map_button" value="Map" onclick="show_map();" />
	</div>



</body>
</html>