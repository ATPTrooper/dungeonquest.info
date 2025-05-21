<html>
<head>
	<meta http-equiv="content-type" content="text/html; charset=UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1.0, user-scalable=no">
	<link type="text/css" rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/themes/smoothness/jquery-ui.css">
	<link type="text/css" rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
	<link type="text/css" rel="stylesheet" href="/dungeonquest/dice.css">
	<link type="text/css" rel="stylesheet" href="/dungeonquest/dice_2.css">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
	<script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
	<script type="text/javascript" src="/dungeonquest/dungeonquest.js"></script>
	<script type="text/javascript" src="/dungeonquest/dungeonquest_classes.js"></script>
	<script type="text/javascript">
		
		
		var debug = false;
		var hero1;
		
		var hero_map = {};
		var empty_map_orientation = {};
		var hero_loot = [];
		var encounter_queue = [];
		
		var boardgame_width = 13;
		var boardgame_height = 10;
		
		var mini_board_width = 5;
		var mini_board_height = 4;
		
		var medium_board_width = 7;
		var medium_board_width = 7;
		
		var enable_mini_board = true;
		var enable_medium_board = true;
		
		var max_timer = 31;
		
		if (debug) { max_timer = 99; }
		
		var timer = max_timer;
		
		var game_over = false;
		var ferrox_dead = true;
		var trap_triggered = false;
		var trap_triggered_type = "";
		
		var previous_direction = "";
		var current_direction = "";
		
		var image_directory = "/dungeonquest/second_edition/";
		image_directory = "/dungeonquest/images/second_edition_friendlybombs/";
		
		$(document).ready(function() {
			
			$('#character_selection_screen').show();
			$('#game').hide();
			
			load_characters();
			create_game_board();
			
			// End of Battle
			$('#ferrox_modal').on('hidden.bs.modal', function () {
				$('.mini_dice_placeholder').append($('.mini_dice_sheet'));
			})
			
			// End of Trap
			$('#trap_modal').on('hidden.bs.modal', function () {
				//$('.mini_dice_placeholder').append($('.mini_dice_sheet'));

				$('.mini_dice_placeholder').attr("onclick", "").unbind("click");
				$(".mini_dice_placeholder").on('click', function(){ roll_dice(2); });
				$('.mini_hero_sheet_placeholder').append($('.mini_hero_sheet'));
				
				
				if (debug) {
				for (var i = 0; i < encounter_queue.length; i++) {
					write_to_console("encounter_queue["+i+"] " + encounter_queue[0].type + ", resolved: "+encounter_queue[0].resolved+", success: "+encounter_queue[0].success);
				}
				}
				
				if (encounter_queue[0] != undefined) {
					
					if (encounter_queue[0].type == "opendoor" || 
						encounter_queue[0].type == "jammeddoor" || 
						encounter_queue[0].type == "bridge" || 
						encounter_queue[0].type == "portcullis" || 
						encounter_queue[0].type == "pit" || 
						encounter_queue[0].type == "rubble" || 
						encounter_queue[0].type == "spiderweb" ||
						encounter_queue[0].type == "darkness") {
							
						trap_triggered = false;
						trap_triggered_type = "";
					}
					
					write_to_console('1 encounter_queue.length: '+encounter_queue.length);
					
					if (encounter_queue[0].resolved == '1' && encounter_queue[0].success == '1') {
						
						// resolved 1st encounter, remove 1st encounter
						if (encounter_queue.length > 1) {
							encounter_queue.splice(0, 1);
							force_trap_modal();
						} else {
							var temp_direction = encounter_queue[0].direction;
							force_hero_movement(temp_direction);
							encounter_queue = [];
							draw_hero_stats("hero1", hero1);
						}
						
					} else if (encounter_queue[0].resolved == '1' && encounter_queue[0].fail == '1') {
						
						// failed encounter, update timer and hero health
						encounter_queue = [];
						update_timer();
						draw_hero_stats("hero1", hero1);
						
					} else {
						
						// do nothing and remove all encounters
						encounter_queue = [];
					}
					
					write_to_console('2 encounter_queue.length: '+encounter_queue.length);
					
				} else {
					// alert("encounter not defined");
				}
				
				
				
			})
			
			// End of Trap
			$('#alert_modal').on('hidden.bs.modal', function () {
				$('.mini_dice_sheet').show();
			})
			
			// End of Trap
			$('#ending_modal').on('hidden.bs.modal', function () {
				$('.mini_dice_sheet').show();
			})
			
			
	
		
			var container = document.getElementById("mini_dungeon_board");

			container.addEventListener("touchstart", startTouch, false);
			container.addEventListener("touchmove", moveTouch, false);

			var container = document.getElementById("medium_dungeon_board");

			container.addEventListener("touchstart", startTouch, false);
			container.addEventListener("touchmove", moveTouch, false);
			// Swipe Up / Down / Left / Right
			var initialX = null;
			var initialY = null;

			function startTouch(e) {
				initialX = e.touches[0].clientX;
				initialY = e.touches[0].clientY;
			};

			function moveTouch(e) {
				if (initialX === null) {
					return;
				}

				if (initialY === null) {
					return;
				}

				var currentX = e.touches[0].clientX;
				var currentY = e.touches[0].clientY;

				var diffX = initialX - currentX;
				var diffY = initialY - currentY;

				if (Math.abs(diffX) > Math.abs(diffY)) {
					// sliding horizontally
					if (diffX > 0) {
						// swiped left
						move_hero('4');
					} else {
						// swiped right
						move_hero('2');
					}  
				} else {
					// sliding vertically
					if (diffY > 0) {
						// swiped up
						move_hero('1');
					} else {
						// swiped down
						move_hero('3');
					}  
				}

				initialX = null;
				initialY = null;

				e.preventDefault();
			};
		});
		
		document.onkeydown = function(e) {
			if (hero1 == undefined) { return; }
			switch(e.which) {
				case 38: // up
					if (!$('#alert_modal').hasClass('in') && !$('#ending_modal').hasClass('in'))
					move_hero('1');
					break;
				case 39: // right
					if (!$('#alert_modal').hasClass('in') && !$('#ending_modal').hasClass('in'))
					move_hero('2');
					break;
				case 40: // down
					if (!$('#alert_modal').hasClass('in') && !$('#ending_modal').hasClass('in'))
					move_hero('3');
					break;
				case 37: // left
					if (!$('#alert_modal').hasClass('in') && !$('#ending_modal').hasClass('in'))
					move_hero('4');
					break;
				case 13: // enter
					$('#alert_modal_close_button').click();
					// $('#alert_model').remove();
					// $(".modal-backdrop").remove();
					break;
				case 27: // escape
					$('#alert_modal_close_button').click();
					// $('#alert_model').remove();
					// $(".modal-backdrop").remove();
					break;
				default: return; // exit this handler for other keys
			}
			e.preventDefault(); // prevent the default action (scroll / move caret)
		};
		
		function load_characters(){
			for (var i = 0; i < CharactersJSON.length; i++){
				var $character_selection = $('<div class="character_selection" onclick="select_character(\''+CharactersJSON[i].name+'\')" />');
				var $character_name = $('<div><h4>'+CharactersJSON[i].name+'</h4></div>');
				var $character_table = $('<table class="table table-condensed table-bordered character_table" />');
				var $character_tbody = $('<tbody/>');
				$character_tbody.append('<tr><td rowspan="5" class="character_sheet_image">'+
					'<img class="character_image img img-responsive" src="/dungeonquest/characters/'+CharactersJSON[i].image_url_2+'" /></td>'+
					'<td class="character_sheet_stats">HP</td>'+
					'<td class="character_sheet_values">'+CharactersJSON[i].health+'</td></tr>');
				$character_tbody.append('<tr><td>Str</td><td>'+CharactersJSON[i].strength+'</td></tr>');
				$character_tbody.append('<tr><td>Agi</td><td>'+CharactersJSON[i].agility+'</td></tr>');
				$character_tbody.append('<tr><td>Def</td><td>'+CharactersJSON[i].defense+'</td></tr>');
				$character_tbody.append('<tr><td>Luk</td><td>'+CharactersJSON[i].luck+'</td></tr>');
				$character_tbody.append('<tr><td colspan="3" class="character_sheet_description"><i>'+CharactersJSON[i].description+'</i></td></tr>');
				$character_table.append($character_tbody);
				$character_selection.append($character_name);
				$character_selection.append($character_table);
				$('#character_selection_placeholder').append($character_selection);
			}
		}
		
		function select_character(p_Character){
			for (var i = 0; i < CharactersJSON.length; i++){
				if (p_Character == CharactersJSON[i].name) {
					hero1 = new Hero(CharactersJSON[i]);
					draw_hero_stats('hero1', hero1);
				}
			}
			if (hero1 != undefined) {
				start_game();
				$('#character_selection_screen').hide();
				$('#game').show();
			} else {
				alert("Error loading character");
			}
		}
		
		function draw_hero_stats(p_hero_num, p_hero) {
			$('.'+p_hero_num+'_name').html(p_hero.name);
			$('.'+p_hero_num+'_name_short').html(p_hero.name_short);
			$('.'+p_hero_num+'_health').html(p_hero.health);
			$('.'+p_hero_num+'_strength').html(p_hero.strength);
			$('.'+p_hero_num+'_agility').html(p_hero.agility);
			$('.'+p_hero_num+'_defense').html(p_hero.defense);
			$('.'+p_hero_num+'_luck').html(p_hero.luck);
			$('.'+p_hero_num+'_description').html(p_hero.description);
			$('.'+p_hero_num+'_img').attr("src", "/dungeonquest/characters/"+p_hero.image_url_2);
		}
		
		function create_game_board() {
			// Create Main Board
			for (var i = boardgame_height; i > 0; i--){
				jQuery('<div/>', { id: 'dungeon_board_row_'+(i), 'class': 'dungeon_row' }).appendTo('.dungeon_board');
				for (var j = 0; j < boardgame_width; j++) {
					jQuery('<div/>', { id: 'dungeon_board_cell_'+(j+1)+'_'+(i), 'class': 'dungeon_cell', }).appendTo('#dungeon_board_row_'+(i));
					var random_empty_tile_number = Math.floor(Math.random() * 25 + 1);
					var random_empty_tile_direction = Math.floor(Math.random() * 4 + 1);
					$('#dungeon_board_cell_'+(j+1)+'_'+(i)).css("background-image", "url('"+image_directory+"empty_"+random_empty_tile_number+".jpg')");
					$('#dungeon_board_cell_'+(j+1)+'_'+(i)).css("transform", "rotate("+eval((random_empty_tile_direction-1)*90)+"deg)");
					empty_map_orientation[(j+1)*100 + i] = eval((random_empty_tile_direction-1)*90);
				}
			}
			// Create Mini Board
			if (enable_mini_board) {
				for (var i = 1; i > -3; i--){
					jQuery('<div/>', { id: 'mini_board_row_'+(i), 'class': 'mini_dungeon_row' }).appendTo('#mini_dungeon_board');
					for (var j = -2; j < 3; j++) { 
						jQuery('<div/>', { id: 'mini_board_cell_'+(j)+'_'+(i), 'class': 'mini_dungeon_cell', }).appendTo('#mini_board_row_'+(i));
					}
				}
			}
			// Create Medium Board
			if (enable_medium_board) {
				for (var i = 3; i > -4; i--){
					jQuery('<div/>', { id: 'medium_board_row_'+(i), 'class': 'medium_dungeon_row' }).appendTo('#medium_dungeon_board');
					for (var j = -3; j < 4; j++) { 
						jQuery('<div/>', { id: 'medium_board_cell_'+(j)+'_'+(i), 'class': 'medium_dungeon_cell', }).appendTo('#medium_board_row_'+(i));
					}
				}
			}
		}

		function update_mini_game_board(){
			
			$('.mini_dungeon_cell').css('background-image','');
			//$('.mini_dungeon_cell').css('transform', 'rotate(0deg)');
			$('.mini_dungeon_cell').css('background-color', 'white');
			//$('.mini_dungeon_cell').css('transform', 'rotate(0deg)');
			
			// mini board adjustments
			var x_adjustment = 0;
			var y_adjustment = 0;
			
			if (hero1.x == 1) { x_adjustment = 2; }
			if (hero1.x == 2) { x_adjustment = 1; }
			if (hero1.x == boardgame_width - 1) { x_adjustment = -1; }
			if (hero1.x == boardgame_width) { x_adjustment = -2; }
			
			if (hero1.y == 1) { y_adjustment = 2; }
			if (hero1.y == 2) { y_adjustment = 1; }
			if (hero1.y == boardgame_height -1) { y_adjustment = 0; }
			if (hero1.y == boardgame_height) { y_adjustment = -1; }
			
			for (var i = -2; i < 3; i++) {
				for (var j = -2; j < 2; j++) {
					if (hero1 != undefined) {
						
						// copy background to mini map
						var background_url = $('#dungeon_board_cell_'+(hero1.x+i+x_adjustment)+'_'+(hero1.y+j+y_adjustment)).css('background-image');
						var background_rotation = empty_map_orientation[eval(hero1.x+i+x_adjustment+1)*100 + eval(hero1.y+j+y_adjustment)];
						
						if (background_url != undefined) {
							background_url = background_url.replace('url(','').replace(')','').replace(/\"/gi, "");
							$('#mini_board_cell_'+i+'_'+j).css('background-image', 'url("'+background_url+'")');
							$('#mini_board_cell_'+i+'_'+j).css('transform', 'rotate('+background_rotation+'deg)');
						}
						
						// copy explored tile to mini map
						var map_tile = hero_map[eval(hero1.x+i+x_adjustment)*100 + eval(hero1.y+j+y_adjustment)];
						if (map_tile != undefined) {
							$('#mini_board_cell_'+i+'_'+j).css('background-image', 'url("'+image_directory+map_tile.image_url+'")');
							$('#mini_board_cell_'+i+'_'+j).css('transform', 'rotate('+eval(map_tile.orientation)+'deg)');
						}
						
					}
				}
			}
			
		}
		

		function update_medium_game_board(){
			
			$('.medium_dungeon_cell').css('background-image','');
			//$('.medium_dungeon_cell').css('transform', 'rotate(0deg)');
			$('.medium_dungeon_cell').css('background-color', 'white');
			//$('.medium_dungeon_cell').css('transform', 'rotate(0deg)');
			
			// medium board adjustments
			var x_adjustment = 0;
			var y_adjustment = 0;
			
			if (hero1.x == 1) { x_adjustment = 3; }
			if (hero1.x == 2) { x_adjustment = 2; }
			if (hero1.x == 3) { x_adjustment = 1; }
			if (hero1.x == boardgame_width - 2) { x_adjustment = -1; }
			if (hero1.x == boardgame_width - 1) { x_adjustment = -2; }
			if (hero1.x == boardgame_width) { x_adjustment = -3; }
			
			if (hero1.y == 1) { y_adjustment = 3; }
			if (hero1.y == 2) { y_adjustment = 2; }
			if (hero1.y == 3) { y_adjustment = 1; }
			if (hero1.y == boardgame_height -2) { y_adjustment = -1; }
			if (hero1.y == boardgame_height -1) { y_adjustment = -2; }
			if (hero1.y == boardgame_height) { y_adjustment = -3; }
			
			for (var i = -3; i < 4; i++) {
				for (var j = -3; j < 4; j++) {
					if (hero1 != undefined) {
						
						// copy background to medium map
						var background_url = $('#dungeon_board_cell_'+(hero1.x+i+x_adjustment)+'_'+(hero1.y+j+y_adjustment)).css('background-image');
						var background_rotation = empty_map_orientation[eval(hero1.x+i+x_adjustment+1)*100 + eval(hero1.y+j+y_adjustment)];
						
						if (background_url != undefined) {
							background_url = background_url.replace('url(','').replace(')','').replace(/\"/gi, "");
							$('#medium_board_cell_'+i+'_'+j).css('background-image', 'url("'+background_url+'")');
							$('#medium_board_cell_'+i+'_'+j).css('transform', 'rotate('+background_rotation+'deg)');
						}
						
						// copy explored tile to medium map
						var map_tile = hero_map[eval(hero1.x+i+x_adjustment)*100 + eval(hero1.y+j+y_adjustment)];
						if (map_tile != undefined) {
							$('#medium_board_cell_'+i+'_'+j).css('background-image', 'url("'+image_directory+map_tile.image_url+'")');
							$('#medium_board_cell_'+i+'_'+j).css('transform', 'rotate('+eval(map_tile.orientation)+'deg)');
						}
						
					}
				}
			}
			
		}
		
		function outside_board(x, y) {
			if (x < 1 || y < 1 || x > boardgame_width || y > boardgame_height) {
				return true;
			}
		}
		
		function start_game() {
			
			timer = max_timer;
			game_over = false;
			ferrox_dead = true;
			trap_triggered = false;
			trap_triggered_type = "";
			
			hero_map = {};
			reset_game_board();
			hero_loot = [];
			
			$('.console').empty();
			$('.loot_console').empty();
			$('.ferrox_battle_console').empty();
			
			$('.mini_console').prepend("["+eval(timer-max_timer)+"] Swipe to Move<br/>");
			$('.loot_console').prepend("<br/>");
			
			remove_heros();
			draw_hero();
			update_timer();
			
		}
		
		function reset_game() {
		}
		
		function reset_game_board(){
			
			for (var i = boardgame_height; i > 0; i--){
				for (var j = 0; j < boardgame_width; j++) {
					var random_empty_tile_number = Math.floor(Math.random() * 25 + 1);
					var random_empty_tile_direction = Math.floor(Math.random() * 4 + 1);
					$('#dungeon_board_cell_'+(j+1)+'_'+(i)).css("background-image", "url('"+image_directory+"empty_"+random_empty_tile_number+".jpg')");
					$('#dungeon_board_cell_'+(j+1)+'_'+(i)).css("transform", "rotate("+eval((random_empty_tile_direction-1)*90)+"deg)");
					
					$('#dungeon_board_cell_'+(j+1)+'_'+(i+1)).empty();
					//write_to_console("url('"+image_directory+"empty_"+random_empty_tile_number+".jpg')");
				}
			}
			
			// load corner tiles
			hero_map[100 + 1] = new Chamber(0, 1, 1, 2, 2, 0, get_rotation_angle(4), 'corner.jpg');
			hero_map[boardgame_width*100 + 1] = new Chamber(0, 1, 2, 2, 1, 0, get_rotation_angle(3), 'corner.jpg');
			hero_map[100 + boardgame_height] = new Chamber(0, 2, 1, 1, 2, 0, get_rotation_angle(1), 'corner.jpg');
			hero_map[boardgame_width*100 + boardgame_height] = new Chamber(0, 2, 2, 1, 1, 0, get_rotation_angle(2), 'corner.jpg');
			
			// load treasure tiles
			hero_map[705] = new Chamber(0, 1, 1, 1, 1, 99, 0, 'treasure_bottom.jpg');
			hero_map[706] = new Chamber(0, 1, 1, 1, 1, 99, 0, 'treasure_top.jpg');
			
			// draw corner tiles
			$('#dungeon_board_cell_'+(1)+'_'+(1)).css("background-image", "url('"+image_directory+"corner.jpg')");
			$('#dungeon_board_cell_'+(1)+'_'+(1)).css("transform", "rotate("+get_rotation_angle(4)+"deg)");
			$('#dungeon_board_cell_'+(boardgame_width)+'_'+(1)).css("background-image", "url('"+image_directory+"corner.jpg')");
			$('#dungeon_board_cell_'+(boardgame_width)+'_'+(1)).css("transform", "rotate("+get_rotation_angle(3)+"deg)");
			$('#dungeon_board_cell_'+(1)+'_'+(boardgame_height)).css("background-image", "url('"+image_directory+"corner.jpg')");
			$('#dungeon_board_cell_'+(1)+'_'+(boardgame_height)).css("transform", "rotate("+get_rotation_angle(1)+"deg)");
			$('#dungeon_board_cell_'+(boardgame_width)+'_'+(boardgame_height)).css("background-image", "url('"+image_directory+"corner.jpg')");
			$('#dungeon_board_cell_'+(boardgame_width)+'_'+(boardgame_height)).css("transform", "rotate("+get_rotation_angle(2)+"deg)");
			
			// draw treasure tiles
			$('#dungeon_board_cell_7_5').css('background-image','url("/dungeonquest/treasure_room_bottom.jpg")');
			$('#dungeon_board_cell_7_6').css('background-image','url("/dungeonquest/treasure_room_top.jpg")');
			$('#dungeon_board_cell_7_5').css('background-image','url("/dungeonquest/second_edition/treasure_bottom.jpg")');
			$('#dungeon_board_cell_7_6').css('background-image','url("/dungeonquest/second_edition/treasure_top.jpg")');
			$('#dungeon_board_cell_7_5').css('background-image','url("/dungeonquest/images/second_edition_friendlybombs/treasure_bottom.jpg")');
			$('#dungeon_board_cell_7_6').css('background-image','url("/dungeonquest/images/second_edition_friendlybombs/treasure_top.jpg")');
			$('#dungeon_board_cell_7_5').css('transform','rotate(0deg)');
			$('#dungeon_board_cell_7_6').css('transform','rotate(0deg)');

			update_mini_game_board();
			update_medium_game_board();
		}
		
		function get_rotation_angle(p_Orientation){
			return (p_Orientation - 1) * 90;
		}
		
		function remove_heros() {
			
			for (var i = 0; i < boardgame_height; i++){				
				for (var j = 0; j < boardgame_width; j++) {
					$('#dungeon_board_cell_'+(j+1)+'_'+(i+1)).empty();
				}
			}
			
			if (enable_mini_board) {
				for (var i = -3; i < 4; i++) {
					for (var j = -3; j < 4; j++) {
						$('#mini_board_cell_'+(j+1)+'_'+(i+1)).empty();
					}
				}
			}
			
			if (enable_medium_board) {
				for (var i = -4; i < 5; i++) {
					for (var j = -4; j < 5; j++) {
						$('#medium_board_cell_'+(j+1)+'_'+(i+1)).empty();
					}
				}
			}
			
		}
		
		function draw_hero(){
			
			var hero_margin = "margin: 0px;";
			
			if (hero1 != undefined) {
				$('#dungeon_board_cell_'+(hero1.x)+'_'+(hero1.y)).append('<div style="height: 100%; width: 100%; background-size: 30%; background-position: center; background-repeat: no-repeat; background-image: content(\'/dungeonquest/characters/'+hero1.image_url_2+'\')"></div>');
				
				// mini board adjustments
				var x_adjustment = 0;
				var y_adjustment = 0;
				
				if (hero1.x == 1) { x_adjustment = 2; }
				if (hero1.x == 2) { x_adjustment = 1; }
				if (hero1.x == boardgame_width - 1) { x_adjustment = -1; }
				if (hero1.x == boardgame_width) { x_adjustment = -2; }
				
				if (hero1.y == 1) { y_adjustment = 2; }
				if (hero1.y == 2) { y_adjustment = 1; }
				if (hero1.y == boardgame_height -1) { y_adjustment = 0; }
				if (hero1.y == boardgame_height) { y_adjustment = -1; }
				
				if (enable_mini_board) {
					$('#mini_board_cell_'+eval(0-x_adjustment)+'_'+eval(0-y_adjustment)+'').append('<div style="height: 100%; width: 100%; display: flex; background-size: 30%; background-position: center; background-repeat: no-repeat; background-image: url(\'/dungeonquest/characters/'+hero1.image_url_2+'\')"></div>');
				}
				
				// medium board adjustments
				x_adjustment = 0;
				y_adjustment = 0;
				
				if (hero1.x == 1) { x_adjustment = 3; }
				if (hero1.x == 2) { x_adjustment = 2; }
				if (hero1.x == 3) { x_adjustment = 1; }
				if (hero1.x == boardgame_width - 2) { x_adjustment = -1; }
				if (hero1.x == boardgame_width - 1) { x_adjustment = -2; }
				if (hero1.x == boardgame_width) { x_adjustment = -3; }
				
				if (hero1.y == 1) { y_adjustment = 3; }
				if (hero1.y == 2) { y_adjustment = 2; }
				if (hero1.y == 3) { y_adjustment = 1; }
				if (hero1.y == boardgame_height -2) { y_adjustment = -1; }
				if (hero1.y == boardgame_height -1) { y_adjustment = -2; }
				if (hero1.y == boardgame_height) { y_adjustment = -3; }
				
				if (enable_medium_board) {
					$('#medium_board_cell_'+eval(0-x_adjustment)+'_'+eval(0-y_adjustment)+'').append('<div style="height: 100%; width: 100%; display: flex; background-size: 30%; background-position: center; background-repeat: no-repeat; background-image: url(\'/dungeonquest/characters/'+hero1.image_url_2+'\')"></div>');
				}
			}
			
			switch(hero_map[hero1.x*100 + hero1.y].type) {
				case '3':
					write_to_console('You see a narrow Bridge');
					break;
				case '4':
					write_to_console('You see a wide Pit');
					break;
				case '8':
					write_to_console('You see stairs leading into the catacombs');
					break;
				case '10':
					write_to_console('You see a room full of Darkness');
					break;
				case '11':
					write_to_console('You see a room full of Spider Web');
					break;
				case '12':
					write_to_console('You see a caved-in room full of Rubbles');
					break;
			}
		}
		
		function quit_game() {
			if (confirm("Do you want to quit?")) {
				reset_game();
				$('#character_selection_screen').show();
				$('#game').hide();
			}
		}
		
		function move_hero(p_direction){
			
			// do not allow movement if modal is open
			if (event_not_resolved()) { return; }
			
			if (hero1 != undefined && timer > 0) {
				
				var curr_hero_chamber = hero_map[hero1.x*100 + hero1.y];
				var next_hero_chamber;
				var prev_hero_chamber;
				
				switch(p_direction) {
					case '1':
						next_hero_chamber = hero_map[hero1.x*100 + hero1.y+1];
						prev_hero_chamber = hero_map[hero1.x*100 + hero1.y-1];
						if (is_inside_board(hero1.x, hero1.y + 1)) {
							
							if (curr_hero_chamber.secret_door == '1') {
								curr_hero_chamber.secret_door = '0';
								hero1.y++;
								break;
							}
							
							if (!is_facing_wall(curr_hero_chamber, p_direction) && !is_adjacent_to_wall(next_hero_chamber, '3')) {
								if (valid_movement(p_direction, curr_hero_chamber, next_hero_chamber, prev_hero_chamber)) { 
									hero1.y++; 
									previous_direction = p_direction;
								} else {
									return;
								}
							} else {
								write_to_console('You see a wall');
								return;
							}
						} else {
							prompt_exit_dungeon();
							return;
						}
						break;
					case '2':
						next_hero_chamber = hero_map[(hero1.x+1)*100 + hero1.y];
						prev_hero_chamber = hero_map[(hero1.x+1)*100 + hero1.y];
						if (is_inside_board(hero1.x + 1, hero1.y)) {
							
							if (curr_hero_chamber.secret_door == '1') {
								curr_hero_chamber.secret_door = '0';
								hero1.x++;
								break;
							}
							
							if (!is_facing_wall(curr_hero_chamber, p_direction) && !is_adjacent_to_wall(next_hero_chamber, '4')) {
								if (valid_movement(p_direction, curr_hero_chamber, next_hero_chamber, prev_hero_chamber)) { 
									hero1.x++; 
									previous_direction = p_direction;
								} else {
									return;
								}
							} else {
								write_to_console('You see a wall');
								return;
							}
						} else { 
							prompt_exit_dungeon();
							return;
						}
						break;
					case '3':
						next_hero_chamber = hero_map[hero1.x*100 + hero1.y-1];
						prev_hero_chamber = hero_map[hero1.x*100 + hero1.y+1];
						if (is_inside_board(hero1.x, hero1.y - 1)) {
							
							if (curr_hero_chamber.secret_door == '1') {
								curr_hero_chamber.secret_door = '0';
								hero1.y--;
								break;
							}
							
							if (!is_facing_wall(curr_hero_chamber, p_direction) && !is_adjacent_to_wall(next_hero_chamber, '1')) {
								if (valid_movement(p_direction, curr_hero_chamber, next_hero_chamber, prev_hero_chamber)) { 
									hero1.y--; 
									previous_direction = p_direction; 
								} else {
									return;
								}
							} else {
								write_to_console('You see a wall');
								return;
							}
						} else { 
							prompt_exit_dungeon();
							return;
						}
						break;
					case '4':
						next_hero_chamber = hero_map[(hero1.x-1)*100 + hero1.y];
						prev_hero_chamber = hero_map[(hero1.x+1)*100 + hero1.y-1];
						if (is_inside_board(hero1.x - 1, hero1.y)) {
							
							if (curr_hero_chamber.secret_door == '1') {
								curr_hero_chamber.secret_door = '0';
								hero1.x--;
								break;
							}
							
							if (!is_facing_wall(curr_hero_chamber, p_direction) && !is_adjacent_to_wall(next_hero_chamber, '2')) {
								if (valid_movement(p_direction, curr_hero_chamber, next_hero_chamber, prev_hero_chamber)) { 
									hero1.x--;
									previous_direction = p_direction; 
								} else {
									return;
								}
							} else {
								write_to_console('You see a wall');
								return;
							}
						} else {
							prompt_exit_dungeon();
							return;
						}
						break;
					default:
						return;
						
				}
				
				draw_chamber(p_direction);
				remove_heros();
				draw_hero();
				
				// do not update time for corridors
				if (hero_map[hero1.x*100 + hero1.y].type != '2') {
					update_timer();
				}
				
				
			}
		}
		
		function prompt_exit_dungeon() {
			if (is_exit_chamber(hero1)) {
				if (confirm("Would you like to leave the Dungeon")) {
					exit_dungeon();
				}
			} else {
				write_to_console('You see a impenetrable barrier');
			}
			return false;
		}
		
		function exit_dungeon() {
			if (check_hero_exit()) {
				
				
				
				setTimeout(function () {
					//alert('Hero left the Dungeon');
					toggle_alert_modal('success','Exit Dungeon','','You left the dungeon with items worth '+loot_total()+' Gold!!');
				}, 100);
				
			}
			// calculate treasure loot and record detail
		}
		
		function is_exit_chamber(p_Hero) {
			return ((p_Hero.x == 1 && p_Hero.y == 1) ||
				(p_Hero.x == 1 && p_Hero.y == boardgame_height) ||
				(p_Hero.x == boardgame_width && p_Hero.y == boardgame_height) ||
				(p_Hero.x == boardgame_width && p_Hero.y == 1));
		}
		
		function is_treasure_chamber(p_Hero) {
			return ((p_Hero.x == 7 && p_Hero.y == 5) || 
				(p_Hero.x == 7 && p_Hero.y == 6));
		}
		
		function is_inside_board(p_x, p_y) {
			if (p_x > 0 && p_x < boardgame_width+1 && p_y > 0 && p_y < boardgame_height+1) {
				// if (hero1.x == p_x && hero1.y == p_y) {
					// return false;
				// }				
				return true;
			} else {
				return false;
			}
		}
		
		function is_facing_wall(p_hero_chamber, p_direction) {
			if (p_hero_chamber == undefined) {
				return false;
			}
			switch (p_direction) {
				case '1':
					return p_hero_chamber.top == '2';
					break;
				case '2':
					return p_hero_chamber.right == '2';
					break;
				case '3':
					return p_hero_chamber.bottom == '2';
					break;
				case '4':
					return p_hero_chamber.left == '2';
					break;
			}
			return false;
		}
		
		function is_adjacent_to_wall(p_next_hero_chamber, p_direction) {
			if (p_next_hero_chamber == undefined) {
				return false;
			}
			switch (p_direction) {
				case '1':
					return p_next_hero_chamber.top == '2';
					break;
				case '2':
					return p_next_hero_chamber.right == '2';
					break;
				case '3':
					return p_next_hero_chamber.bottom == '2';
					break;
				case '4':
					return p_next_hero_chamber.left == '2';
					break;
			}
			return false;
		}
		
		function valid_movement(p_direction, p_curr_hero_chamber, p_next_hero_chamber, p_prev_hero_chamber) {
			
			var next_top = "";
			var next_right = "";
			var next_bottom = "";
			var next_left = "";
			
			var prev_top = "";
			var prev_right = "";
			var prev_bottom = "";
			var prev_left = "";
			
			if (p_next_hero_chamber != undefined) {
				next_top = p_next_hero_chamber.top;
				next_right = p_next_hero_chamber.right;
				next_bottom = p_next_hero_chamber.bottom;
				next_left = p_next_hero_chamber.left;
			}
			
			if (p_prev_hero_chamber != undefined) {
				prev_top = p_prev_hero_chamber.top;
				prev_right = p_prev_hero_chamber.right;
				prev_bottom = p_prev_hero_chamber.bottom;
				prev_left = p_prev_hero_chamber.left;
			}
			
			var new_encounter;
			var encounter_counter = 0;
			var randomNumber = Math.floor(Math.random() * 14 + 1);
			var randomDoor = DoorJSON[randomNumber];
			
			do {
				randomNumber = Math.floor(Math.random() * 14 + 1);
				randomDoor = DoorJSON[randomNumber];
			} while (randomDoor == undefined)
				
			if (moving_backwards(p_direction)) {
				
				switch (String(p_direction)) {
					case '1':
						if (debug) {
						write_to_console("moving backwards " + p_curr_hero_chamber.top + " | " + next_bottom);
						}
						if (p_curr_hero_chamber.top == '3') {
							add_encounter('door', p_direction);
							encounter_counter++;
						}
						if (p_curr_hero_chamber.top == '5') {
							add_encounter("portcullis", p_direction);
							encounter_counter++;
						}
						if (next_bottom == '3') {
							add_encounter('door', p_direction);
							encounter_counter++;
						}
						if (next_bottom == '5') {
							add_encounter("portcullis", p_direction);
							encounter_counter++;
						}
						if (encounter_counter == 0) {
							return true;
						}
						break;
						
					case '2':
						if (debug) {
						write_to_console("moving backwards " + p_curr_hero_chamber.right + " | " + next_left);
						}
						if (p_curr_hero_chamber.right == '3') {
							add_encounter("door", p_direction);
							encounter_counter++;
						}
						if (p_curr_hero_chamber.right == '5') {
							add_encounter("portcullis", p_direction);
							encounter_counter++;
						}
						if (next_left == '3') {
							add_encounter("door", p_direction);
							encounter_counter++;
						}
						if (next_left == '5') {
							add_encounter("portcullis", p_direction);
							encounter_counter++;
						}
						if (encounter_counter == 0) {
							return true;
						}
						break;
						
					case '3':
						if (debug) {
						write_to_console("moving backwards " + p_curr_hero_chamber.bottom + " | " + next_top);
						}
						if (p_curr_hero_chamber.bottom == '3') {
							add_encounter("door", p_direction);
							encounter_counter++;
						}
						if (p_curr_hero_chamber.bottom == '5') {
							add_encounter("portcullis", p_direction);
							encounter_counter++;
						}
						if (next_top == '3') {
							add_encounter("door", p_direction);
							encounter_counter++;
						}
						if (next_top == '5') {
							add_encounter("portcullis", p_direction);
							encounter_counter++;
						}
						if (encounter_counter == 0) {
							return true;
						}
						break;
						
					case '4':
						if (debug) {
						write_to_console("moving backwards " + p_curr_hero_chamber.left + " | " + next_right);
						}
						if (p_curr_hero_chamber.left == '3') {
							add_encounter("door", p_direction);
							encounter_counter++;
						}
						if (p_curr_hero_chamber.left == '5') {
							add_encounter("portcullis", p_direction);
							encounter_counter++;
						}
						if (next_right == '3') {
							add_encounter("door", p_direction);
							encounter_counter++;
						}
						if (next_right == '5') {
							add_encounter("portcullis", p_direction);
							encounter_counter++;
						}
						if (encounter_counter == 0) {
							return true;
						}
						break;
					default:
				}
				
				if (encounter_counter > 0) {
					force_trap_modal();
					return false;
				}
			}
			
			switch (String(p_direction)) {
				case '1':
					return resolve_obstacle(p_direction, p_curr_hero_chamber.top, next_bottom);
					break;
				case '2':
					return resolve_obstacle(p_direction, p_curr_hero_chamber.right, next_left);
					break;
				case '3':
					return resolve_obstacle(p_direction, p_curr_hero_chamber.bottom, next_top);
					break;
				case '4':
					return resolve_obstacle(p_direction, p_curr_hero_chamber.left, next_right);
					break;
				default:
					return false;
			}
			
		}
		
		function add_encounter(type, p_direction) {
			
			// pre-determine door type
			var randomNumber = Math.floor(Math.random() * 14 + 1);
			var randomDoor = DoorJSON[randomNumber];
			
			do {
				randomNumber = Math.floor(Math.random() * 14 + 1);
				randomDoor = DoorJSON[randomNumber];
			} while (randomDoor == undefined)
			
			var new_encounter;
			switch (type) {
				case "door":
					switch (randomDoor.value) {
						case "1":
							new_encounter = new Encounter('opendoor', 'Open Door', '#D1001C', 'white', 'You open the Door', p_direction);
							encounter_queue.push(new_encounter);
							break;
						case "2":
							new_encounter = new Encounter('jammeddoor', 'Jammed Door', '#D1001C', 'white', 'The Door is Jammed', p_direction);
							encounter_queue.push(new_encounter);
							break;
						case "3":
							new_encounter = new Encounter('speardoor', 'Trapped Door', '#D1001C', 'white', 'A Spear flies towards you', p_direction);
							encounter_queue.push(new_encounter);
							break;
					}
					break;
				case "bridge":
					new_encounter = new Encounter('bridge', 'Bridge', '#D1001C', 'white', 'Do you want to cross the Bridge?', p_direction);
					encounter_queue.push(new_encounter);
					break;
				case "portcullis":
					new_encounter = new Encounter('portcullis', 'Portcullis', '#D1001C', 'white', 'Do you want to raise the Portcullis?', p_direction);
					encounter_queue.push(new_encounter);
					break;
				case "pit":
					new_encounter = new Encounter('pit', 'Pit', '#D1001C', 'white', 'Do you want to jump over the Pit?', p_direction);
					encounter_queue.push(new_encounter);
					break;
				case "rubble":
					new_encounter = new Encounter('pit', 'Rubble', '#D1001C', 'white', 'Do you want to move through the Rubble?', p_direction);
					encounter_queue.push(new_encounter);
					break;
				case "spiderweb":
					new_encounter = new Encounter('spiderweb', 'Spider Web', '#D1001C', 'white', 'Do you want to move through the Spider Web?', p_direction);
					encounter_queue.push(new_encounter);
					break;
				case "darkness":
					new_encounter = new Encounter('darkness', 'Darkness', '#000000', 'white', 'Do you want to move through the Darkness?', p_direction);
					encounter_queue.push(new_encounter);
					break;
			}
		}
		
		//obstacle legend
		//1 open
		//2 wall
		//3 door
		//4 bridge
		//5 portcullis
		//6 pit
		//7 cave-in/rubble
		//8 spiderweb
		//9 darkness
		
		function resolve_obstacle(p_direction, p_obstacle, p_next_obstacle) {
			
			encounter_queue = [];
			var temp_next_obstacle = p_next_obstacle;
			
			if (temp_next_obstacle == '') { temp_next_obstacle = '1' };
			
			// passage and passage
			if (p_obstacle == '1' && temp_next_obstacle == '1') {
				write_to_console('You move to the next chamber');
				return true;
				
			// wall or wall
			} else if (p_obstacle == '2' || temp_next_obstacle == '2') {
				write_to_console('You see a wall');
				return false;
			}
			
			switch (p_obstacle) {
				case "3":
					add_encounter("door", p_direction);
					break;
				case "4":
					add_encounter("bridge", p_direction);
					break;
				case "5":
					add_encounter("portcullis", p_direction);
					break;
				case "6":
					add_encounter("pit", p_direction);
					break;
				case "7":
					add_encounter("rubble", p_direction);
					break;
				case "8":
					add_encounter("spiderweb", p_direction);
					break;
				case "9":
					add_encounter("darkness", p_direction);
					break;
			}
			
			switch (temp_next_obstacle) {
				case "3":
					if (p_obstacle != '9' && p_obstacle != '3') {
						add_encounter("door", p_direction);
					}
					break;
				case "5":
					if (p_obstacle != '9' && p_obstacle != '5') {
						add_encounter("portcullis", p_direction);
					}
					break;
			}
			
			if (encounter_queue.length > 0) {
				force_trap_modal();
				return false;
			}
			
			return true;
			
		}
		
		function moving_backwards(p_Direction) {
			switch (String(p_Direction)) {
				case '1':
					return String(previous_direction) == '3';
					break;
				case '2':
					return String(previous_direction) == '4';
					break;
				case '3':
					return String(previous_direction) == '1';
					break;
				case '4':
					return String(previous_direction) == '2';
					break;
				return false;
			}
		}
		
		function roll_dice(p_dice) {
			var dice1;
			var dice2;
			var total = 0;
			
			if (p_dice == 1) {
				
				dice1 = Math.floor(Math.random() * 6 + 1);
				dice.dataset.side = dice1;
				dice.classList.toggle("reRoll");
				mini_dice.dataset.side = dice1;
				mini_dice.classList.toggle("reRoll");
				total = dice1;
				
			} else if (p_dice == 2) {
				
				dice1 = Math.floor(Math.random() * 6 + 1);
				dice.dataset.side = dice1;
				dice.classList.toggle("reRoll");
				mini_dice.dataset.side = dice1;
				mini_dice.classList.toggle("reRoll");
				
				dice2 = Math.floor(Math.random() * 6 + 1);
				dice_2.dataset.side = dice2;
				dice_2.classList.toggle("reRoll");
				mini_dice_2.dataset.side = dice2;
				mini_dice_2.classList.toggle("reRoll");
				total = dice1 + dice2;
			}
			
			$('.dice_total').html(total);
			return total;
		}
		
		
		function roll_ferrox_dice(p_dice) {
			
			var dice1;
			var dice2;
			var total = 0;
			
			if (p_dice == 1) {
				
				// determine ferrox life
				dice1 = Math.floor(Math.random() * 6 + 1);
				mini_dice.dataset.side = dice1;
				mini_dice.classList.toggle("reRoll");
				
				total = dice1;
				$('.dice_total').html(total);
				
				return total;
				
			} else if (p_dice == 2) {
				
				// test Strength to fight ferrox
				dice1 = Math.floor(Math.random() * 6 + 1);
				mini_dice.dataset.side = dice1;
				mini_dice.classList.toggle("reRoll");
				
				dice2 = Math.floor(Math.random() * 6 + 1);
				mini_dice_2.dataset.side = dice2;
				mini_dice_2.classList.toggle("reRoll");
				
				total = dice1 + dice2;
				$('.dice_total').html(total);
			}
			
			
			if (ferrox_dead || hero1.health <= 0) {
				
				$('.dice_total').css('background-color', 'white');
				$('.dice_total').css('color', 'black');
				return;
			}
			
			if (total <= hero1.strength) {
				$('.dice_total').css('background-color', '#00FF00');
				$('.dice_total').css('color', '#FFFFFF');
				write_to_ferrox_battle_console("["+total+"] You wounded Ferrox 1 Wound");
				write_to_console("["+total+"] You wounded Ferrox 1 Wound");
				
				update_ferrox_health(-1);
				if (parseInt(ferrox_monster.health) <= 0) {
					write_to_ferrox_battle_console("You killed the Ferrox!!");
					write_to_console("You killed the Ferrox!!");
					$('.ferrox_attack_text').css("background-color", "#00FF00");
					$('.ferrox_attack_text').css("color", "white");
					$('.ferrox_attack_text').html("You killed the Ferrox!!");
					ferrox_dead = true;
				}
				
			} else {
				
				$('.dice_total').css('background-color', '#D1001C');
				$('.dice_total').css('color', '#FFFFFF');
				write_to_ferrox_battle_console("["+total+"] You received 1 wounded from Ferrox");
				write_to_console("["+total+"] You received 1 wounded from Ferrox");
				
				update_hero_health(-1);
				if (parseInt(hero1.health) <= 0) {
					write_to_ferrox_battle_console("You were killed by the Ferrox!!");
					write_to_console("You were killed by the Ferrox!!");
					$('.ferrox_attack_text').css("background-color", "#D1001C");
					$('.ferrox_attack_text').css("color", "white");
					$('.ferrox_attack_text').html("You were killed by the Ferrox!!");
					game_over = true;
				}
			}
			
			if (ferrox_dead) {
				$('.ferrox_attack_text').html("You killed the Ferrox!!");
				$('#fight_ferrox_button').attr("disabled", true);
				return;
			}
			
			if (hero1.health <= 0) {
				$('.ferrox_attack_text').html("You were killed by the Ferrox!!");
				$('#fight_ferrox_button').attr("disabled", true);
				return;
			}
		

			return total;
		}
		
		function force_trap_modal() {
			
			var current_encounter = encounter_queue[0];
			
			if (current_encounter != undefined) {
			
				trap_triggered = true;
				trap_triggered_type = current_encounter.type;
				current_direction = current_encounter.direction;
				
				$('.mini_dice_placeholder').attr("onclick", "").unbind("click");
				$(".mini_dice_placeholder").on('click', function(){ roll_trap_dice(); });
				
				$('.trap_hero_sheet_placeholder').append($('.mini_hero_sheet'));
				
				$('.dice_total').css('background-color', '#D1001C');
				$('.dice_total').css('color', '#FFFFFF');
				
				$('#trap_button').attr("disabled", false);

				$('#trap_modal_title').text(current_encounter.title);
				$('.trap_text').css("background-color", current_encounter.background);
				$('.trap_text').css("color", current_encounter.color);
				$('.trap_text').html(current_encounter.description);
				
				switch(encounter_queue[0].type) {
					case "opendoor":
						encounter_queue[0].resolved = '1';
						encounter_queue[0].success = '1';
						$('#trap_button').attr("disabled", true);
						break;
					case "jammeddoor":
						encounter_queue[0].resolved = '1';
						encounter_queue[0].success = '0';
						$('#trap_button').attr("disabled", true);
						break;
				}
				
				$('#trap_modal').modal("show");
			}
			
		}
		
		function move_through_darkness(p_Direction, p_dice_roll) {
			var now = new Date();
			
			var curr_hero_chamber = hero_map[hero1.x*100 + hero1.y];
			
			var dice_roll = p_dice_roll;
			
			//alert("current chamber: " + curr_hero_chamber.id + ", dice: " + dice_roll);
			if (String(curr_hero_chamber.id) == '64') {
				//alert("chamber: " + curr_hero_chamber.id + ", dice: " + dice_roll);
				switch(String(dice_roll)){
					case '1':
					case '2':
						$('.dice_total').css('background-color', '#000000');
						$('.dice_total').css('color', '#FFFFFF');
						stumble_backwards(dice_roll, p_Direction);
						return false;
						break;
					case '3':
					case '4':
						$('.dice_total').css('background-color', '#000000');
						$('.dice_total').css('color', '#FFFFFF');
						stumble_to_the_left(dice_roll, p_Direction);
						return false;
						break;
					case '5':
					case '6':
						$('.dice_total').css('background-color', '#000000');
						$('.dice_total').css('color', '#FFFFFF');
						stumble_to_the_right(dice_roll, p_Direction);
						return false;
						break;
				}
			} else if (String(curr_hero_chamber.id) == '65') {
				//alert("chamber: " + curr_hero_chamber.id + ", dice: " + dice_roll);
				switch(String(dice_roll)){
					case '1':
					case '2':
					case '3':
						$('.dice_total').css('background-color', '#000000');
						$('.dice_total').css('color', '#FFFFFF');
						stumble_backwards(dice_roll, p_Direction);
						return false;
						break;
					case '4':
					case '5':
					case '6':
						$('.dice_total').css('background-color', '#000000');
						$('.dice_total').css('color', '#FFFFFF');
						stumble_to_the_left(dice_roll, p_Direction);
						return false;
						break;
				}
			} else if (String(curr_hero_chamber.id) == '66') {
				//alert("chamber: " + curr_hero_chamber.id + ", dice: " + dice_roll);
				switch(String(dice_roll)){
					case '1':
					case '2':
					case '3':
						$('.dice_total').css('background-color', '#000000');
						$('.dice_total').css('color', '#FFFFFF');
						stumble_backwards(dice_roll, p_Direction);
						return false;
						break;
					case '4':
					case '5':
					case '6':
						$('.dice_total').css('background-color', '#000000');
						$('.dice_total').css('color', '#FFFFFF');
						stumble_to_the_right(dice_roll, p_Direction);
						return false;
						break;
				}
			} else {
				write_to_console("You are stuck, report bug");
				return false;
			}
		}
		
		function stumble_to_the_right(p_dice_roll, p_Direction) {
			
			var curr_hero_chamber = hero_map[hero1.x*100 + hero1.y];
			
			var next_hero_chamber;
			var new_direction;

			if (String(curr_hero_chamber.orientation) == '0') {
				next_hero_chamber = hero_map[(hero1.x+1)*100 + hero1.y];
				//toggle_alert_modal('darkness', 'Darkness', '', 'You aimlessly wandered east');
				$('.trap_text').html('You aimlessly wandered east');
				new_direction = '2';
			} else if (String(curr_hero_chamber.orientation) == '90') {
				next_hero_chamber = hero_map[(hero1.x)*100 + hero1.y-1];
				//toggle_alert_modal('darkness', 'Darkness', '', 'You aimlessly wandered south');
				$('.trap_text').html('You aimlessly wandered south');
				new_direction = '3';
			} else if (String(curr_hero_chamber.orientation) == '180') {
				next_hero_chamber = hero_map[(hero1.x-1)*100 + hero1.y];
				//toggle_alert_modal('darkness', 'Darkness', '', 'You aimlessly wandered west');
				$('.trap_text').html('You aimlessly wandered west');
				new_direction = '4';
			} else if (String(curr_hero_chamber.orientation) == '270') {
				next_hero_chamber = hero_map[(hero1.x)*100 + hero1.y+1];
				//toggle_alert_modal('darkness', 'Darkness', '', 'You aimlessly wandered north');
				$('.trap_text').html('You aimlessly wandered north');
				new_direction = '1';
			}
			encounter_queue[0].direction = new_direction;
			
			prep_hero_movement_into_darkness(p_dice_roll, p_Direction, new_direction, next_hero_chamber);

		}
		
		function stumble_to_the_left(p_dice_roll, p_Direction) {
			
			var curr_hero_chamber = hero_map[hero1.x*100 + hero1.y];
			
			var next_hero_chamber;
			var new_direction;

			if (String(curr_hero_chamber.orientation) == '0') {
				next_hero_chamber = hero_map[(hero1.x-1)*100 + hero1.y];
				//toggle_alert_modal('darkness', 'Darkness', '', 'You aimlessly wandered west');
				$('.trap_text').html('You aimlessly wandered west');
				new_direction = '4';
			} else if (String(curr_hero_chamber.orientation) == '90') {
				next_hero_chamber = hero_map[(hero1.x)*100 + hero1.y+1];
				//toggle_alert_modal('darkness', 'Darkness', '', 'You aimlessly wandered north');
				$('.trap_text').html('You aimlessly wandered north');
				new_direction = '1';
			} else if (String(curr_hero_chamber.orientation) == '180') {
				next_hero_chamber = hero_map[(hero1.x+1)*100 + hero1.y];
				//toggle_alert_modal('darkness', 'Darkness', '', 'You aimlessly wandered east');
				$('.trap_text').html('You aimlessly wandered east');
				new_direction = '2';
			} else if (String(curr_hero_chamber.orientation) == '270') {
				next_hero_chamber = hero_map[(hero1.x)*100 + hero1.y-1];
				//toggle_alert_modal('darkness', 'Darkness', '', 'You aimlessly wandered south');
				$('.trap_text').html('You aimlessly wandered south');
				new_direction = '3';
			}
			encounter_queue[0].direction = new_direction;
			
			prep_hero_movement_into_darkness(p_dice_roll, p_Direction, new_direction, next_hero_chamber);

		}
		
		function stumble_backwards(p_dice_roll, p_Direction) {
			
			var curr_hero_chamber = hero_map[hero1.x*100 + hero1.y];
			
			var next_hero_chamber;
			var new_direction;

			if (String(curr_hero_chamber.orientation) == '0') {
				next_hero_chamber = hero_map[hero1.x*100 + hero1.y-1];
				//toggle_alert_modal('darkness', 'Darkness', '', 'You aimlessly wandered south');
				$('.trap_text').html('You aimlessly wandered south');
				new_direction = '3';
			} else if (String(curr_hero_chamber.orientation) == '90') {
				next_hero_chamber = hero_map[(hero1.x-1)*100 + hero1.y];
				//toggle_alert_modal('darkness', 'Darkness', '', 'You aimlessly wandered west');
				$('.trap_text').html('You aimlessly wandered west');
				new_direction = '4';
			} else if (String(curr_hero_chamber.orientation) == '180') {
				next_hero_chamber = hero_map[hero1.x*100 + hero1.y+1];
				//toggle_alert_modal('darkness', 'Darkness', '', 'You aimlessly wandered north');
				$('.trap_text').html('You aimlessly wandered north');
				new_direction = '1';
			} else if (String(curr_hero_chamber.orientation) == '270') {
				next_hero_chamber = hero_map[(hero1.x+1)*100 + hero1.y];
				//toggle_alert_modal('darkness', 'Darkness', '', 'You aimlessly wandered east');
				$('.trap_text').html('You aimlessly wandered east');
				new_direction = '2';
			}
			encounter_queue[0].direction = new_direction;
			
			prep_hero_movement_into_darkness(p_dice_roll, p_Direction, new_direction, next_hero_chamber);

		}
		
		function prep_hero_movement_into_darkness(p_dice_roll, p_Direction, p_new_direction, next_hero_chamber) {
			
			var temp_previous_direction = previous_direction;
			var randomNumber = Math.floor(Math.random() * 14 + 1);
			var randomDoor = DoorJSON[randomNumber];
			
			do {
				randomNumber = Math.floor(Math.random() * 14 + 1);
				randomDoor = DoorJSON[randomNumber];
			} while (randomDoor == undefined)
			
			if (p_new_direction == '1') {
				if (is_inside_board(hero1.x, hero1.y+1)) {
					if (next_hero_chamber != undefined) {
						
						if (next_hero_chamber.bottom == '2') {
							lost_in_darkness(p_dice_roll);
						} else {
							//force_hero_movement('2');
							if (next_hero_chamber.bottom == '3') {
								add_encounter('door', p_new_direction);
							} else if (next_hero_chamber.bottom == '5') {
								add_encounter("portcullis", p_direction);
							}
						}
					}
				} else {
					lost_in_darkness(p_dice_roll);
				}
			} else if (p_new_direction == '2') {
				if (is_inside_board(hero1.x+1, hero1.y)) {
					if (next_hero_chamber != undefined) {
						
						if (next_hero_chamber.left == '2') {
							lost_in_darkness(p_dice_roll);
						} else {
							//force_hero_movement('2');
							if (next_hero_chamber.left == '3') {
								add_encounter('door', p_new_direction);
							} else if (next_hero_chamber.left == '5') {
								add_encounter("portcullis", p_direction);
							}
						}
					}
				} else {
					lost_in_darkness(p_dice_roll);
				}
			} else if (p_new_direction == '3') {
				if (is_inside_board(hero1.x, hero1.y-1)) {
					if (next_hero_chamber != undefined) {
						
						if (next_hero_chamber.top == '2') {
							lost_in_darkness(p_dice_roll);
						} else {
							//force_hero_movement('2');
							if (next_hero_chamber.top == '3') {
								add_encounter('door', p_new_direction);
							} else if (next_hero_chamber.top == '5') {
								add_encounter("portcullis", p_direction);
							}
						}
					}
				} else {
					lost_in_darkness(p_dice_roll);
				}
			} else if (p_new_direction == '4') {
				if (is_inside_board(hero1.x-1, hero1.y)) {
					if (next_hero_chamber != undefined) {
						
						if (next_hero_chamber.right == '2') {
							lost_in_darkness(p_dice_roll);
						} else {
							//force_hero_movement('2');
							if (next_hero_chamber.right == '3') {
								add_encounter('door', p_new_direction);
							} else if (next_hero_chamber.right == '5') {
								add_encounter("portcullis", p_direction);
							}
						}
					}
				} else {
					lost_in_darkness(p_dice_roll);
				}
			} else {
				write_to_console("stumble left error, hero orientation: " + curr_hero_chamber.orientation);
			}
			
			if (Math.abs(temp_previous_direction - p_new_direction) == 2) {
				write_to_console("["+p_dice_roll+"] You aimlessly stumbled back to the previous room");
			} else {
				write_to_console("["+p_dice_roll+"] You proceed into the darkness");
			}
		}
		
		function lost_in_darkness(p_dice_roll) {
			encounter_queue[0].resolved = '1';
			encounter_queue[0].success = '0';
			write_to_console("["+p_dice_roll+"] You are lost in darkness");
			$('.trap_text').html("You are lost in the dark");
			//toggle_alert_modal('darkness', 'Darkness', '', 'You are lost in darkness');
			update_timer();
		}
		
		function force_hero_movement(p_Direction) {
			if (p_Direction == '1') {
				hero1.y++;
				previous_direction = p_Direction;
			} else if (p_Direction == '2') {
				hero1.x++;
				previous_direction = p_Direction;
			} else if (p_Direction == '3') {
				hero1.y--;
				previous_direction = p_Direction;
			} else if (p_Direction == '4') {
				hero1.x--;
				previous_direction = p_Direction;
			}
			draw_chamber(p_Direction);
			remove_heros();
			draw_hero();
			update_timer();
		}
		
		function draw_chamber(p_Direction) {
			
			if (!is_exit_chamber(hero1) && !is_treasure_chamber(hero1)) {
				
				if (hero_map[eval(hero1.x*100 + hero1.y)] == undefined) { 
				
					var randomNumber = Math.floor(Math.random() * 100+1);
					var randomChamber = ChamberJSON[randomNumber];
					
					do {
						randomNumber = Math.floor(Math.random() * 100+1);
						randomChamber = ChamberJSON[randomNumber];
					} while (randomChamber == undefined)
						
					// record new chamber id
					$('.new_chamber').html(randomNumber);

					var new_top = "";
					var new_right = "";
					var new_bottom = "";
					var new_left = "";
					
					if (p_Direction == 1) {
						new_top = randomChamber.top;
						new_right = randomChamber.right;
						new_bottom = randomChamber.bottom;
						new_left = randomChamber.left;
					} else if (p_Direction == 2) {
						new_top = randomChamber.left;
						new_right = randomChamber.top;
						new_bottom = randomChamber.right;
						new_left = randomChamber.bottom;
					} else if (p_Direction == 3) {
						new_top = randomChamber.bottom;
						new_right = randomChamber.left;
						new_bottom = randomChamber.top;
						new_left = randomChamber.right;
					} else if (p_Direction == 4) {
						new_top = randomChamber.right;
						new_right = randomChamber.bottom;
						new_bottom = randomChamber.left;
						new_left = randomChamber.top;
					}
					
					hero_map[eval(hero1.x*100 + hero1.y)] = new Chamber(randomNumber, new_top, new_right, new_bottom, new_left, randomChamber.type, eval((p_Direction-1)*90), randomChamber.image_url);
					hero_map[eval(hero1.x*100 + hero1.y)].searched = 0;
					
					if (debug) {
						write_to_console('new tile: ' + randomNumber + " " + new_top + " " + new_right + " " + new_bottom + " " + new_left);
					}
					
					$('#dungeon_board_cell_'+(hero1.x)+'_'+(hero1.y)).css("background-image", "url('"+image_directory+randomChamber.image_url+"')");
					$('#dungeon_board_cell_'+(hero1.x)+'_'+(hero1.y)).css("transform", "rotate("+eval((p_Direction-1)*90)+"deg)");
					
					if (randomNumber == 61 || randomNumber == 62 || randomNumber == 63) {
						trigger_trap("");
					}
					
				} else {
					
					if (hero_map[eval(hero1.x*100 + hero1.y)].id == 61 || hero_map[eval(hero1.x*100 + hero1.y)].id == 62 || hero_map[eval(hero1.x*100 + hero1.y)].id == 63) {
						trigger_trap("");
					}
				}
				
			}
			
			if (enable_mini_board) {
				update_mini_game_board();
			}
			if (enable_medium_board) {
				update_medium_game_board();
			}
		}
		
		//chamber type
		//0 starting
		//1 chamber
		//2 corridor
		//3 bridge
		//4 pit
		//5 rotating
		//6 left chasm
		//7 right chasm
		//8 catacombs
		//9 trap
		//10 darkness
		//11 spiderweb
		//12 cavein
		//99
		
		function update_timer(){
			timer--;
			$('.timer').html(timer);
			if (timer == 0 && !check_hero_exit()) {
				setTimeout(function () {
					game_over = true;
					toggle_alert_timer('danger', 'Time Ended', '', 'The dungeon closes and you die trapped inside!!!');
				}, 100);
			}
		}
		
		function check_hero_exit() {
			if (hero1.x == 1 && hero1.y == 1) {
				game_over = true;
			}
			if (hero1.x == 1 && hero1.y == 10) {
				game_over = true;
			}
			if (hero1.x == 13 && hero1.y == 1) {
				game_over = true;
			}
			if (hero1.x == 13 && hero1.y == 10) {
				game_over = true;
			}
				
			return game_over;
		}
		
		function search_crypt(){
			
			if (event_not_resolved()) { return; }
			
			var curr_hero_chamber = hero_map[hero1.x*100 + hero1.y];
			
			if (parseInt(curr_hero_chamber.crypt) > 0) {
				if (parseInt(curr_hero_chamber.crypt_searched) < 1) {
					if (confirm("Do you want to search the crypt?")) {
						// search the crypt
						curr_hero_chamber.crypt_searched = '1';
						write_to_console('You searched the crypt');
						update_timer();
					} else {
						return false;
					}
				} else {
					write_to_console('Crypt already searched');
				}
			} else {
				write_to_console('There is no crypt');
			}
		}
		
		function search_corpse(){

			if (event_not_resolved()) { return; }
			
			var curr_hero_chamber = hero_map[hero1.x*100 + hero1.y];
			
			if (parseInt(curr_hero_chamber.corpse) > 0) {
				if (parseInt(curr_hero_chamber.corpse_searched) < 1) {
					if (confirm("Do you want to search the corpse?")) {
						// search the corpse
						curr_hero_chamber.corpse_searched = '1';
						write_to_console('You searched the corpse');
						update_timer();
					} else {
						return false;
					}
				} else {
					write_to_console('Corpse already searched');
				}
			} else {
				write_to_console('There is no corpse');
			}
		}
		
		var ferrox_monster = new Ferrox();
		
		function fight_ferrox() {
			
			if (event_not_resolved()) { return; }
			
			$('.ferrox_dice_placeholder').append($('.mini_dice_sheet'));
			$('#ferrox_modal').modal('toggle');

			$('#fight_ferrox_button').attr("disabled", false);
			
			$('.ferrox_attack_text').css("background-color", "white");
			$('.ferrox_attack_text').css("color", "black");
			$('.ferrox_attack_text').html("Test your Strength [ "+hero1.strength+" ] against Ferrox");

			ferrox_dead = false;
			ferrox_monster = new Ferrox();
			ferrox_monster.health = roll_ferrox_dice(1);
			
			$('.dice_total').css('background-color', '#D1001C');
			$('.dice_total').css('color', '#FFFFFF');
			
			write_to_ferrox_battle_console("The Ferrox has "+ferrox_monster.health+" health");
			write_to_ferrox_battle_console("A Ferrox appears and attacks you!!");
			write_to_console("The Ferrox has "+ferrox_monster.health+" health");
			write_to_console("A Ferrox appears and attacks you!!");
			
			draw_ferrox_stats("ferrox", ferrox_monster);
			
		}
		
		function write_to_ferrox_battle_console(p_Message) {
			$('.ferrox_battle_console').prepend(Math.abs(timer-max_timer) + ': ' + p_Message + '<br/>');
		}
		
		function draw_ferrox_stats(p_monster_name, p_monster) {
					
			$('.'+p_monster_name+'_name').html(p_monster.name);
			$('.'+p_monster_name+'_name_short').html(p_monster.name_short);
			$('.'+p_monster_name+'_health').html(p_monster.health);
			$('.'+p_monster_name+'_strength').html('?');
			$('.'+p_monster_name+'_agility').html('?');
			$('.'+p_monster_name+'_defense').html('?');
			$('.'+p_monster_name+'_luck').html('?');
			$('.'+p_monster_name+'_description').html(p_monster.description);
			$('.'+p_monster_name+'_img').attr("src", "/dungeonquest/monsters/"+p_monster.image_url);
			
		}
		
		function search_room(){
			
			//chamber type
			//0 starting
			//1 chamber
			//2 corridor
			//3 bridge
			//4 pit
			//5 rotating
			//6 left chasm
			//7 right chasm
			//8 catacombs
			//9 trap
			//10 darkness
			//11 spiderweb
			//12 cavein
			//99 treasure

			if (event_not_resolved()) { return; }
		
			var curr_hero_chamber = hero_map[hero1.x*100 + hero1.y];
			
			if (curr_hero_chamber.type != 0 && 
				curr_hero_chamber.type != 2 && 
				curr_hero_chamber.type != 3 && 
				curr_hero_chamber.type != 4 && 
				curr_hero_chamber.type != 9 && 
				curr_hero_chamber.type != 10 && 
				curr_hero_chamber.type != 11 && 
				curr_hero_chamber.type != 12 && 
				curr_hero_chamber.type != 99) {
					
				if (parseInt(curr_hero_chamber.searched) < 1) {
					if (confirm("Do you want to search the room?")) {
						// search the room
						curr_hero_chamber.searched = '1';
						write_to_console('You searched the room');
						
						// draw random room
						var randomNumber;
						var randomDraw;
						
						randomNumber = Math.floor(Math.random() * 70+1);
						randomDraw = SearchJSON[randomNumber];
						write_to_console(randomNumber);
							
						do {
							randomNumber = Math.floor(Math.random() * 70+1);
							randomDraw = SearchJSON[randomNumber];
						} while (randomDraw == undefined);
						
						
						switch (randomDraw.type) {
							case "door":
								write_to_console('You find a Secret Door, you may move to any adjacent space');
								curr_hero_chamber.secret_door = '1';
								break;
							case "empty":
								write_to_console('The room is empty');
								break;
							case "item":
								write_to_console('You find an item');
								toggle_alert_modal('success', 'Search','','You find an Item');
								break;
							case "centipede":
								randomNumber = roll_dice(2);
								$('.dice_total').css('background-color', '#D1001C');
								$('.dice_total').css('color', '#FFFFFF');
								write_to_console('A Giant Centipede attacks you!!');
								write_to_console('['+randomNumber+'] You suffered '+randomNumber+' wounds!!');
								toggle_alert_modal('danger', 'A Giant Centipede Attacks You!!!','','You suffered '+randomNumber+' wounds!!');
								update_hero_health(-randomNumber);
								check_hero_health();
								break;
							case "ferrox":
								fight_ferrox();
								break;
							case "trap":
								write_to_console('You sprung a Trap!!');
								trigger_trap("");
								break;
						}
						
						update_timer();
					} else {
						return false;
					}
				} else {
					write_to_console('Room already searched');
					toggle_alert_modal('secondary', 'Search','','The room has already been searched');
				}
			} else {
				write_to_console("There is nothing to search here");
			}
		}
		
		function enter_catacombs(){
			
			// Catacombs not implemented, you die
			toggle_alert_modal('danger','Catacombs','','Catacombs not implemented.  You die!!!');
		}
		
		function search_dragon_chamber(){
			
			if (event_not_resolved()) { return; }

			var randomNumber;
			var randomDraw;
			
			if (debug || hero_map[hero1.x*100 + hero1.y].type == '99') {
			
				if (confirm("Do you want to loot the treasure room?")) {
					
					do {
						randomNumber = Math.floor(Math.random() * 8+1);
						randomDraw = DragonJSON[randomNumber];
					} while (randomDraw == undefined)
					
					if (String(randomDraw.awake) == '0') {
						
						// The Dragon is Sleeping
						write_to_console('The dragon sleeping');
						do {
							randomNumber = Math.floor(Math.random() * 100+1);
							randomDraw = TreasureJSON[randomNumber];
						} while (randomDraw == undefined)
							
						//hero_loot[randomNumber] = randomDraw;
						hero_loot.push(new LootItem(Math.abs(timer-max_timer), randomDraw.id, randomDraw.name, randomDraw.value, '', ''));
						draw_loot_bag();
						write_to_console('You find ' + randomDraw.name);
						
						do {
							randomNumber = Math.floor(Math.random() * 100+1);
							randomDraw = TreasureJSON[randomNumber];
						} while (randomDraw == undefined)
							
						//hero_loot[randomNumber] = randomDraw;
						hero_loot.push(new LootItem(Math.abs(timer-max_timer), randomDraw.id, randomDraw.name, randomDraw.value, '', ''));
						draw_loot_bag();
						write_to_console('You find ' + randomDraw.name);
					} else {
						
						// The Dragon Attacks
						randomNumber = roll_dice(2);
						$('.dice_total').css('background-color', '#D1001C');
						$('.dice_total').css('color', '#FFFFFF');
						write_to_console('The Dragon attacks you!!!');
						write_to_console('['+randomNumber+'] You suffered '+randomNumber+' wounds!!!');
						toggle_alert_modal('danger', 'The Dragon Attacks You!!!','/dungeonquest/dragon_rage.jpg','You suffered '+randomNumber+' wounds!!!');
						update_hero_health(-randomNumber);
						check_hero_health();
						
						
					}
					
					update_timer();
				}
				
			} else {
				alert("There is no treasure");
			}

		}
		
		function update_hero_health(p_health) {
			hero1.health = parseInt(hero1.health) + parseInt(p_health);
			draw_hero_stats('hero1', hero1);
		}
		
		function update_ferrox_health(p_health) {
			ferrox_monster.health = parseInt(ferrox_monster.health) + parseInt(p_health);
			draw_ferrox_stats('ferrox', ferrox_monster);
			
		}
		
		function check_hero_health(){
			if (parseInt(hero1.health) <= 0) {
				write_to_console("You have died!!!");
				game_over = true;
				toggle_ending_modal('death', 'Game Over','','You succumbed to your wounds!!!');
			}
		}
		
		function toggle_alert_modal(p_modal_type, p_modal_title, p_modal_image, p_modal_text){
			
			$('.mini_dice_sheet').hide();
			
			$('#alert_modal_title').text(p_modal_title);
			$('#alert_modal_text').text(p_modal_text);
			
			if (p_modal_image == '') {
				$('#alert_modal_image').hide();
			} else {
				$('#alert_modal_image').attr("src", p_modal_image);
				$('#alert_modal_image').show();
			}
					
			switch (p_modal_type) {
				case "normal":
					$('#alert_modal_text').css("background-color", "white");
					$('#alert_modal_text').css("color", "black");
					break;
				case "secondary":
					$('#alert_modal_text').css("background-color", "#DDDDDD");
					$('#alert_modal_text').css("color", "black");
					break;
				case "success":
					$('#alert_modal_text').css("background-color", "#00FF00");
					$('#alert_modal_text').css("color", "white");
					break;
				case "darkness":
					$('#alert_modal_text').css("background-color", "black");
					$('#alert_modal_text').css("color", "white");
					break;
				case "danger":
					$('#alert_modal_text').css("background-color", "#D1001C");
					$('#alert_modal_text').css("color", "white");
					break;
				case "gameover":
					$('#alert_modal_text').css("background-color", "gray");
					$('#alert_modal_text').css("color", "white");
					break;
			}
			$('#alert_modal').modal('toggle');
			
		}
		
		function toggle_ending_modal(p_modal_type, p_modal_title, p_modal_image, p_modal_text){
			
			$('#ending_modal').modal('toggle');
			$('#ending_modal_title').text(p_modal_title);
			$('#ending_modal_text').text(p_modal_text);
			
			if (p_modal_image == '') {
				$('#ending_modal_image').hide();
			} else {
				$('#ending_modal_image').attr("src", p_modal_image);
				$('#ending_modal_image').show();
			}
					
			switch (p_modal_type) {
				case "death":
					$('#ending_modal_text').css("background-color", "#D1001C");
					$('#ending_modal_text').css("color", "white");
					break;
				case "monster_death":
					$('#ending_modal_text').css("background-color", "#D1001C");
					$('#ending_modal_text').css("color", "white");
					break;
			}
		}
		
		function draw_loot_bag() {
			var total_gold = 0;
			$('.loot_console').empty();
			for (var i = 0; i < hero_loot.length; i++) {
				total_gold += parseInt(hero_loot[i].value);
				write_to_loot_console(i, hero_loot[i]);
			}
			$('.total_gold').html(" - " + total_gold + " Gold");
		}
		
		function trigger_trap(p_trap_type) {
			
			if (event_not_resolved()) { return; }
			
			var randomNumber;
			var randomDraw;
			
			randomNumber = Math.floor(Math.random() * 7+1);
			randomDraw = TrapsJSON[randomNumber];
			
			trap_triggered_type = randomDraw.type;
			
			$('#trap_modal_title').text("You Triggered a Trap!");
			
			if (trap_triggered_type == "collapse") {
				
				$('.trap_text').css("background-color", "#D1001C");
				$('.trap_text').css("color", "white");
				$('.trap_text').html("The Ceiling Collapsed!!<br/>You sense the room rotated!!");
				
				toggle_alert_modal('danger', 'The Ceiling Collapsed', '', 'The Ceiling Collapsed.  New passages are revealed while others are blocked by debris!!');
				
				var current_hero_map = hero_map[hero1.x*100 + hero1.y];
				current_hero_map.orientation += 90;
				
				var temp_top = current_hero_map.top;
				current_hero_map.top = current_hero_map.left;
				current_hero_map.left = current_hero_map.bottom;
				current_hero_map.bottom = current_hero_map.right;
				current_hero_map.right = temp_top;
				
				$('#dungeon_cell_'+hero1.x+1+'_'+hero1.y).css("transform", "rotate("+hero_map[hero1.x*100 + hero1.y].orientation+"deg)");
				update_mini_game_board();
				update_medium_game_board();
				
				return;
				
			} else if (trap_triggered_type == "explosion") {
				
				update_hero_health(-4);
				update_timer();
				
				if (parseInt(hero1.health) <= 0) {
					write_to_console("You suffer 4 wounds!!");
					write_to_console('You are killed by an Explosion!!');
					toggle_alert_modal('danger', 'Explosion', '', 'You are killed by an Explosion!!');
				} else {
					write_to_console("You suffer 4 wounds!!");
					write_to_console('You are wounded by an Explosion!!');
					toggle_alert_modal('danger', 'Explosion', '', 'You are wounded by an Explosion!! You suffer 4 wounds');
				}
				
				return;
				
			}
			
			trap_triggered = true;
			
			$('.trap_hero_sheet_placeholder').append($('.mini_hero_sheet'));
			

			$('.mini_dice_placeholder').attr("onclick", "").unbind("click");
			$(".mini_dice_placeholder").on('click', function(){ roll_trap_dice(); });
			
			$('.dice_total').css('background-color', '#D1001C');
			$('.dice_total').css('color', '#FFFFFF');
			
			$('#trap_button').attr("disabled", false);
			
			$('.trap_character_sheet').empty();
			$('.trap_character_sheet').append($('.mini_hero_sheet'));
			
			$('#trap_modal').modal('toggle');
			
			
			switch(trap_triggered_type) {
				case "trapdoor":
					$('.trap_text').css("background-color", "#D1001C");
					$('.trap_text').css("color", "white");
					$('.trap_text').html("A Trap Door opens under you. <br/> Test Agility [ "+hero1.agility+" ]");
					break;
				case "snakes":
					$('.trap_text').css("background-color", "#D1001C");
					$('.trap_text').css("color", "white");
					$('.trap_text').html("You are bitten by a Poisonous Snake!!");
					break;
				case "gas":
					$('.trap_text').css("background-color", "#D1001C");
					$('.trap_text').css("color", "white");
					$('.trap_text').html("You see Poisonous Gas pouring into the room!!");
					break;
				case "collapse":
					// handled above
					return;
					break;
				case "explosion":
					// handled above
					return;
					break;
				case "blade":
					$('.trap_text').css("background-color", "#D1001C");
					$('.trap_text').css("color", "white");
					$('.trap_text').html("A Swinging Blade falls at you!! <br/>Test Armor [ "+hero1.defense+" ]");
					break;
				case "crossfire":
					$('.trap_text').css("background-color", "#D1001C");
					$('.trap_text').css("color", "white");
					$('.trap_text').html("Arrows are shot at you from the walls!! <br/>Test Armor [ "+hero1.defense+" ]");
					break;
				case "spear":
					$('.trap_text').css("background-color", "#D1001C");
					$('.trap_text').css("color", "white");
					$('.trap_text').html("You are stabbed from a spear trap!!");
					write_to_console("You are stabbed from a spear trap!!");
					break;
			}
			
			
		}
		
		function roll_trap_dice() {
			
			var dice1;
			var dice2;
			var total = 0;
				
			//passage legend
			//1 open
			//2 wall
			//3 door
			//4 bridge
			//5 portcullis
			//6 pit
			//7 cave-in/rubble
			//8 spiderweb
			//9 darkness
			
			switch(trap_triggered_type) {
					
				case "snakes":
				case "gas":
				case "spear":
				case "darkness":
				case "speardoor":
				
					dice1 = Math.floor(Math.random() * 6 + 1);
					mini_dice.dataset.side = dice1;
					mini_dice.classList.toggle("reRoll");
					break;
				
				case "trapdoor":
				case "blade":
				case "crossfire":
				case "bridge":
				case "portcullis":
				case "pit":
				case "rubble":
				default:
				
					dice1 = Math.floor(Math.random() * 6 + 1);
					mini_dice.dataset.side = dice1;
					mini_dice.classList.toggle("reRoll");
					
					dice2 = Math.floor(Math.random() * 6 + 1);
					mini_dice_2.dataset.side = dice2;
					mini_dice_2.classList.toggle("reRoll");
					break;
			}
			
			if (!trap_triggered || hero1.health <= 0) {
				return;
			}
			
			write_to_console("trap_triggered_type: " + trap_triggered_type);
			
			switch(trap_triggered_type) {
				case "trapdoor":
					
					total = dice1 + dice2;
					$('.dice_total').html(total);
					
					if (parseInt(total) <= parseInt(hero1.agility)) {
						write_to_console('['+total+'] You narrowly avoided the Trap Door!!');
						$('.dice_total').css("background-color", "#00FF00");
						$('.dice_total').css("color", "white");
						$('.trap_text').css("background-color", "#00FF00");
						$('.trap_text').html("You narrowly avoided the Trap Door!!");
					} else {
						write_to_console('['+total+'] [Catacombs not Implemented] You fell into the trapdoor and died!!');
						$('.dice_total').css("background-color", "#D1001C");
						$('.dice_total').css("color", "white");
						$('.trap_text').css("background-color", "#D1001C");
						$('.trap_text').html("You fell into the trapdoor and died!!");
						update_hero_health(-hero1.health);
						game_over = true;
						
					}
				
					break;
				case "snakes":
					
					total = dice1;
					$('.dice_total').html(total);
					
					write_to_console('['+total+'] You are bitten by a Poisonous Snake!!');
					$('.dice_total').css("background-color", "#D1001C");
					$('.dice_total').css("color", "white");
					$('.trap_text').html("You suffer "+total+" wounds");
					update_hero_health(-total);
					if (hero1.health <= 0) {
						write_to_console('['+total+'] You are killed by the Poisonous Snake!!');
						$('.trap_text').html("You are killed by the Poisonous Snake!!");
					}
				
					break;
				case "gas":
					
					total = dice1;
					$('.dice_total').html(total);
					
					write_to_console('['+total+'] You see Poisonous Gas pouring into the room!!');
					$('.dice_total').css("background-color", "#D1001C");
					$('.dice_total').css("color", "white");
					
					if (total > 3) {
						
						$('.trap_text').html("You suffer "+ eval(total - 3) +" wounds and lose time");
						
						write_to_console('You suffer '+ eval(total - 3) +' wounds');
						write_to_console('You lose time [ '+ eval(total - 3) +' ]');
						
						update_hero_health(-eval(total - 3));
						
						for (var i = 0; i < total - 3; i++) {
							update_timer();
						}
						
						if (hero1.health <= 0) {
							write_to_console('['+total+'] You are killed by the Poisonous Gas!!');
							$('.trap_text').html("You are killed by the Poisonous Gas!!");
						}
					} else {
						write_to_console('['+total+'] You avoided the Poisonous Gas!!');
						$('.dice_total').css("background-color", "#00FF00");
						$('.dice_total').css("color", "white");
						$('.trap_text').css("background-color", "#00FF00");
						$('.trap_text').css("color", "white");
						$('.trap_text').html("You avoided the Poisonous Gas!!");
					}
					
					break;
				case "explosion":
					// handled above
					break;
				case "blade":
					
					total = dice1 + dice2;
					$('.dice_total').html(total);
					
					if (parseInt(total) <= parseInt(hero1.defense)) {
						write_to_console('['+total+'] Your armor protected you from the Swinging Blade!!');
						$('.dice_total').css("background-color", "#00FF00");
						$('.dice_total').css("color", "white");
						$('.trap_text').css("background-color", "#00FF00");
						$('.trap_text').html("Your armor protected you from the Swinging Blade!!");
					} else {
						write_to_console('['+total+'] You are killed by the Swinging Blade!!');
						$('.dice_total').css("background-color", "#D1001C");
						$('.dice_total').css("color", "white");
						$('.trap_text').html("You are killed by the Swinging Blade!!");
						update_hero_health(-hero1.health);
						game_over = true;
					}
					
					break;
				case "crossfire":
					
					total = dice1 + dice2;
					$('.dice_total').html(total);
					
					if (parseInt(total) <= parseInt(hero1.defense)) {
						write_to_console('['+total+'] You evaded the Arrows!!');
						$('.dice_total').css("background-color", "#00FF00");
						$('.dice_total').css("color", "white");
						$('.trap_text').css("background-color", "#00FF00");
						$('.trap_text').html("You evaded the Arrows!!");
						
					} else {
						var wounds = parseInt(total) - parseInt(hero1.defense);
						write_to_console('['+total+'] You suffer '+wounds+' wounds!!');
						write_to_console('['+total+'] You are wounded by the Arrows!!');
						$('.dice_total').css("background-color", "#D1001C");
						$('.dice_total').css("color", "white");
						$('.trap_text').html("You are wounded by the Arrows!!<br/>You suffer "+wounds+" wounds!!");
						update_hero_health(-wounds);
						if (hero1.health <= 0) {
							write_to_console('['+total+'] You are killed by Arrows!!');
							$('.trap_text').html("You are killed by Arrows!!");
						}
					}
					break;
					
				case "door":
				
					break;
					
				case "opendoor":
					
					total = dice1;
					$('.dice_total').html(total);
					encounter_queue[0].resolved = '1';
					encounter_queue[0].success = '1';
					
					write_to_console('['+total+'] You open the Door');
					$('.dice_total').css("background-color", "#000000");
					$('.dice_total').css("color", "black");
					$('.trap_text').html("You open the Door");
				
					break;
					
				case "jammeddoor":
					
					total = dice1;
					$('.dice_total').html(total);
					encounter_queue[0].resolved = '1';
					encounter_queue[0].success = '0';
					
					write_to_console('['+total+'] The Door is jammed!!');
					$('.dice_total').css("background-color", "#D1001C");
					$('.dice_total').css("color", "white");
				
					break;
					
				case "speardoor":
					
					total = dice1;
					$('.dice_total').html(total);
					
					//encounter_queue[0].resolved = '1';
					//encounter_queue[0].success = '0';
					
					write_to_console('['+total+'] You are stabbed by a Spear!!');
					$('.dice_total').css("background-color", "#D1001C");
					$('.dice_total').css("color", "white");
					$('.trap_text').html("You suffer "+total+" wounds");
					write_to_console('You suffer '+total+' wounds');
					update_hero_health(-total);
					if (hero1.health <= 0) {
						write_to_console('['+total+'] You are killed by the Spear!!');
						$('.trap_text').html("You are killed by the Spear!!");
					}
				
					break;
					
				case "bridge":
					
					total = dice1 + dice2;
					$('.dice_total').html(total);
					
					var hero_agility = hero1.agility - loot_weight();
					if (total <= hero_agility) {
						encounter_queue[0].resolved = '1';
						encounter_queue[0].success = '1';
						write_to_console('['+total+'] You crossed the Bridge!!');
						$('.dice_total').css("background-color", "#00FF00");
						$('.dice_total').css("color", "white");
						$('.trap_text').html("You crossed the Bridge");
					} else {
						encounter_queue[0].resolved = '1';
						encounter_queue[0].success = '0';
						$('.dice_total').css("background-color", "#D1001C");
						$('.dice_total').css("color", "white");
						// update_hero_health(-total);
						// if (hero1.health <= 0) {
							// write_to_console('['+total+'] You fall off the Bridge!!');
							// $('.trap_text').html("You fall off the Bridge!!<br/>[ Catacombs not implemented ]");
							// game_over = true;
						// }
					}
					
					break;
					
				case "portcullis":
					
					total = dice1 + dice2;
					$('.dice_total').html(total);
					
					if (total <= hero1.strength) {
						encounter_queue[0].resolved = '1';
						encounter_queue[0].success = '1';
						write_to_console('['+total+'] You raised the Portcullis!!');
						$('.dice_total').css("background-color", "#00FF00");
						$('.dice_total').css("color", "white");
						$('.trap_text').html("You raised the Portcullis!!");
					} else {
						encounter_queue[0].resolved = '1';
						encounter_queue[0].success = '0';
						$('.dice_total').css("background-color", "#D1001C");
						$('.dice_total').css("color", "white");
							write_to_console('['+total+'] You failed to raise the Portcullis!!');
							$('.trap_text').html("You failed to raise the Portcullis!!");
					}
					
					break;
					
				case "pit":
					
					total = dice1 + dice2;
					$('.dice_total').html(total);
					
					write_to_console('encounter_queue.length: '+encounter_queue.length);
					
					if (total <= hero1.luck) {
						encounter_queue[0].resolved = '1';
						encounter_queue[0].success = '1';
						write_to_console('['+total+'] You jumped over the Pit!!');
						$('.dice_total').css("background-color", "#00FF00");
						$('.dice_total').css("color", "white");
						$('.trap_text').html("You jumped over the Pit");
					} else {
						encounter_queue[0].resolved = '1';
						encounter_queue[0].success = '0';
						$('.dice_total').css("background-color", "#D1001C");
						$('.dice_total').css("color", "white");
						update_hero_health(-hero1.health);
						if (hero1.health <= 0) {
							write_to_console('['+total+'] You fell into the Pit and died!!');
							$('.trap_text').html("You fell into the Pit and died!!");
							game_over = true;
						}
					}
					
					break;
					
				case "rubble":
					
					total = dice1 + dice2;
					$('.dice_total').html(total);
					
					if (total <= hero1.agility) {
						encounter_queue[0].resolved = '1';
						encounter_queue[0].success = '1';
						write_to_console('['+total+'] You found passage through the Rubble!!');
						$('.dice_total').css("background-color", "#00FF00");
						$('.dice_total').css("color", "white");
						$('.trap_text').html("You found passage through the Rubble");
					} else {
						encounter_queue[0].resolved = '1';
						encounter_queue[0].success = '0';
						$('.dice_total').css("background-color", "#D1001C");
						$('.dice_total').css("color", "white");
							write_to_console('['+total+'] You failed to find passage through the Rubble!!');
							$('.trap_text').html("You failed to find passage through the Rubble!!");
					}
					
					break;
					
				case "spiderweb":
					
					total = dice1 + dice2;
					$('.dice_total').html(total);
					
					if (total <= hero1.strength) {
						encounter_queue[0].resolved = '1';
						encounter_queue[0].success = '1';
						write_to_console('['+total+'] You moved through the Spider Web!!');
						$('.dice_total').css("background-color", "#00FF00");
						$('.dice_total').css("color", "white");
						$('.trap_text').html("You moved through the Spider Web");
					} else {
						encounter_queue[0].resolved = '1';
						encounter_queue[0].success = '0';
						$('.dice_total').css("background-color", "#D1001C");
						$('.dice_total').css("color", "white");
							write_to_console('['+total+'] You are stuck in the Spider Web!!');
							$('.trap_text').html("You are stuck in the Spider Web!!");
					}
					
					break;
					
					
				case "darkness":
					
					total = dice1;
					$('.dice_total').html(total);
					encounter_queue[0].resolved = '1';
					encounter_queue[0].success = '1';
					move_through_darkness(encounter_queue[0].direction, total);
					
					break;
			}
			
					
			$('#trap_button').attr("disabled", true);

			trap_triggered = false;
			trap_triggered_type = "";
			
			
		}
		
		function write_to_console(p_Message) {
			$('.console').prepend(Math.abs(timer-max_timer) + ': ' + p_Message + '<br/>');
		}
		
		function write_to_loot_console(p_item_index, p_loot) {
			$('.loot_console').append( '<span class="loot_item" onclick="drop_loot('+p_item_index+')">'+p_loot.timer+ ': ' + p_loot.name + ' (' +p_loot.value+' Gold)<br/></span>');
		}
		
		function drop_loot(p_item_index){
			
			for(var i = 0; i<hero_loot.length; i++){
				if (i == p_item_index){
					if (confirm("Do you want to drop: "+hero_loot[i].name)) {
						//alert(p_item_index+" "+hero_loot[i].name);
						hero_loot.splice(i, 1);
					}
				}
				
			}
			draw_loot_bag();
		}
		
		function loot_weight() {
			return (hero_loot.length);
		}
		
		function loot_total() {
			var total_gold = 0;
			for (var i = 0; i < hero_loot.length; i++) {
				total_gold += parseInt(hero_loot[i].value);
			}
			return total_gold;
		}
		
		function event_not_resolved() {
			
			if (hero1.health <= 0) {
				toggle_alert_modal('danger', 'You are Dead', '', 'Your corpse lies in the ruins of the Dungeon!!');
				return true;
			}
			
			if (game_over) {
				toggle_alert_modal('gameover', 'Game Over','','The game has ended');
				return true;
			}
			
			if (!ferrox_dead) {
				$('.ferrox_dice_placeholder').append($('.mini_dice_sheet'));
				$('#fight_ferrox_button').attr("disabled", false);
				$('#ferrox_modal').modal('toggle');
				return true;
			}
			
			if (encounter_queue.length > 0) {
				$('#trap_modal').modal('toggle');
				return true;
			}
			
			if (trap_triggered) {
				//$('.trap_dice_placeholder').append($('.mini_dice_sheet'));
				

				$('.mini_dice_placeholder').attr("onclick", "").unbind("click");
				$(".mini_dice_placeholder").on('click', function(){ roll_trap_dice(); });
				$('.trap_hero_sheet_placeholder').append($('.mini_hero_sheet'));
				
				$('#trap_button').attr("disabled", false);
				$('#trap_modal').modal('toggle');
				return true;
			}
			
			return false;
			
		}
		
		function print_encounter() {
			
			if (debug) {
				for (var i = 0; i < encounter_queue.length; i++) {
					write_to_console(encounter_queue[i].type + ", " + encounter_queue[i].title + ", " + encounter_queue[i].description_color + ", " + encounter_queue[i].description + ", " + encounter_queue[i].direction);
				}
			}
		}
		
		
	
		
	</script>
	<style type="text/css">
		body { text-align: center; margin: 5px;}
		
		
		.btn { display: inline-block; margin-top: 2px; margin-bottom: 2px; min-width: 70px;}
		.table-condensed>tbody>tr>td { padding: 5px 5px 0 5px; }
		.table>tbody>tr>td {}
		.table { margin-bottom: 0px; }
		h5 { text-align: left; margin: 5px; }
		
		.table>tbody>tr>td ,.table>tbody>tr>th { padding: 2px;}
		
		.character_selection_screen { text-align: center; }
		.character_selection { display: inline-block; margin: 5px; cursor: pointer; vertical-align: top; }
		.character_selection:hover { background-color: #EEEEEE }
		.character_table { width: 170px; }
		.character_sheet_image { width: 60%; style="padding: 2px;"}
		.character_image { width: 100%; }
		.character_sheet_stats { width: 20% }
		.character_sheet_values { width: 20% }
		.character_sheet_description { padding: 10px; font-style: italic; font-size: 12px; height: 80px; overflow-wrap: break-word; word-wrap: break-word; }
		
		
		.character_dashboard { display: inline-block; width: 300px; vertical-align: top; }
		.hero_table { width: 100%; }
		.timer_sheet { }
		.hero_description { padding: 5px; font-style: italic; font-size: 12px; }
		
		
		.hero_name_header { text-align: left; }
		
		.mini_hero_table { width: 300px; }
		.medium_hero_table { width: 350px; }
		
		.dice_sheet { position: relative; text-align: center; }
		.mini_dice_sheet { position: relative; text-align: center; }
		
		.dungeon_dashboard { display: inline-block; }
		.dungeon_board { display: inline-block; padding: 0px; margin: 0 auto; }
		.dungeon_row { display: block flex; padding: 0px; margin: 0px; vertical-align: middle; }
		.dungeon_cell { display: inline-block; padding: 0px; margin: 0px; vertical-align: middle; width: 60px; height: 60px; background-size: 100% 100%; }
		
		.mini_dungeon_board { display: inline-block; padding: 0px; margin: 0 auto; }
		.mini_dungeon_row { display: block; padding: 0px; margin: 0px; vertical-align: middle; }
		.mini_dungeon_cell { display: inline-block; padding: 0px; margin: 0px; vertical-align: middle; width: 60px; height: 60px; background-size: 100% 100%; }
		.mini_movement_controls { display: inline-block; }
		.mini_movement_buttons { display: block; }
		
		.medium_dungeon_board { display: inline-block; padding: 0px; margin: 0 auto; }
		.medium_dungeon_row { display: block; padding: 0px; margin: 0px; vertical-align: middle; }
		.medium_dungeon_cell { display: inline-block; padding: 0px; margin: 0px; vertical-align: middle; width: 60px; height: 60px; background-size: 100% 100%; }
		.medium_movement_controls { display: inline-block; }
		.medium_movement_buttons { display: block; }
		
		.movement_dashboard { width: 400px; margin: 20px;}
		.timer { display: inline-block; }
		.movement_controls { display: block; }
		.movement_buttons { display: inline-block; }
		
		
		
		.console_sheets { text-align: left; }
		.console_sheet { overflow-y:scroll; height: 100px; }
		.loot_sheet { overflow-y:scroll; height: 100px; }
		
		.mini_console_sheet { overflow-y:scroll; height: 80px; }
		.mini_loot_sheet { overflow-y:scroll; height: 80px; }
		
		.ferrox_console_sheet { overflow-y:scroll; height: 100px; } 
		.trap_console_sheet { overflow-y:scroll; height: 100px; } 
		
		
		.console { text-align: left; font-size: 12px; padding: 2px; }
		.loot_console { text-align: left; font-size: 12px; padding: 2px; }
		.ferrox_battle_console { text-align: left; font-size: 12px; padding: 2px; width: 100%;}
		
		.loot_item { cursor: pointer; }
		
		@media screen and (min-width: 769px) {
			.div-mobile { display: none; }
			.div-desktop { display: block; }
		}

		@media screen and (max-width: 768px) {
			.div-mobile { display: block; }
			.div-desktop { display: none; }
		}
		
	</style>
	
</head>
<body>

<div class="" style="">


<!-- Character Selection -->
<div class="character_selection_screen" id="character_selection_screen" style="display: none;">
	<h3><a href="/dungeonquest">DungeonQuest</a></h3><br/>
	<div id="character_selection_placeholder"></div>
	<p>The Dungeon is full of traps and danger.  But much treasure lies beyond.  Are you prepared for adventure?</p><br/>
</div>


<!-- Dungeon Quest Game (Deskto and Mobile) -->
<div class="game" id="game">

	<!-- Desktop View -->
	<div class="div-desktop">

		<!-- Character Dashboard -->
		<div class="character_dashboard" id="character_dashboard" style="margin-right: 10px;">

			<!-- Hero Sheet -->
			<div class="hero_sheet">
				<h4 class="hero_name_header"><span class="hero1_name">Name</span></h4>
				<table class="hero_table table table-condensed table-bordered">
				<tbody>
				<tr>
					<td rowspan="4" style="width: 30%; padding: 0px;">
					<img class="hero1_img character_image img img-responsive" src="" /></td>
					<td colspan="3"><span class="hero1_name_short">Name</span></td>
				</tr>
				<tr>
					<td style="width: 20%">Str <span class="hero1_strength"></span></td>
					<td style="width: 20%">❤️ <span class="hero1_health"></span></td>
					<td style="width: 20%">⏱️ <span class="timer"></span></td>
				</tr>
				<tr>
					<td style="width: 20%">Agi <span class="hero1_agility"></td>
					<td style="width: 20%">Def <span class="hero1_defense"></span></td>
					<td style="width: 20%">Luk <span class="hero1_luck"></span></td>
				</tr>
				<tr>
					<td colspan="3">

						<!-- Dice Sheet -->
						<div class="dice_placeholder" onclick="roll_dice(2)" style="margin: 10px 0 10px 0;">
						<div class="dice_sheet">	
							<div style="display: inline-block; position: relative; margin: 0 5px 0 5px;">
								<div id="dice" data-side="1">
								<div class="sides side-1"><span class="dot dot-1"></span></div>
								<div class="sides side-2"><span class="dot dot-1"></span><span class="dot dot-2"></span></div>
								<div class="sides side-3"><span class="dot dot-1"></span><span class="dot dot-2"></span><span class="dot dot-3"></span></div>
								<div class="sides side-4"><span class="dot dot-1"></span><span class="dot dot-2"></span><span class="dot dot-3"></span><span class="dot dot-4"></span></div>
								<div class="sides side-5"><span class="dot dot-1"></span><span class="dot dot-2"></span><span class="dot dot-3"></span><span class="dot dot-4"></span><span class="dot dot-5"></span></div>
								<div class="sides side-6"><span class="dot dot-1"></span><span class="dot dot-2"></span><span class="dot dot-3"></span><span class="dot dot-4"></span><span class="dot dot-5"></span><span class="dot dot-6"></span></div>
								</div>
							</div><div style="display: inline-block; vertical-align: top; position: relative; margin: 0 5px 0 5px;">
								<div id="dice_2" data-side="1">
								<div class="sides side-1"><span class="dot dot-1"></span></div>
								<div class="sides side-2"><span class="dot dot-1"></span><span class="dot dot-2"></span></div>
								<div class="sides side-3"><span class="dot dot-1"></span><span class="dot dot-2"></span><span class="dot dot-3"></span></div><div class="sides side-4">
								<span class="dot dot-1"></span><span class="dot dot-2"></span><span class="dot dot-3"></span><span class="dot dot-4"></span></div>
								<div class="sides side-5"><span class="dot dot-1"></span><span class="dot dot-2"></span><span class="dot dot-3"></span><span class="dot dot-4"></span><span class="dot dot-5"></span></div>
								<div class="sides side-6"><span class="dot dot-1"></span><span class="dot dot-2"></span><span class="dot dot-3"></span><span class="dot dot-4"></span><span class="dot dot-5"></span><span class="dot dot-6"></span></div>
								</div>
							</div><div style="display: inline-block; position: relative; vertical-align: top; margin: 0 5px 0 5px;">
								<div class="dice_total" style="text-align: center; width: 50px; height: 50px; font-size: 30px; border: solid 2px black;">12</div>
							</div>
						</div>
						</div>
					</td>
				</tr>
				</tbody>
				</table>
			</div>
			
			<!-- Console and Loot Sheet -->
			<div class="console_sheets">
				<h5>Loot <span class="total_gold"></span></h5>
				<div class="loot_sheet table-bordered">
					<div class="loot_console "></div>
				</div>
				<h5>Console</h5>
				<div class="console_sheet table-bordered">
					<div class="console"></div>
				</div>
			</div>
			
			
		</div>

		<!-- Dungeon Board -->
		<div class="dungeon_dashboard">
			<h4 class="">Dungeon Map</h4>
			<div class="dungeon_board" style="display: none;"></div>
			<div class="medium_dungeon_board" id="medium_dungeon_board"></div>
			
			<!-- Control Buttons -->
			<div class="movement_dashboard" id="movement_dashboard">
				<div style="display: inline-block;"><input class="btn btn-primary" type="button" name="fight_button" value="Fight" onclick="fight_ferrox();" /></div>
				<div style="display: inline-block;"><input class="btn btn-primary" type="button" name="trap_button" value="Trap" onclick="trigger_trap('');" /></div>
				<div style="display: inline-block;"><input class="btn btn-primary" type="button" name="search_button" value="Search" onclick="search_room();" /></div>
				<div style="display: inline-block;"><input class="btn btn-primary" type="button" name="corpse_button" value="Corpse" onclick="search_corpse();" /></div>
				<div style="display: inline-block;"><input class="btn btn-primary" type="button" name="crypt_button" value="Crypt" onclick="search_crypt();" /></div>
				<div style="display: inline-block;"><input class="btn btn-primary" type="button" name="treasure_button" value="Loot" onclick="search_dragon_chamber();" /></div>
				<div style="display: inline-block;"><input class="btn btn-primary" type="button" name="catacombs_button" value="Catacombs" onclick="enter_catacombs();" /></div>
				<div style="display: inline-block;"><input class="btn btn-primary" type="button" name="quit_button" id="quit_button" value="Quit" onclick="quit_game();" /></div>
			</div>
		</div>
	</div>

	<!-- Mobile View -->
	<div class="div-mobile">

		<!-- Mobile Character Dashboard -->
		<div class="character_dashboard" id="character_dashboard" style="display: inline-block;">

			<!-- Mobile Hero Sheet -->
			<div class="mini_hero_sheet_placeholder">
			<div class="mini_hero_sheet" style="display: inline-block; width: 300px;">
				<div style="width: 300px">
					<table class="mini_hero_table table table-condensed table-bordered">
					<tbody>
					<tr>
						<td rowspan="4" style="width: 30%; padding: 0px;">
						<img class="hero1_img character_image img img-responsive" src="" /></td>
						<td colspan="3"><span class="hero1_name_short">Name</span></td>
					</tr>
					<tr>
						<td style="width: 20%">Str <span class="hero1_strength"></span></td>
						<td style="width: 20%">❤️ <span class="hero1_health"></span></td>
						<td style="width: 20%">⏱️ <span class="timer"></span></td>
					</tr>
					<tr>
						<td style="width: 20%">Agi <span class="hero1_agility"></td>
						<td style="width: 20%">Def <span class="hero1_defense"></span></td>
						<td style="width: 20%">Luk <span class="hero1_luck"></span></td>
					</tr>
					<tr>
						<td colspan="3">
							<!-- Mobile Dice Sheet -->
							<div class="mini_dice_placeholder" style="margin: 2px 0 2px 0;" onclick="roll_dice(2)">
							<div class="mini_dice_sheet">	
								<div style="display: inline-block; position: relative; margin: 0 5px 0 5px;">
									<div id="mini_dice" data-side="1">
									<div class="sides side-1"><span class="dot dot-1"></span></div>
									<div class="sides side-2"><span class="dot dot-1"></span><span class="dot dot-2"></span></div>
									<div class="sides side-3"><span class="dot dot-1"></span><span class="dot dot-2"></span><span class="dot dot-3"></span></div>
									<div class="sides side-4"><span class="dot dot-1"></span><span class="dot dot-2"></span><span class="dot dot-3"></span><span class="dot dot-4"></span></div>
									<div class="sides side-5"><span class="dot dot-1"></span><span class="dot dot-2"></span><span class="dot dot-3"></span><span class="dot dot-4"></span><span class="dot dot-5"></span></div>
									<div class="sides side-6"><span class="dot dot-1"></span><span class="dot dot-2"></span><span class="dot dot-3"></span><span class="dot dot-4"></span><span class="dot dot-5"></span><span class="dot dot-6"></span></div>
									</div>
								</div><div style="display: inline-block; vertical-align: top; position: relative; margin: 0 5px 0 5px;">
									<div id="mini_dice_2" data-side="1">
									<div class="sides side-1"><span class="dot dot-1"></span></div>
									<div class="sides side-2"><span class="dot dot-1"></span><span class="dot dot-2"></span></div>
									<div class="sides side-3"><span class="dot dot-1"></span><span class="dot dot-2"></span><span class="dot dot-3"></span></div><div class="sides side-4">
									<span class="dot dot-1"></span><span class="dot dot-2"></span><span class="dot dot-3"></span><span class="dot dot-4"></span></div>
									<div class="sides side-5"><span class="dot dot-1"></span><span class="dot dot-2"></span><span class="dot dot-3"></span><span class="dot dot-4"></span><span class="dot dot-5"></span></div>
									<div class="sides side-6"><span class="dot dot-1"></span><span class="dot dot-2"></span><span class="dot dot-3"></span><span class="dot dot-4"></span><span class="dot dot-5"></span><span class="dot dot-6"></span></div>
									</div>
								</div><div style="display: inline-block; position: relative; vertical-align: top; margin: 0 5px 0 5px;">
								<div class="dice_total" style="text-align: center; width: 50px; height: 50px; font-size: 30px; border: solid 2px black;">12</div>
								</div>
							</div>
							</div>
						</td>
					</tr>
					</tbody>
					</table>
				</div>
			</div>
			</div>
			<!-- Control Buttons -->
			<div class="mini_movement_dashboard" id="mini_movement_dashboard" style="display: inline-block; width: 300px; text-align: center; ">
				<input class="btn btn-primary" type="button" name="search_button" value="Search" onclick="search_room();" />
				<input class="btn btn-primary" type="button" name="fight_button" value="Fight" onclick="fight_ferrox();" />
				<input class="btn btn-primary" type="button" name="corpse_button" value="Corpse" onclick="search_corpse();" />
				<input class="btn btn-primary" type="button" name="crypt_button" value="Crypt" onclick="search_crypt();" />
				<input class="btn btn-primary" type="button" name="treasure_button" value="Loot" onclick="search_dragon_chamber();" />
				<input class="btn btn-primary" type="button" name="quit_button" id="quit_button" value="Quit" onclick="quit_game();" />
			</div>
		</div>
		<!-- Mobile Dungeon Board -->
		<div class="dungeon_dashboard">
			<div>
				<div class="mini_dungeon_board" id="mini_dungeon_board"></div>
			</div>
		</div>
		<!-- Mobile Console and Loot Sheet -->
		<div style="display: inline-block; margin-top: 5px; width: 100%;">
			<div class="mini_console_sheet table-bordered" style="display: inline-block; width: 290px; margin-bottom: 5px;">
				<div class="console mini_console"></div>
			</div>
			<div class="mini_loot_sheet table-bordered" style="display: inline-block; width: 290px; margin-bottom: 5px;">
				<div class="loot_console "></div>
			</div>
		</div>
	</div>
</div>

</div>

		
<!-- Monster Sheet -->	
<div style="display: none;"><h4 class="monster_name_header"><span class="monster_name">Name</span></h4></div>
<div class="monster_sheet" id="monster_sheet" style="display: none;">
	<table class="monster_table table table-sm table-bordered">
		<tbody>
		<tr><td rowspan="5" style="width: 70%; padding: 0px;">
			<img class="monster_img img img-fluid" src="" /></td>
			<td class="col-sm-8" style="width: 15%">HP</td>
			<td class="col-sm-4" style="width: 15%"><span class="monster_health" /></td></tr>
		<tr><td>Str</td><td><span class="monster_strength" /></td></tr>
		<tr><td>Agi</td><td><span class="monster_agility" /></td></tr>
		<tr><td>Def</td><td><span class="monster_defense" /></td></tr>
		<tr><td>Luk</td><td><span class="monster_luck" /></td></tr>
		<tr><td colspan="3" class="monster_description"></td></tr>
		</tbody>
	</table>
</div>
			
			

<!-- Dragon Modal -->
<div class="modal fade" id="dragon_modal" tabindex="-1" role="dialog" aria-labelledby="dragon_modal_label" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header" style="margin: 0px;">
        <h4 class="modal-title" id="dragon_modal_label" class="dragon_modal_label">The Dragon Attacks You!!!</h4>
      </div>
      <div class="modal-body" style="vertical-align: top; text-align: center;">
	  
			<div style="text-align: center;"><img class="img img-responsive" style="width: 100%; text-align: center;" src="/dungeonquest/dragon_rage.jpg" /></div>
			<div id="dragon_attack_text" class="" style="background-color: #D1001C; color: white; font-size: 40px;"></div>
      </div>
      <div class="modal-footer">
      </div>
    </div>
  </div>
</div>

<!-- Alert Modal-->
<div class="modal" id="alert_modal" tabindex="-1" role="dialog" aria-labelledby="alert_modal_label" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header" style="margin: 0px;">
        <h4 class="modal-title" id="alert_modal_title" class="alert_modal_label"></h4>
      </div>
      <div class="modal-body" style="vertical-align: top; text-align: center;">
			<div><img id="alert_modal_image" class="img img-responsive" style="width: 80%; margin: 20px auto;" src="" /></div>
			<div id="alert_modal_text" class="alert" style="margin: 20px auto; width: 250px;"></div>
      </div>
      <div class="modal-footer">
			<button type="submit" id="alert_modal_close_button" class="alert_modal_close_button btn btn-primary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

<!-- Ending Modal-->
<div class="modal" id="ending_modal" tabindex="-1" role="dialog" aria-labelledby="ending_modal_label" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header" style="margin: 0px;">
        <h4 class="modal-title" id="ending_modal_title" class="ending_modal_title"></h4>
      </div>
      <div class="modal-body" style="vertical-align: top; text-align: center;">
			<div><img id="ending_modal_image" class="img img-responsive" style="width: 80%; margin: 20px auto;" src="" /></div>
			<div id="ending_modal_text" class="alert" style="margin: 20px auto; width: 250px;"></div>
      </div>
      <div class="modal-footer">
			<button type="submit" id="ending_modal_close_button" class="btn btn-primary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

<!-- Ferrox Modal-->
<div class="modal" id="ferrox_modal" tabindex="-1" role="dialog" aria-labelledby="ferrox_modal_label" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header" style="margin: 0px;">
        <h4 class="modal-title" id="ferrox_modal_title" class="ferrox_modal_title">You Encountered a Monster!</h4>
      </div>
      <div class="modal-body" style="vertical-align: top; margin: 0 auto;">
			<div><img id="ferrox_modal_image" class="img img-responsive" style="width: 80%; margin: 20px auto; display: none;" src="" /></div>
			
			<!-- Ferrox Sheet -->
			<div class="ferrox_battle_sheet" style="">
				<table class="table-bordered table-condensed" style="margin: 0 auto; width: 300px;">
					<tr>
						<td rowspan="3" style="width: 30%" valign="top"><img class="ferrox_img img img-responsive" style="" src="" /></td>
						<td style="width: 50%;"><span class="ferrox_name"></span></td>
						<td style="width: 20%;">❤️ <span class="ferrox_health"></span></td>
					</tr>
					<tr>
						<td style="width: 50%;"><span class="hero1_name_short"></span></td>
						<td style="width: 20%;">❤️ <span class="hero1_health"></span></td>
					</tr>
					<tr>
						<td colspan="2">
							<div class="ferrox_dice_placeholder" style="cursor: pointer; margin: 5px 0 5px 0;" onclick="roll_ferrox_dice(2)"></div>
						</td>
					</tr>
				</table>
			</div>
			
			<div class="ferrox_attack_text alert" style="margin: 5px auto; width: 300px;"></div>
			
			<!-- Console and Loot Sheet -->
			<div class="battle_console_sheets" style="margin: 0 auto; width: 300px;">
				<div class="ferrox_console_sheet table-bordered" style="">
					<div class="ferrox_battle_console"></div>
				</div>
			</div>
			
      </div>
      <div class="modal-footer">
			<button type="button" id="fight_ferrox_button" class="fight_ferrox_button btn btn-primary" value="Fight" onclick="roll_ferrox_dice(2)">Fight</button>
			<button type="submit" id="ferrox_modal_close_button" class="btn btn-primary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

<!-- Trap Modal-->
<div class="modal" id="trap_modal" tabindex="-1" role="dialog" aria-labelledby="trap_modal_label" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-body" style="vertical-align: top; margin: 0 auto;">
		<!-- Trap Hero Sheet -->
		<div><img id="trap_modal_image" class="img img-responsive" style="width: 80%; margin: 20px auto; display: none;" src="" /></div>
		<!-- Trap Hero Sheet -->
		<div class="trap_hero_sheet_placeholder"></div>
		<!-- Trap Text -->
		<div class="trap_text alert" style="margin: 10px auto; width: 270px;"></div>
		<!-- Console and Loot Sheet -->
		<div class="trap_console_sheets" style="display: inline-block; margin: 0 auto; width: 300px;">
			<div class="trap_console_sheet table-bordered" style="">
				<div class="console" style="font-size: 12px; text-align: left;"></div>
			</div>
		</div>
      </div>
      <div class="modal-footer">
		<button type="button" id="trap_button" class="trap_button btn btn-primary" value="Trap" onclick="roll_trap_dice()">Roll Dice</button>
		<button type="submit" id="trap_modal_close_button" class="btn btn-primary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>


</body>
</html>