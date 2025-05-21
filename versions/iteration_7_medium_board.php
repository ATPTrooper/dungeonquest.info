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
		
		
		
		var hero1;
		
		var hero_map = {};
		var empty_map_orientation = {};
		var hero_loot = {};
		
		var boardgame_width = 13;
		var boardgame_height = 10;
		
		var mini_board_width = 5;
		var mini_board_height = 5;
		
		var medium_board_width = 7;
		var medium_board_width = 7;
		
		var enable_mini_board = true;
		var enable_medium_board = true;
		
		var max_timer = 100;
		
		var timer = max_timer;
		var game_over = false;
		
		var previous_direction = "";
		
		var image_directory = "/dungeonquest/second_edition/";
		
		$(document).ready(function() {
			
			$('#character_selection_screen').show();
			$('#game').hide();
			
			load_characters();
			create_game_board();
			
			
			// End of Battle
			$('#battle_modal').on('hidden.bs.modal', function () {
				$('#monster_battle_sheet').empty();
				$('#hero_battle_sheet').empty();
			})
			
			
	
		
			var container = document.getElementById("mini_dungeon_board");

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
			
			for (var i = boardgame_height; i > 0; i--){
				jQuery('<div/>', {
					id: 'dungeon_board_row_'+(i),
					'class': 'dungeon_row'
				}).appendTo('.dungeon_board');
				
				for (var j = 0; j < boardgame_width; j++) {
					jQuery('<div/>', {
						id: 'dungeon_board_cell_'+(j+1)+'_'+(i),
						'class': 'dungeon_cell',
					}).appendTo('#dungeon_board_row_'+(i));
					
					
					var random_empty_tile_number = Math.floor(Math.random() * 25 + 1);
					var random_empty_tile_direction = Math.floor(Math.random() * 4 + 1);
					
					$('#dungeon_board_cell_'+(j+1)+'_'+(i)).css("background-image", "url('"+image_directory+"empty_"+random_empty_tile_number+".jpg')");
					$('#dungeon_board_cell_'+(j+1)+'_'+(i)).css("transform", "rotate("+eval((random_empty_tile_direction-1)*90)+"deg)");
					
					empty_map_orientation[(j+1)*100 + i] = eval((random_empty_tile_direction-1)*90);
					
				}
			}
			
			if (enable_mini_board) {
				for (var i = 2; i > -3; i--){
					jQuery('<div/>', {
						id: 'mini_board_row_'+(i),
						'class': 'mini_dungeon_row'
					}).appendTo('#mini_dungeon_board');
					
					for (var j = -2; j < 3; j++) { 
						jQuery('<div/>', {
							id: 'mini_board_cell_'+(j)+'_'+(i),
							'class': 'mini_dungeon_cell',
						}).appendTo('#mini_board_row_'+(i));
					}
				}
			}
			
			if (enable_medium_board) {
				for (var i = 3; i > -4; i--){
					jQuery('<div/>', {
						id: 'medium_board_row_'+(i),
						'class': 'medium_dungeon_row'
					}).appendTo('#medium_dungeon_board');
					
					for (var j = -3; j < 4; j++) { 
						jQuery('<div/>', {
							id: 'medium_board_cell_'+(j)+'_'+(i),
							'class': 'medium_dungeon_cell',
						}).appendTo('#medium_board_row_'+(i));
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
			if (hero1.y == boardgame_height -1) { y_adjustment = -1; }
			if (hero1.y == boardgame_height) { y_adjustment = -2; }
			
			for (var i = -2; i < 3; i++) {
				for (var j = -2; j < 3; j++) {
					
					if (hero1 != undefined) {
						
						// copy background to mini map
						var background_url = $('#dungeon_board_cell_'+(hero1.x+i+x_adjustment)+'_'+(hero1.y+j+y_adjustment)).css('background-image');
						var background_rotation = empty_map_orientation[eval(hero1.x+i+x_adjustment+1)*100 + eval(hero1.y+j+y_adjustment)];
						
						//write_to_console("hero location: " + hero1.x + " " + hero1.y + " " + background_rotation);	
						
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
						
						//write_to_console("hero location: " + hero1.x + " " + hero1.y + " " + background_rotation);	
						
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
			
			hero_map = {};
			reset_game_board();
			
			remove_heros();
			draw_hero();
			update_timer();
			
		}
		
		function reset_game_board(){
			
			// for (var i = 0; i < boardgame_height; i++){				
				// for (var j = 0; j < boardgame_width; j++) {
					// $('#dungeon_board_cell_'+(j+1)+'_'+(i+1)).css('background-image','');
					// $('#dungeon_board_cell_'+(j+1)+'_'+(i+1)).empty();
				// }
			// }
			
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
			
			if (hero1 != undefined) {
				$('#dungeon_board_cell_'+(hero1.x)+'_'+(hero1.y)).append('<div style="height: 100%; width: 100%; background-size: 30%; background-position: center; background-repeat: no-repeat; background-image: url(http://www.ragnarok-online.info/images_icon/ui_HP_1.png)"></div>');
				
				
				
				// mini board adjustments
				var x_adjustment = 0;
				var y_adjustment = 0;
				
				if (hero1.x == 1) { x_adjustment = 2; }
				if (hero1.x == 2) { x_adjustment = 1; }
				if (hero1.x == boardgame_width - 1) { x_adjustment = -1; }
				if (hero1.x == boardgame_width) { x_adjustment = -2; }
				
				if (hero1.y == 1) { y_adjustment = 2; }
				if (hero1.y == 2) { y_adjustment = 1; }
				if (hero1.y == boardgame_height -1) { y_adjustment = -1; }
				if (hero1.y == boardgame_height) { y_adjustment = -2; }
				
				if (enable_mini_board) {
					$('#mini_board_cell_'+eval(0-x_adjustment)+'_'+eval(0-y_adjustment)+'').append('<div style="height: 100%; width: 100%; display: flex; background-size: 30%; background-position: center; background-repeat: no-repeat; background-image: url(http://www.ragnarok-online.info/images_icon/ui_HP_1.png)"></div>');
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
					$('#medium_board_cell_'+eval(0-x_adjustment)+'_'+eval(0-y_adjustment)+'').append('<div style="height: 100%; width: 100%; display: flex; background-size: 30%; background-position: center; background-repeat: no-repeat; background-image: url(http://www.ragnarok-online.info/images_icon/ui_HP_1.png)"></div>');
				}
			}
			
			
			switch(hero_map[hero1.x*100 + hero1.y].type) {
				case '3':
					write_to_console('You see a plank');
					break;
				case '4':
					write_to_console('You see a large pit');
					break;
				case '8':
					write_to_console('You see stairs leading into the catacombs');
					break;
				case '10':
					write_to_console('You see a room full of darkness');
					break;
				case '11':
					write_to_console('You see a room full of spider web');
					break;
				case '12':
					write_to_console('You see a caved-in room full of boulders');
					break;
			}
		}
		
		function reset_game() {
			timer = max_timer;
			game_over = false;
			select_character(hero1.name);
			clear_console();
			start_game();
		}
		
		function clear_console() {
			$('#console').empty();
			$('#console').append('<tbody><tr><th colspan="2">Console Log</th></tr></tbody>');
		}
		
		function quit_game() {
			reset_game();
			$('#character_selection_screen').show();
			$('#game').hide();
		}
		
		document.onkeydown = function(e) {
			
			//$('#dice_modal').modal('hide');
			
			
			switch(e.which) {

				case 38: // up
				move_hero('1');
				break;

				case 39: // right
				move_hero('2');
				break;

				case 40: // down
				move_hero('3');
				break;
				
				case 37: // left
				move_hero('4');
				break;

				default: return; // exit this handler for other keys
			}
			e.preventDefault(); // prevent the default action (scroll / move caret)
		};
		
		function move_hero(p_direction){
			
			if (hero1 != undefined && timer > 0 && !game_over) {
				
				var curr_hero_chamber = hero_map[hero1.x*100 + hero1.y];
				var next_hero_chamber;
				
				switch(p_direction) {
					case '1':
						next_hero_chamber = hero_map[hero1.x*100 + hero1.y+1];
						if (is_inside_board(hero1.x, hero1.y + 1)) {
							if (!is_facing_wall(curr_hero_chamber, p_direction) && !is_adjacent_to_wall(next_hero_chamber, '3')) {
								if (valid_movement(p_direction, curr_hero_chamber, next_hero_chamber)) { 
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
						if (is_inside_board(hero1.x + 1, hero1.y)) {
							if (!is_facing_wall(curr_hero_chamber, p_direction) && !is_adjacent_to_wall(next_hero_chamber, '4')) {
								if (valid_movement(p_direction, curr_hero_chamber, next_hero_chamber)) { 
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
						if (is_inside_board(hero1.x, hero1.y - 1)) {
							if (!is_facing_wall(curr_hero_chamber, p_direction) && !is_adjacent_to_wall(next_hero_chamber, '1')) {
								if (valid_movement(p_direction, curr_hero_chamber, next_hero_chamber)) { 
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
						if (is_inside_board(hero1.x - 1, hero1.y)) {
							if (!is_facing_wall(curr_hero_chamber, p_direction) && !is_adjacent_to_wall(next_hero_chamber, '2')) {
								if (valid_movement(p_direction, curr_hero_chamber, next_hero_chamber)) { 
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
					alert('Hero left the Dungeon');
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
		
		function valid_movement(p_direction, p_curr_hero_chamber, p_next_hero_chamber) {
			
			var curr_hero_chamber = p_curr_hero_chamber;
			var next_hero_chamber = p_next_hero_chamber;
			
			switch (String(p_direction)) {
				case '1':
					if (next_hero_chamber != undefined) {
						return resolve_obstacle(p_direction, curr_hero_chamber.top, next_hero_chamber.bottom);
					} else {
						return resolve_obstacle(p_direction, curr_hero_chamber.top, '');
					}
					break;
				case '2':
					if (next_hero_chamber != undefined) {
						return resolve_obstacle(p_direction, curr_hero_chamber.right, next_hero_chamber.left);
					} else {
						return resolve_obstacle(p_direction, curr_hero_chamber.right, '');
					}
					break;
				case '3':
					if (next_hero_chamber != undefined) {
						return resolve_obstacle(p_direction, curr_hero_chamber.bottom, next_hero_chamber.top);
					} else {
						return resolve_obstacle(p_direction, curr_hero_chamber.bottom, '');
					}
					break;
				case '4':
					if (next_hero_chamber != undefined) {
						return resolve_obstacle(p_direction, curr_hero_chamber.left, next_hero_chamber.right);
					} else {
						return resolve_obstacle(p_direction, curr_hero_chamber.left, '');
					}
					break;
				default:
					return false;
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
		
		function resolve_obstacle(p_direction, p_obstacle, p_adjacent_obstacle) {
			
			var temp_adjacent_obstacle = p_adjacent_obstacle;
			
			if (temp_adjacent_obstacle == '') { temp_adjacent_obstacle = '1' };
			
			// passage and passage
			if (p_obstacle == '1' && temp_adjacent_obstacle == '1') {
				write_to_console('You move to the next chamber');
				return true;
				
			// wall or wall
			} else if (p_obstacle == '2' || temp_adjacent_obstacle == '2') {
				write_to_console('You see a wall');
				return false;
				
			// passage and door
			} else if (p_obstacle == '1' && temp_adjacent_obstacle == '3') {
				return open_door(p_direction);
				
			// passage and portcullis
			} else if (p_obstacle == '1' && temp_adjacent_obstacle == '5') {
				return lift_portcullis(p_direction);
			
			// door and door
			} else if (p_obstacle == '3' && temp_adjacent_obstacle == '3') {
				return open_door(p_direction);
			
			// door and portcullis
			} else if (p_obstacle == '3' && temp_adjacent_obstacle == '5') {
				return open_door(p_direction) && lift_portcullis(p_direction);
				
			// door and ANYTHING
			} else if (p_obstacle == '3') {
				return open_door(p_direction);
			
			// bridge and door
			} else if (p_obstacle == '4' && temp_adjacent_obstacle == '3') {
				return cross_bridge(p_direction) && open_door(p_direction);
			
			// bridge and portcullis
			} else if (p_obstacle == '4' && temp_adjacent_obstacle == '5') {
				return cross_bridge(p_direction) && lift_portcullis(p_direction);
			
			// bridge and ANYTHING
			} else if (p_obstacle == '4') {
				return cross_bridge(p_direction);
			
			// portcullis and door
			} else if (p_obstacle == '5' && temp_adjacent_obstacle == '3') {
				return lift_portcullis(p_direction) && open_door(p_direction);
				
			// portcullis and ANYTHING
			} else if (p_obstacle == '5') {
				return lift_portcullis(p_direction);
				
			// pit and door
			} else if (p_obstacle == '6' && temp_adjacent_obstacle == '3') {
				return jump_across_pit(p_direction) && open_door(p_direction);
				
			// pit and portcullis
			} else if (p_obstacle == '6' && temp_adjacent_obstacle == '4') {
				return jump_across_pit(p_direction) && lift_portcullis(p_direction);
				
			// pit and ANYTHING
			} else if (p_obstacle == '6') {
				return jump_across_pit(p_direction);
				
			// rubble and door
			} else if (p_obstacle == '7' && temp_adjacent_obstacle == '3') {
				return move_through_rubble(p_direction) && open_door(p_direction);
				
			// rubble and portcullis
			} else if (p_obstacle == '7' && temp_adjacent_obstacle == '4') {
				return move_through_rubble(p_direction) && lift_portcullis(p_direction);
				
			// rubble and ANYTHING
			} else if (p_obstacle == '7') {
				return move_through_rubble(p_direction);
				
			// spiderweb and door
			} else if (p_obstacle == '8' && temp_adjacent_obstacle == '3') {
				return move_through_spiderweb(p_direction) && open_door(p_direction);
				
			// spiderweb and portcullis
			} else if (p_obstacle == '8' && temp_adjacent_obstacle == '4') {
				return move_through_spiderweb(p_direction) && lift_portcullis(p_direction);
				
			// spiderweb and ANYTHING
			} else if (p_obstacle == '8') {
				return move_through_spiderweb(p_direction);
				
			// darkness and door
			} else if (p_obstacle == '9' && temp_adjacent_obstacle == '3') {
				return move_through_darkness(p_direction) && open_door(p_direction);
				
			// darkness and portcullis
			} else if (p_obstacle == '9' && temp_adjacent_obstacle == '5') {
				return move_through_darkness(p_direction) && lift_portcullis(p_direction);
				
			// darkness and ANYTHING
			} else if (p_obstacle == '9') {
				return move_through_darkness(p_direction);
				
			} else {
				return true;
			}

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
		
		// Door
		function open_door(p_Direction) {
			
			var randomNumber = Math.floor(Math.random() * 14 + 1);
			var randomDoor = DoorJSON[randomNumber];
			
			alert(randomDoor.name + " " + randomDoor.description);
			
			write_to_console('You opened the door');
			return true;
		}
		
		
		// Portcullis
		function lift_portcullis(p_Direction) {
			
			if (confirm("Do you want to raise the portcullis?")) {
				
				var dice_roll = roll_dice(2);
				if (dice_roll <= hero1.strength) {
					$('.dice_total').css('background-color', '#00FF00');
					$('.dice_total').css('color', '#FFFFFF');
					write_to_console('['+dice_roll+'] You raised the portcullis');
					return true;
				} else {
					$('.dice_total').css('background-color', '#FF0000');
					$('.dice_total').css('color', '#FFFFFF');
					write_to_console('['+dice_roll+'] Could not raise the portcullis');
					update_timer();
					return false;
				}
			} else {
				return false;
			}
		}
		
		// Bridge
		function cross_bridge(p_Direction) {
			
			
			if (moving_backwards(p_Direction)) {
				return true;
			} else {
				var dice_roll = roll_dice(2);
				if (confirm("Do you want to cross the plank?")) {
					// allow to drop loot or penalty to agility
					if (dice_roll <= hero1.agility) {
						$('.dice_total').css('background-color', '#00FF00');
						$('.dice_total').css('color', '#FFFFFF');
						write_to_console('['+dice_roll+'] You crossed the plank');
						return true;
					} else {
						$('.dice_total').css('background-color', '#FF0000');
						$('.dice_total').css('color', '#FFFFFF');
						write_to_console('['+dice_roll+'] You fell off the plank');
						update_timer();
						return false;
					}
				} else {
					return false;
				}
			}
		}
		
		function jump_across_pit(p_Direction) {
			var now = new Date();
			
			if (moving_backwards(p_Direction)) {
				return true;
			} else {
				if (confirm("Do you want to jump across the pit?")) {
					var dice_roll = roll_dice(2);
					if (dice_roll <= hero1.luck) {
						$('.dice_total').css('background-color', '#00FF00');
						$('.dice_total').css('color', '#FFFFFF');
						write_to_console('['+dice_roll+'] You jumped across the pit');
						return true;
					} else {
						$('.dice_total').css('background-color', '#FF0000');
						$('.dice_total').css('color', '#FFFFFF');
						write_to_console('['+dice_roll+'] You fell into the pit');
						update_timer();
						return false;
					}
				} else {
					return false;
				}
			}
		}
		
		function move_through_rubble(p_Direction) {
			var now = new Date();
			
			if (moving_backwards(p_Direction)) {
				return true;
			} else {
				if (confirm("Do you want to move through the rubble?")) {
					var dice_roll = roll_dice(2);
					if (dice_roll <= hero1.agility) {
						$('.dice_total').css('background-color', '#00FF00');
						$('.dice_total').css('color', '#FFFFFF');
						write_to_console('['+dice_roll+'] You moved through the rubble');
						return true;
					} else {
						$('.dice_total').css('background-color', '#FF0000');
						$('.dice_total').css('color', '#FFFFFF');
						write_to_console('['+dice_roll+'] You are stuck in the rubble');
						update_timer();
						return false;
					}
				} else {
					return false;
				}
			}
		}
		
		function move_through_spiderweb(p_Direction) {
			var now = new Date();
			
			if (moving_backwards(p_Direction)) {
				return true;
			} else {
				if (confirm("Do you want to move through the spider?")) {
					var dice_roll = roll_dice(2);
					if (dice_roll <= hero1.strength) {
						$('.dice_total').css('background-color', '#00FF00');
						$('.dice_total').css('color', '#FFFFFF');
						write_to_console('['+dice_roll+'] You moved through the spider web');
						return true;
					} else {
						$('.dice_total').css('background-color', '#FF0000');
						$('.dice_total').css('color', '#FFFFFF');
						write_to_console('['+dice_roll+'] You are stuck in the spider web');
						update_timer();
						return false;
					}
				} else {
					return false;
				}
			}
		}
		
		function move_through_darkness(p_Direction) {
			var now = new Date();
			
			var curr_hero_chamber = hero_map[hero1.x*100 + hero1.y];
			
			if (moving_backwards(p_Direction)) {
				return true;
			} else {
				if (confirm("Do you want to move through the darkness?")) {
					var dice_roll = roll_dice(1);
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
				} else {
					return false;
				}
			}
		}
		
		function stumble_to_the_right(p_dice_roll, p_Direction) {
			
			var curr_hero_chamber = hero_map[hero1.x*100 + hero1.y];
			
			var next_hero_chamber;
			var new_direction;

			if (String(curr_hero_chamber.orientation) == '0') {
				next_hero_chamber = hero_map[(hero1.x+1)*100 + hero1.y];
				new_direction = '2';
			} else if (String(curr_hero_chamber.orientation) == '90') {
				next_hero_chamber = hero_map[(hero1.x)*100 + hero1.y-1];
				new_direction = '3';
			} else if (String(curr_hero_chamber.orientation) == '180') {
				next_hero_chamber = hero_map[(hero1.x-1)*100 + hero1.y];
				new_direction = '4';
			} else if (String(curr_hero_chamber.orientation) == '270') {
				next_hero_chamber = hero_map[(hero1.x)*100 + hero1.y+1];
				new_direction = '1';
			}
			
			prep_hero_movement_into_darkness(p_dice_roll, p_Direction, new_direction, next_hero_chamber);

		}
		
		function stumble_to_the_left(p_dice_roll, p_Direction) {
			
			var curr_hero_chamber = hero_map[hero1.x*100 + hero1.y];
			
			var next_hero_chamber;
			var new_direction;

			if (String(curr_hero_chamber.orientation) == '0') {
				next_hero_chamber = hero_map[(hero1.x-1)*100 + hero1.y];
				new_direction = '4';
			} else if (String(curr_hero_chamber.orientation) == '90') {
				next_hero_chamber = hero_map[(hero1.x)*100 + hero1.y+1];
				new_direction = '1';
			} else if (String(curr_hero_chamber.orientation) == '180') {
				next_hero_chamber = hero_map[(hero1.x+1)*100 + hero1.y];
				new_direction = '2';
			} else if (String(curr_hero_chamber.orientation) == '270') {
				next_hero_chamber = hero_map[(hero1.x)*100 + hero1.y-1];
				new_direction = '3';
			}
			
			prep_hero_movement_into_darkness(p_dice_roll, p_Direction, new_direction, next_hero_chamber);

		}
		
		function stumble_backwards(p_dice_roll, p_Direction) {
			
			var curr_hero_chamber = hero_map[hero1.x*100 + hero1.y];
			
			var next_hero_chamber;
			var new_direction;

			if (String(curr_hero_chamber.orientation) == '0') {
				next_hero_chamber = hero_map[hero1.x*100 + hero1.y-1];
				new_direction = '3';
			} else if (String(curr_hero_chamber.orientation) == '90') {
				next_hero_chamber = hero_map[(hero1.x-1)*100 + hero1.y];
				new_direction = '4';
			} else if (String(curr_hero_chamber.orientation) == '180') {
				next_hero_chamber = hero_map[hero1.x*100 + hero1.y+1];
				new_direction = '1';
			} else if (String(curr_hero_chamber.orientation) == '270') {
				next_hero_chamber = hero_map[(hero1.x+1)*100 + hero1.y];
				new_direction = '2';
			}
			
			prep_hero_movement_into_darkness(p_dice_roll, p_Direction, new_direction, next_hero_chamber);

		}
		
		function prep_hero_movement_into_darkness(p_dice_roll, p_Direction, p_new_direction, next_hero_chamber) {
			
			var temp_previous_direction = previous_direction;
			
			if (p_new_direction == '1') {
				if (is_inside_board(hero1.x, hero1.y+1) && (next_hero_chamber == undefined || next_hero_chamber.bottom != '2')) {
					force_hero_movement('1');
				} else {
					lost_in_darkness(p_dice_roll)
				}
			} else if (p_new_direction == '2') {
				if (is_inside_board(hero1.x+1, hero1.y) && (next_hero_chamber == undefined || next_hero_chamber.left != '2')) {
					force_hero_movement('2');
				} else {
					lost_in_darkness(p_dice_roll)
				}
			} else if (p_new_direction == '3') {
				if (is_inside_board(hero1.x, hero1.y-1) && (next_hero_chamber == undefined || next_hero_chamber.top != '2')) {
					force_hero_movement('3');
				} else {
					lost_in_darkness(p_dice_roll)
				}
			} else if (p_new_direction == '4') {
				if (is_inside_board(hero1.x-1, hero1.y) && (next_hero_chamber == undefined || next_hero_chamber.right != '2')) {
					force_hero_movement('4');
				} else {
					lost_in_darkness(p_dice_roll)
				}
			} else {
				write_to_console("stumble left error, hero orientation: " + curr_hero_chamber.orientation);
			}
			//alert("direction: " + temp_previous_direction + ", new direction: " + p_new_direction);
			if (Math.abs(temp_previous_direction - p_new_direction) == 2) {
				write_to_console("["+p_dice_roll+"] You wander into the darkness and stumbled back to the previous room");
			} else {
				write_to_console("["+p_dice_roll+"] You wander into the darkness");
			}
		}
		
		function lost_in_darkness(p_dice_roll) {
			
			write_to_console("["+p_dice_roll+"] You are lost in darkness");
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
					
					write_to_console('new tile: ' + randomNumber + " " + new_top + " " + new_right + " " + new_bottom + " " + new_left);
					
					$('#dungeon_board_cell_'+(hero1.x)+'_'+(hero1.y)).css("background-image", "url('"+image_directory+randomChamber.image_url+"')");
					$('#dungeon_board_cell_'+(hero1.x)+'_'+(hero1.y)).css("transform", "rotate("+eval((p_Direction-1)*90)+"deg)");
					

		//chamber legend
		//1 open
		//2 wall
		//3 door
		//4 bridge
		//5 portcullis
		//6 pit
		//7 cave-in/rubble
		//8 spiderweb
		//9 darkness
		
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
					
					
				}
				
			}
			
			if (enable_mini_board) {
				update_mini_game_board();
			}
			if (enable_medium_board) {
				update_medium_game_board();
			}
		}

		
		function update_timer(){
			timer--;
			$('.timer').html(timer);
			if (timer == 0 && !check_hero_exit()) {
				
				setTimeout(function () {
					alert("Time Ended: You are Dead!");
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
		
		function crypt(){
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
		function corpse(){
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
		function search(){
			var curr_hero_chamber = hero_map[hero1.x*100 + hero1.y];
			
			if (curr_hero_chamber.type != 0 && curr_hero_chamber.type != 2 && curr_hero_chamber.type != 3) {
				if (parseInt(curr_hero_chamber.searched) < 1) {
					if (confirm("Do you want to search the room?")) {
						// search the room
						curr_hero_chamber.searched = '1';
						write_to_console('You searched the room');
						update_timer();
					} else {
						return false;
					}
				} else {
					write_to_console('Room already searched');
				}
			} else {
				write_to_console("There is nothing to search here");
			}
		}
		
		function door(){
		}
		
		function treasure(){
			alert("hero on location: " + hero1.x + "," + hero1.y);
		}
		function catacombs(){
		}
		
		function search_dragon_chamber(){
			
			var randomNumber;
			var randomDraw;
			
			if (hero_map[hero1.x*100 + hero1.y].type == '99') {
			
				if (confirm("Do you want to loot the treasure room?")) {
					
					do {
						randomNumber = Math.floor(Math.random() * 8+1);
						randomDraw = DragonJSON[randomNumber];
					} while (randomDraw == undefined)
						
					alert(randomDraw.name + " " + randomDraw.awake);
					
					if (String(randomDraw.awake) == '0') {
						write_to_console('The dragon sleeping');
						do {
							randomNumber = Math.floor(Math.random() * 100+1);
							randomDraw = TreasureJSON[randomNumber];
						} while (randomDraw == undefined)
							
						hero_loot[randomNumber] = randomDraw;
						$('#loot_console').append("<div>"+randomDraw.name+"</div><div>"+randomDraw.value+"</div>");
						write_to_loot_console(randomDraw.name + " ("+randomDraw.value+" Gold)");
						write_to_console('You find ' + randomDraw.name);
						
						do {
							randomNumber = Math.floor(Math.random() * 100+1);
							randomDraw = TreasureJSON[randomNumber];
						} while (randomDraw == undefined)
							
						hero_loot[randomNumber] = randomDraw;
						write_to_loot_console(randomDraw.name + " ("+randomDraw.value+" Gold)");
						write_to_console('You find ' + randomDraw.name);
					} else {
						write_to_console('The dragon wakes up!!!');
						
						randomNumber = Math.floor(Math.random() * 12+1);
						write_to_console('['+randomNumber+'] You suffer '+randomNumber+' wounds!!!');
						
						hero1.health = hero1.health - randomNumber;
						draw_hero_stats('hero1', hero1);
						check_hero_health();
						
						
					}
					
					update_timer();
				}
				
			} else {
				alert("There is no treasure");
			}
			
		}
		
		function check_hero_health(){
			if (hero1.health <= 0) {
				alert("You have died!!");
				write_to_console("You have died!!!");
				game_over = true;
			}
		}
		
		
		function fight(){
			$('#battle_modal').modal('toggle');
			
			// function load monster()
			
			var monster_name = $('.monster_name_header').clone();
			var monster_copy = $('.monster_table').clone();
			$('#monster_battle_sheet').append(monster_name);
			$('#monster_battle_sheet').append(monster_copy);
			
			var hero1_name = $('.hero1_name_header').clone();
			var hero1_copy = $('.hero1_table').clone();
			$('#hero_battle_sheet').append(hero1_name);
			$('#hero_battle_sheet').append(hero1_copy);
		}
		
		function check_room() {
		}
		
		function random_encounter() {
		}
		
		function random_trap() {
		}
		
		function write_to_console(p_Message) {
			$('.console').prepend(Math.abs(timer-max_timer) + ': ' + p_Message + '<br/>');
		}
		
		function write_to_loot_console(p_Message) {
			$('.loot_console').prepend(Math.abs(timer-max_timer) + ': ' + p_Message + '<br/>');
		}
		
		
	
		
	</script>
	<style type="text/css">
		body { margin: 20px; text-align: center;}
		
		
		.btn { margin-top: 2px; min-width: 80px;}
		.table-condensed>tbody>tr>td { padding: 5px 5px 0 5px; }
		.table>tbody>tr>td {}
		.table { margin-bottom: 10px; }
		
		.table>tbody>tr>td ,.table>tbody>tr>th { padding: 2px;}
		
		.character_selection_screen { text-align: center; }
		.character_selection { display: inline-block; margin: 10px; cursor: pointer; vertical-align: top; }
		.character_selection:hover { background-color: #EEEEEE }
		.character_table { width: 200px; }
		.character_sheet_image { width: 60%; style="padding: 2px;"}
		.character_image { width: 100%; }
		.character_sheet_stats { width: 20% }
		.character_sheet_values { width: 20% }
		.character_sheet_description { padding: 10px; font-style: italic; font-size: 12px; height: 80px; overflow-wrap: break-word; word-wrap: break-word; }
		
		
		.character_dashboard { display: inline-block; width: 250px; vertical-align: top; }
		.hero_table { width: 100%; }
		.timer_sheet { }
		.hero_description { padding: 5px; font-style: italic; font-size: 12px; }
		
		.hero_name_header { text-align: left; }
		
		.mini_hero_table { width: 250px; }
		.medium_hero_table { width: 350px; }
		
		.dice_sheet { position: relative; text-align: center; }
		
		.dungeon_dashboard { display: inline-block; }
		.dungeon_board { display: inline-block; padding: 0px; margin: 0 auto; }
		.dungeon_row { display: block flex; padding: 0px; margin: 0px; vertical-align: middle; }
		.dungeon_cell { display: inline-block; padding: 0px; margin: 0px; vertical-align: middle; width: 60px; height: 60px; background-size: 100% 100%; }
		
		.mini_dungeon_board { display: inline-block; padding: 0px; margin: 0 auto; }
		.mini_dungeon_row { display: block; padding: 0px; margin: 0px; vertical-align: middle; }
		.mini_dungeon_cell { display: inline-block; padding: 0px; margin: 0px; vertical-align: middle; width: 40px; height: 40px; background-size: 100% 100%; }
		.mini_movement_controls { display: inline-block; }
		.mini_movement_buttons { display: block; }
		
		.medium_dungeon_board { display: inline-block; padding: 0px; margin: 0 auto; }
		.medium_dungeon_row { display: block; padding: 0px; margin: 0px; vertical-align: middle; }
		.medium_dungeon_cell { display: inline-block; padding: 0px; margin: 0px; vertical-align: middle; width: 40px; height: 40px; background-size: 100% 100%; }
		.medium_movement_controls { display: inline-block; }
		.medium_movement_buttons { display: block; }
		
		.movement_dashboard { vertical-align: top; margin: 20px;}
		.timer { display: inline-block; }
		.movement_controls { display: block; }
		.movement_buttons { display: inline-block; }
		
		
		
		.console_sheets { text-align: left; }
		.console_sheet { overflow-y:scroll; height: 100px; }
		.loot_sheet { overflow-y:scroll; height: 100px; }
		
		.mini_console_sheet { overflow-y:scroll; height: 100px; }
		.mini_loot_sheet { overflow-y:scroll; height: 100px; }
		
		
		.console { text-align: left; font-size: 12px; padding: 2px; }
		.loot_console { text-align: left; font-size: 12px; padding: 2px; }
		
		
		
		.monster_battle_sheet { display: inline-block; vertical-align: top; }
		.hero_battle_sheet { display: inline-block; vertical-align: top; }
		
		@media screen and (min-width: 769px) {

			.div-mobile { display: none; }
			.div-desktop { display: block; }

		}

		@media screen and (max-width: 768px) {

			.div-mobile { display: block; }
			.div-desktop { display: none; }

		}
		
	</style>
	
	
	<style type="text/css">

	</style>
</head>
<body>

<div class="container-fluid" style="">


<!-- Character Selection -->
<div class="character_selection_screen" id="character_selection_screen" style="display: none;">
	<h3><a href="/dungeonquest">DungeonQuest</a></h3><br/><br/>
	<div id="character_selection_placeholder"></div>
	<p>The Dungeon is full of traps and danger.  But treasure lies beyond.  Are you prepared for adventure?</p><br/>
</div>


<!-- Dungeon Quest Game (Deskto and Mobile) -->
<div class="game" id="game">

	<!-- Desktop View -->
	<div class="div-desktop">

		<!-- Character Dashboard -->
		<div class="character_dashboard" id="character_dashboard" style="margin-right: 20px;">

			<!-- Hero Sheet -->
			<div class="hero_sheet">
				<h4 class="hero_name_header"><span class="hero1_name">Name</span></h4>
				<table class="hero_table table table-condensed table-bordered">
					<tbody>
					<tr><td rowspan="6" style="width: 70%; padding: 2px;">
						<img class="hero1_img character_image img img-responsive" src="" /></td>
						<td class="col-sm-8" style="width: 15%">⏱️</td>
						<td class="col-sm-4" style="width: 15%"><span class="timer"/></td></tr> 
					<tr><td>❤️</td><td><span class="hero1_health" /></td></tr>
					<tr><td>Str</td><td><span class="hero1_strength" /></td></tr>
					<tr><td>Agi</td><td><span class="hero1_agility" /></td></tr>
					<tr><td>Def</td><td><span class="hero1_defense" /></td></tr>
					<tr><td>Luk</td><td><span class="hero1_luck" /></td></tr>
					</tbody>
				</table>
			</div>
			
			<!-- Dice Sheet -->
			<div class="dice_sheet" onclick="roll_dice(2)">	
				<div style="display: inline-block; position: relative; margin: 0 5px 0 5px;">
					<div id="dice" data-side="1">
					<div class="sides side-1"><span class="dot dot-1"></span></div>
					<div class="sides side-2"><span class="dot dot-1"></span><span class="dot dot-2"></span></div>
					<div class="sides side-3"><span class="dot dot-1"></span><span class="dot dot-2"></span><span class="dot dot-3"></span></div>
					<div class="sides side-4"><span class="dot dot-1"></span><span class="dot dot-2"></span><span class="dot dot-3"></span><span class="dot dot-4"></span></div>
					<div class="sides side-5"><span class="dot dot-1"></span><span class="dot dot-2"></span><span class="dot dot-3"></span><span class="dot dot-4"></span><span class="dot dot-5"></span></div>
					<div class="sides side-6"><span class="dot dot-1"></span><span class="dot dot-2"></span><span class="dot dot-3"></span><span class="dot dot-4"></span><span class="dot dot-5"></span><span class="dot dot-6"></span></div>
					</div>
				</div>
				<div style="display: inline-block; vertical-align: top; position: relative; margin: 0 5px 0 5px;">
					<div id="dice_2" data-side="1">
					<div class="sides side-1"><span class="dot dot-1"></span></div>
					<div class="sides side-2"><span class="dot dot-1"></span><span class="dot dot-2"></span></div>
					<div class="sides side-3"><span class="dot dot-1"></span><span class="dot dot-2"></span><span class="dot dot-3"></span></div><div class="sides side-4">
					<span class="dot dot-1"></span><span class="dot dot-2"></span><span class="dot dot-3"></span><span class="dot dot-4"></span></div>
					<div class="sides side-5"><span class="dot dot-1"></span><span class="dot dot-2"></span><span class="dot dot-3"></span><span class="dot dot-4"></span><span class="dot dot-5"></span></div>
					<div class="sides side-6"><span class="dot dot-1"></span><span class="dot dot-2"></span><span class="dot dot-3"></span><span class="dot dot-4"></span><span class="dot dot-5"></span><span class="dot dot-6"></span></div>
					</div>
				</div>
				<div style="display: inline-block; position: relative; vertical-align: top; margin: 0 5px 0 5px;">
					<div class="dice_total" style="text-align: center; width: 50px; height: 50px; font-size: 30px; border: solid 2px black;">12</div>
				</div>
			</div>
			
			<!-- Console and Loot Sheet -->
			<div class="console_sheets">
				<h4>Loot</h4>
				<div class="loot_sheet table-bordered">
					<div class="loot_console "></div>
				</div>
				<h4>Console</h4>
				<div class="console_sheet table-bordered">
					<div class="console"></div>
				</div>
			</div>
			
			
		</div>

		<!-- Dungeon Board -->
		<div class="dungeon_dashboard">
			<h4 class="">Dungeon Map</h4>aa
			<div class="dungeon_board"></div>bb
			<div class="medium_dungeon_board" id="medium_dungeon_board"></div>cc
			
		
			<!-- Control Buttons -->
			<div class="movement_dashboard" id="movement_dashboard">
				<div>
					<div style="display: inline-block;"><input class="btn btn-primary" type="button" name="search_button" value="Search" onclick="search();" /></div>
					<div style="display: inline-block;"><input class="btn btn-primary" type="button" name="corpse_button" value="Corpse" onclick="corpse();" /></div>
					<div style="display: inline-block;"><input class="btn btn-primary" type="button" name="crypt_button" value="Crypt" onclick="crypt();" /></div>
					<div style="display: inline-block;"><input class="btn btn-primary" type="button" name="treasure_button" value="Loot" onclick="search_dragon_chamber();" /></div>
					<div style="display: none;"><input class="btn btn-primary" type="button" name="catacombs_button" value="Catacombs" onclick="catacombs();" /></div>
					<div style="display: none;"><input class="btn btn-primary" type="button" name="door_button" value="Door" onclick="door();" /></div>
					<div style="display: none;"><input class="btn btn-primary" type="button" name="trap_button" value="Trap" onclick="trap();" /></div>
					<div style="display: none;"><input class="btn btn-primary" type="button" name="dragon_button" value="Dragon" onclick="dragon();" /></div>
					<div style="display: inline-block;"><input class="btn btn-primary" type="button" name="fight_button" id="fight_button" value="Fight" onclick="fight();" /></div>
					<div style="display: inline-block;"><input class="btn btn-primary" type="button" name="quit_button" id="quit_button" value="Quit" onclick="quit_game();" /></div>
				</div>
			</div>
			
		</div>

		
	</div>

	<!-- Mobile View -->
	<div class="div-mobile">

		<!-- Mobile Character Dashboard -->
		<div class="character_dashboard" id="character_dashboard">

			<!-- Mobile Hero Sheet -->
			<div class="hero_sheet">
				<div style="width: 250px">
					<h4 class="hero_name_header"><span class="hero1_name_short">Name</span> ⏱️ <span class="timer"></span> ❤️ <span class="hero1_health"></span></h4>
					<table class="mini_hero_table table table-condensed table-bordered">
						<tbody>
						<tr>
							<td rowspan="4" style="width: 20%; padding: 0px;">
							<img class="hero1_img character_image img img-responsive" src="" /></td>
						</tr>
						<tr>
							<td style="width: 20%">Str</td>
							<td style="width: 20%">Agi</td>
							<td style="width: 20%">Def</td>
							<td style="width: 20%">Luk</td>
						</tr>
						<tr>
							<td style="width: 20%"><span class="hero1_strength" /></td>
							<td><span class="hero1_agility" /></td>
							<td style="width: 20%"><span class="hero1_defense" /></td>
							<td><span class="hero1_luck" /></td>
						</tr>
						</tbody>
					</table>
				</div>
			</div>
			
			<!-- Mobile Dice Sheet -->
			<div class="dice_sheet" onclick="roll_dice(2)">	
				<div style="display: inline-block; position: relative; margin: 0 5px 0 5px;">
					<div id="mini_dice" data-side="1">
					<div class="sides side-1"><span class="dot dot-1"></span></div>
					<div class="sides side-2"><span class="dot dot-1"></span><span class="dot dot-2"></span></div>
					<div class="sides side-3"><span class="dot dot-1"></span><span class="dot dot-2"></span><span class="dot dot-3"></span></div>
					<div class="sides side-4"><span class="dot dot-1"></span><span class="dot dot-2"></span><span class="dot dot-3"></span><span class="dot dot-4"></span></div>
					<div class="sides side-5"><span class="dot dot-1"></span><span class="dot dot-2"></span><span class="dot dot-3"></span><span class="dot dot-4"></span><span class="dot dot-5"></span></div>
					<div class="sides side-6"><span class="dot dot-1"></span><span class="dot dot-2"></span><span class="dot dot-3"></span><span class="dot dot-4"></span><span class="dot dot-5"></span><span class="dot dot-6"></span></div>
					</div>
				</div>
				<div style="display: inline-block; vertical-align: top; position: relative; margin: 0 5px 0 5px;">
					<div id="mini_dice_2" data-side="1">
					<div class="sides side-1"><span class="dot dot-1"></span></div>
					<div class="sides side-2"><span class="dot dot-1"></span><span class="dot dot-2"></span></div>
					<div class="sides side-3"><span class="dot dot-1"></span><span class="dot dot-2"></span><span class="dot dot-3"></span></div><div class="sides side-4">
					<span class="dot dot-1"></span><span class="dot dot-2"></span><span class="dot dot-3"></span><span class="dot dot-4"></span></div>
					<div class="sides side-5"><span class="dot dot-1"></span><span class="dot dot-2"></span><span class="dot dot-3"></span><span class="dot dot-4"></span><span class="dot dot-5"></span></div>
					<div class="sides side-6"><span class="dot dot-1"></span><span class="dot dot-2"></span><span class="dot dot-3"></span><span class="dot dot-4"></span><span class="dot dot-5"></span><span class="dot dot-6"></span></div>
					</div>
				</div>
				<div style="display: inline-block; position: relative; vertical-align: top; margin: 0 5px 0 5px;">
				<div class="dice_total" style="text-align: center; width: 50px; height: 50px; font-size: 30px; border: solid 2px black;">12</div>
				</div>
			</div>
			
		</div>
		
		<!-- Mobile Dungeon Board -->
		<div class="dungeon_dashboard">
			<div>
				<div class="mini_dungeon_board" id="mini_dungeon_board"></div>
				<div>Swipe for Movement</div>
			</div>
		</div>
		
		<!-- Mobile Console and Loot Sheet -->
		<div>
			<div style="display: inline-block; width: 100%;">
			<div style="display: inline-block; width: 250px;">
				<h4>Console</h4>
				<div class="mini_console_sheet table-bordered">
					<div class="console"></div>
				</div>
			</div>
			<div style="display: inline-block; width: 250px;">
				<h4>Loot</h4>
				<div class="mini_loot_sheet table-bordered">
					<div class="loot_console "></div>
				</div>
			</div>
			</div>
		</div>
		
		
		<!-- Control Buttons -->
		<div class="movement_dashboard" id="movement_dashboard">
			<div>
				<div style="display: inline-block;"><input class="btn btn-primary" type="button" name="search_button" value="Search" onclick="search();" /></div>
				<div style="display: inline-block;"><input class="btn btn-primary" type="button" name="corpse_button" value="Corpse" onclick="corpse();" /></div>
				<div style="display: inline-block;"><input class="btn btn-primary" type="button" name="crypt_button" value="Crypt" onclick="crypt();" /></div>
				<div style="display: inline-block;"><input class="btn btn-primary" type="button" name="treasure_button" value="Loot" onclick="search_dragon_chamber();" /></div>
				<div style="display: inline-block;"><input class="btn btn-primary" type="button" name="fight_button" id="fight_button" value="Fight" onclick="fight();" /></div>
				<div style="display: inline-block;"><input class="btn btn-primary" type="button" name="quit_button" id="quit_button" value="Quit" onclick="quit_game();" /></div>
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
			
			
<!-- Battle Modal -->
<div class="modal fade" id="battle_modal" tabindex="-1" role="dialog" aria-labelledby="battle_modal_label" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header" style="margin: 0px;">
        <h4 class="modal-title" id="battle_modal_label">Encounter</h4>
      </div>
      <div class="modal-body" style="vertical-align: top;">
		<div class="hero_battle_sheet" id="hero_battle_sheet"></div>
		<div style="display: inline-block; margin: 20px;"><b>VS</b></div>
		<div class="monster_battle_sheet" id="monster_battle_sheet"></div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary">Fight</button>
        <button type="button" class="btn btn-primary">Run</button>
      </div>
    </div>
  </div>
</div>


</body>
</html>