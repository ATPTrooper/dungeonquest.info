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
	<script type="text/javascript" src="/dungeonquest/dungeonquest.js?v=1"></script>
	<script type="text/javascript" src="/dungeonquest/dungeonquest_classes.js?v=1"></script>
	<script type="text/javascript">
		
		
		var debug = false;
		var hero1;
		
		var hero_map = {};
		var empty_map_orientation = {};
		var hero_loot = [];
		var encounter_queue = [];
		
		var used_chamber_tiles = [];
		var used_treasure_cards = [];
		var used_dragon_cards = [];
		var used_corpse_cards = [];
		var used_crypt_cards = [];
		var used_search_cards = [];
		
		var boardgame_width = 13;
		var boardgame_height = 10;
		
		var mini_board_width = 4;
		var mini_board_height = 3;
		
		var medium_board_width = 7;
		var medium_board_width = 7;
		
		var enable_mini_board = true;
		var enable_medium_board = true;
		
		var max_timer = 31;
		
		if (debug) { max_timer = 100; }
		
		var timer = max_timer;
		
		var game_over = false;
		var ferrox_dead = true;
		var monster_dead = true;
		var trap_triggered = false;
		var trap_triggered_type = "";
		
		var previous_direction = "";
		var current_direction = "";
		
		var character_image_directory = "/dungeonquest/characters/";
		//character_image_directory = "/dungeonquest/images/second_edition_characters/";
		
		var image_directory = "/dungeonquest/second_edition/";
		image_directory = "/dungeonquest/images/second_edition_friendlybombs/";
		
		var empty_image_directory = "/dungeonquest/second_edition/";
		empty_image_directory = "/dungeonquest/images/second_edition_friendlybombs/empty/";
		
		
		
		var sound_directory = "/dungeonquest/sound/";
		var audio;
		
		function play_audio(p_type){
			
			switch(p_type) {
				case "dice":
					audio = new Audio(sound_directory+'dice.mp3');
					audio.play();
					break;
				case "door":
					audio = new Audio(sound_directory+'door.mp3');
					audio.play();
					break;
				case "portcullis_close":
					audio = new Audio(sound_directory+'portcullis_close.mp3');
					audio.play();
					break;
				case "portcullis_open":
					audio = new Audio(sound_directory+'portcullis_open.mp3');
					audio.play();
					break;
				case "rotating_room":
					audio = new Audio(sound_directory+'rotating_chamber.mp3');
					audio.play();
					break;
				case "searchcrypt":
					audio = new Audio(sound_directory+'search_crypt.mp3');
					audio.play();
					break;
				case "collapse":
					audio = new Audio(sound_directory+'collapse.mp3');
					audio.play();
					break;
				case "spiderweb":
					audio = new Audio(sound_directory+'spiderweb.mp3');
					audio.play();
					break;
				case "bridge":
					audio = new Audio(sound_directory+'wood_creak.mp3');
					audio.play();
					break;
				case "jump":
					audio = new Audio(sound_directory+'jump.mp3');
					audio.play();
					break;
				case "explosion":
					audio = new Audio(sound_directory+'explosion.mp3');
					audio.play();
					break;
				case "gold":
					audio = new Audio(sound_directory+'gold.mp3');
					audio.play();
					break;
				case "searchcorpse":
					audio = new Audio(sound_directory+'search_corpse.mp3');
					audio.play();
					break;
				case "dragonsleeping":
					audio = new Audio(sound_directory+'dragon_sleeping.mp3');
					audio.play();
					break;
				case "dragonroar":
					audio = new Audio(sound_directory+'dragon_roar.mp3');
					audio.play();
					break;
				case "monster_wounded":
					audio = new Audio(sound_directory+'monster_wounded.mp3');
					audio.play();
					break;
				case "hero_wounded":
					audio = new Audio(sound_directory+'hero_wounded.mp3');
					audio.play();
					break;
				case "torchout":
					audio = new Audio(sound_directory+'torchout.mp3');
					audio.play();
					break;
			}
		}
		
		$(document).ready(function() {
			
			$('#character_selection_screen').show();
			$('#game').hide();
			
			load_characters();
			create_game_board();
			
			// End of Battle
			$('#ferrox_modal').on('hidden.bs.modal', function () {
				$('.mini_dice_placeholder').append($('.mini_dice_sheet'));
			})
			
			// End of Battle
			$('#monster_modal').on('hidden.bs.modal', function () {
				$('.mini_dice_placeholder').append($('.mini_dice_sheet'));
			})
			
			// End of Trap
			$('#trap_modal').on('hidden.bs.modal', function () {
				//$('.mini_dice_placeholder').append($('.mini_dice_sheet'));

				$('.mini_dice_placeholder').attr("onclick", "").unbind("click");
				$(".mini_dice_placeholder").on('click', function(){ roll_dice(2); });
				$('.mini_hero_sheet_placeholder').append($('.mini_hero_sheet'));
				
				// debug
				write_to_debugger("Encounter List: (" + encounter_queue.length + ") encounters");
				if (encounter_queue[0] != undefined) {
					for (var i = 0; i < encounter_queue.length; i++) {
						write_to_debugger("List "+eval(i+1)+": " + encounter_queue[0].type + ", Resolved: "+encounter_queue[0].resolved+", Success: "+encounter_queue[0].success);
					}
				}
				
				if (encounter_queue[0] != undefined) {
					
					// movement encounters
					if (encounter_queue[0].type == "opendoor" || 
						encounter_queue[0].type == "jammeddoor" || 
						encounter_queue[0].type == "speardoor" || 
						encounter_queue[0].type == "bridge" || 
						encounter_queue[0].type == "portcullis" || 
						encounter_queue[0].type == "pit" || 
						encounter_queue[0].type == "rubble" || 
						encounter_queue[0].type == "spiderweb" ||
						encounter_queue[0].type == "darkness") {
							
						if (encounter_queue[0].resolved == '1') {
							if (encounter_queue[0].success == '1') {
								write_to_debugger("resolved 1st encounter, remove 1st encounter");
								if (encounter_queue.length > 1) {
									write_to_debugger("movement[1] started: "+ encounter_queue[0].type);
									encounter_queue.splice(0, 1);
									force_trap_modal();
									
									// if (encounter_queue[0].type == "opendoor") {
										// play_audio("door");
									// }
									
								} else {
									write_to_debugger("movement[0] successful: "+ encounter_queue[0].type);
									write_to_debugger("calling force hero movement");
									force_hero_movement(encounter_queue[0].direction);
									
									write_to_debugger("clearing encounter queue");
									// if (encounter_queue[0].type == "opendoor") {
										// play_audio("door");
									// }
									
									encounter_queue = [];
									trap_triggered = false;
									trap_triggered_type = "";
									draw_hero_stats("hero1", hero1);
									
								}
								return;
							} else {
								write_to_debugger("movement not successful: "+ encounter_queue[0].type);
								encounter_queue = [];
								trap_triggered = false;
								trap_triggered_type = "";
								update_timer();
								return;
							}
						} else {
							if (encounter_queue[0].type == "bridge" || 
								encounter_queue[0].type == "portcullis" || 
								encounter_queue[0].type == "pit" || 
								encounter_queue[0].type == "rubble" || 
								encounter_queue[0].type == "spiderweb" ||
								encounter_queue[0].type == "darkness") {
								
								//write_to_debugger("escape movement: "+ encounter_queue[0].type);
								encounter_queue = [];
								trap_triggered = false;
								trap_triggered_type = "";
								return;
							}
						}
					}
						
					// trap encounters (escape encounters except for collapse)
					if (encounter_queue[0].type == "trapdoor" ||
						encounter_queue[0].type == "snakes" ||
						encounter_queue[0].type == "gas" ||
						encounter_queue[0].type == "collapse" ||
						encounter_queue[0].type == "explosion" ||
						encounter_queue[0].type == "blade" ||
						encounter_queue[0].type == "crossfire") {
							
						if (encounter_queue[0].resolved == '1') {
							
							if (encounter_queue[0].success == '1') {
								
								write_to_debugger("passed trap: "+ encounter_queue[0].type);
								encounter_queue = [];
								trap_triggered = false;
								trap_triggered_type = "";
								update_timer();
								return;
								
							} else {
								
								write_to_debugger("failed trap: "+ encounter_queue[0].type);
								encounter_queue = [];
								trap_triggered = false;
								trap_triggered_type = "";
								update_timer();
								return;
								
							}
							
						} else {
							//write_to_debugger("cannot escape trap: "+ encounter_queue[0].type);
							return;
						}
							
							
					}
					
					// chamber encounters
					if (encounter_queue[0].type == "razorwingattack") {
							
						if (encounter_queue[0].resolved == '1') {
							if (encounter_queue[0].success == '1') {
								
								//write_to_debugger("passed chamber encounter: "+ encounter_queue[0].type);
								encounter_queue = [];
								trap_triggered = false;
								trap_triggered_type = "";
								update_timer();
								return;
								
							} else {
								
								//write_to_debugger("failed chamber encounter: "+ encounter_queue[0].type);
								encounter_queue = [];
								trap_triggered = false;
								trap_triggered_type = "";
								update_timer();
								return;
							}
						} else {
							//write_to_debugger("cannot escape chamber encounter: "+ encounter_queue[0].type);
							return;
							
						}
					}
					
					
					// search
					if (encounter_queue[0].type == "alreadysearched" ||
						encounter_queue[0].type == "secretdoor" ||
						encounter_queue[0].type == "emptyroom" ||
						encounter_queue[0].type == "finditem" ||
						encounter_queue[0].type == "centipede" ||
						encounter_queue[0].type == "passagedown" ||
						encounter_queue[0].type == "alreadysearched") {
							
						if (encounter_queue[0].resolved == '1') {
							if (encounter_queue[0].success == '1') {
								
								//write_to_debugger("passed search: "+ encounter_queue[0].type);
								encounter_queue = [];
								trap_triggered = false;
								trap_triggered_type = "";
								update_timer();
								return;
								
							} else {
								
								//write_to_debugger("failed search: "+ encounter_queue[0].type);
								encounter_queue = [];
								trap_triggered = false;
								trap_triggered_type = "";
								update_timer();
								return;
							}
						} else {
							//write_to_debugger("cannot escape search: "+ encounter_queue[0].type);
							return;
							
						}
					}
					
					
					// search crypt
					if (encounter_queue[0].type == "cryptalreadysearched" ||
						encounter_queue[0].type == "emptycrypt" ||
						encounter_queue[0].type == "findcryptitem" ||
						encounter_queue[0].type == "oldbones" ||
						encounter_queue[0].type == "spectralstorm") {
							
						if (encounter_queue[0].resolved == '1') {
							if (encounter_queue[0].success == '1') {
								
								//write_to_debugger("passed crypt search: "+ encounter_queue[0].type);
								encounter_queue = [];
								trap_triggered = false;
								trap_triggered_type = "";
								update_timer();
								return;
								
							} else {
								
								//write_to_debugger("failed search: "+ encounter_queue[0].type);
								encounter_queue = [];
								trap_triggered = false;
								trap_triggered_type = "";
								update_timer();
								return;
							}
						} else {
							//write_to_debugger("cannot escape crypt search: "+ encounter_queue[0].type);
							return;
							
						}
					}
					
					
					// search corpse
					if (encounter_queue[0].type == "corpsealreadysearched" ||
						encounter_queue[0].type == "emptycorpse" ||
						encounter_queue[0].type == "findcorpseitem" ||
						encounter_queue[0].type == "scorpion" ||
						encounter_queue[0].type == "oldbones") {
							
						if (encounter_queue[0].resolved == '1') {
							if (encounter_queue[0].success == '1') {
								
								//write_to_debugger("passed corpse search: "+ encounter_queue[0].type);
								encounter_queue = [];
								trap_triggered = false;
								trap_triggered_type = "";
								update_timer();
								return;
								
							} else {
								
								//write_to_debugger("failed corpse search: "+ encounter_queue[0].type);
								encounter_queue = [];
								trap_triggered = false;
								trap_triggered_type = "";
								update_timer();
								return;
							}
						} else {
							//write_to_debugger("cannot escape corpse search: "+ encounter_queue[0].type);
							return;
							
						}
					}
					
					// catacombs
					if (encounter_queue[0].type == "catacombs") {
							
						if (encounter_queue[0].resolved == '1') {
							if (encounter_queue[0].success == '1') {
								
								//write_to_debugger("passed catacombs search: "+ encounter_queue[0].type);
								encounter_queue = [];
								trap_triggered = false;
								trap_triggered_type = "";
								update_timer();
								return;
								
							} else {
								
								//write_to_debugger("failed catacombs search: "+ encounter_queue[0].type);
								encounter_queue = [];
								trap_triggered = false;
								trap_triggered_type = "";
								update_timer();
								return;
							}
						} else {
							//write_to_debugger("cannot escape catacombs: "+ encounter_queue[0].type);
							return;
							
						}
					}
					
					// Dragon Breath
					if (encounter_queue[0].type == "dragonbreath") {
						if (encounter_queue[0].resolved == '1') {
								
							//write_to_debugger("passed dragonbreath search: "+ encounter_queue[0].type);
							encounter_queue = [];
							trap_triggered = false;
							trap_triggered_type = "";
							update_timer();
							return;
							
						} else {
							//write_to_debugger("cannot escape dragon breath: "+ encounter_queue[0].type);
							return;
							
						}
					}
					
					// Potion
					if (encounter_queue[0].type == "potion") {
						if (encounter_queue[0].resolved == '1') {
								
							//write_to_debugger("passed dragonbreath search: "+ encounter_queue[0].type);
							encounter_queue = [];
							trap_triggered = false;
							trap_triggered_type = "";
							update_timer();
							return;
							
						} else {
							//write_to_debugger("cannot escape dragon breath: "+ encounter_queue[0].type);
							return;
							
						}
					}
					
					// Torch
					if (encounter_queue[0].type == "torchout") {
						if (encounter_queue[0].resolved == '1') {
							
							if (encounter_queue[0].success == '1') {
								write_to_debugger("passed torchout search: "+ encounter_queue[0].type);
								encounter_queue = [];
								trap_triggered = false;
								trap_triggered_type = "";
								update_timer();
								return;
							} else {
								write_to_debugger("failed torchout search: "+ encounter_queue[0].type);
								encounter_queue[0].resolved = '0';
								encounter_queue[0].success = '0';
								trap_triggered = true;
								trap_triggered_type = "torchout";
								update_timer();
								return;
							}
							
						} else {
							write_to_debugger("cannot escape torchout: "+ encounter_queue[0].type);
							return;
							
						}
					}
					
					// Dragon Breath
					if (encounter_queue[0].type == "death" ||
						encounter_queue[0].type == "gameover") {
							
						//write_to_debugger("cannot escape death: "+ encounter_queue[0].type);
						return;
					}
					
					
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
					'<img class="character_image img img-responsive" src="'+character_image_directory+CharactersJSON[i].image_url_2+'" /></td>'+
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
			$('.'+p_hero_num+'_img').attr("src", character_image_directory+p_hero.image_url_2);
		}
		
		function create_game_board() {
			// Create Main Board
			for (var i = boardgame_height; i > 0; i--){
				jQuery('<div/>', { id: 'dungeon_board_row_'+(i), 'class': 'dungeon_row' }).appendTo('.dungeon_board');
				for (var j = 0; j < boardgame_width; j++) {
					jQuery('<div/>', { id: 'dungeon_board_cell_'+(j+1)+'_'+(i), 'class': 'dungeon_cell', }).appendTo('#dungeon_board_row_'+(i));
					var random_empty_tile_number = Math.floor(Math.random() * 45 + 1);
					var random_empty_tile_direction = Math.floor(Math.random() * 4 + 1);
					$('#dungeon_board_cell_'+(j+1)+'_'+(i)).css("background-image", "url('"+image_directory+"empty/"+random_empty_tile_number+".jpg')");
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
			monster_dead = true;
			trap_triggered = false;
			trap_triggered_type = "";
			
			empty_map_orientation = {};
			
			hero_map = {};
			reset_game_board();
			hero_loot = [];
			encounter_queue = [];
			
			used_chamber_tiles = [];
			used_treasure_cards = [];
			used_dragon_cards = [];
			used_corpse_cards = [];
			used_crypt_cards = [];
			used_search_cards = [];
			
			$('.console').empty();
			$('.loot_console').empty();
			$('.ferrox_battle_console').empty();
			
			write_to_console("Use Keyboard or Swipe to Move");
			$('.loot_console').prepend("<br/>");
			
			remove_heros();
			draw_hero();
			update_timer();
			
			var dice1 = Math.floor(Math.random() * 6 + 1);
			mini_dice.dataset.side = dice1;
			mini_dice.classList.toggle("reRoll");
			
			var dice2 = Math.floor(Math.random() * 6 + 1);
			mini_dice_2.dataset.side = dice2;
			mini_dice_2.classList.toggle("reRoll");
			
			$('.dice_total').html(dice1 + dice2);
			
		}
		
		function reset_game() {
		}
		
		function reset_game_board(){
			
			for (var i = boardgame_height; i > 0; i--){
				for (var j = 0; j < boardgame_width; j++) {
					var random_empty_tile_number = Math.floor(Math.random() * 45 + 1);
					var random_empty_tile_direction = Math.floor(Math.random() * 4 + 1);
					$('#dungeon_board_cell_'+(j+1)+'_'+(i)).css("background-image", "url('"+image_directory+"empty/"+random_empty_tile_number+".jpg')");
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
				$('#dungeon_board_cell_'+(hero1.x)+'_'+(hero1.y)).append('<div style="height: 100%; width: 100%; background-size: 30%; background-position: center; background-repeat: no-repeat; background-image: content(\''+character_image_directory+hero1.image_url_2+'\')"></div>');
				
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
					$('#mini_board_cell_'+eval(0-x_adjustment)+'_'+eval(0-y_adjustment)+'').append('<div style="height: 100%; width: 100%; display: flex; background-size: 30%; background-position: center; background-repeat: no-repeat; background-image: url(\''+character_image_directory+hero1.image_url_2+'\')"></div>');
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
					$('#medium_board_cell_'+eval(0-x_adjustment)+'_'+eval(0-y_adjustment)+'').append('<div style="height: 100%; width: 100%; display: flex; background-size: 30%; background-position: center; background-repeat: no-repeat; background-image: url(\''+character_image_directory+hero1.image_url_2+'\')"></div>');
				}
			}
			
			var current_chamber = hero_map[hero1.x*100 + hero1.y];
			
			switch(current_chamber.type) {
				case 'starting':
					write_to_console('Move: ' + current_chamber.description);
					break;
				case 'chamber':
					write_to_console('Move: ' + current_chamber.description);
					break;
				case 'corridor':
					write_to_console('Move: ' + current_chamber.description);
					break;
				case 'door':
					write_to_console('Move: ' + current_chamber.description);
					break;
				case 'bridge':
					write_to_console('Move: ' + current_chamber.description);
					break;
				case 'pit':
					write_to_console('Move: ' + current_chamber.description);
					break;
				case 'rotating':
					write_to_console('Move: ' + current_chamber.description);
					break;
				case 'leftchasm':
					write_to_console('Move: ' + current_chamber.description);
					break;
				case 'rightchasm':
					write_to_console('Move: ' + current_chamber.description);
					break;
				case 'trap':
					write_to_console('Move: ' + current_chamber.description);
					break;
				case 'darkness':
					write_to_console('Move: ' + current_chamber.description);
					break;
				case 'spiderweb':
					write_to_console('Move: ' + current_chamber.description);
					break;
				case 'rubble':
					write_to_console('Move: ' + current_chamber.description);
					break;
				case 'catacombs':
					write_to_console('Move: ' + current_chamber.description);
					break;
				case 'portcullis':
					write_to_console('Move: ' + current_chamber.description);
					break;
				case 'treasure':
					write_to_console('Move: ' + current_chamber.description);
					break;
			}
			
			var current_chamber = hero_map[hero1.x*100 + hero1.y];
			
			$('.search_button').attr('disabled', true);
			$('.crypt_button').attr('disabled', true);
			$('.corpse_button').attr('disabled', true);
			$('.treasure_button').attr('disabled', true);
			$('.catacombs_button').attr('disabled', true);
			
			if (current_chamber.searchable == '1' && current_chamber.searched == '0') {
				$('.search_button').attr('disabled', false);
			}
			if (current_chamber.crypt == '1' && current_chamber.crypt_searched == '0') {
				$('.crypt_button').attr('disabled', false);
			}
			if (current_chamber.corpse == '1' && current_chamber.corpse_searched == '0') {
				$('.corpse_button').attr('disabled', false);
			}
			if (current_chamber.type == 'treasure') {
				$('.treasure_button').attr('disabled', false);
			}
			
			current_chamber = hero_map[eval(hero1.x*100 + hero1.y)];
			
			// encounter or trap
			switch(current_chamber.type) {
				case 'chamber': // passage
				case 'door': // corridor
				case 'leftchasm': // trap
				case 'rightchasm': // trap
				case 'catacombs': // trap
				case 'rubble': // trap
				case 'portcullis': // trap
					encounter_chamber();
					break;
				case 'trap': // trap
					trigger_trap("");
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
			
			// finish unresolved encounter
			if (event_not_resolved()) { return; }
			
			// do not allow movement if modal is open
			// if ($('.trap_modal').data('bs.modal')?.isShown) { return; }
			
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
								previous_direction = p_direction;
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
								previous_direction = p_direction;
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
								previous_direction = p_direction;
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
								previous_direction = p_direction;
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
				if (hero_map[hero1.x*100 + hero1.y].type != 'corridor') {
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
					toggle_alert_modal('success','Exit Dungeon','','You left the dungeon with items worth '+loot_total()+' Gold!');
				}, 100);
				
			}
			// calculate treasure loot and record detail
		}
		
		function is_exit_chamber(p_Hero) {
			var current_chamber = hero_map[hero1.x*100 + hero1.y];
			
			if (current_chamber != undefined) {
				return hero_map[hero1.x*100 + hero1.y].type == "starting";
			} else {
				return false;
			}
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
						if (p_curr_hero_chamber.top == '3') {
							add_encounter('door', p_direction);
							encounter_counter++;
						}
						if (p_curr_hero_chamber.top == '5') {
							add_encounter("portcullis", p_direction);
							encounter_counter++;
						}
						if (p_curr_hero_chamber.top == '8' && p_curr_hero_chamber.stuck_in_spiderweb == '1') {
							add_encounter('spiderweb', p_direction);
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
						if (p_curr_hero_chamber.right == '3') {
							add_encounter("door", p_direction);
							encounter_counter++;
						}
						if (p_curr_hero_chamber.right == '5') {
							add_encounter("portcullis", p_direction);
							encounter_counter++;
						}
						if (p_curr_hero_chamber.right == '8' && p_curr_hero_chamber.stuck_in_spiderweb == '1') {
							add_encounter('spiderweb', p_direction);
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
						if (p_curr_hero_chamber.bottom == '3') {
							add_encounter("door", p_direction);
							encounter_counter++;
						}
						if (p_curr_hero_chamber.bottom == '5') {
							add_encounter("portcullis", p_direction);
							encounter_counter++;
						}
						if (p_curr_hero_chamber.bottom == '8' && p_curr_hero_chamber.stuck_in_spiderweb == '1') {
							add_encounter('spiderweb', p_direction);
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
						if (p_curr_hero_chamber.left == '3') {
							add_encounter("door", p_direction);
							encounter_counter++;
						}
						if (p_curr_hero_chamber.left == '5') {
							add_encounter("portcullis", p_direction);
							encounter_counter++;
						}
						if (p_curr_hero_chamber.left == '8' && p_curr_hero_chamber.stuck_in_spiderweb == '1') {
							add_encounter('spiderweb', p_direction);
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
			
			if (debug) {
				//console_to_console("roll_dice");
			}
			
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
				
				// determine Ferrox life
				dice1 = Math.floor(Math.random() * 6 + 1);
				mini_dice.dataset.side = dice1;
				mini_dice.classList.toggle("reRoll");
				
				total = dice1;
				$('.dice_total').html(total);
				
				return total;
				
			} else if (p_dice == 2) {
				
				// test Strength to fight Ferrox
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
				return;
			}
			
			if (total <= hero1.strength) {
				$('.dice_total').css('background-color', '#01E501');
				$('.dice_total').css('color', '#FFFFFF');
				write_to_console("["+total+"] You wounded Ferrox");
				
				play_audio("monster_wounded");
				
				update_ferrox_health(-1);
				if (parseInt(ferrox_monster.health) <= 0) {
					write_to_console("You killed the Ferrox!");
					$('.ferrox_attack_text').css("background-color", "#01E501");
					$('.ferrox_attack_text').css("color", "white");
					$('.ferrox_attack_text').html("You killed the Ferrox!");
					ferrox_dead = true;
				}
				
			} else {
				
				$('.dice_total').css('background-color', '#D1001C');
				$('.dice_total').css('color', '#FFFFFF');
				write_to_console("["+total+"] You received 1 wound");
				
				play_audio("hero_wounded");
				
				update_hero_health(-1);
				if (parseInt(hero1.health) <= 0) {
					write_to_console("You were killed by the Ferrox!");
					$('.ferrox_attack_text').css("background-color", "#D1001C");
					$('.ferrox_attack_text').css("color", "white");
					$('.ferrox_attack_text').html("You were killed by the Ferrox!");
					game_over = true;
				}
			}
			
			if (ferrox_dead) {
				$('.ferrox_attack_text').html("You killed the Ferrox!");
				$('#fight_ferrox_button').attr("disabled", true);
				update_timer();
				return;
			}
			
			if (hero1.health <= 0) {
				$('#fight_ferrox_button').attr("disabled", true);
				update_timer();
				return;
			}
		
			return total;
		}
		
		
		
		function roll_battle_dice(p_button) {
			
			var dice1;
			var dice2;
			var total = 0;
			
			if (p_button == 'fight') {
				
				// determine Monster life
				dice1 = Math.floor(Math.random() * 6 + 1);
				mini_dice.dataset.side = dice1;
				mini_dice.classList.toggle("reRoll");
				
				total = dice1;
				$('.dice_total').html(total);
				

				
			} else if (p_button == 'flee') {
				
				// test Agility to flee Monster
				dice1 = Math.floor(Math.random() * 6 + 1);
				mini_dice.dataset.side = dice1;
				mini_dice.classList.toggle("reRoll");
				
				dice2 = Math.floor(Math.random() * 6 + 1);
				mini_dice_2.dataset.side = dice2;
				mini_dice_2.classList.toggle("reRoll");
				
				total = dice1 + dice2;
				$('.dice_total').html(total);
				
			}
			
			if (monster_dead || hero1.health <= 0) {
				return;
			}
			
			if (p_button == 'fight') { 
			
				switch (total.toString()) {
					case '1':
					case '2':
						$('.dice_total').css('background-color', '#D1001C');
						$('.dice_total').css('color', '#FFFFFF');
						write_to_console("["+total+"] You received 1 wound");
						update_hero_health(-1);
						play_audio("hero_wounded");
						break;
					case '3':
					case '4':
						$('.dice_total').css('background-color', '#01E501');
						$('.dice_total').css('color', '#FFFFFF');
						write_to_console("["+total+"] You wounded Monster");
						$('.dice_total').css('background-color', '#D1001C');
						$('.dice_total').css('color', '#FFFFFF');
						write_to_console("["+total+"] You received 1 wound");
						update_hero_health(-1);
						update_monster_health(-1);
						play_audio("hero_wounded");
						play_audio("monster_wounded");
						break;
					case '5':
						$('.dice_total').css('background-color', '#01E501');
						$('.dice_total').css('color', '#FFFFFF');
						write_to_console("["+total+"] You wounded Monster");
						update_monster_health(-1);
						play_audio("monster_wounded");
						break;
					case '6':
						$('.dice_total').css('background-color', '#01E501');
						$('.dice_total').css('color', '#FFFFFF');
						write_to_console("["+total+"] You wounded Monster");
						update_monster_health(-2);
						play_audio("monster_wounded");
						break;
				}
				
				if (parseInt(hero1.health) <= 0) {
					write_to_console("You were killed by the Monster!");
					$('.battle_attack_text').css("background-color", "#D1001C");
					$('.battle_attack_text').css("color", "white");
					$('.battle_attack_text').html("You were killed by the Monster!");
					game_over = true;
					$('#fight_button').attr("disabled", true);
					$('#flee_button').attr("disabled", true);
					update_timer();
					return;
				}
				
				if (parseInt(monster.health) <= 0) {
					write_to_console("You killed the Monster!");
					$('.battle_attack_text').css("background-color", "#01E501");
					$('.battle_attack_text').css("color", "white");
					$('.battle_attack_text').html("You killed the Monster!");
					monster_dead = true;
					$('#fight_button').attr("disabled", true);
					$('#flee_button').attr("disabled", true);
					update_timer();
					return;
				}
			
			} else if (p_button == 'flee') {
				
				if (total <= hero1.agility) {
					
					$('.dice_total').css('background-color', '#01E501');
					$('.dice_total').css('color', '#FFFFFF');
					write_to_console("["+total+"] You escaped");
					
					update_hero_health(-monster.escape_penalty);
					write_to_console("You escaped!");
					write_to_console("You received "+monster.escape_penalty+" wounds!");
					$('.battle_attack_text').html("You escaped! <br/> You received "+monster.escape_penalty+" wounds!");
					play_audio("hero_wounded");
				
					if (parseInt(hero1.health) <= 0) {
						write_to_console("You were killed by the Monster!");
						$('.battle_attack_text').css("background-color", "#D1001C");
						$('.battle_attack_text').css("color", "white");
						$('.battle_attack_text').html("You were killed by the Monster!");
						game_over = true;
						$('#fight_button').attr("disabled", true);
						$('#flee_button').attr("disabled", true);
						update_timer();
						return;
					}
					
					monster_dead = true;
					
				} else {
					
					$('.dice_total').css('background-color', '#D1001C');
					$('.dice_total').css('color', '#FFFFFF');
					write_to_console("["+total+"] You failed to escape");
					write_to_console("["+total+"] You received 1 wound");
					
					update_hero_health(-1);
					play_audio("hero_wounded");
				
					if (parseInt(hero1.health) <= 0) {
						write_to_console("You were killed by the Monster!");
						$('.battle_attack_text').css("background-color", "#D1001C");
						$('.battle_attack_text').css("color", "white");
						$('.battle_attack_text').html("You were killed by the Monster!");
						game_over = true;
						$('#fight_button').attr("disabled", true);
						$('#flee_button').attr("disabled", true);
						update_timer();
						return;
					}
					

				}
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
				
				// Dice Color
				switch(current_encounter.type) {
					case "darkness":
					case "torchout":
						$('.dice_total').css('background-color', '#000000');
						$('.dice_total').css('color', '#FFFFFF');
						play_audio("torchout");
						break;
				}
				
				// Auto Resolve
				switch(current_encounter.type) {
					case "opendoor":
					case "alreadysearched":
					case "secretdoor":
					case "emptyroom":
					case "finditem":
					case "passagedown":
					case "cryptalreadysearched":
					case "emptycrypt":
					case "finditem":
					case "corpsealreadysearched":
					case "emptycorpse":
					case "finditem":
					case "catacombs":
						current_encounter.resolved = '1';
						current_encounter.success = '1';
						$('#trap_button').attr("disabled", true);
						break;
					case "jammeddoor":
						current_encounter.resolved = '1';
						current_encounter.success = '0';
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
			if (String(curr_hero_chamber.id) == '107' || String(curr_hero_chamber.id) == '108') {
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
			} else if (String(curr_hero_chamber.id) == '109') {
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
			} else if (String(curr_hero_chamber.id) == '110') {
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
				$('.trap_text').html('You aimlessly wandered east');
				new_direction = '2';
			} else if (String(curr_hero_chamber.orientation) == '90') {
				next_hero_chamber = hero_map[(hero1.x)*100 + hero1.y-1];
				$('.trap_text').html('You aimlessly wandered south');
				new_direction = '3';
			} else if (String(curr_hero_chamber.orientation) == '180') {
				next_hero_chamber = hero_map[(hero1.x-1)*100 + hero1.y];
				$('.trap_text').html('You aimlessly wandered west');
				new_direction = '4';
			} else if (String(curr_hero_chamber.orientation) == '270') {
				next_hero_chamber = hero_map[(hero1.x)*100 + hero1.y+1];
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
				$('.trap_text').html('You aimlessly wandered west');
				new_direction = '4';
			} else if (String(curr_hero_chamber.orientation) == '90') {
				next_hero_chamber = hero_map[(hero1.x)*100 + hero1.y+1];
				$('.trap_text').html('You aimlessly wandered north');
				new_direction = '1';
			} else if (String(curr_hero_chamber.orientation) == '180') {
				next_hero_chamber = hero_map[(hero1.x+1)*100 + hero1.y];
				$('.trap_text').html('You aimlessly wandered east');
				new_direction = '2';
			} else if (String(curr_hero_chamber.orientation) == '270') {
				next_hero_chamber = hero_map[(hero1.x)*100 + hero1.y-1];
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
				$('.trap_text').html('You aimlessly wandered south');
				new_direction = '3';
			} else if (String(curr_hero_chamber.orientation) == '90') {
				next_hero_chamber = hero_map[(hero1.x-1)*100 + hero1.y];
				$('.trap_text').html('You aimlessly wandered west');
				new_direction = '4';
			} else if (String(curr_hero_chamber.orientation) == '180') {
				next_hero_chamber = hero_map[hero1.x*100 + hero1.y+1];
				$('.trap_text').html('You aimlessly wandered north');
				new_direction = '1';
			} else if (String(curr_hero_chamber.orientation) == '270') {
				next_hero_chamber = hero_map[(hero1.x+1)*100 + hero1.y];
				$('.trap_text').html('You aimlessly wandered east');
				new_direction = '2';
			}
			encounter_queue[0].direction = new_direction;
			
			prep_hero_movement_into_darkness(p_dice_roll, p_Direction, new_direction, next_hero_chamber);

		}
		
		function prep_hero_movement_into_darkness(p_dice_roll, p_direction, p_new_direction, next_hero_chamber) {
			
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
								add_encounter("portcullis", p_new_direction);
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
								add_encounter("portcullis", p_new_direction);
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
								add_encounter("portcullis", p_new_direction);
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
								add_encounter("portcullis", p_new_direction);
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
				write_to_console("["+p_dice_roll+"] Your orientation got turned around");
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
			
			if (!is_exit_chamber(hero1) && !is_treasure_chamber(hero1)) {
				
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
					
					write_to_debugger('Draw Tile: ' + randomNumber + ": " + new_top + " " + new_right + " " + new_bottom + " " + new_left + " " + randomChamber.type);
					
					$('#dungeon_board_cell_'+(hero1.x)+'_'+(hero1.y)).css("background-image", "url('"+image_directory+randomChamber.image_url+"')");
					$('#dungeon_board_cell_'+(hero1.x)+'_'+(hero1.y)).css("transform", "rotate("+eval((p_direction-1)*90)+"deg)");


					if (current_chamber.type == "rotating") {
						// rotate tile 90 degrees clockwise
						write_to_console("You feel the ground shaking");
						
						var temp_chamber_top = current_chamber.top;
						current_chamber.top = current_chamber.bottom;
						current_chamber.bottom = temp_chamber_top;
						var temp_chamber_right = current_chamber.right;
						current_chamber.right = current_chamber.left;
						current_chamber.left = temp_chamber_right;
						current_chamber.orientation = eval((p_direction-1+2)*90)
						
						$('#dungeon_board_cell_'+(hero1.x)+'_'+(hero1.y)).css("transform", "rotate("+current_chamber.orientation+"deg)");
						play_audio('rotating_room');
					}

					if (current_chamber.type == "portcullis") {
						// rotate tile 90 degrees clockwise
						write_to_console("The Portcullis closes behind you!");
						play_audio('portcullis_close');
					}
					

					// set searchable
					switch(current_chamber.type) {
						case 'chamber': // passage
						case 'door': // corridor
						case 'rotating': // portcullis
						case 'catacombs': // trap
						case 'portcullis': // trap
							current_chamber.searchable = '1';
							break;
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
		//13 door
		//14 portcullis
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
		//99 dragon
		
		function update_timer(){
			timer--;
			$('.timer').html(timer);
			if (timer == 0 && !check_hero_exit()) {
				setTimeout(function () {
					game_over = true;
					add_encounter('gameover', '0');
					force_trap_modal();
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
		
		function search_chamber(){
			
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
			
			if (debug || curr_hero_chamber.searchable == '1') {
					
				if (debug || parseInt(curr_hero_chamber.searched) < 1) {
					// search the room
					curr_hero_chamber.searched = '1';
					
					// draw random room
					var randomNumber;
					var randomDraw;
					
					randomNumber = Math.floor(Math.random() * 70+1);
					randomDraw = SearchJSON[randomNumber];
						
					do {
						randomNumber = Math.floor(Math.random() * 70+1);
						randomDraw = SearchJSON[randomNumber];
					} while (randomDraw == undefined || search_card_already_used(randomNumber));
					
					if (randomDraw.type == "finditem") {
						used_search_cards.push(randomNumber);
					}
					
					write_to_console('You searched the Chamber');
					//write_to_debugger("Search: " + randomNumber + ": " + randomDraw.type);
					
					switch (randomDraw.type) {
						case "secretdoor":
							write_to_console('Search: You find a Secret Door');
							write_to_console('You may move to any adjacent space');
							curr_hero_chamber.secret_door = '1';
							//add_encounter('secretdoor', '0');
							//force_trap_modal();
							//roll_trap_dice();
							break;
						case "emptyroom":
							write_to_console('Search: The Room is Empty');
							//add_encounter('emptyroom', '0');
							//force_trap_modal();
							//roll_trap_dice();
							break;
						case "finditem":
							add_hero_item(randomDraw.id, randomDraw.name, randomDraw.value, '', '');
							write_to_console('Search: You find ' + randomDraw.name);
							//add_encounter('finditem', '0');
							//force_trap_modal();
							//roll_trap_dice();
							play_audio("gold");
							break;
						case "centipede":
							write_to_console('Search: A Giant Centipede!');
							add_encounter('centipede', '0')
							force_trap_modal();
							break;
						case "ferrox":
							fight_ferrox();
							break;
						case "trap":
							write_to_console('Search: Triggered a Trap!');
							trigger_trap("");
							break;
						case "passagedown":
							write_to_console('Search: Found passage to Catacombs!');
							curr_hero_chamber.catacombs = '1';
							//add_encounter('passagedown', '0');
							//force_trap_modal();
							//roll_trap_dice();
							break;
					}
					
				} else {
					//add_encounter('alreadysearched', '0');
					write_to_console('Search: Chamber already searched');
					//force_trap_modal();
					//roll_trap_dice();
				}
			} else {
				write_to_console("There is nothing to search here");
			}
		}
		
		function search_crypt(){
			
			if (event_not_resolved()) { return; }
			
			var curr_hero_chamber = hero_map[hero1.x*100 + hero1.y];
			
			if (debug || parseInt(curr_hero_chamber.crypt) > 0) {
				if (debug || parseInt(curr_hero_chamber.crypt_searched) < 1) {
					
					var randomNumber = Math.floor(Math.random() * 14 + 1);
					var randomDraw = CryptJSON[randomNumber];
					
					do {
						randomNumber = Math.floor(Math.random() * 14 + 1);
						randomDraw = CryptJSON[randomNumber];
					} while (randomDraw == undefined || crypt_card_already_used(randomNumber))
						
					if (randomDraw.type == "finditem") {
						used_crypt_cards.push(randomNumber);
					}
						
					curr_hero_chamber.crypt_searched = '1';
					
					write_to_console('You searched the Crypt');
					//write_to_debugger("Search Crypt: " + randomNumber + ": " + randomDraw.type);
					
					switch (randomDraw.type) {
						case "emptycrypt":
							write_to_console('Search: Crypt is Empty');
							//add_encounter('emptycrypt', '0');
							//force_trap_modal();
							//roll_trap_dice();
							update_timer();
							break;
						case "finditem":
							add_hero_item(randomDraw.id, randomDraw.name, randomDraw.value, '', '');
							write_to_console('Search: You find ' + randomDraw.name);
							//add_encounter('findcryptitem', '0');
							//force_trap_modal();
							//roll_trap_dice();
							play_audio("gold");
							update_timer();
							break;
						case "hiddentrap":
							write_to_console('Search: Triggered Hidden Trap');
							trigger_trap("");
							break;
						case "spectralstorm":
							write_to_console('Search: Army of Undead');
							write_to_console('[ Army of Undead not Implemented ]');
							//add_encounter('spectralstorm', '0')
							//force_trap_modal();
							break;
						case "oldbones":
							write_to_console('Search: Skeleton [Not Implemented]');
							//add_encounter('oldbones', '0')
							//force_trap_modal();
							//roll_trap_dice();
							update_timer();
							break;
					}
					
					update_timer();

				} else {
					//add_encounter('cryptalreadysearched', '0');
					write_to_console('Search: Crypt already Searched');
					//force_trap_modal();
					//roll_trap_dice();
				}
			} else {
				write_to_console('There is no Crypt');
			}
		}
		
		function search_corpse(){

			if (event_not_resolved()) { return; }
			
			var curr_hero_chamber = hero_map[hero1.x*100 + hero1.y];
			
			if (debug || parseInt(curr_hero_chamber.corpse) > 0) {
				if (debug || parseInt(curr_hero_chamber.corpse_searched) < 1) {

					var randomNumber = Math.floor(Math.random() * 14 + 1);
					var randomDraw = CorpseJSON[randomNumber];
					
					do {
						randomNumber = Math.floor(Math.random() * 14 + 1);
						randomDraw = CorpseJSON[randomNumber];
					} while (randomDraw == undefined || corpse_card_already_used(randomNumber))
						
					if (randomDraw.type == "finditem") {
						used_corpse_cards.push(randomNumber);
					}
						
					curr_hero_chamber.corpse_searched = '1';
					
					write_to_console('You searched the Corpse');
					//write_to_debugger("Search Corpse: " + randomNumber + ": " + randomDraw.type);
					
					switch (randomDraw.type) {
						case "emptycorpse":
							write_to_console('Search: Corpse is Empty');
							//add_encounter('emptycorpse', '0');
							//force_trap_modal();
							//roll_trap_dice();
							update_timer();
							break;
						case "finditem":
							add_hero_item(randomDraw.id, randomDraw.name, randomDraw.value, '', '');
							write_to_console('Search: You find ' + randomDraw.name);
							//add_encounter('findcorpseitem', '0');
							//force_trap_modal();
							//roll_trap_dice();
							play_audio("gold");
							update_timer();
							break;
						case "scorpion":
							write_to_console('Search: Scorpion');
							add_encounter('scorpion', '0')
							force_trap_modal();
							break;
						case "oldbones":
							write_to_console('Search: Skeleton [Not Implemented]');
							//add_encounter('oldbones', '0')
							//force_trap_modal();
							//roll_trap_dice();
							update_timer();
							break;
					}
					
				} else {
					//add_encounter('corpsealreadysearched', '0');
					write_to_console('Search: Corpse already Searched');
					//force_trap_modal();
					//roll_trap_dice();
				}
			} else {
				write_to_console('There is no Corpse');
			}
		}
		
		function encounter_chamber() {
			
			//if (event_not_resolved()) { 
			//	write_to_debugger("encounter_chamber: event_not_resolved");
			//	return; 
			//}
			
			var curr_hero_chamber = hero_map[hero1.x*100 + hero1.y];
			
			var randomNumber = Math.floor(Math.random() * 70 + 1);
			var randomDraw = ChamberEventJSON[randomNumber];
			
			do {
				randomNumber = Math.floor(Math.random() * 70 + 1);
				randomDraw = ChamberEventJSON[randomNumber];
			} while (randomDraw == undefined)
			
			//write_to_console('Encounter Chamber: '+randomDraw.name);
			write_to_debugger("Encounter Chamber: " + randomNumber + ": " + randomDraw.type);
			
			switch (randomDraw.type) {
				case "deadadventurer":
					write_to_console('Encounter: You see a Corpse');
					curr_hero_chamber.corpse = '1';
					if (curr_hero_chamber.corpse == '1' && curr_hero_chamber.corpse_searched == '0') {
						$('.corpse_button').attr('disabled', false);
					}
					break;
				case "crypt":
					write_to_console('Encounter: You see a Crypt');
					curr_hero_chamber.crypt = '1';
					if (curr_hero_chamber.crypt == '1' && curr_hero_chamber.crypt_searched == '0') {
						$('.crypt_button').attr('disabled', false);
					}
					break;
				case "emptychamber":
					write_to_console('Encounter: The Chamber is Empty');
					break;
				case "ambush":
					write_to_console('Encounter: A Monster Attacks You!');
					fight_monster();
					break;
				case "hiddentrap":
					write_to_console('Encounter: You sprung a Hidden Trap');
					trigger_trap("");
					break;
				case "passagedown":
					write_to_console('Encounter: You see a Passage going down');
					curr_hero_chamber.catacombs = '1';
					break;
				case "collapse":
					write_to_console('Encounter: The Ceiling Collapsed');
					add_encounter('collapse', '0')
					force_trap_modal();
					//roll_trap_dice();
					break;
				case "torchout":
					write_to_console('Encounter: Your Torch goes out');
					hero1.torch = '0';
					add_encounter('torchout', '0');
					force_trap_modal();
					break;
				case "secretdoor":
					write_to_console('Encounter: You find a Secret Door');
					curr_hero_chamber.secret_door = '1';
					break;
				case "razorwingattack":
					write_to_console('Encounter: A Razorwing Attacks you');
					add_encounter('razorwingattack', '0')
					force_trap_modal();
					break;
				case "wizardscurse":
					write_to_console('Encounter: A Wizard Curses the Dungeon');
					// Magic
					break;
				case "finditem":
					//write_to_console('Encounter: You find an Item');
					//add_encounter('finditem', '0');
					add_hero_item(randomDraw.id, randomDraw.name, randomDraw.value, '', '');
					write_to_console('You find ' + randomDraw.name);
					//force_trap_modal();
					//roll_trap_dice();
					play_audio("gold");
					break;
				case "lingeringshade":
					write_to_console('Encounter: Lingering Shade');
					curr_hero_chamber.lingering_shade = '1';
					break;
			}
		}
		
		var ferrox_monster = new Ferrox();
		var monster = new Monster();
		
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
			
			write_to_console("A Ferrox appears and attacks you!");
			write_to_console("The Ferrox has "+ferrox_monster.health+" health");
			
			draw_ferrox_stats("ferrox", ferrox_monster);
			
		}
		
		function fight_monster() {
			
			if (event_not_resolved()) { return; }
			
			var randomNumber = Math.floor(Math.random() * MonsterJSON.length + 1);
			var randomDraw = MonsterJSON[randomNumber];
			
			do {
				randomNumber = Math.floor(Math.random() * 14 + 1);
				randomDraw = MonsterJSON[randomNumber];
			} while (randomDraw == undefined)
				
			write_to_debugger(randomDraw.name);
			write_to_debugger(randomDraw.type);
			write_to_debugger(randomDraw.health);
			write_to_debugger(randomDraw.escape_penalty);
			write_to_debugger(randomDraw.image_url);
			
			
			$('.battle_dice_placeholder').append($('.mini_dice_sheet'));
			$('#battle_modal').modal('toggle');

			$('#fight_button').attr("disabled", false);
			$('#flee_button').attr("disabled", false);
			
			$('.battle_attack_text').css("background-color", "white");
			$('.battle_attack_text').css("color", "black");
			$('.battle_attack_text').html("Fight Monster");

			monster_dead = false;
			monster = new Monster();
			
			monster.name = randomDraw.name;
			monster.type = randomDraw.type;
			monster.health = randomDraw.health;
			monster.escape_penalty = randomDraw.escape_penalty;
			monster.image_url = randomDraw.image_url;
			
			$('.dice_total').css('background-color', '#D1001C');
			$('.dice_total').css('color', '#FFFFFF');
			
			write_to_console("A "+randomDraw.name+" appears and attacks you!");
			
			draw_monster_stats(monster);
			draw_hero_stats('hero1', hero1);
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
		
		function draw_monster_stats(p_monster) {
					
			$('.battle_monster_name').html(p_monster.name);
			$('.battle_monster_name_short').html(p_monster.name);
			$('.battle_monster_health').html(p_monster.health);
			$('.battle_monster_strength').html('?');
			$('.battle_monster_agility').html('?');
			$('.battle_monster_defense').html('?');
			$('.battle_monster_luck').html('?');
			$('.battle_monster_description').html(p_monster.description);
			$('.battle_monster_img').attr("src", "/dungeonquest/monsters/"+p_monster.image_url);
			$('.battle_monster_escape_penalty').html(p_monster.escape_penalty);
			
		}
		
		function enter_catacombs(){
			
			// Catacombs not implemented, you die
			add_encounter('catacombs', '0');
			force_trap_modal();
			roll_trap_dice();
		}
		
		function treasure_card_already_used(p_id) {
			for (var i = 0; i < used_treasure_cards.length; i++) {
				if (parseInt(used_treasure_cards[i]) == parseInt(p_id)) {
					return true;
				}
			}
			return false;
		}
		
		function chamber_tile_already_used(p_id) {
			for (var i = 0; i < used_chamber_tiles.length; i++) {
				if (used_chamber_tiles[i] == p_id) {
					return true;
				}
			}
			return false;
		}
		
		function dragon_card_already_used(p_id) {
			for (var i = 0; i < used_dragon_cards.length; i++) {
				if (parseInt(used_dragon_cards[i]) == parseInt(p_id)) {
					return true;
				}
			}
			return false;
		}
		
		function corpse_card_already_used(p_id) {
			for (var i = 0; i < used_corpse_cards.length; i++) {
				if (parseInt(used_corpse_cards[i]) == parseInt(p_id)) {
					return true;
				}
			}
			return false;
		}
		
		function crypt_card_already_used(p_id) {
			for (var i = 0; i < used_crypt_cards.length; i++) {
				if (parseInt(used_crypt_cards[i]) == parseInt(p_id)) {
					return true;
				}
			}
			return false;
		}
		
		function search_card_already_used(p_id) {
			for (var i = 0; i < used_search_cards.length; i++) {
				if (parseInt(used_search_cards[i]) == parseInt(p_id)) {
					return true;
				}
			}
			return false;
		}
		
		function search_dragon_chamber(){
			
			if (event_not_resolved()) { return; }

			var randomNumber;
			var randomDraw;
			
			if (used_dragon_cards.length >= Object.keys(DragonJSON).length) {
				write_to_console("The Treasure Chamber is empty");
				return;
			}
			
			for (var i = 0; i < used_dragon_cards.length; i++) {
				write_to_debugger("Past Dragon Events: "+used_dragon_cards[i]);
			}
			
			
			if ((debug || hero_map[hero1.x*100 + hero1.y].type == 'treasure') && 
				used_dragon_cards.length < Object.keys(DragonJSON).length && 
				used_treasure_cards.length < Object.keys(TreasureJSON).length) {
					
				write_to_console("You loot the Dragon Chamber");
					
				do {
					randomNumber = Math.floor(Math.random() * 8+1);
					randomDraw = DragonJSON[randomNumber];
				} while (randomDraw == undefined || (dragon_card_already_used(randomNumber) && used_dragon_cards.length < Object.keys(DragonJSON).length))
					
				// The Dragon will always be in the Deck
				if (randomDraw.awake != '1') {
					used_dragon_cards.push(randomNumber);
				}
				
				if (String(randomDraw.awake) == '0') {
					
					// The Dragon is Sleeping
					write_to_console('The dragon sleeping');
					
					if (used_treasure_cards.length < Object.keys(TreasureJSON).length) {
						do {
							randomNumber = Math.floor(Math.random() * 100+1);
							randomDraw = TreasureJSON[randomNumber];
						} while (randomDraw == undefined || (treasure_card_already_used(randomNumber) && used_treasure_cards.length < Object.keys(TreasureJSON).length))
						used_treasure_cards.push(randomNumber);
					
						add_hero_item(randomDraw.id, randomDraw.name, randomDraw.value, '', '');
						write_to_console('You find ' + randomDraw.name);
					}
					
					if (used_treasure_cards.length < Object.keys(TreasureJSON).length) {
						do {
							randomNumber = Math.floor(Math.random() * 100+1);
							randomDraw = TreasureJSON[randomNumber];
						} while (randomDraw == undefined || (treasure_card_already_used(randomNumber) && used_treasure_cards.length < Object.keys(TreasureJSON).length))
						used_treasure_cards.push(randomNumber);
					
						add_hero_item(randomDraw.id, randomDraw.name, randomDraw.value, '', '');
						write_to_console('You find ' + randomDraw.name);
					}
					
					play_audio("dragonsleeping");
					
				} else {
					
					// The Dragon Attacks
					add_encounter('dragonbreath', '0');
					force_trap_modal();
					play_audio("dragonroar");
					
				}
				
				update_timer();
				
			} else {
				write_to_console("The Treasure Chamber is empty");
			}

		}
		
		function add_hero_item(p_id, p_name, p_value, p_image_url, p_image_url_2) {
			hero_loot.push(new LootItem(Math.abs(timer-max_timer), p_id, p_name, p_value, p_image_url, p_image_url_2));
			draw_loot_bag();
		}
		
		function update_hero_health(p_health) {
			hero1.health = parseInt(hero1.health) + parseInt(p_health);
			draw_hero_stats('hero1', hero1);
		}
		
		function update_ferrox_health(p_health) {
			ferrox_monster.health = parseInt(ferrox_monster.health) + parseInt(p_health);
			draw_ferrox_stats('ferrox', ferrox_monster);
		}
		
		function update_monster_health(p_health) {
			monster.health = parseInt(monster.health) + parseInt(p_health);
			draw_monster_stats(monster);
		}
		
		function check_hero_health(){
			if (parseInt(hero1.health) <= 0) {
				write_to_console("You have died!");
				game_over = true;
				//toggle_ending_modal('death', 'Game Over','','You succumbed to your wounds!');
				add_encounter('death', '0');
				force_trap_modal();
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
					$('#alert_modal_text').css("background-color", "#01E501");
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
						
			var randomNumber = Math.floor(Math.random() * 7+1);
			var randomDraw = TrapsJSON[randomNumber];
			
			do {
				randomNumber = Math.floor(Math.random() * 7+1);
				randomDraw = TrapsJSON[randomNumber];
			} while (randomDraw == undefined)
			
			trap_triggered_type = randomDraw.type;
			trap_triggered = true;
			
			switch(trap_triggered_type) {
				case "trapdoor":
					write_to_console('Trap: Trapdoor');
					add_encounter('trapdoor', '0');
					force_trap_modal();
					break;
				case "snakes":
					write_to_console('Trap: Poisonous Snakes');
					add_encounter('snakes', '0');
					force_trap_modal();
					break;
				case "gas":
					write_to_console('Trap: Poisonous Gas');
					add_encounter('gas', '0');
					force_trap_modal();
					break;
				case "collapse":
					write_to_console('Trap: The Ceiling Collapses');
					add_encounter('collapse', '0');
					force_trap_modal();
					//roll_trap_dice();
					break;
				case "explosion":
					write_to_console('Trap: Triggered Explosion');
					add_encounter('explosion', '0');
					force_trap_modal();
					break;
				case "blade":
					write_to_console('Trap: Swinging Blade');
					add_encounter('blade', '0');
					force_trap_modal();
					break;
				case "crossfire":
					write_to_console('Trap: Hidden Darts');
					add_encounter('crossfire', '0');
					force_trap_modal();
					break;
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
				
				// PASSAGE
				case "door":
					switch (randomDoor.value) {
						case "1":
							write_to_debugger("Bug: Add OpenDoor Encounter");
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
					new_encounter = new Encounter('bridge', 'Bridge', '#D1001C', 'white', 'Do you want to cross the Bridge? <br/> [ Test Agility - Loot Weight ]', p_direction);
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
					new_encounter = new Encounter('rubble', 'Rubble', '#D1001C', 'white', 'Do you want to move through the Rubble?', p_direction);
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
					
				// CHAMBER ENCOUNTERS
				case "finditem":
					new_encounter = new Encounter('finditem', 'Find Corpse Item', '#000000', 'white', 'You find an Item', p_direction);
					encounter_queue.push(new_encounter);
					break;
				case "razorwingattack":
					new_encounter = new Encounter('razorwingattack', 'Razorwing Attack', '#D1001C', 'white', 'You were attacked by a Razorwing', p_direction);
					encounter_queue.push(new_encounter);
					break;
				case "torchout":
					new_encounter = new Encounter('torchout', 'Torch Goes Out', '#000000', 'white', 'Your Torch Goes Out!', p_direction);
					encounter_queue.push(new_encounter);
					break;
				case "wizardscurse":
					new_encounter = new Encounter('wizardscurse', 'Wizard\'s Curse', '#000000', 'white', 'A Wizard Curses the Dungeon!', p_direction);
					encounter_queue.push(new_encounter);
					break;
				case "lingeringshade":
					new_encounter = new Encounter('lingeringshade', 'Lingering Shade', '#000000', 'white', 'A Lingering Shadow follows you!', p_direction);
					encounter_queue.push(new_encounter);
					break;
					
				// CATACOMBS
				case "catacombs":
					new_encounter = new Encounter('catacombs', 'Catacombs', '#000000', 'white', '[ Catacombs not Implemented ]', p_direction);
					encounter_queue.push(new_encounter);
					break;
					
					
				// SEARCH
				case "alreadysearched":
					new_encounter = new Encounter('alreadysearched', 'Already Searched', '#000000', 'white', 'The room has already been Searched', p_direction);
					encounter_queue.push(new_encounter);
					break;
				case "secretdoor":
					new_encounter = new Encounter('secretdoor', 'Secret Door', '#000000', 'white', 'You found a Secret Door', p_direction);
					encounter_queue.push(new_encounter);
					break;
				case "emptyroom":
					new_encounter = new Encounter('emptyroom', 'Empty Room', '#000000', 'white', 'The room is Empty', p_direction);
					encounter_queue.push(new_encounter);
					break;
				case "finditem":
					new_encounter = new Encounter('finditem', 'Find Item', '#000000', 'white', 'You find an Item', p_direction);
					encounter_queue.push(new_encounter);
					break;
				case "centipede":
					new_encounter = new Encounter('centipede', 'Find Item', '#D1001C', 'white', 'A Centipede Attacks You!', p_direction);
					encounter_queue.push(new_encounter);
					break;
				case "passagedown":
					new_encounter = new Encounter('passagedown', 'Passage Down', '#000000', 'white', 'You found a passage to the Catacombs!', p_direction);
					encounter_queue.push(new_encounter);
					break;
					
					
				// SEARCH CRYPT
				case "cryptalreadysearched":
					new_encounter = new Encounter('cryptalreadysearched', 'Crypt Already Searched', '#000000', 'white', 'The Crypt has already been Searched', p_direction);
					encounter_queue.push(new_encounter);
					break;
				case "emptycrypt":
					new_encounter = new Encounter('emptycrypt', 'Empty Crypt', '#000000', 'white', 'There is nothing in the Crypt', p_direction);
					encounter_queue.push(new_encounter);
					break;
				case "findcryptitem":
					new_encounter = new Encounter('finditem', 'Find Crypt Item', '#000000', 'white', 'You find an Item in the Crypt', p_direction);
					encounter_queue.push(new_encounter);
					break;
				case "oldbones":
					new_encounter = new Encounter('oldbones', 'Old Bones', '#D1001C', 'white', 'You see a Skeleton!', p_direction);
					encounter_queue.push(new_encounter);
					break;
				case "spectralstorm":
					new_encounter = new Encounter('spectralstorm', 'Spectral Storm', '#000000', 'white', 'An Army of Undead charges at you!', p_direction);
					encounter_queue.push(new_encounter);
					break;
				case "hiddentrap":
					new_encounter = new Encounter('hiddentrap', 'Hidden Trap', '#D1001C', 'white', 'You sprung a Trap!', p_direction);
					encounter_queue.push(new_encounter);
					break;
				
					
				// SEARCH CORPSE
				case "corpsealreadysearched":
					new_encounter = new Encounter('corpsealreadysearched', 'Corpse Already Searched', '#000000', 'white', 'The Corpse has already been Searched', p_direction);
					encounter_queue.push(new_encounter);
					break;
				case "emptycorpse":
					new_encounter = new Encounter('emptycorpse', 'Empty Corpse', '#000000', 'white', 'There is nothing on the Corpse', p_direction);
					encounter_queue.push(new_encounter);
					break;
				case "findcorpseitem":
					new_encounter = new Encounter('finditem', 'Find Corpse Item', '#000000', 'white', 'You find an Item on the Corpse', p_direction);
					encounter_queue.push(new_encounter);
					break;
				case "scorpion":
					new_encounter = new Encounter('scorpion', 'Scorpion', '#D1001C', 'white', 'A Scorpion Attacks You!', p_direction);
					encounter_queue.push(new_encounter);
					break;
				case "oldbones":
					new_encounter = new Encounter('oldbones', 'Old Bones', '#000000', 'white', 'You see a Skeleton!', p_direction);
					encounter_queue.push(new_encounter);
					break;
					
				// TRAP
				case "trapdoor":
					new_encounter = new Encounter('trapdoor', 'Trap Door', '#000000', 'white', 'A Trap Door opens beneath you! <br/> [ Test Agility ]', p_direction);
					encounter_queue.push(new_encounter);
					break;
				case "snakes":
					new_encounter = new Encounter('snakes', 'Poisonous Snakes', '#000000', 'white', 'A Poisonous Snake bites you!', p_direction);
					encounter_queue.push(new_encounter);
					break;
				case "gas":
					new_encounter = new Encounter('gas', 'Poisonous Gas', '#000000', 'white', 'Poisonous Gas spills into the room!', p_direction);
					encounter_queue.push(new_encounter);
					break;
				case "collapse":
					new_encounter = new Encounter('collapse', 'Ceiling Collapse', '#000000', 'white', 'The Ceiling Collapses!', p_direction);
					encounter_queue.push(new_encounter);
					break;
				case "explosion":
					new_encounter = new Encounter('explosion', 'Explosion', '#D1001C', 'white', 'You were hit by an explosion!', p_direction);
					encounter_queue.push(new_encounter);
					break;
				case "blade":
					new_encounter = new Encounter('blade', 'Swinging Blade', '#000000', 'white', 'A Blade swings down at you! <br/> [ Test Armor ]', p_direction);
					encounter_queue.push(new_encounter);
					break;
				case "crossfire":
					new_encounter = new Encounter('crossfire', 'Hidden Darts', '#000000', 'white', 'Hidden Darts fires at you! <br/> [ Test Armor ]', p_direction);
					encounter_queue.push(new_encounter);
					break;
					
				// TREASURE CHAMBER
				case "dragonbreath":
					new_encounter = new Encounter('dragonbreath', 'Dragon Breath', '#D1001C', 'white', 'The Dragon Attacks you!', p_direction);
					encounter_queue.push(new_encounter);
					break;
					
				// POTION
				case "potion":
					new_encounter = new Encounter('potion', 'Unstable Potion', '#D1001C', 'white', 'You drink Unstable Potion!', p_direction);
					encounter_queue.push(new_encounter);
					break;
					
				// TORCH
				case "torchout":
					new_encounter = new Encounter('torchout', 'Your Torch Goes Out', '#D1001C', 'white', 'Your Torch Goes Out!', p_direction);
					encounter_queue.push(new_encounter);
					break;
					
				// DEATH
				case "death":
					new_encounter = new Encounter('death', 'Death', '#D1001C', 'white', 'Your body lies lifeless in the Dungeon!', p_direction);
					encounter_queue.push(new_encounter);
					break;
					
				// GAMEOVER
				case "gameover":
					new_encounter = new Encounter('gameover', 'Game Over', '#D1001C', 'white', 'The Game has Ended!', p_direction);
					encounter_queue.push(new_encounter);
					break;
					
				
			}
		}
		
		function roll_trap_dice() {
			
			write_to_debugger("Roll Trap Dice");
			write_to_debugger("Trap Triggered: " + trap_triggered);
			write_to_debugger("Trap Triggered Type: " + trap_triggered_type);
			
			var dice1 = 0;
			var dice2 = 0;
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
					
				case "darkness":
				case "snakes":
				case "gas":
				case "explosion":
				case "speardoor":
				case "oldbones":
				case "scorpion":
				case "razorwingattack":
				
					dice1 = Math.floor(Math.random() * 6 + 1);
					mini_dice.dataset.side = dice1;
					mini_dice.classList.toggle("reRoll");
					break;

				default:
				
					dice1 = Math.floor(Math.random() * 6 + 1);
					mini_dice.dataset.side = dice1;
					mini_dice.classList.toggle("reRoll");
					
					dice2 = Math.floor(Math.random() * 6 + 1);
					mini_dice_2.dataset.side = dice2;
					mini_dice_2.classList.toggle("reRoll");
					break;
			}
			
			if (!trap_triggered) {
				$('.dice_total').html(dice1 + dice2);
				return;
			}
			
			if (hero1.health <= 0) {
				trap_triggered_type = "death";
			}
			
			
			switch(trap_triggered_type) {
				
				case "opendoor":
					
					encounter_queue[0].resolved = '1';
					encounter_queue[0].success = '1';
					
					write_to_console('Door was unlocked');
					$('.dice_total').css("background-color", "#000000");
					$('.dice_total').css("color", "black");
					$('.trap_text').html("Door was unlocked");
					play_audio("door");
					break;
					
				case "jammeddoor":
					
					encounter_queue[0].resolved = '1';
					encounter_queue[0].success = '0';
					
					write_to_console('The Door was Jammed Shut!');
					$('.dice_total').css("background-color", "#D1001C");
					$('.dice_total').css("color", "white");
					break;
					
				case "speardoor":
					
					total = dice1;
					$('.dice_total').html(total);
					encounter_queue[0].resolved = '1';
					encounter_queue[0].success = '0';
					
					write_to_console('['+total+'] You were stabbed by a Spear!');
					write_to_console('['+total+'] You suffer '+total+' wounds');
					$('.trap_text').html("You were stabbed by a Spear! <br/> You suffer "+total+" wounds!");
					$('.dice_total').css("background-color", "#D1001C");
					$('.dice_total').css("color", "white");
					update_hero_health(-total);
					if (hero1.health <= 0) {
						write_to_console('['+total+'] You were killed by the Spear!');
						$('.trap_text').html("You were killed by the Spear!");
						game_over = true;
					}
					break;
					
				case "trapdoor":
					
					total = dice1 + dice2;
					$('.dice_total').html(total);
					encounter_queue[0].resolved = '1';
					encounter_queue[0].success = '0';
					
					if (parseInt(total) <= parseInt(hero1.agility)) {
						write_to_console('['+total+'] You avoided the Trap Door!');
						$('.dice_total').css("background-color", "#01E501");
						$('.dice_total').css("color", "white");
						$('.trap_text').css("background-color", "#01E501");
						$('.trap_text').html("You avoided the Trap Door!");
					} else {
						write_to_console('['+total+'] [Catacombs not Implemented] You fell into the trapdoor!');
						write_to_console('['+total+'] You suffer '+total+' wounds');
						$('.dice_total').css("background-color", "#D1001C");
						$('.dice_total').css("color", "white");
						$('.trap_text').css("background-color", "#D1001C");
						$('.trap_text').html("[Catacombs not Implemented] <br/> You fell into the trapdoor! <br/> You suffer "+total+" wounds");
						update_hero_health(-total);
						if (hero1.health <= 0) {
							write_to_console('['+total+'] You were killed in the fall!');
							$('.trap_text').html("You were killed in the fall!");
							game_over = true;
						}
					}
					break;
					
				case "snakes":
					
					total = dice1;
					$('.dice_total').html(total);
					encounter_queue[0].resolved = '1';
					encounter_queue[0].success = '0';
					
					write_to_console('['+total+'] You were bitten by a Poisonous Snake!');
					write_to_console('['+total+'] You suffer '+ total +' wounds!');
					$('.trap_text').html("You suffer "+ total +" wounds!");
					$('.dice_total').css("background-color", "#D1001C");
					$('.dice_total').css("color", "white");
					update_hero_health(-total);
					if (hero1.health <= 0) {
						write_to_console('['+total+'] You are killed by the Poisonous Snake!');
						$('.trap_text').html("You are killed by the Poisonous Snake!");
						game_over = true;
					}
					break;
					
				case "gas":
					
					total = dice1;
					$('.dice_total').html(total);
					encounter_queue[0].resolved = '1';
					encounter_queue[0].success = '0';
					
					write_to_console('['+total+'] Poisonous Gas pours into the room!');
					$('.dice_total').css("background-color", "#D1001C");
					$('.dice_total').css("color", "white");
					
					if (total > 3) {
						$('.trap_text').html("You suffer "+ eval(total - 3) +" wounds and lose time!");
						write_to_console('['+total+'] You suffer '+ eval(total - 3) +' wounds!');
						write_to_console('['+total+'] You lose time!');
						update_hero_health(-eval(total - 3));
						for (var i = 0; i < total - 3; i++) {
							update_timer();
						}
						if (hero1.health <= 0) {
							write_to_console('['+total+'] You were killed by Poison!');
							$('.trap_text').html("You were killed by Poison!");
							game_over = true;
						}
					} else {
						write_to_console('['+total+'] You avoided the Poisonous Gas!');
						$('.trap_text').css("background-color", "#01E501");
						$('.trap_text').css("color", "white");
						$('.trap_text').html("You avoided the Poisonous Gas!");
					}
					break;
					
				case "explosion":
				
					total = dice1;
					$('.dice_total').html(total);
					encounter_queue[0].resolved = '1';
					encounter_queue[0].success = '0';
					
					play_audio("explosion");
					
					update_hero_health(-total);
					if (parseInt(hero1.health) <= 0) {
						write_to_console('['+total+'] You suffer '+total+' wounds!');
						write_to_console('['+total+'] You are killed by an Explosion!');
						$('.trap_text').html('You are killed by an Explosion!');
						game_over = true;
					} else {
						write_to_console('['+total+'] You are wounded by an Explosion!');
						write_to_console('['+total+'] You suffer '+total+' wounds!');
						$('.trap_text').html('You are wounded by an Explosion! <br/> You suffer '+total+' wounds!');
					}
					break;
					
				case "collapse":
				
					encounter_queue[0].resolved = '1';
					encounter_queue[0].success = '0';
					
					write_to_console('The Ceiling Collapsed!');
					write_to_console('Some passages are blocked!');
					write_to_console('Some passages opened!');
					$('.trap_text').html("The Ceiling Collapsed! <br/> Some passages are blocked! <br/> Some passages are opened!");
					$('.trap_text').css("background-color", "#D1001C");
					$('.trap_text').css("color", "white");
					
					play_audio("collapse");
					
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
					
					break;
					
				case "blade":
					
					total = dice1 + dice2;
					$('.dice_total').html(total);
					encounter_queue[0].resolved = '1';
					encounter_queue[0].success = '0';
					
					if (parseInt(total) <= parseInt(hero1.defense)) {
						write_to_console('['+total+'] The Swinging Blade deflected off your Armor!');
						$('.trap_text').html("The Swinging Blade deflected off your Armor!");
						$('.dice_total').css("background-color", "#01E501");
						$('.dice_total').css("color", "white");
						$('.trap_text').css("background-color", "#01E501");
					} else {
						write_to_console('['+total+'] You were killed by the Swinging Blade!');
						$('.trap_text').html("You were killed by the Swinging Blade!");
						$('.dice_total').css("background-color", "#D1001C");
						$('.dice_total').css("color", "white");
						update_hero_health(-hero1.health);
						game_over = true;
					}
					break;
					
				case "crossfire":
					
					total = dice1 + dice2;
					$('.dice_total').html(total);
					encounter_queue[0].resolved = '1';
					encounter_queue[0].success = '0';
					
					if (parseInt(total) <= parseInt(hero1.defense)) {
						write_to_console('['+total+'] You evaded the Hidden Darts!');
						$('.trap_text').html("You evaded the Hidden Darts!");
						$('.dice_total').css("background-color", "#01E501");
						$('.dice_total').css("color", "white");
						$('.trap_text').css("background-color", "#01E501");
					} else {
						var wounds = parseInt(total) - parseInt(hero1.defense);
						write_to_console('['+total+'] You were hit by Hidden Darts!');
						write_to_console('['+total+'] You suffer '+wounds+' wounds!');
						$('.trap_text').html("You were hit by the Hidden Darts! <br/> You suffer "+wounds+" wounds!");
						$('.dice_total').css("background-color", "#D1001C");
						$('.dice_total').css("color", "white");
						update_hero_health(-wounds);
						if (hero1.health <= 0) {
							write_to_console('['+total+'] You were killed by the Hidden Darts!');
							$('.trap_text').html("You were killed by the Hidden Darts!");
							game_over = true;
						}
					}
					break;
					
				case "centipede":
					
					total = dice1 + dice2;
					$('.dice_total').html(total);
					encounter_queue[0].resolved = '1';
					encounter_queue[0].success = '0';
					
					write_to_console('['+total+'] You were attacked by a Centipede!');
					write_to_console('['+total+'] You suffer '+total+' wounds!');
					$('.dice_total').css("background-color", "#D1001C");
					$('.dice_total').css("color", "white");
					$('.trap_text').html("You suffer "+total+" wounds!");
					update_hero_health(-total);
					if (hero1.health <= 0) {
						write_to_console('['+total+'] You are killed by the Centipede!');
						$('.trap_text').html("You are killed by the Centipede!");
						game_over = true;
					}
						
					break;
					
				case "bridge":
					
					total = dice1 + dice2;
					$('.dice_total').html(total);
					
					var hero_agility = hero1.agility - loot_weight();
					if (total <= hero_agility) {
						encounter_queue[0].resolved = '1';
						encounter_queue[0].success = '1';
						write_to_console('['+total+'] You crossed the Bridge!');
						$('.trap_text').html("You crossed the Bridge!");
						$('.dice_total').css("background-color", "#01E501");
						$('.dice_total').css("color", "white");
						play_audio('bridge');
					} else {
						encounter_queue[0].resolved = '1';
						encounter_queue[0].success = '0';
						write_to_console('['+total+'] You fell off the Bridge!');
						$('.trap_text').html("You fell off the Bridge!");
						$('.dice_total').css("background-color", "#D1001C");
						$('.dice_total').css("color", "white");
					}
					break;
					
				case "portcullis":
					
					total = dice1 + dice2;
					$('.dice_total').html(total);
					
					if (total <= hero1.strength) {
						encounter_queue[0].resolved = '1';
						encounter_queue[0].success = '1';
						write_to_console('['+total+'] You raised the Portcullis!');
						$('.trap_text').html("You raised the Portcullis!");
						$('.dice_total').css("background-color", "#01E501");
						$('.dice_total').css("color", "white");
						play_audio('portcullis_open');
					} else {
						encounter_queue[0].resolved = '1';
						encounter_queue[0].success = '0';
						write_to_console('['+total+'] You failed to raise the Portcullis!');
						$('.trap_text').html("You failed to raise the Portcullis!");
						$('.dice_total').css("background-color", "#D1001C");
						$('.dice_total').css("color", "white");
					}
					break;
					
				case "pit":
					
					total = dice1 + dice2;
					$('.dice_total').html(total);
					
					if (total <= hero1.luck) {
						encounter_queue[0].resolved = '1';
						encounter_queue[0].success = '1';
						write_to_console('['+total+'] You jumped over the Pit!');
						$('.trap_text').html("You jumped over the Pit!");
						$('.dice_total').css("background-color", "#01E501");
						$('.dice_total').css("color", "white");
						play_audio('jump');
					} else {
						encounter_queue[0].resolved = '1';
						encounter_queue[0].success = '0';
						$('.dice_total').css("background-color", "#D1001C");
						$('.dice_total').css("color", "white");
						update_hero_health(-hero1.health);
						if (hero1.health <= 0) {
							write_to_console('['+total+'] You fell into the Pit and died!');
							$('.trap_text').html("You fell into the Pit and died!");
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
						write_to_console('['+total+'] You found passage through the Rubble!');
						$('.trap_text').html("You found passage through the Rubble!");
						$('.dice_total').css("background-color", "#01E501");
						$('.dice_total').css("color", "white");
					} else {
						encounter_queue[0].resolved = '1';
						encounter_queue[0].success = '0';
						write_to_console('['+total+'] You failed to find passage!');
						$('.trap_text').html("You failed to find passage!");
						$('.dice_total').css("background-color", "#D1001C");
						$('.dice_total').css("color", "white");
					}
					break;
					
				case "spiderweb":
					
					total = dice1 + dice2;
					$('.dice_total').html(total);
					
					if (total <= hero1.strength) {
						encounter_queue[0].resolved = '1';
						encounter_queue[0].success = '1';
						hero_map[hero1.x*100 + hero1.y].stuck_in_spiderweb = '0';
						write_to_console('['+total+'] You broke through the Spider Web!');
						$('.trap_text').html("You broke through the Spider Web!");
						$('.dice_total').css("background-color", "#01E501");
						$('.dice_total').css("color", "white");
						play_audio("spiderweb");
					} else {
						encounter_queue[0].resolved = '1';
						encounter_queue[0].success = '0';
						hero_map[hero1.x*100 + hero1.y].stuck_in_spiderweb = '1';
						write_to_console('['+total+'] You are stuck in the Spider Web!');
						$('.trap_text').html("You are stuck in the Spider Web!");
						$('.dice_total').css("background-color", "#D1001C");
						$('.dice_total').css("color", "white");
						play_audio("spiderweb");
					}
					break;
					
				case "darkness":
					
					total = dice1;
					$('.dice_total').html(total);
					encounter_queue[0].resolved = '1';
					encounter_queue[0].success = '1';
					move_through_darkness(encounter_queue[0].direction, total);
					break;
					
				case "spectralstorm":
					
					total = dice1 + dice2;
					$('.dice_total').html(total);
					
					if (total <= hero1.strength) {
						encounter_queue[0].resolved = '1';
						encounter_queue[0].success = '1';
						write_to_console('['+total+'] You escaped from the Army of Undead!');
						$('.trap_text').html("You escaped from the Army of Undead!");
						$('.dice_total').css("background-color", "#000000");
						$('.dice_total').css("color", "white");
					} else {
						encounter_queue[0].resolved = '1';
						encounter_queue[0].success = '0';
						write_to_console('['+total+'] You barely got away from the Army of Undead!');
						$('.trap_text').html("You barely got away from the Army of Undead!");
						$('.dice_total').css("background-color", "#D1001C");
						$('.dice_total').css("color", "white");
						update_hero_health(-Math.ceil(hero1.health/2));
						if (hero1.health <= 0) {
							write_to_console('['+total+'] You are killed by the Army of Undead!');
							$('.trap_text').html("You are killed by the Army of Undead!");
							game_over = true;
						}
					}
					break;
					
				case "scorpion":
					
					total = dice1;
					$('.dice_total').html(total);
					encounter_queue[0].resolved = '1';
					encounter_queue[0].success = '0';
					
					write_to_console('['+total+'] You were attacked by a Scorpion!');
					write_to_console('['+total+'] You suffer '+total+' wounds!');
					$('.dice_total').css("background-color", "#D1001C");
					$('.dice_total').css("color", "white");
					$('.trap_text').html("You suffer "+total+" wounds!");
					update_hero_health(-total);
					if (hero1.health <= 0) {
						write_to_console('['+total+'] You are killed by the Scorpion!');
						$('.trap_text').html("You are killed by the Scorpion!");
						game_over = true;
					}
						
					break;
					
				case "razorwingattack":
					
					total = dice1;
					$('.dice_total').html(total);
					encounter_queue[0].resolved = '1';
					encounter_queue[0].success = '0';
					
					write_to_console('['+total+'] You were attacked by a Razorwing!');
					write_to_console('['+total+'] You suffer '+total+' wounds!');
					$('.dice_total').css("background-color", "#D1001C");
					$('.dice_total').css("color", "white");
					$('.trap_text').html("You suffer "+total+" wounds!");
					update_hero_health(-total);
					if (hero1.health <= 0) {
						write_to_console('['+total+'] You are killed by the Razorwing!');
						$('.trap_text').html("You are killed by the Razorwing!");
						game_over = true;
					}
						
					break;
					
				case "potion":
					
					total = dice1 + dice2;
					var wounds = 0;
					var heal = 0;
					$('.dice_total').html(total);
					encounter_queue[0].resolved = '1';
					encounter_queue[0].success = '0';
					write_to_console("potion roll: "+total);
					switch(total.toString()) {
						case "2":
							write_to_console("You are poisoned by the potion");
							update_hero_health(-hero1.health);
							break;
						case "3":
						case "4":
						case "5":
							write_to_console("You suffer 4 wounds");
							update_hero_health(-4);
							wounds = 4;
							break;
						case "6":
						case "7":
							write_to_console("Nothing happens");
							break;
						case "8":
						case "9":
						case "10":
							write_to_console("You heal 3 wounds");
							update_hero_health(3);
							heal = 3;
							if (hero1.health > hero1.max_health) { hero1.health = hero1.max_health; }
							break;
						case "11":
						case "12":
							write_to_console("You heal all your wounds");
							heal = hero1.max_health - hero1.health;
							update_hero_health(heal);
							break;
					}
					
					$('.dice_total').css("background-color", "#D1001C");
					$('.dice_total').css("color", "white");
					if (wounds > 0) {
						$('.trap_text').html("You suffer "+wounds+" wounds!");
					}
					if (heal > 0) {
						$('.trap_text').html("You heal "+heal+" wounds!");
					}
					
					
					if (hero1.health <= 0) {
						write_to_console('['+total+'] You die from the potion!');
						$('.trap_text').html("You die from the potion!");
						game_over = true;
					}
						
					break;
					
				case "torchout":
					
					total = dice1 + dice2;
					$('.dice_total').html(total);
					
					if (total <= hero1.luck) {
						encounter_queue[0].resolved = '1';
						encounter_queue[0].success = '1';
						hero1.torch = '1';
						write_to_console('['+total+'] You lit your Torch!');
						$('.trap_text').html("You lit your Torch!");
						$('.dice_total').css("background-color", "#01E501");
						$('.dice_total').css("color", "white");
					} else {
						encounter_queue[0].resolved = '1';
						encounter_queue[0].success = '0';
						write_to_console('['+total+'] You failed to light your Torch!');
						$('.trap_text').html("You failed to light your Torch!");
						$('.dice_total').css("background-color", "#000000");
						$('.dice_total').css("color", "white");
					}
					break;
					
				case "dragonbreath":
					
					total = dice1 + dice2;
					$('.dice_total').html(total);
					encounter_queue[0].resolved = '1';
					encounter_queue[0].success = '0';
					
					write_to_console('['+total+'] You were attacked by the Dragon!');
					write_to_console('['+total+'] You suffer '+total+' wounds!');
					$('.dice_total').css("background-color", "#D1001C");
					$('.dice_total').css("color", "white");
					$('.trap_text').html("You suffer "+total+" wounds!");
					update_hero_health(-total);
					if (hero1.health <= 0) {
						write_to_console('['+total+'] You are killed by the Dragon!');
						$('.trap_text').html("You are killed by the Dragon!");
						game_over = true;
					}
						
					break;
					
				case "alreadysearched":
				case "secretdoor":
				case "emptyroom":
				case "finditem":
				case "passagedown":
				case "cryptalreadysearched":
				case "findcryptitem":
				case "oldbones":
				case "corpsealreadysearched":
				case "findcorpseitem":
				case "finditem":
				case "wizardscurse":
				case "lingeringshade":
				case "catacombs":
				
					encounter_queue[0].resolved = '1';
					encounter_queue[0].success = '0';
					break;
					
				case "death":
				case "gameover":
				
					encounter_queue[0].resolved = '0';
					encounter_queue[0].success = '0';
					break;
			}
				
			$('#trap_button').attr("disabled", true);

			trap_triggered = false;
			trap_triggered_type = "";
			
			
		}
		
		function write_to_console(p_Message) {
			$('.console').prepend(Math.abs(timer-max_timer) + ': ' + p_Message + '<br/>');
		}
		
		function write_to_debugger(p_Message) {
			if (debug) {
				$('.console').prepend('<span style="color: gray;">'+Math.abs(timer-max_timer) + ': ' + p_Message + '</span><br/>');
			}
		}
		
		function write_to_loot_console(p_item_index, p_loot) {
			if (p_loot.name == "Unstable Potion") {
				$('.loot_console').append( '<span class="loot_item" onclick="drink_potion('+p_item_index+')">'+p_loot.timer+ ': ' + p_loot.name + ' (' +p_loot.value+' Gold)<br/></span>');
			} else {
				$('.loot_console').append( '<span class="loot_item" onclick="drop_loot('+p_item_index+')">'+p_loot.timer+ ': ' + p_loot.name + ' (' +p_loot.value+' Gold)<br/></span>');
			}
		}
		
		function drink_potion(p_item_index){
			
			for(var i = 0; i<hero_loot.length; i++){
				if (i == p_item_index){
					if (confirm("Do you want to drink potion? "+hero_loot[i].name)) {
						//alert(p_item_index+" "+hero_loot[i].name);
						hero_loot.splice(i, 1);
						add_encounter("potion");
						force_trap_modal();
					}
				}
				
			}
			draw_loot_bag();
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
				encounter_queue = [];
				add_encounter('death', '0');
				force_trap_modal();
				//roll_trap_dice();
				return true;
			}
			
			if (game_over) {
				encounter_queue = [];
				add_encounter('gameover', '0');
				force_trap_modal();
				//roll_trap_dice();
				return true;
			}
			
			if (!ferrox_dead) {
				$('.ferrox_dice_placeholder').append($('.mini_dice_sheet'));
				$('#fight_ferrox_button').attr("disabled", false);
				$('#ferrox_modal').modal('toggle');
				return true;
			}
			
			if (!monster_dead) {
				$('.monster_dice_placeholder').append($('.mini_dice_sheet'));
				$('#fight_button').attr("disabled", false);
				$('#flee_button').attr("disabled", false);
				$('#battle_modal').modal('toggle');
				return true;
			}
			
			if (encounter_queue.length > 0) {
				write_to_debugger("Event not resolved: encounter queue length > 0");
				$('#trap_modal').modal('toggle');
				
				if ($('#trap_modal').hasClass('in')) {
					$('.mini_dice_placeholder').attr("onclick", "").unbind("click");
					$(".mini_dice_placeholder").on('click', function(){ roll_trap_dice(); });
					$('.trap_hero_sheet_placeholder').append($('.mini_hero_sheet'));
					$('#trap_button').attr("disabled", false);
				} else {
					$('.mini_dice_placeholder').attr("onclick", "").unbind("click");
					$('.mini_hero_sheet_placeholder').append($('.mini_hero_sheet'));
				}
				
				
				//force_trap_modal();
				return true;
			}
			
			
			if (trap_triggered) {
				write_to_debugger("Event not resolved: trap_triggered");
				$('#trap_modal').modal('toggle');
				
				if ($('#trap_modal').hasClass('in')) {
					$('.mini_dice_placeholder').attr("onclick", "").unbind("click");
					$(".mini_dice_placeholder").on('click', function(){ roll_trap_dice(); });
					$('.trap_hero_sheet_placeholder').append($('.mini_hero_sheet'));
					$('#trap_button').attr("disabled", false);
				} else {
					$('.mini_dice_placeholder').attr("onclick", "").unbind("click");
					$('.mini_hero_sheet_placeholder').append($('.mini_hero_sheet'));
				}
				
				//force_trap_modal();
				return true;
			}
			
			//alert(3);
			
			return false;
			
		}
		
		function print_encounter() {
			for (var i = 0; i < encounter_queue.length; i++) {
				write_to_debugger(encounter_queue[i].type + ", " + encounter_queue[i].title + ", " + encounter_queue[i].description_color + ", " + encounter_queue[i].description + ", " + encounter_queue[i].direction);
			}
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
				<input class="btn btn-primary search_button" type="button" name="search_button" value="Search" onclick="search_chamber();" />
				<input class="btn btn-primary corpse_button" type="button" name="corpse_button" value="Corpse" onclick="search_corpse();" />
				<input class="btn btn-primary crypt_button" type="button" name="crypt_button" value="Crypt" onclick="search_crypt();" />
				<input class="btn btn-primary treasure_button" type="button" name="treasure_button" value="Loot" onclick="search_dragon_chamber();" />
				<input class="btn btn-primary catacombs_button" type="button" name="catacombs_button" value="Catacombs" onclick="enter_catacombs();" />
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
				<input class="btn btn-primary search_button" type="button" name="search_button" value="Search" onclick="search_chamber();" />
				<input class="btn btn-primary fight_button" type="button" name="fight_button" value="Fight" onclick="fight_ferrox();" />
				<input class="btn btn-primary corpse_button" type="button" name="corpse_button" value="Corpse" onclick="search_corpse();" />
				<input class="btn btn-primary crypt_button" type="button" name="crypt_button" value="Crypt" onclick="search_crypt();" />
				<input class="btn btn-primary catacombs_button" type="button" name="catacombs_button" value="Catacombs" onclick="enter_catacombs();" />
				<input class="btn btn-primary treasure_button" type="button" name="treasure_button" value="Loot" onclick="search_dragon_chamber();" />
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
			<div class="trap_console_sheets" style="margin: 0 auto; width: 300px;">
				<div class="trap_console_sheet table-bordered" style="">
					<div class="console" style="font-size: 12px; text-align: left;"></div>
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


<!-- Battle Modal-->
<div class="modal" id="battle_modal" tabindex="-1" role="dialog" aria-labelledby="battle_modal_label" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header" style="margin: 0px;">
        <h4 class="modal-title" id="battle_modal_title" class="battle_modal_title">You Encountered a Monster!</h4>
      </div>
      <div class="modal-body" style="vertical-align: top; margin: 0 auto;">
			<div><img id="battle_modal_image" class="battle_monster_img img img-responsive" style="width: 300px; margin: 0 auto;" src="" /></div>
			
			<!-- Battle Sheet -->
			<div class="battle_battle_sheet" style="">
				<table class="table-bordered table-condensed" style="margin: 0 auto; width: 300px;">
					<tr>
						<td style="width: 50%;"><span class="battle_monster_name"></span></td>
						<td style="width: 20%;">❤️ <span class="battle_monster_health"></span></td>
					</tr>
					<tr>
						<td style="width: 50%;"><span class="hero1_name"></span></td>
						<td style="width: 20%;">❤️ <span class="hero1_health"></span></td>
					</tr>
					<tr>
						<td style="width: 50%;">Escape Penalty</td>
						<td style="width: 20%;">❤️ <span class="battle_monster_escape_penalty"></span></td>
					</tr>
					<tr>
						<td colspan="2">
							<div class="battle_dice_placeholder" style="cursor: pointer; margin: 5px 0 5px 0;" onclick="roll_battle_dice('fight')"></div>
						</td>
					</tr>
				</table>
			</div>
			
			<div class="battle_attack_text alert" style="margin: 5px auto; width: 300px;"></div>
			
			<!-- Console and Loot Sheet -->
			<div class="trap_console_sheets" style="margin: 0 auto; width: 300px;">
				<div class="trap_console_sheet table-bordered" style="">
					<div class="console" style="font-size: 12px; text-align: left;"></div>
				</div>
			</div>
			
      </div>
      <div class="modal-footer">
			<button type="button" id="fight_button" class="fight_button btn btn-primary" value="Fight" onclick="roll_battle_dice('fight')">Fight</button>
			<button type="button" id="flee_button" class="flee_button btn btn-primary" value="Fight" onclick="roll_battle_dice('flee')">Flee</button>
			<button type="submit" id="battle_modal_close_button" class="btn btn-primary" data-dismiss="modal">Close</button>
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
		<div class="trap_text alert" style="margin: 5px auto; width: 300px;"></div>
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