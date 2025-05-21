<html>
<head>
	<meta http-equiv="content-type" content="text/html; charset=UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1.0, user-scalable=no">
	<link rel="icon" type="image/png" href="/images/second_edition/dungeonquest_favicon.jpg" />
	<link type="text/css" rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
	<link type="text/css" rel="stylesheet" href="/dice.css">
	<link type="text/css" rel="stylesheet" href="/dice_2.css">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
	<script type="text/javascript" src="/dungeonquest.js"></script>
	<script type="text/javascript" src="/dungeonquest_classes.js"></script>
	<script type="text/javascript">
		
		
		var hero1;
		
		var hero_map = {};
		var empty_map_orientation = {};
		
		var boardgame_width = 13;
		var boardgame_height = 10;
		
		var mini_board_width = 4;
		var mini_board_height = 3;
		
		var medium_board_width = 7;
		var medium_board_width = 7;
		
		var enable_mini_board = true;
		var enable_medium_board = true;
		
		var max_timer = 31;
		
		var timer = max_timer;
		
		var game_over = false;
		
		var previous_direction = "";
		var current_direction = "";
		
		var character_image_directory = "/images/second_edition_characters/";
		var tiles_image_directory = "/images/second_edition_friendlybombs/";
		
		
		
		var sound_directory = "/sound/";
		var audio;
		
		function play_audio(p_type){
			
		}
		
		$(document).ready(function() {
			
			$('#character_selection_screen').show();
			$('#game').hide();
			
			load_characters();
			create_game_board();
			
		
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
						if (is_inside_board(hero1.x-1, hero1.y))
						force_hero_movement('4');
					} else {
						// swiped right	
						if (is_inside_board(hero1.x+1, hero1.y))
						force_hero_movement('2');
					}  
				} else {
					// sliding vertically
					if (diffY > 0) {
						// swiped up	
						if (is_inside_board(hero1.x, hero1.y+1))
						force_hero_movement('1');
					} else {
						// swiped down
						if (is_inside_board(hero1.x, hero1.y-1))
						force_hero_movement('3');
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
					if (is_inside_board(hero1.x, hero1.y+1))
					force_hero_movement('1');
					break;
				case 39: // right
					if (is_inside_board(hero1.x+1, hero1.y))
					force_hero_movement('2');
					break;
				case 40: // down
					if (is_inside_board(hero1.x, hero1.y-1))
					force_hero_movement('3');
					break;
				case 37: // left
					if (is_inside_board(hero1.x-1, hero1.y))
					force_hero_movement('4');
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
					'<img class="character_image img img-responsive" src="'+character_image_directory+CharactersJSON[i].image_url+'" /></td>'+
					'<td class="character_sheet_stats">HP</td>'+
					'<td class="character_sheet_values">'+CharactersJSON[i].health+'</td></tr>');
				$character_tbody.append('<tr><td>Str</td><td>'+CharactersJSON[i].strength+'</td></tr>');
				$character_tbody.append('<tr><td>Agi</td><td>'+CharactersJSON[i].agility+'</td></tr>');
				$character_tbody.append('<tr><td>Def</td><td>'+CharactersJSON[i].defense+'</td></tr>');
				$character_tbody.append('<tr><td>Luk</td><td>'+CharactersJSON[i].luck+'</td></tr>');
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
			$('.'+p_hero_num+'_img').attr("src", character_image_directory+p_hero.image_url);
		}
		
		function create_game_board() {
			// Create Main Board
			for (var i = boardgame_height; i > 0; i--){
				jQuery('<div/>', { id: 'dungeon_board_row_'+(i), 'class': 'dungeon_row' }).appendTo('.dungeon_board');
				for (var j = 0; j < boardgame_width; j++) {
					jQuery('<div/>', { id: 'dungeon_board_cell_'+(j+1)+'_'+(i), 'class': 'dungeon_cell', }).appendTo('#dungeon_board_row_'+(i));
					var random_empty_tile_number = Math.floor(Math.random() * 45 + 1);
					var random_empty_tile_direction = Math.floor(Math.random() * 4 + 1);
					$('#dungeon_board_cell_'+(j+1)+'_'+(i)).css("background-image", "url('"+tiles_image_directory+"empty/"+random_empty_tile_number+".jpg')");
					$('#dungeon_board_cell_'+(j+1)+'_'+(i)).css("transform", "rotate("+eval((random_empty_tile_direction-1)*90)+"deg)");
					empty_map_orientation[(j+1)*100 + i] = eval((random_empty_tile_direction-1)*90);
				}
			}
			// Create Mini Board
			if (enable_mini_board) {
				for (var i = 1; i > -2; i--){
					jQuery('<div/>', { id: 'mini_board_row_'+(i), 'class': 'mini_dungeon_row' }).appendTo('#mini_dungeon_board');
					for (var j = -1; j < 3; j++) { 
						jQuery('<div/>', { id: 'mini_board_cell_'+(j)+'_'+(i), 'class': 'mini_dungeon_cell', }).appendTo('#mini_board_row_'+(i));
					}
				}
			}
			// Create Medium Board
			if (enable_medium_board) {
				for (var i = 2; i > -3; i--){
					jQuery('<div/>', { id: 'medium_board_row_'+(i), 'class': 'medium_dungeon_row' }).appendTo('#medium_dungeon_board');
					for (var j = -2; j < 3; j++) { 
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
			
			if (hero1.x == 1) { x_adjustment = 1; }
			//if (hero1.x == 2) { x_adjustment = 2; }
			if (hero1.x == boardgame_width - 1) { x_adjustment = -1; }
			if (hero1.x == boardgame_width) { x_adjustment = -2; }
			
			if (hero1.y == 1) { y_adjustment = 1; }
			//if (hero1.y == 2) { y_adjustment = 2; }
			if (hero1.y == boardgame_height -1) { y_adjustment = 0; }
			if (hero1.y == boardgame_height) { y_adjustment = -1; }
			

			
			
			for (var i = -1; i < 3; i++) {
				for (var j = -1; j < 2; j++) {
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
							$('#mini_board_cell_'+i+'_'+j).css('background-image', 'url("'+tiles_image_directory+map_tile.image_url+'")');
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
			
			if (hero1.x == 1) { x_adjustment = 2; }
			if (hero1.x == 2) { x_adjustment = 1; }
			if (hero1.x == boardgame_width - 1) { x_adjustment = -1; }
			if (hero1.x == boardgame_width - 0) { x_adjustment = -2; }
			
			if (hero1.y == 1) { y_adjustment = 2; }
			if (hero1.y == 2) { y_adjustment = 1; }
			if (hero1.y == boardgame_height -1) { y_adjustment = -1; }
			if (hero1.y == boardgame_height -0) { y_adjustment = -2; }
			
			for (var i = -2; i < 3; i++) {
				for (var j = -2; j < 3; j++) {
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
							$('#medium_board_cell_'+i+'_'+j).css('background-image', 'url("'+tiles_image_directory+map_tile.image_url+'")');
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
			
			hero_map = {};
			reset_game_board();
			
			$('.console').empty();
			$('.loot_console').empty();
			
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
					var random_empty_tile_number = Math.floor(Math.random() * 45 + 1);
					var random_empty_tile_direction = Math.floor(Math.random() * 4 + 1);
					$('#dungeon_board_cell_'+(j+1)+'_'+(i)).css("background-image", "url('"+tiles_image_directory+"empty/"+random_empty_tile_number+".jpg')");
					$('#dungeon_board_cell_'+(j+1)+'_'+(i)).css("transform", "rotate("+eval((random_empty_tile_direction-1)*90)+"deg)");
					
					$('#dungeon_board_cell_'+(j+1)+'_'+(i+1)).empty();
				}
			}
			
			// load corner tiles
			hero_map[100 + 1] = new Chamber(0, 1, 1, 2, 2, 0, get_rotation_angle(4), 'corner.jpg', 'South West Tower');
			hero_map[boardgame_width*100 + 1] = new Chamber(0, 1, 2, 2, 1, 0, get_rotation_angle(3), 'corner.jpg', 'North West Tower');
			hero_map[100 + boardgame_height] = new Chamber(0, 2, 1, 1, 2, 0, get_rotation_angle(1), 'corner.jpg', 'South East Tower');
			hero_map[boardgame_width*100 + boardgame_height] = new Chamber(0, 2, 2, 1, 1, 0, get_rotation_angle(2), 'corner.jpg', 'North East Tower');
			
			hero_map[100 + 1].type = 'starting';
			hero_map[boardgame_width*100 + 1].type = 'starting';
			hero_map[100 + boardgame_height].type = 'starting';
			hero_map[boardgame_width*100 + boardgame_height].type = 'starting';
			
			// load treasure tiles
			hero_map[705] = new Chamber(0, 1, 1, 1, 1, 99, 0, 'treasure_bottom.jpg', 'Treasure Chamber');
			hero_map[706] = new Chamber(0, 1, 1, 1, 1, 99, 0, 'treasure_top.jpg', 'Treasure Chamber');
			
			hero_map[705].type = 'treasure';
			hero_map[706].type = 'treasure';
			
			// draw corner tiles
			$('#dungeon_board_cell_'+(1)+'_'+(1)).css("background-image", "url('"+tiles_image_directory+"corner.jpg')");
			$('#dungeon_board_cell_'+(1)+'_'+(1)).css("transform", "rotate("+get_rotation_angle(4)+"deg)");
			$('#dungeon_board_cell_'+(boardgame_width)+'_'+(1)).css("background-image", "url('"+tiles_image_directory+"corner.jpg')");
			$('#dungeon_board_cell_'+(boardgame_width)+'_'+(1)).css("transform", "rotate("+get_rotation_angle(3)+"deg)");
			$('#dungeon_board_cell_'+(1)+'_'+(boardgame_height)).css("background-image", "url('"+tiles_image_directory+"corner.jpg')");
			$('#dungeon_board_cell_'+(1)+'_'+(boardgame_height)).css("transform", "rotate("+get_rotation_angle(1)+"deg)");
			$('#dungeon_board_cell_'+(boardgame_width)+'_'+(boardgame_height)).css("background-image", "url('"+tiles_image_directory+"corner.jpg')");
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
				$('#dungeon_board_cell_'+(hero1.x)+'_'+(hero1.y)).append('<div style="height: 100%; width: 100%; background-size: 30%; background-position: center; background-repeat: no-repeat; background-image: content(\''+character_image_directory+hero1.image_url+'\')"></div>');
				
				// mini board adjustments
				var x_adjustment = 0;
				var y_adjustment = 0;
				
				if (hero1.x == 1) { x_adjustment = 1; }
				//if (hero1.x == 2) { x_adjustment = 1; }
				if (hero1.x == boardgame_width - 1) { x_adjustment = -1; }
				if (hero1.x == boardgame_width) { x_adjustment = -2; }
				
				if (hero1.y == 1) { y_adjustment = 1; }
				//if (hero1.y == 2) { y_adjustment = 1; }
				if (hero1.y == boardgame_height -1) { y_adjustment = 0; }
				if (hero1.y == boardgame_height) { y_adjustment = -1; }
				
				if (enable_mini_board) {
					$('#mini_board_cell_'+eval(0-x_adjustment)+'_'+eval(0-y_adjustment)+'').append('<div style="height: 100%; width: 100%; display: flex; background-size: 30%; background-position: center; background-repeat: no-repeat; background-image: url(\''+character_image_directory+hero1.image_url+'\')"></div>');
				}
				
				// medium board adjustments
				x_adjustment = 0;
				y_adjustment = 0;
				
				if (hero1.x == 1) { x_adjustment = 2; }
				if (hero1.x == 2) { x_adjustment = 1; }
				if (hero1.x == boardgame_width - 1) { x_adjustment = -1; }
				if (hero1.x == boardgame_width - 0) { x_adjustment = -2; }
				
				if (hero1.y == 1) { y_adjustment = 2; }
				if (hero1.y == 2) { y_adjustment = 1; }
				if (hero1.y == boardgame_height -1) { y_adjustment = -1; }
				if (hero1.y == boardgame_height -0) { y_adjustment = -2; }
				
				if (enable_medium_board) {
					$('#medium_board_cell_'+eval(0-x_adjustment)+'_'+eval(0-y_adjustment)+'').append('<div style="height: 100%; width: 100%; display: flex; background-size: 30%; background-position: center; background-repeat: no-repeat; background-image: url(\''+character_image_directory+hero1.image_url+'\')"></div>');
				}
			}
			
		}
		
		function quit_game() {
			if(confirm('Quit?')) {
				document.location.href = '/dungeonquest'
			}
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

		
		function force_hero_movement(p_direction) {
			if (p_direction != '0') {
				if (p_direction == '1') {
					hero1.y++;
					previous_direction = p_direction;
				} else if (p_direction == '2') {
					hero1.x++;
					previous_direction = p_direction;
				} else if (p_direction == '3') {
					hero1.y--;
					previous_direction = p_direction;
				} else if (p_direction == '4') {
					hero1.x--;
					previous_direction = p_direction;
				}
				draw_chamber(p_direction);
				remove_heros();
				draw_hero();
				update_timer();
			}
		}
		
		function draw_chamber(p_direction) {
			
			var current_chamber;
			
			if (true) {
				
				if (hero_map[eval(hero1.x*100 + hero1.y)] == undefined) { 
				
					var randomNumber = Math.floor(Math.random() * 134+1);
					var randomChamber = ChamberJSON[randomNumber];
					
					do {
						randomNumber = Math.floor(Math.random() * 134+1);
						randomChamber = ChamberJSON[randomNumber];
					} while (randomChamber == undefined)
						
					// record new chamber id
					$('.new_chamber').html(randomNumber);

					var new_top = "";
					var new_right = "";
					var new_bottom = "";
					var new_left = "";
					
					if (p_direction == 1) {
						new_top = randomChamber.top;
						new_right = randomChamber.right;
						new_bottom = randomChamber.bottom;
						new_left = randomChamber.left;
					} else if (p_direction == 2) {
						new_top = randomChamber.left;
						new_right = randomChamber.top;
						new_bottom = randomChamber.right;
						new_left = randomChamber.bottom;
					} else if (p_direction == 3) {
						new_top = randomChamber.bottom;
						new_right = randomChamber.left;
						new_bottom = randomChamber.top;
						new_left = randomChamber.right;
					} else if (p_direction == 4) {
						new_top = randomChamber.right;
						new_right = randomChamber.bottom;
						new_bottom = randomChamber.left;
						new_left = randomChamber.top;
					}
					
					hero_map[eval(hero1.x*100 + hero1.y)] = new Chamber(randomNumber, new_top, new_right, new_bottom, new_left, randomChamber.type, eval((p_direction-1)*90), randomChamber.image_url, randomChamber.description);
					hero_map[eval(hero1.x*100 + hero1.y)].searched = 0;
					current_chamber = hero_map[eval(hero1.x*100 + hero1.y)];
					
					
					$('#dungeon_board_cell_'+(hero1.x)+'_'+(hero1.y)).css("background-image", "url('"+tiles_image_directory+randomChamber.image_url+"')");
					$('#dungeon_board_cell_'+(hero1.x)+'_'+(hero1.y)).css("transform", "rotate("+eval((p_direction-1)*90)+"deg)");

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
		}
		


		
	
		
	</script>
	<style type="text/css">
		body { text-align: center; margin: 5px; }
		
		
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
		
		.mini_dungeon_board { display: inline-block; padding: 5px; margin: 0 auto; }
		.mini_dungeon_row { display: block; padding: 0px; margin: 0px; vertical-align: middle; }
		.mini_dungeon_cell { display: inline-block; padding: 0px; margin: 0px; vertical-align: middle; width: 75px; height: 75px; background-size: 100% 100%; }
		.mini_movement_controls { display: inline-block; }
		.mini_movement_buttons { display: block; }
		
		.medium_dungeon_board { display: inline-block; padding: 0px; margin: 0 auto; }
		.medium_dungeon_row { display: block; padding: 0px; margin: 0px; vertical-align: middle; }
		.medium_dungeon_cell { display: inline-block; padding: 0px; margin: 0px; vertical-align: middle; width: 110px; height: 110px; background-size: 100% 100%; }
		.medium_movement_controls { display: inline-block; }
		.medium_movement_buttons { display: block; }
		
		.movement_dashboard { width: 400px; margin: 20px;}
		.timer { display: inline-block; }
		.movement_controls { display: block; }
		.movement_buttons { display: inline-block; }
		
		
		
		.console_sheets { text-align: left; }
		.console_sheet { overflow-y:scroll; height: 200px; }
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
		
		.alert { padding: 5px; }
		
	</style>
	
</head>
<body>

<div class="" style="">


<!-- Character Selection -->
<div class="character_selection_screen" id="character_selection_screen" style="display: none;">
	<h3><a href="/dungeonquest">DungeonQuest</a></h3><br/>
	<div id="character_selection_placeholder"></div><br/>
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
				<input class="btn btn-primary search_button" type="button" name="search_button" value="Search" onclick="" />
				<input class="btn btn-primary quit_button" type="button" name="quit_button" id="quit_button" value="Quit" onclick="quit_game();" />
			</div>
		</div>
	</div>

	<!-- Mobile View -->
	<div class="div-mobile">

		<!-- Mobile Character Dashboard -->
		<div class="character_dashboard" id="character_dashboard" style="display: inline-block;">

			<!-- Mobile Hero Sheet -->
			<div class="mini_hero_sheet_placeholder">
			<div class="mini_hero_sheet" style="display: inline-block; width: 300px; padding: 5px;">
				<div style="width: 300px">
					<table class="mini_hero_table table table-condensed table-bordered">
					<tbody>
					<tr>
						<td rowspan="3" style="width: 28%; padding: 0px;">
						<img class="hero1_img character_image img img-responsive" src="" /></td>
						<td colspan="2"><span class="hero1_name_short">Name</span></td>
						<td style="width: 18%">❤️ <span class="hero1_health"></span></td>
						<td style="width: 18%">⏱️ <span class="timer"></span></td>
					</tr>
					<tr>
						<td style="width: 18%">Str <span class="hero1_strength"></span></td>
						<td style="width: 18%">Agi <span class="hero1_agility"></td>
						<td style="width: 18%">Def <span class="hero1_defense"></span></td>
						<td style="width: 18%">Luk <span class="hero1_luck"></span></td>
					</tr>
					<tr>
						<td colspan="4">
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
				<input class="btn btn-primary search_button" type="button" name="search_button" value="Search" onclick="" />
				<input class="btn btn-primary quit_button" type="button" name="quit_button" id="quit_button" value="Quit" onclick="quit_game();" />
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
			<div class="mini_console_sheet table-bordered" style="display: inline-block; width: 300px; margin-bottom: 5px;">
				<div class="console mini_console"></div>
			</div>
			<div class="mini_loot_sheet table-bordered" style="display: inline-block; width: 300px; margin-bottom: 5px;">
				<div class="loot_console "></div>
			</div>
		</div>
	</div>
</div>

</div>





</body>
</html>