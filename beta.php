<html>
<head>
	<meta http-equiv="content-type" content="text/html; charset=UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1.0, user-scalable=no">
	<link rel="icon" type="image/png" href="/dungeonquest/images/second_edition/dungeonquest_favicon.jpg" />
	<link type="text/css" rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
	<link type="text/css" rel="stylesheet" href="/dungeonquest/dice.css">
	<link type="text/css" rel="stylesheet" href="/dungeonquest/dice_2.css">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
	<script type="text/javascript" src="/dungeonquest/dungeonquest_second_edition.js"></script>
	<script type="text/javascript" src="/dungeonquest/dungeonquest_classes.js"></script>
	<script type="text/javascript">
		
		
		var debug = false;
		var hero1;
		
		var hero_map = {};
		var empty_map_orientation = {};
		var hero_loot = [];
		var encounter_queue = [];
		
		var chamber_deck;
		var chamber_event_deck;
		var catacombs_deck;
		var monster_deck;
		var door_deck;
		var search_deck;
		var crypt_deck;
		var corpse_deck;
		var trap_deck;
		var dragon_deck;
		var treasure_deck;
		
		var boardgame_width = 13;
		var boardgame_height = 10;
		
		var mini_board_width = 4;
		var mini_board_height = 3;
		
		var medium_board_width = 7;
		var medium_board_width = 7;
		
		var enable_mini_board = true;
		var enable_medium_board = true;
		
		var max_timer = 27;
		
		if (debug) { max_timer = 100; }
		
		var timer = max_timer;
		
		var game_over = false;
		var monster_dead = true;
		
		var previous_direction = "";
		var current_direction = "";
		
		var character_image_directory = "/dungeonquest/images/second_edition_characters/";
		var image_directory = "/dungeonquest/images/second_edition_friendlybombs/";
		var chamber_image_directory = "/dungeonquest/images/second_edition/";
		var empty_image_directory = "/dungeonquest/images/second_edition_friendlybombs/empty/";
		var sound_directory = "/dungeonquest/sound/";
		var audio;
		
		function play_audio(p_type){
			audio = new Audio(sound_directory+p_type+'.mp3');
			audio.play();
		}
		
		
		function remove_movement_encounters() {
			write_to_debugger("Remove Movement Encounters");
			for (var i = encounter_queue.length-1; i >= 0; i--) {
				if (encounter_queue[i].type == 'opendoor' || 
					encounter_queue[i].type == 'jammeddoor' || 
					encounter_queue[i].type == 'doortrap' || 
					encounter_queue[i].type == 'bridge' || 
					encounter_queue[i].type == 'portcullis' || 
					encounter_queue[i].type == 'pit' || 
					encounter_queue[i].type == 'rubble' || 
					encounter_queue[i].type == 'spiderweb' || 
					encounter_queue[i].type == 'darkness') {
					write_to_debugger("Removed: " + encounter_queue[i].type);
					encounter_queue.splice(i, 1);
				}
			}
		}
		
		function splash_click() {
			$('.splash_screen').hide();
			$('.introduction_screen').show();
			$('.character_selection_screen').hide();
			$('.board_game_screen').hide();
		}
		
		function introduction_click() {
			$('.splash_screen').hide();
			$('.introduction_screen').hide();
			$('.character_selection_screen').show();
			$('.board_game_screen').hide();
		}
		
		//bug open door open door when there is collapse after
		
		$(document).ready(function() {
			
			$('.splash_screen').hide();
			$('.introduction_screen').show();
			$('#game').hide();
			
			load_characters();
			create_game_board();
			
			// Close Rules Modal
			$('#rules_modal').on('hidden.bs.modal', function () {
				$('.mini_dice_placeholder').append($('.mini_dice_sheet'));
				$('.mini_dice_sheet').show();
			})
			
			
			// Show Encounter Modal
			$('#encounter_modal').on('show.bs.modal', function () {
				
				write_to_debugger("Show Trap Modal");
				var current_encounter = encounter_queue[0];
				
				if (current_encounter != undefined) {
				
					current_direction = current_encounter.direction;
					
					$('.encounter_hero_sheet_placeholder').show();
					$('.encounter_battle_sheet').hide();
					$('.mini_dice_placeholder').append($('.mini_dice_sheet'));
					$('.encounter_dice_button').show();
					
					$('.mini_dice_placeholder').attr("onclick", "").unbind("click");
					$(".mini_dice_placeholder").on('click', function(){ roll_encounter_dice(''); });
					$('.encounter_hero_sheet_placeholder').append($('.mini_hero_sheet'));
					
					
					$('.dice_total').css('background-color', '#D1001C');
					$('.dice_total').css('color', '#FFFFFF');
					
					
					// Fight Button
					if (current_encounter.show_fight == '1') { $('.encounter_fight_button').show(); } else { $('.encounter_fight_button').hide(); }
					if (current_encounter.can_fight == '1') { $('.encounter_fight_button').attr("disabled", false); } else { $('.encounter_fight_button').attr("disabled", true); }
					
					// Shoot Button
					if (current_encounter.show_shoot == '1') { $('.encounter_shoot_button').show(); } else { $('.encounter_shoot_button').hide(); }
					if (current_encounter.can_shoot == '1') { $('.encounter_shoot_button').attr("disabled", false); } else { $('.encounter_shoot_button').attr("disabled", true); }
					
					// Flee Button (show if encounter can flee, and disable if portcullis is closed behind you)
					if (current_encounter.show_flee == '1') { $('.encounter_flee_button').show(); } else { $('.encounter_flee_button').hide(); }
					if (current_encounter.can_flee == '1') { $('.encounter_flee_button').attr("disabled", false); } else { $('.encounter_flee_button').attr("disabled", true); }
					if (escape_closed()) { $('.encounter_flee_button').attr("disabled", true); }

					$('.encounter_text').css("background-color", current_encounter.background);
					$('.encounter_text').css("color", current_encounter.color);
					$('.encounter_text').html(current_encounter.description);
					
					
					// Trap Door
					if (current_encounter.type == "trapdoor") {
						if (hero1.fell_in_pit == '1') {
							$('.encounter_text').html("Climb out of the Pit <br/> [ Test Agility ]");
						} else if (current_encounter.resolved == '1') {
							$('.encounter_text').css("background-color", "#01E501");
							$('.encounter_text').html("You climbed out of the pit");
						}
					}
					
					$('.encounter_dice_button').attr("disabled", false);
					
					// Dice Color
					switch(current_encounter.type) {
						case "darkness":
							$('.dice_total').css('background-color', '#000000');
							$('.dice_total').css('color', '#FFFFFF');
							break;
						case "torchout":
							$('.dice_total').css('background-color', '#000000');
							$('.dice_total').css('color', '#FFFFFF');
							break;
					}
					
					// Battle Sheet
					switch(current_encounter.type) {
						case "goblin":
						case "orc":
						case "troll":
						case "undead":
						case "blackknight":
							$('.encounter_hero_sheet_placeholder').hide();
							$('.encounter_battle_sheet').show();
							$('.battle_dice_placeholder').append($('.mini_dice_sheet'));
							$('.encounter_dice_button').hide();
							draw_monster_stats(monster);
							draw_hero_stats('hero1', hero1);
							break;
					}
					
					
					// Auto Resolve
					switch(current_encounter.type) {
						case "opendoor":
							current_encounter.resolved = '1';
							current_encounter.success = '1';
							$('#encounter_dice_button').attr("disabled", true);
							break;
						case "jammeddoor":
							current_encounter.resolved = '1';
							current_encounter.success = '0';
							$('#encounter_dice_button').attr("disabled", true);
							break;
						case "doortrap":
							current_encounter.resolved = '0';
							current_encounter.success = '0';
							break;
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
							$('#encounter_dice_button').attr("disabled", true);
							break;
					}
					
				}
			})
			
			
			// Close Encounter Modal
			$('#encounter_modal').on('hidden.bs.modal', function () {
				$('.mini_hero_sheet_placeholder').append($('.mini_hero_sheet'));
				$('.mini_dice_placeholder').append($('.mini_dice_sheet'));
				$('.mini_dice_placeholder').attr("onclick", "").unbind("click");
				$(".mini_dice_placeholder").on('click', function(){ roll_dice(2); });
				$('.mini_dice_sheet').show();
				
				
				$('.encounter_hero_sheet_placeholder').show();
				$('.encounter_battle_sheet').hide();
				$('.mini_dice_placeholder').append($('.mini_dice_sheet'));
				$('.encounter_dice_button').show();
				
				// debug
				write_to_debugger("Trap Modal Closed: "+encounter_queue[0].type);
				write_to_debugger("Encounter Queue List: (" + encounter_queue.length + ") encounters");

				for (var i = 0; i < encounter_queue.length; i++) {
					write_to_debugger("List "+eval(i+1)+": " + encounter_queue[i].type + ", Resolved: "+encounter_queue[i].resolved+", Success: "+encounter_queue[i].success);
				}
				
				if (encounter_queue[0] != undefined) {
					
					// Movement Encounters
					// If Movement Encounter Failed or Cancelled, Remove all subsequent Movement Encounters Queue
					if (encounter_queue[0].type == "opendoor" || 
						encounter_queue[0].type == "jammeddoor" || 
						encounter_queue[0].type == "doortrap" || 
						encounter_queue[0].type == "bridge" || 
						encounter_queue[0].type == "portcullis" || 
						encounter_queue[0].type == "pit" || 
						encounter_queue[0].type == "rubble" || 
						encounter_queue[0].type == "spiderweb" ||
						encounter_queue[0].type == "darkness") {
							
						if (encounter_queue[0].resolved == '1') {
							if (encounter_queue[0].success == '1') {
								write_to_debugger("Movement: "+encounter_queue[0].type+": R = 1, S = 1");
								write_to_debugger("Encounter Queue Length: "+encounter_queue.length);
								if (encounter_queue.length > 1) {
									write_to_debugger("Second Encounter: "+encounter_queue[1].type);
									encounter_queue.splice(0, 1);
									force_encounter_modal();
								} else {
									var force_hero_direction = encounter_queue[0].direction;
									encounter_queue.splice(0, 1);
									force_hero_movement(force_hero_direction);
									draw_hero_stats("hero1", hero1);
								}
								return;
							} else {
								write_to_debugger("Movement: "+encounter_queue[0].type+": R = 1, S = 0");
								
								remove_movement_encounters();
								update_timer();
								return;
							}
						} else {
							write_to_debugger("Movement: "+encounter_queue[0].type+": R = 0, S = 0");
							// Allow cancel all movement except doortrap
							if (encounter_queue[0].type != "doortrap") {
								remove_movement_encounters();
							}
							return;
						}
					}
					
					// Trap Encounters
					if (encounter_queue[0].type == "trapdoor" ||
						encounter_queue[0].type == "snakes" ||
						encounter_queue[0].type == "gas" ||
						encounter_queue[0].type == "collapse" ||
						encounter_queue[0].type == "explosion" ||
						encounter_queue[0].type == "crossfire") {
							
						if (encounter_queue[0].resolved == '1') {
							if (encounter_queue[0].success == '1') {
								write_to_debugger("Trap: "+ encounter_queue[0].type+": R = 1, S = 1");
								encounter_queue.splice(0, 1);
								return;
							} else {
								write_to_debugger("Trap: "+ encounter_queue[0].type+": R = 1, S = 0");
								encounter_queue.splice(0, 1);
								return;
							}
						} else {
							write_to_debugger("Trap: "+ encounter_queue[0].type+": R = 0");
							return;
						}
					}
					
					// Chamber Encounters
					if (encounter_queue[0].type == "vampirebats" ||
						encounter_queue[0].type == "giantspider" ||
						encounter_queue[0].type == "wizardscurse" ||
						encounter_queue[0].type == "goblin" ||
						encounter_queue[0].type == "orc" ||
						encounter_queue[0].type == "troll" ||
						encounter_queue[0].type == "undead" ||
						encounter_queue[0].type == "blackknight") {
							
						if (encounter_queue[0].resolved == '1') {
							if (encounter_queue[0].success == '1') {
								write_to_debugger("Chamber: "+ encounter_queue[0].type+": R = 1, S = 1");
								encounter_queue.splice(0, 1);
								return;
							} else {
								write_to_debugger("Chamber: "+ encounter_queue[0].type+": R = 1, S = 0");
								encounter_queue.splice(0, 1);
								return;
							}
						} else {
							write_to_debugger("Chamber: "+ encounter_queue[0].type+": R = 0");
							return;
						}
					}
					
					// Search Chamber
					if (encounter_queue[0].type == "alreadysearched" ||
						encounter_queue[0].type == "secretdoor" ||
						encounter_queue[0].type == "emptyroom" ||
						encounter_queue[0].type == "finditem" ||
						encounter_queue[0].type == "centipede" ||
						encounter_queue[0].type == "passagedown" ||
						encounter_queue[0].type == "alreadysearched") {
							
						if (encounter_queue[0].resolved == '1') {
							if (encounter_queue[0].success == '1') {
								write_to_debugger("Chamber: "+ encounter_queue[0].type+": R = 1, S = 1");
								encounter_queue.splice(0, 1);
								return;
							} else {
								write_to_debugger("Chamber: "+ encounter_queue[0].type+": R = 1, S = 0");
								encounter_queue.splice(0, 1);
								return;
							}
						} else {
							write_to_debugger("Chamber: "+ encounter_queue[0].type+": R = 0");
							return;
						}
					}
					
					// Search Crypt
					if (encounter_queue[0].type == "cryptalreadysearched" ||
						encounter_queue[0].type == "emptycrypt" ||
						encounter_queue[0].type == "findcryptitem" ||
						encounter_queue[0].type == "skeleton" ||
						encounter_queue[0].type == "crypttrap") {
							
						if (encounter_queue[0].resolved == '1') {
							if (encounter_queue[0].success == '1') {
								write_to_debugger("Crypt: "+ encounter_queue[0].type+": R = 1, S = 1");
								encounter_queue.splice(0, 1);
								return;
							} else {
								write_to_debugger("Crypt: "+ encounter_queue[0].type+": R = 1, S = 0");
								encounter_queue.splice(0, 1);
								return;
							}
						} else {
							write_to_debugger("Crypt: "+ encounter_queue[0].type+": R = 0");
							return;
						}
					}
					
					// Search Corpse
					if (encounter_queue[0].type == "corpsealreadysearched" ||
						encounter_queue[0].type == "emptycorpse" ||
						encounter_queue[0].type == "findcorpseitem" ||
						encounter_queue[0].type == "scorpion") {
							
						if (encounter_queue[0].resolved == '1') {
							if (encounter_queue[0].success == '1') {
								write_to_debugger("Corpse: "+ encounter_queue[0].type+": R = 1, S = 1");
								encounter_queue.splice(0, 1);
								return;
							} else {
								write_to_debugger("Corpse: "+ encounter_queue[0].type+": R = 1, S = 0");
								encounter_queue.splice(0, 1);
								return;
							}
						} else {
							write_to_debugger("Corpse: "+ encounter_queue[0].type+": R = 0");
							return;
						}
					}
					
					// Catacombs [not implemented]
					if (encounter_queue[0].type == "catacombs") {
							
						if (encounter_queue[0].resolved == '1') {
							if (encounter_queue[0].success == '1') {
								write_to_debugger("Catacombs: "+ encounter_queue[0].type+": R = 1, S = 1");
								encounter_queue.splice(0, 1);
								return;
							} else {
								write_to_debugger("Catacombs: "+ encounter_queue[0].type+": R = 1, S = 0");
								encounter_queue.splice(0, 1);
								return;
							}
						} else {
							write_to_debugger("Catacombs: "+ encounter_queue[0].type+": R = 0");
							return;
						}
					}
					
					// Dragon Breath
					if (encounter_queue[0].type == "dragonbreath") {
							
						if (encounter_queue[0].resolved == '1') {
							if (encounter_queue[0].success == '1') {
								write_to_debugger("Dragon: "+ encounter_queue[0].type+": R = 1, S = 1");
								encounter_queue.splice(0, 1);
								return;
							} else {
								write_to_debugger("Dragon: "+ encounter_queue[0].type+": R = 1, S = 0");
								encounter_queue.splice(0, 1);
								return;
							}
						} else {
							write_to_debugger("Dragon: "+ encounter_queue[0].type+": R = 0");
							return;
						}
					}
					
					// Potion
					if (encounter_queue[0].type == "potion") {
							
						if (encounter_queue[0].resolved == '1') {
							if (encounter_queue[0].success == '1') {
								write_to_debugger("Potion: "+ encounter_queue[0].type+": R = 1, S = 1");
								encounter_queue.splice(0, 1);
								return;
							} else {
								write_to_debugger("Potion: "+ encounter_queue[0].type+": R = 1, S = 0");
								encounter_queue.splice(0, 1);
								return;
							}
						} else {
							write_to_debugger("Potion: "+ encounter_queue[0].type+": R = 0");
							return;
						}
					}
					
					// Torch
					if (encounter_queue[0].type == "torchout") {
						if (encounter_queue[0].resolved == '1') {
							if (encounter_queue[0].success == '1') {
								write_to_debugger("Torchout: "+ encounter_queue[0].type+": R = 1, S = 1");
								encounter_queue.splice(0, 1);
								return;
							} else {
								write_to_debugger("Torchout: "+ encounter_queue[0].type+": R = 1, S = 0");
								encounter_queue[0].resolved = '0';
								encounter_queue[0].success = '0';
								return;
							}
						} else {
							write_to_debugger("Torchout: "+ encounter_queue[0].type+": R = 0, S = 0");
							return;
						}
					}
					
					// Death / Game Over
					if (encounter_queue[0].type == "death" ||
						encounter_queue[0].type == "gameover") {
						return;
					}
					
				} else {
					// alert("encounter not defined");
				}
				
			})
			
			
			// Setup Swipe Control for iPhone
			var container = document.getElementById("mini_dungeon_board");
			container.addEventListener("touchstart", startTouch, false);
			container.addEventListener("touchmove", moveTouch, false);

			// Setup Swipe Control for iPad
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
				if (initialX === null) { return; }
				if (initialY === null) { return; }

				var currentX = e.touches[0].clientX;
				var currentY = e.touches[0].clientY;

				var diffX = initialX - currentX;
				var diffY = initialY - currentY;

				if (Math.abs(diffX) > Math.abs(diffY)) {
					if (diffX > 0) {
						move_hero('4'); // swiped left
					} else {
						move_hero('2'); // swiped right
					}  
				} else {
					if (diffY > 0) {
						move_hero('1'); // swiped up
					} else {
						move_hero('3'); // swiped down
					}  
				}

				initialX = null;
				initialY = null;

				e.preventDefault();
			};
		});
		
		
		// Setup Movement Control for Keyboard
		document.onkeydown = function(e) {
			if (hero1 == undefined) { return; }
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
				case 13: // enter
					break;
				case 27: // escape
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
				//$character_tbody.append('<tr><td colspan="3"><div class="">'+CharactersJSON[i].description+'</div></td></tr>');
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
			$('.mini_dungeon_cell').css('background-color', 'white');
			
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
						var background_rotation = empty_map_orientation[eval((hero1.x+i+x_adjustment+1)*100 + (hero1.y+j+y_adjustment))];
						
						
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
			$('.medium_dungeon_cell').css('background-color', 'white');
			
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
						var background_rotation = empty_map_orientation[eval((hero1.x+i+x_adjustment+1)*100 + (hero1.y+j+y_adjustment))];
						
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
		
		function start_game() {
			
			timer = max_timer;
			game_over = false;
			monster_dead = true;
			
			update_timer();
			
			//empty_map_orientation = {};
			
			hero_map = {};
			reset_game_board();
			hero_loot = [];
			encounter_queue = [];
			
			
			chamber_deck = new CardDeck('chamber', ChamberJSON);
			chamber_event_deck = new CardDeck('chamber event', ChamberEventJSON);
			catacombs_deck = new CardDeck('catacombs', CatacombsJSON);
			monster_deck = new CardDeck('monster', MonsterJSON);
			door_deck = new CardDeck('door', DoorJSON);
			search_deck = new CardDeck('search', SearchJSON);
			crypt_deck = new CardDeck('crypt', CryptJSON);
			corpse_deck = new CardDeck('corpse', CorpseJSON);
			trap_deck = new CardDeck('trap', TrapsJSON);
			dragon_deck = new CardDeck('dragon', DragonJSON);
			treasure_deck = new CardDeck('treasure', TreasureJSON);
			
			
			//$('.console').empty();
			$('.loot_console').empty();
			
			write_to_debugger("chamber_deck "+Object.keys(chamber_deck._cardsJSON).length);
			write_to_debugger("chamber_event_deck "+Object.keys(chamber_event_deck._cardsJSON).length);
			write_to_debugger("catacombs_deck "+Object.keys(catacombs_deck._cardsJSON).length);
			write_to_debugger("monster_deck "+Object.keys(monster_deck._cardsJSON).length);
			write_to_debugger("door_deck "+Object.keys(door_deck._cardsJSON).length);
			write_to_debugger("search_deck "+Object.keys(search_deck._cardsJSON).length);
			write_to_debugger("crypt_deck "+Object.keys(crypt_deck._cardsJSON).length);
			write_to_debugger("corpse_deck "+Object.keys(corpse_deck._cardsJSON).length);
			write_to_debugger("trap_deck "+Object.keys(trap_deck._cardsJSON).length);
			write_to_debugger("dragon_deck "+Object.keys(dragon_deck._cardsJSON).length);
			write_to_debugger("treasure_deck "+Object.keys(treasure_deck._cardsJSON).length);
			
			write_to_console("Use Keyboard or Swipe to Move");
			$('.loot_console').prepend("<br/>");
			
			remove_heros();
			draw_hero();
			//update_timer();
			
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
			
			var hero_orientation = hero_map[hero1.x*100 + hero1.y].orientation;
			var hero_position = previous_direction;
			
			if (hero_position == "") { hero_position = '1'; }
			
			switch (hero_position) {
				case "1":
					hero_position = "center bottom";
					break;
				case "2":
					hero_position = "left center";
					break;
				case "3":
					hero_position = "center top";
					break;
				case "4":
					hero_position = "right center";
					break;
			}
			
			if (hero_map[hero1.x*100 + hero1.y].type == "starting") {
					hero_position = "center";
			}
			
			
			if (hero1 != undefined) {
				$('#dungeon_board_cell_'+(hero1.x)+'_'+(hero1.y)).append('<div style="position: absolute; left: 0; right: 0; top: 0; bottom: 0; margin: auto; height: 80%; width: 80%; background-size: 40%; background-position: '+hero_position+'; background-repeat: no-repeat; transform: rotate('+eval(-hero_orientation)+'deg); background-image: url(\''+character_image_directory+hero1.image_url+'\')"></div>');
				
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
					$('#mini_board_cell_'+eval(0-x_adjustment)+'_'+eval(0-y_adjustment)+'').append('<div style="position: absolute; left: 0; right: 0; top: 0; bottom: 0; margin: auto; height: 80%; width: 80%; background-size: 40%; background-position: '+hero_position+'; background-repeat: no-repeat; transform: rotate('+eval(-hero_orientation)+'deg); background-image: url(\''+character_image_directory+hero1.image_url+'\')"></div>');
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
					$('#medium_board_cell_'+eval(0-x_adjustment)+'_'+eval(0-y_adjustment)+'').append('<div style="position: absolute; left: 0; right: 0; top: 0; bottom: 0; margin: auto; height: 80%; width: 80%; background-size: 40%; background-position: '+hero_position+'; background-repeat: no-repeat; transform: rotate('+eval(-hero_orientation)+'deg); background-image: url(\''+character_image_directory+hero1.image_url+'\')"></div>');
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
			
			if (!debug) {
				$('.encounter_button').hide();
				$('.door_button').hide();
				
				$('.search_button').attr('disabled', true);
				$('.crypt_button').attr('disabled', true);
				$('.corpse_button').attr('disabled', true);
				$('.treasure_button').attr('disabled', true);
				$('.catacombs_button').attr('disabled', true);
			}
			
			if (current_chamber.searchable == '1' && current_chamber.searched < 2) {
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
					trigger_trap();
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
			// if ($('.encounter_modal').data('bs.modal')?.isShown) { return; }
			
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
							
							if (!is_facing_wall(curr_hero_chamber, p_direction) && !is_adjacent_to_wall(next_hero_chamber, p_direction)) {
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
							
							if (!is_facing_wall(curr_hero_chamber, p_direction) && !is_adjacent_to_wall(next_hero_chamber, p_direction)) {
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
							
							if (!is_facing_wall(curr_hero_chamber, p_direction) && !is_adjacent_to_wall(next_hero_chamber, p_direction)) {
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
							
							if (!is_facing_wall(curr_hero_chamber, p_direction) && !is_adjacent_to_wall(next_hero_chamber, p_direction)) {
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
				
				play_audio("walking_5");
				draw_chamber(p_direction);
				remove_heros();
				draw_hero();
				
				// do not update time for corridors
				if (hero_map[hero1.x*100 + hero1.y].type != 'corridor' && hero_map[hero1.x*100 + hero1.y].type != 'starting') {
					update_timer();
				}
				
			}
		}
		
		function prompt_exit_dungeon() {
			if (is_exit_chamber()) {
				if (confirm("Would you like to leave the Dungeon")) {
					add_encounter('exit', '0');
					force_encounter_modal();
					game_over = true;
				}
			} else {
				write_to_console('You see a impenetrable barrier');
			}
			return false;
		}
		
		function is_exit_chamber() {
			var current_chamber = hero_map[hero1.x*100 + hero1.y];
			
			if (current_chamber != undefined) {
				return hero_map[hero1.x*100 + hero1.y].type == "starting";
			} else {
				return false;
			}
		}
		
		function is_treasure_chamber(p_Hero) {
			var current_chamber = hero_map[hero1.x*100 + hero1.y];
			
			if (current_chamber != undefined) {
				return hero_map[hero1.x*100 + hero1.y].type == "treasure";
			} else {
				return false;
			}
		}
		
		function is_inside_board(p_x, p_y) {
			if (p_x > 0 && p_x < boardgame_width+1 && p_y > 0 && p_y < boardgame_height+1) {			
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
					return p_next_hero_chamber.bottom == '2';
					break;
				case '2':
					return p_next_hero_chamber.left == '2';
					break;
				case '3':
					return p_next_hero_chamber.top == '2';
					break;
				case '4':
					return p_next_hero_chamber.right == '2';
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
					force_encounter_modal();
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
				force_encounter_modal();
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
		
		
		function show_rules() {
			$('#rules_modal').modal("show");
			$('.mini_dice_sheet').hide();
		}
		
		function about() {
		}
		
		function force_encounter_modal() {
			
			var current_encounter = encounter_queue[0];
			
			if (current_encounter != undefined) {
			
				$('#encounter_modal').modal("show");
			}
			
		}
		
		function move_through_darkness(p_Direction, p_dice_roll) {
			
			var curr_hero_chamber = hero_map[hero1.x*100 + hero1.y];
			
			var dice_roll = p_dice_roll;
			
			// Hard Coded Tile ID
			if (String(curr_hero_chamber.id) == '107' || String(curr_hero_chamber.id) == '108') {
				
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
			// Hard Coded Tile ID
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
			// Hard Coded Tile ID
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
				$('.encounter_text').html('You aimlessly wandered east');
				new_direction = '2';
			} else if (String(curr_hero_chamber.orientation) == '90') {
				next_hero_chamber = hero_map[(hero1.x)*100 + hero1.y-1];
				$('.encounter_text').html('You aimlessly wandered south');
				new_direction = '3';
			} else if (String(curr_hero_chamber.orientation) == '180') {
				next_hero_chamber = hero_map[(hero1.x-1)*100 + hero1.y];
				$('.encounter_text').html('You aimlessly wandered west');
				new_direction = '4';
			} else if (String(curr_hero_chamber.orientation) == '270') {
				next_hero_chamber = hero_map[(hero1.x)*100 + hero1.y+1];
				$('.encounter_text').html('You aimlessly wandered north');
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
				$('.encounter_text').html('You aimlessly wandered west');
				new_direction = '4';
			} else if (String(curr_hero_chamber.orientation) == '90') {
				next_hero_chamber = hero_map[(hero1.x)*100 + hero1.y+1];
				$('.encounter_text').html('You aimlessly wandered north');
				new_direction = '1';
			} else if (String(curr_hero_chamber.orientation) == '180') {
				next_hero_chamber = hero_map[(hero1.x+1)*100 + hero1.y];
				$('.encounter_text').html('You aimlessly wandered east');
				new_direction = '2';
			} else if (String(curr_hero_chamber.orientation) == '270') {
				next_hero_chamber = hero_map[(hero1.x)*100 + hero1.y-1];
				$('.encounter_text').html('You aimlessly wandered south');
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
				$('.encounter_text').html('You aimlessly wandered south');
				new_direction = '3';
			} else if (String(curr_hero_chamber.orientation) == '90') {
				next_hero_chamber = hero_map[(hero1.x-1)*100 + hero1.y];
				$('.encounter_text').html('You aimlessly wandered west');
				new_direction = '4';
			} else if (String(curr_hero_chamber.orientation) == '180') {
				next_hero_chamber = hero_map[hero1.x*100 + hero1.y+1];
				$('.encounter_text').html('You aimlessly wandered north');
				new_direction = '1';
			} else if (String(curr_hero_chamber.orientation) == '270') {
				next_hero_chamber = hero_map[(hero1.x+1)*100 + hero1.y];
				$('.encounter_text').html('You aimlessly wandered east');
				new_direction = '2';
			}
			encounter_queue[0].direction = new_direction;
			
			prep_hero_movement_into_darkness(p_dice_roll, p_Direction, new_direction, next_hero_chamber);

		}
		
		function prep_hero_movement_into_darkness(p_dice_roll, p_direction, p_new_direction, next_hero_chamber) {
			
			var temp_previous_direction = previous_direction;

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
				write_to_console("Stumble Error, Hero Orientation: " + curr_hero_chamber.orientation);
			}
			
			if (Math.abs(temp_previous_direction - p_new_direction) == 2) {
				write_to_console("["+p_dice_roll+"] Your orientation got turned around");
			} else {
				play_audio("walking");
				write_to_console("["+p_dice_roll+"] You proceed into the darkness");
			}
		}
		
		function lost_in_darkness(p_dice_roll) {
			encounter_queue[0].resolved = '1';
			encounter_queue[0].success = '0';
			write_to_console("["+p_dice_roll+"] You are lost in the Darkness");
			$('.encounter_text').html("You are lost in the Darkness");
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
				
					var randomChamber = chamber_deck.draw_unique_card();
										
					// record new chamber id
					$('.new_chamber').html(randomChamber.id);

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
										
					hero_map[eval(hero1.x*100 + hero1.y)] = new Chamber(randomChamber.id, new_top, new_right, new_bottom, new_left, randomChamber.type, eval((p_direction-1)*90), randomChamber.image_url, randomChamber.description);
					hero_map[eval(hero1.x*100 + hero1.y)].searched = 0;
					current_chamber = hero_map[eval(hero1.x*100 + hero1.y)];
					
					write_to_debugger('Draw Tile: ' + randomChamber.id + " " + randomChamber.type + ": " + new_top + " " + new_right + " " + new_bottom + " " + new_left);
					
					$('#dungeon_board_cell_'+(hero1.x)+'_'+(hero1.y)).css("background-image", "url('"+image_directory+randomChamber.image_url+"')");
					$('#dungeon_board_cell_'+(hero1.x)+'_'+(hero1.y)).css("transform", "rotate("+eval((p_direction-1)*90)+"deg)");


					if (current_chamber.type == "rotating") {
						// rotate tile 90 degrees clockwise
						play_audio('rotating_chamber');
						write_to_console("You feel the ground shaking");
						
						var temp_chamber_top = current_chamber.top;
						current_chamber.top = current_chamber.bottom;
						current_chamber.bottom = temp_chamber_top;
						var temp_chamber_right = current_chamber.right;
						current_chamber.right = current_chamber.left;
						current_chamber.left = temp_chamber_right;
						current_chamber.orientation = eval((p_direction-1+2)*90)
						
						$('#dungeon_board_cell_'+(hero1.x)+'_'+(hero1.y)).css("transform", "rotate("+current_chamber.orientation+"deg)");
						
						write_to_debugger('Rotating Tile: ' + current_chamber.id + " " + current_chamber.type + ": " + current_chamber.top + " " + current_chamber.right + " " + current_chamber.bottom + " " + current_chamber.left);
					}

					if (current_chamber.type == "portcullis") {
						// rotate tile 90 degrees clockwise
						play_audio('portcullis_close');
						write_to_console("The Portcullis closes behind you!");
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
				hero_map[eval(hero1.x*100 + hero1.y)].searched = 0;
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
					game_over = true;
					add_encounter('gameover', '0');
					force_encounter_modal();
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
			
			play_audio("search");
			var curr_hero_chamber = hero_map[hero1.x*100 + hero1.y];
			
			if (debug || curr_hero_chamber.searchable == '1') {
				if (debug || parseInt(curr_hero_chamber.searched) < 2) {
					
					update_timer();
					curr_hero_chamber.searched++;
					
					// draw random room
					var randomDraw = search_deck.draw_card_with_unique_items();
					
					switch (randomDraw.type) {
						case "secretdoor":
							play_audio("hidden_door");
							write_to_console('Search: Secret Door');
							write_to_console('You may move to any adjacent space');
							curr_hero_chamber.secret_door = '1';
							//add_encounter('secretdoor', '0');
							//force_encounter_modal();
							break;
						case "emptyroom":
							write_to_console('Search: Empty');
							//add_encounter('emptyroom', '0');
							//force_encounter_modal();
							break;
						case "finditem":
							play_audio("gold");
							write_to_console('Search: Find ' + randomDraw.name + ' - ' + randomDraw.value + ' GP');
							add_to_loot_bag(randomDraw.key, randomDraw.name, randomDraw.value, '', '');
							//add_encounter('finditem', '0');
							//force_encounter_modal();
							break;
						case "centipede":
							play_audio("centipede");
							write_to_console('Search: Giant Centipede!');
							add_encounter('centipede', '0')
							force_encounter_modal();
							break;
						case "trap":
							write_to_console('Search: Trap!');
							trigger_trap();
							break;
						case "passagedown":
							write_to_console('Search: Passage to Catacombs!');
							curr_hero_chamber.catacombs = '1';
							//add_encounter('passagedown', '0');
							//force_encounter_modal();
							break;
					}
					
				} else {
					write_to_console('Search: Already searched');
				}
			} else {
				write_to_console("Search: Nothing to Search");
			}
		}
		
		function search_crypt(){
			
			if (event_not_resolved()) { return; }
			
			play_audio("search");
			var curr_hero_chamber = hero_map[hero1.x*100 + hero1.y];
			
			if (debug || parseInt(curr_hero_chamber.crypt) > 0) {
				if (debug || parseInt(curr_hero_chamber.crypt_searched) < 1) {
					
					update_timer();
					curr_hero_chamber.crypt_searched = '1';
					
					var randomDraw = crypt_deck.draw_card_with_unique_items();
					
					switch (randomDraw.type) {
						case "emptycrypt":
							write_to_console('Crypt: Empty');
							//add_encounter('emptycrypt', '0');
							//force_encounter_modal();
							break;
						case "finditem":
							play_audio("gold");
							write_to_console('Crypt: Find ' + randomDraw.name + ' - ' + randomDraw.value + ' GP');
							add_to_loot_bag(randomDraw.id, randomDraw.name, randomDraw.value, '', '');
							//add_encounter('findcryptitem', '0');
							//force_encounter_modal();
							break;
						case "crypttrap":
							write_to_console('Crypt: Trap!');
							add_encounter('crypttrap', '0')
							force_encounter_modal();
							break;
						case "skeleton":
							write_to_console('Crypt: Skeleton');
							add_encounter('skeleton', '0')
							force_encounter_modal();
							break;
					}

				} else {
					write_to_console('Crypt: Already Searched');
				}
			} else {
				write_to_console('Crypt: Nothing to Search');
			}
		}
		
		function search_corpse(){

			if (event_not_resolved()) { return; }
			
			play_audio("search");
			
			var curr_hero_chamber = hero_map[hero1.x*100 + hero1.y];
			
			if (debug || parseInt(curr_hero_chamber.corpse) > 0) {
				if (debug || parseInt(curr_hero_chamber.corpse_searched) < 1) {

					update_timer();
					curr_hero_chamber.corpse_searched = '1';
				
					var randomDraw = corpse_deck.draw_card_with_unique_items();
					
					switch (randomDraw.type) {
						case "emptycorpse":
							write_to_console('Corpse: Empty');
							//add_encounter('emptycorpse', '0');
							//force_encounter_modal();
							break;
						case "finditem":
							play_audio("gold");
							write_to_console('Corpse: Find ' + randomDraw.name + ' - ' + randomDraw.value + ' GP');
							add_to_loot_bag(randomDraw.id, randomDraw.name, randomDraw.value, '', '');
							//add_encounter('findcorpseitem', '0');
							//force_encounter_modal();
							break;
						case "scorpion":
							write_to_console('Corpse: Scorpion!');
							add_encounter('scorpion', '0')
							force_encounter_modal();
							break;
					}
					
				} else {
					write_to_console('Corpse: Already Searched');
				}
			} else {
				write_to_console('Corpse: Nothing to Search');
			}
		}
		
		function search_dragon_chamber(){
			
			if (event_not_resolved()) { return; }

			var randomDraw;
			
			if (dragon_deck.empty()) {
				write_to_console("Treasure: Empty");
				return;
			}
			
			
			if ((debug || hero_map[hero1.x*100 + hero1.y].type == 'treasure') && 
				!dragon_deck.empty() && 
				!treasure_deck.empty()) {
					
				update_timer();
					
				//write_to_console("You loot the Dragon Chamber");
				randomDraw = dragon_deck.draw_dragon_card();
				
				if (String(randomDraw.type) == 'sleeping') {
					
					// The Dragon is Sleeping
					write_to_console('Treasure: Dragon Sleeping');
					
					if (!treasure_deck.empty()) {
						play_audio("gold");
						randomDraw = treasure_deck.draw_unique_card();
						add_to_loot_bag(randomDraw.id, randomDraw.name, randomDraw.value, '', '');
						write_to_console('Treasure: Find ' + randomDraw.name + ' - ' + randomDraw.value + ' GP');
					}
					
					if (!treasure_deck.empty()) {
						play_audio("gold");
						randomDraw = treasure_deck.draw_unique_card();
						add_to_loot_bag(randomDraw.id, randomDraw.name, randomDraw.value, '', '');
						write_to_console('Treasure: Find ' + randomDraw.name + ' - ' + randomDraw.value + ' GP');
					}
					
					play_audio("dragon_sleeping");
					
				} else {
					
					// The Dragon Attacks
					play_audio("dragon_roar");
					write_to_console('Treasure: Dragon Awake!');
					write_to_console('Treasure: Dropped Loot Bag!');
					empty_loot_bag();
					add_encounter('dragonbreath', '0');
					force_encounter_modal();
					
				}
				
			} else {
				write_to_console("The Treasure Chamber is empty");
			}

		}
		
		
		function open_door(){
			
			if (event_not_resolved()) { return; }
			
			if (debug) {
				var curr_hero_chamber = hero_map[hero1.x*100 + hero1.y];

				update_timer();
				var randomDraw = door_deck.draw_card();
				
				alert(randomDraw.type);
				
				switch (randomDraw.type) {
					case "dooropen":
						play_audio("door");
						new_encounter = new Encounter('opendoor', 'Open Door', '#D1001C', 'white', 'You open the Door', '0');
						encounter_queue.push(new_encounter);
						force_encounter_modal();
						break;
					case "doorjammed":
						play_audio("locked_door");
						new_encounter = new Encounter('jammeddoor', 'Jammed Door', '#D1001C', 'white', 'The Door is Jammed', '0');
						encounter_queue.push(new_encounter);
						force_encounter_modal();
						break;
					case "doortrap":
						play_audio("speardoor_2");
						new_encounter = new Encounter('doortrap', 'Trapped Door', '#D1001C', 'white', 'A Trap Door opens under you! <br/> Damage: D6', '0');
						encounter_queue.push(new_encounter);
						force_encounter_modal();
						break;
				}
			}

		}
		
		function encounter_chamber() {
			
			//if (event_not_resolved()) { 
			//	write_to_debugger("encounter_chamber: event_not_resolved");
			//	return; 
			//}
			
			var curr_hero_chamber = hero_map[hero1.x*100 + hero1.y];
			
			var randomDraw = chamber_event_deck.draw_unique_card();
			
			write_to_debugger("Encounter: " + randomDraw.id + ": " + randomDraw.type);
			
			//randomDraw.type = "goblin"
			
			switch (randomDraw.type) {
				case "deadadventurer":
					write_to_console('Encounter: Corpse');
					curr_hero_chamber.corpse = '1';
					if (curr_hero_chamber.corpse == '1' && curr_hero_chamber.corpse_searched == '0') {
						$('.corpse_button').attr('disabled', false);
					}
					break;
				case "crypt":
					write_to_console('Encounter: Crypt');
					curr_hero_chamber.crypt = '1';
					if (curr_hero_chamber.crypt == '1' && curr_hero_chamber.crypt_searched == '0') {
						$('.crypt_button').attr('disabled', false);
					}
					break;
				case "emptychamber":
					write_to_console('Encounter: Empty Chamber');
					break;
				case "ambush":
					write_to_console('Encounter: Ambush!');
					add_encounter('goblin', '0');
					force_encounter_modal();
					//fight_monster();
					break;
				case "trapdoor":
					write_to_console('Encounter: Trap Door!');
					add_encounter('trapdoor', '0');
					force_encounter_modal();
					break;
				case "crossfire":
					write_to_console('Encounter: Hidden Arrows!');
					add_encounter('crossfire', '0');
					force_encounter_modal();
					break;
				case "passagedown":
					write_to_console('Encounter: Passage Down');
					curr_hero_chamber.catacombs = '1';
					break;
				case "collapse":
					write_to_console('Encounter: Cave-In');
					add_encounter('collapse', '0')
					force_encounter_modal();
					//roll_encounter_dice('');
					break;
				case "torchout":
					write_to_console('Encounter: Torch Goes Out');
					hero1.torch = '0';
					add_encounter('torchout', '0');
					force_encounter_modal();
					break;
				case "secretdoor":
					write_to_console('Encounter: Secret Door!');
					curr_hero_chamber.secret_door = '1';
					break;
				case "vampirebats":
					write_to_console('Encounter: Vampire Bats');
					add_encounter('vampirebats', '0');
					force_encounter_modal();
					break;
				case "wizardscurse":
					write_to_console('Encounter: Wizard\'s Curse');
					add_encounter('wizardscurse', '0');
					force_encounter_modal();
					// Magic
					break;
				case "finditem":
					play_audio("gold");
					//write_to_console('Encounter: You find an Item');
					//add_encounter('finditem', '0');
					if (randomDraw.name == "Potion" && have_potion()) {
						write_to_console('You discard the extra Potion');
					} else {
						add_to_loot_bag(randomDraw.id, randomDraw.name, randomDraw.value, '', '');
						write_to_console('Encounter: Find ' + randomDraw.name + ' - ' + randomDraw.value + ' GP');
					}
					
					//force_encounter_modal();
					break;
				case "lingeringshade":
					write_to_console('Encounter: Lingering Shade');
					curr_hero_chamber.lingering_shade = '1';
					break;
				case "giantspider":
					write_to_console('Encounter: Giant Spider');
					add_encounter('giantspider', '0')
					force_encounter_modal();
					break;
				case "goblin":
					play_audio("monster_wounded");
					write_to_console('Encounter: Goblin');
					add_encounter('goblin', '0')
					force_encounter_modal();
					break;
				case "orc":
					play_audio("monster_wounded");
					write_to_console('Encounter: Orc');
					add_encounter('orc', '0')
					force_encounter_modal();
					break;
				case "troll":
					play_audio("monster_wounded");
					write_to_console('Encounter: Troll');
					add_encounter('troll', '0')
					force_encounter_modal();
					break;
				case "undead":
					play_audio("monster_wounded");
					write_to_console('Encounter: undead');
					add_encounter('undead', '0')
					force_encounter_modal();
					break;
				case "blackknight":
					play_audio("monster_wounded");
					write_to_console('Encounter: Black Knight');
					add_encounter('blackknight', '0')
					force_encounter_modal();
					break;
			}
		}
		
		var monster = new Monster();
		
		function draw_monster_stats(p_monster) {
					
			$('.battle_monster_name').html(p_monster.name);
			$('.battle_monster_name_short').html(p_monster.name);
			$('.battle_monster_health').html(p_monster.health);
			$('.battle_monster_strength').html('?');
			$('.battle_monster_agility').html('?');
			$('.battle_monster_defense').html('?');
			$('.battle_monster_luck').html('?');
			$('.battle_monster_description').html(p_monster.description);
			$('.battle_monster_img').attr("src", chamber_image_directory+p_monster.image_url);
			$('.battle_monster_escape_penalty').html(p_monster.escape_penalty);
			
		}
		
		function enter_catacombs(){
			
			// Catacombs not implemented, you die
			add_encounter('catacombs', '0');
			force_encounter_modal();
			roll_encounter_dice('');
		}
		
		function add_to_loot_bag(p_id, p_name, p_value, p_image_url, p_image_url) {
			hero_loot.push(new LootItem(Math.abs(timer-max_timer), p_id, p_name, p_value, p_image_url, p_image_url));
			draw_loot_bag();
		}
		
		function empty_loot_bag() {
			hero_loot = [];
			draw_loot_bag();
		}
		
		function update_hero_health(p_health) {
			hero1.health = parseInt(hero1.health) + parseInt(p_health);
			if (hero1.health > hero1.max_health) { hero1.health = hero1.max_health; }
			if (hero1.health < 0) { hero1.health = 0; }
			draw_hero_stats('hero1', hero1);
		}
		
		
		function update_monster_health(p_health) {
			monster.health = parseInt(monster.health) + parseInt(p_health);
			if (monster.health < 0) { monster.health = 0; }
			draw_monster_stats(monster);
		}
		
		function check_hero_health(){
			if (parseInt(hero1.health) <= 0) {
				write_to_console("You have died!");
				game_over = true;
				add_encounter('death', '0');
				force_encounter_modal();
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
		
		function trigger_trap() {
			
			if (event_not_resolved()) { return; }
						
			var randomDraw = trap_deck.draw_card();
			
			switch(randomDraw.type) {
				case "trapdoor":
					play_audio("trap");
					write_to_console('Trap: Trapdoor');
					add_encounter('trapdoor', '0');
					force_encounter_modal();
					break;
				case "snakes":
					play_audio("snake");
					write_to_console('Trap: Poisonous Snakes');
					add_encounter('snakes', '0');
					force_encounter_modal();
					break;
				case "gas":
					play_audio("gas");
					write_to_console('Trap: Poisonous Gas');
					add_encounter('gas', '0');
					force_encounter_modal();
					break;
				case "collapse":
					play_audio("collapse");
					write_to_console('Trap: Cave-In');
					add_encounter('collapse', '0');
					force_encounter_modal();
					//roll_encounter_dice('');
					break;
				case "explosion":
					play_audio("explosion");
					write_to_console('Trap: Explosion');
					add_encounter('explosion', '0');
					force_encounter_modal();
					break;
				case "crossfire":
					play_audio("trap");
					write_to_console('Trap: Cross Fire');
					add_encounter('crossfire', '0');
					force_encounter_modal();
					break;
			}
			
			
		}
		
		function add_encounter(type, p_direction) {
			
			write_to_debugger("Add Encounter: " + type + " " + p_direction)
			
			// pre-determine door type
			var randomDoor = door_deck.draw_card();
			var new_encounter;
			
			switch (type) {
				
				// PASSAGE
				
				case "door":
					switch (randomDoor.type) {
						case "dooropen":
							if (encounter_queue.length == 0) {
								play_audio("door");
							}
							new_encounter = new Encounter('opendoor', 'Open Door', '#D1001C', 'white', 'You open the Door', p_direction);
							encounter_queue.push(new_encounter);
							break;
						case "doorjammed":
							if (encounter_queue.length == 0) {
								play_audio("locked_door");
							}
							new_encounter = new Encounter('jammeddoor', 'Jammed Door', '#D1001C', 'white', 'The Door is Jammed', p_direction);
							encounter_queue.push(new_encounter);
							break;
						case "doortrap":
							if (encounter_queue.length == 0) {
								play_audio("speardoor_2");
							}
							new_encounter = new Encounter('doortrap', 'Trapped Door', '#D1001C', 'white', 'A Trap Door opens under you! <br/> Damage: D6', p_direction);
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
					play_audio("gold");
					new_encounter = new Encounter('finditem', 'Find Corpse Item', '#000000', 'white', 'You find an Item', p_direction);
					encounter_queue.push(new_encounter);
					break;
				case "vampirebats":
					play_audio("razorwing");
					new_encounter = new Encounter('vampirebats', 'Vampire Bats', '#D1001C', 'white', 'You were attacked by Vampire Bats <br/> Damage: D6 - 2', p_direction);
					encounter_queue.push(new_encounter);
					break;
				case "torchout":
					play_audio("torchout");
					new_encounter = new Encounter('torchout', 'Torch Goes Out', '#000000', 'white', 'Your Torch Goes Out! <br/> [Roll D6] 1-3 Success', p_direction);
					encounter_queue.push(new_encounter);
					break;
				case "wizardscurse":
					new_encounter = new Encounter('wizardscurse', 'Wizard\'s Curse', '#000000', 'white', 'A Wizard Curses the Dungeon! <br/> All Corridors will Rotate', p_direction);
					encounter_queue.push(new_encounter);
					break;
				case "lingeringshade":
					new_encounter = new Encounter('lingeringshade', 'Lingering Shade', '#000000', 'white', 'A Lingering Shadow follows you!', p_direction);
					encounter_queue.push(new_encounter);
					break;
				case "giantspider":
					play_audio("centipede");
					new_encounter = new Encounter('giantspider', 'Giant Spider', '#000000', 'white', 'You encounter a Giant Spider!', p_direction);
					encounter_queue.push(new_encounter);
					new_encounter.show_flee = '1';
					new_encounter.can_flee = '1';
					break;
				case "goblin":
					play_audio("monster_wounded");
					new_encounter = new Encounter('goblin', 'Goblin', '#D1001C', 'white', 'You encounter a Goblin!', p_direction);
					encounter_queue.push(new_encounter);
					new_encounter.show_fight = '1';
					new_encounter.can_fight = '1';
					new_encounter.show_flee = '1';
					new_encounter.can_flee = '1';
					
					randomDraw = monster_deck.draw_monster("goblin");
					monster = new Monster();
					monster.name = randomDraw.name;
					monster.type = randomDraw.type;
					monster.health = randomDraw.health;
					monster.escape_penalty = randomDraw.escape_penalty;
					monster.image_url = randomDraw.image_url;
					break;
				case "orc":
					play_audio("monster_wounded");
					new_encounter = new Encounter('orc', 'Orc', '#D1001C', 'white', 'You encounter a Orc!', p_direction);
					encounter_queue.push(new_encounter);
					new_encounter.show_fight = '1';
					new_encounter.can_fight = '1';
					new_encounter.show_flee = '1';
					new_encounter.can_flee = '1';
					
					randomDraw = monster_deck.draw_monster("orc");
					monster = new Monster();
					monster.name = randomDraw.name;
					monster.type = randomDraw.type;
					monster.health = randomDraw.health;
					monster.escape_penalty = randomDraw.escape_penalty;
					monster.image_url = randomDraw.image_url;
					break;
				case "troll":
					play_audio("monster_wounded");
					new_encounter = new Encounter('troll', 'Troll', '#D1001C', 'white', 'You encounter a Troll!', p_direction);
					encounter_queue.push(new_encounter);
					new_encounter.show_fight = '1';
					new_encounter.can_fight = '1';
					new_encounter.show_flee = '1';
					new_encounter.can_flee = '1';
					
					randomDraw = monster_deck.draw_monster("troll");
					monster = new Monster();
					monster.name = randomDraw.name;
					monster.type = randomDraw.type;
					monster.health = randomDraw.health;
					monster.escape_penalty = randomDraw.escape_penalty;
					monster.image_url = randomDraw.image_url;
					break;
				case "undead":
					play_audio("monster_wounded");
					new_encounter = new Encounter('undead', 'Undead', '#D1001C', 'white', 'You encounter an Undead!', p_direction);
					encounter_queue.push(new_encounter);
					new_encounter.show_fight = '1';
					new_encounter.can_fight = '1';
					new_encounter.show_flee = '1';
					new_encounter.can_flee = '1';
					
					randomDraw = monster_deck.draw_monster("undead");
					monster = new Monster();
					monster.name = randomDraw.name;
					monster.type = randomDraw.type;
					monster.health = randomDraw.health;
					monster.escape_penalty = randomDraw.escape_penalty;
					monster.image_url = randomDraw.image_url;
					break;
				case "blackknight":
					play_audio("monster_wounded");
					new_encounter = new Encounter('blackknight', 'Black Knight', '#D1001C', 'white', 'You encounter a Black Knight!', p_direction);
					encounter_queue.push(new_encounter);
					new_encounter.show_fight = '1';
					new_encounter.can_fight = '1';
					new_encounter.show_flee = '0';
					new_encounter.can_flee = '0';
					
					randomDraw = monster_deck.draw_monster("blackknight");
					monster = new Monster();
					monster.name = randomDraw.name;
					monster.type = randomDraw.type;
					monster.health = randomDraw.health;
					monster.escape_penalty = randomDraw.escape_penalty;
					monster.image_url = randomDraw.image_url;
					
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
					play_audio("hidden_door");
					new_encounter = new Encounter('secretdoor', 'Secret Door', '#000000', 'white', 'You found a Secret Door', p_direction);
					encounter_queue.push(new_encounter);
					break;
				case "emptyroom":
					new_encounter = new Encounter('emptyroom', 'Empty Room', '#000000', 'white', 'The room is Empty', p_direction);
					encounter_queue.push(new_encounter);
					break;
				case "finditem":
					play_audio("gold");
					new_encounter = new Encounter('finditem', 'Find Item', '#000000', 'white', 'You find an Item', p_direction);
					encounter_queue.push(new_encounter);
					break;
				case "centipede":
					play_audio("centipede");
					new_encounter = new Encounter('centipede', 'Find Item', '#D1001C', 'white', 'A Centipede Attacks You! <br/> Damage: 2D6', p_direction);
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
					play_audio("gold");
					new_encounter = new Encounter('finditem', 'Find Crypt Item', '#000000', 'white', 'You find an Item in the Crypt', p_direction);
					encounter_queue.push(new_encounter);
					break;
				case "skeleton":
					new_encounter = new Encounter('skeleton', 'Skeleton', '#D1001C', 'white', 'You see a Skeleton! <br/> [Roll D6] 1-3 Nothing Happens <br/> [Roll D6] 4-6 Fight Undead', p_direction);
					encounter_queue.push(new_encounter);
					new_encounter.num_dice_roll = 2;
					break;
				case "crypttrap":
					play_audio("trap");
					new_encounter = new Encounter('crypttrap', 'Crypt Trap', '#D1001C', 'white', 'You sprung a Trap! <br/> Damage: D6 - 3', p_direction);
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
					play_audio("centipede");
					new_encounter = new Encounter('scorpion', 'Scorpion', '#D1001C', 'white', 'A Scorpion Attacks You! <br/> Damage: D6', p_direction);
					encounter_queue.push(new_encounter);
					break;
					
				// TRAP
				case "trapdoor":
					play_audio("trap");
					new_encounter = new Encounter('trapdoor', 'Trap Door', '#D1001C', 'white', 'A Trap Door opens beneath you! <br/> [ Test Agility ]', p_direction);
					new_encounter.num_dice_roll = 2;
					encounter_queue.push(new_encounter);
					break;
				case "snakes":
					play_audio("snake");
					new_encounter = new Encounter('snakes', 'Poisonous Snakes', '#D1001C', 'white', 'A Poisonous Snake bites you! <br/> Damage: D6', p_direction);
					encounter_queue.push(new_encounter);
					break;
				case "gas":
					play_audio("gas");
					new_encounter = new Encounter('gas', 'Poisonous Gas', '#D1001C', 'white', 'Poisonous Gas spills into the room! <br/> Damage: D6-3 <br/> Lose Time: D6-3', p_direction);
					encounter_queue.push(new_encounter);
					break;
				case "collapse":
					play_audio("collapse");
					//new_encounter = new Encounter('collapse', 'Ceiling Collapse', '#D1001C', 'white', 'The Ceiling Caved-In! <br/> Death: Dice = 6', p_direction);
					//new_encounter.num_dice_roll = 2;
					new_encounter = new Encounter('collapse', 'Ceiling Collapse', '#D1001C', 'white', 'The Ceiling Caved-In! <br/> Damage: D6, [6] = Death', p_direction);
					encounter_queue.push(new_encounter);
					break;
				case "explosion":
					play_audio("explosion");
					new_encounter = new Encounter('explosion', 'Explosion', '#D1001C', 'white', 'You were hit by an explosion! <br/> Damage: D6 <br/> Miss a turn', p_direction);
					encounter_queue.push(new_encounter);
					break;
				case "crossfire":
					play_audio("crossfire");
					new_encounter = new Encounter('crossfire', 'Cross Fire', '#D1001C', 'white', 'Hidden Arrows fires at you! <br/> [ Damage: 2D6 - Armor ]', p_direction);
					encounter_queue.push(new_encounter);
					break;
					
				// TREASURE CHAMBER
				case "dragonbreath":
					play_audio("dragon_sleeping");
					new_encounter = new Encounter('dragonbreath', 'Dragon Breath', '#D1001C', 'white', 'The Dragon Attacks you!', p_direction);
					encounter_queue.push(new_encounter);
					break;
					
				// POTION
				case "potion":
					new_encounter = new Encounter('potion', 'Potion', '#D1001C', 'white', 'You drink the Potion!', p_direction);
					encounter_queue.push(new_encounter);
					break;
					
				// TORCH
				case "torchout":
					play_audio("torchout");
					new_encounter = new Encounter('torchout', 'Torch Goes Out', '#000000', 'white', 'Your Torch Goes Out! <br/> [Roll D6] 1-3 Success <br/> [Roll D6] 4-6 Fail', p_direction);
					encounter_queue.push(new_encounter);
					update_timer();
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
					
				// EXIT
				case "exit":
					new_encounter = new Encounter('exit', 'Exit Dungeon', '#01E501', 'white', 'You exit the Dungeon with '+loot_total()+' Gold!', p_direction);
					encounter_queue.push(new_encounter);
					break;
				
			}
			
			if (debug) {
				for (var i = 0; i < encounter_queue.length; i++) {
					write_to_debugger("Encounter Queue #"+i+" " + encounter_queue[i].type);
				}
			}
		}
		
		function hero_dead() {
			if (hero1.health <= 0) {
				play_audio("hero_dying");
				return true;
			}
			return false;
		}
		
		function roll_encounter_dice(p_button) {
			
			var current_encounter = encounter_queue[0];
			
			write_to_debugger("Roll Trap Dice: " + current_encounter.type);
			
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
			
			
			switch(current_encounter.type) {
					
				case "darkness":
				case "snakes":
				case "gas":
				case "explosion":
				case "collapse":
				case "doortrap":
				case "skeleton":
				case "scorpion":
				case "vampirebats":
				case "giantspider":
				case "crypttrap":
				case "torchout":
				case "wizardscurse":
				
					dice1 = Math.floor(Math.random() * 6 + 1);
					mini_dice.dataset.side = dice1;
					mini_dice.classList.toggle("reRoll");
					break;
					
				case "trapdoor":
				
					if (hero1.fell_in_pit == '1' || current_encounter.num_dice_roll == 2) {
						dice1 = Math.floor(Math.random() * 6 + 1);
						mini_dice.dataset.side = dice1;
						mini_dice.classList.toggle("reRoll");
						
						dice2 = Math.floor(Math.random() * 6 + 1);
						mini_dice_2.dataset.side = dice2;
						mini_dice_2.classList.toggle("reRoll");
					} else {
						dice1 = Math.floor(Math.random() * 6 + 1);
						mini_dice.dataset.side = dice1;
						mini_dice.classList.toggle("reRoll");
					}
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
			
			if (encounter_queue.length == 0 || current_encounter.resolved == '1') {
				$('.dice_total').html(dice1 + dice2);
				return;
			}
			
			if (hero1.health <= 0 || game_over) {
				return;
			}
			
			
			switch(current_encounter.type) {
				
				case "opendoor":
					
					current_encounter.resolved = '1';
					current_encounter.success = '1';
					
					write_to_console('Door was unlocked');
					$('.dice_total').css("background-color", "#000000");
					$('.dice_total').css("color", "black");
					$('.encounter_text').html("Door was unlocked");
					break;
					
				case "jammeddoor":
					
					current_encounter.resolved = '1';
					current_encounter.success = '0';
					
					write_to_console('The Door was Jammed Shut!');
					$('.dice_total').css("background-color", "#D1001C");
					$('.dice_total').css("color", "white");
					break;
					
				case "doortrap":
					
					play_audio("hero_wounded");
					
					total = dice1;
					$('.dice_total').html(total);
					current_encounter.resolved = '1';
					current_encounter.success = '0';
					
					write_to_console('['+total+'] You were stabbed by a Spear!');
					write_to_console('['+total+'] You suffer '+total+' wounds');
					$('.encounter_text').html("You were stabbed by a Spear! <br/> You suffer "+total+" wounds!");
					$('.dice_total').css("background-color", "#D1001C");
					$('.dice_total').css("color", "white");
					update_hero_health(-total);
					if (hero_dead()) {
						write_to_console('['+total+'] You were killed by the Spear!');
						$('.encounter_text').html("You were killed by the Spear!");
						game_over = true;
					}
					break;
					
				case "trapdoor":
					
					if (hero1.fell_in_pit == '1') {
						
						if (have_rope()) {
							
							hero1.fell_in_pit = '0'
							$('.encounter_text').html("Rope: You climb out of the pit");
							update_timer();
							
						} else {
							total = dice1 + dice2;
							$('.dice_total').html(total);
							update_timer();
							
							if (parseInt(total) <= parseInt(hero1.agility)) {
								hero1.fell_in_pit = '0'
								current_encounter.resolved = '1';
								current_encounter.success = '1';
								write_to_console('['+total+'] You climbed out of the pit!');
								$('.dice_total').css("background-color", "#01E501");
								$('.dice_total').css("color", "white");
								$('.encounter_text').css("background-color", "#01E501");
								$('.encounter_text').html("You climbed out of the pit!");
							} else {
								current_encounter.resolved = '0';
								current_encounter.success = '0';
								$('.encounter_text').html("You failed to climb out of the pit");
							}
						}
						
					} else {
					
						if (current_encounter.num_dice_roll == 2) {
							
							total = dice1 + dice2;
							$('.dice_total').html(total);
							
							if (parseInt(total) <= parseInt(hero1.agility)) {
								current_encounter.num_dice_roll--;
								current_encounter.resolved = '1';
								current_encounter.success = '1';
								write_to_console('['+total+'] You avoided the Trap Door!');
								$('.dice_total').css("background-color", "#01E501");
								$('.dice_total').css("color", "white");
								$('.encounter_text').css("background-color", "#01E501");
								$('.encounter_text').html("You avoided the Trap Door!");
							} else {
								play_audio("hero_grunt");
								current_encounter.resolved = '0';
								current_encounter.success = '0';
								$('.encounter_text').html("You fell into a pit! <br/> Damage: D6");
							}
							
						} else {
							
							total = dice1;
							$('.dice_total').html(total);
							current_encounter.resolved = '0';
							current_encounter.success = '0';
							
							play_audio("hero_wounded");
							hero1.fell_in_pit = '1';
								
							write_to_console('['+total+'] You suffer '+total+' wounds');
							$('.dice_total').css("background-color", "#D1001C");
							$('.dice_total').css("color", "white");
							$('.encounter_text').css("background-color", "#D1001C");
							$('.encounter_text').html("You fell into the trapdoor! <br/> You suffer "+total+" wounds");
							update_hero_health(-total);
							if (hero_dead()) {
								write_to_console('['+total+'] You were killed in the fall!');
								$('.encounter_text').html("You were killed in the fall!");
								game_over = true;
							}
						}
					}
					
					break;
					
				case "snakes":
					
					play_audio("hero_wounded");
					
					total = dice1;
					$('.dice_total').html(total);
					current_encounter.resolved = '1';
					current_encounter.success = '0';
					
					write_to_console('['+total+'] You were bitten by a Poisonous Snake!');
					write_to_console('['+total+'] You suffer '+ total +' wounds!');
					$('.encounter_text').html("You suffer "+ total +" wounds!");
					$('.dice_total').css("background-color", "#D1001C");
					$('.dice_total').css("color", "white");
					update_hero_health(-total);
					if (hero_dead()) {
						write_to_console('['+total+'] You are killed by the Poisonous Snake!');
						$('.encounter_text').html("You are killed by the Poisonous Snake!");
						game_over = true;
					}
					break;
					
				case "gas":
					
					total = dice1;
					$('.dice_total').html(total);
					current_encounter.resolved = '1';
					current_encounter.success = '0';
					
					write_to_console('['+total+'] Poisonous Gas pours into the room!');
					$('.dice_total').css("background-color", "#D1001C");
					$('.dice_total').css("color", "white");
					
					if (total > 3) {
						play_audio("hero_wounded");
						$('.encounter_text').html("You suffer "+ eval(total - 3) +" wounds and lose "+ eval(total - 3) +" time!");
						write_to_console('['+total+'] You suffer '+ eval(total - 3) +' wounds!');
						write_to_console('['+total+'] You lose '+ eval(total - 3) +' time!');
						update_hero_health(-eval(total - 3));
						for (var i = 0; i < total - 3; i++) {
							update_timer();
						}
						if (hero_dead()) {
							write_to_console('['+total+'] You were killed by Poison!');
							$('.encounter_text').html("You were killed by Poison!");
							game_over = true;
						}
					} else {
						write_to_console('['+total+'] You avoided the Poisonous Gas!');
						$('.encounter_text').css("background-color", "#01E501");
						$('.encounter_text').css("color", "white");
						$('.encounter_text').html("You avoided the Poisonous Gas!");
					}
					break;
					
				case "explosion":
				
					play_audio("hero_wounded");
					update_timer();
					
					total = dice1;
					$('.dice_total').html(total);
					current_encounter.resolved = '1';
					current_encounter.success = '0';
					
					update_hero_health(-total);
					if (parseInt(hero1.health) <= 0) {
						write_to_console('['+total+'] You suffer '+total+' wounds!');
						write_to_console('['+total+'] You are killed by an Explosion!');
						$('.encounter_text').html('You are killed by an Explosion!');
						game_over = true;
					} else {
						write_to_console('['+total+'] You are wounded by an Explosion!');
						write_to_console('['+total+'] You suffer '+total+' wounds!');
						$('.encounter_text').html('You are wounded by an Explosion! <br/> You suffer '+total+' wounds!');
					}
					break;
					
				case "collapse":
				
					total = dice1;
					$('.dice_total').html(total);
						
					if (current_encounter.num_dice_roll == 2) {
						
						
						if (parseInt(total) == 6) {
							current_encounter.resolved = '1';
							current_encounter.success = '0';
							play_audio("hero_dying");
							write_to_console('['+total+'] You were killed from the Cave-In!');
							$('.encounter_text').html("You were killed from the Cave-In!");
							$('.dice_total').css("background-color", "#D1001C");
							$('.dice_total').css("color", "white");
							update_hero_health(-hero1.health);
							game_over = true;
							
							current_encounter.num_dice_roll--;
							
						} else {
							current_encounter.resolved = '0';
							current_encounter.success = '0';
							$('.encounter_text').html("You were injured in the Cave-In! <br/> Damage: D6");
						}
						
					} else {
				
						play_audio("hero_wounded");
						
						current_encounter.resolved = '1';
						current_encounter.success = '0';
						
						if (total < 6) {
							write_to_console('['+total+'] You were wounded from the Cave-In!');
							write_to_console('['+total+'] You suffer '+total+' wounds!');
							$('.dice_total').css("background-color", "#D1001C");
							$('.dice_total').css("color", "white");
							update_hero_health(-total);
							
							if (hero_dead()) {
								play_audio("hero_dying");
								write_to_console('['+total+'] You were killed from the Cave-In!');
								$('.encounter_text').html("You were killed from the Cave-In!");
								$('.dice_total').css("background-color", "#D1001C");
								$('.dice_total').css("color", "white");
								game_over = true;
							}
							
						} else {
							play_audio("hero_dying");
							write_to_console('['+total+'] You were killed from the Cave-In!');
							$('.encounter_text').html("You were killed from the Cave-In!");
							$('.dice_total').css("background-color", "#D1001C");
							$('.dice_total').css("color", "white");
							update_hero_health(-hero1.health);
							game_over = true;
						}
						
						
					}
					
					break;
					
				case "crossfire":
					
					
					total = dice1 + dice2;
					$('.dice_total').html(total);
					current_encounter.resolved = '1';
					current_encounter.success = '0';
					
					if (parseInt(total) <= parseInt(hero1.defense)) {
						write_to_console('['+total+'] Your armor deflected the Hidden Arrows!');
						$('.encounter_text').html("Your armor deflected the Hidden Arrows!");
						$('.dice_total').css("background-color", "#01E501");
						$('.dice_total').css("color", "white");
						$('.encounter_text').css("background-color", "#01E501");
					} else {
						play_audio("hero_wounded");
						var wounds = parseInt(total) - parseInt(hero1.defense);
						write_to_console('['+total+'] You were hit by Hidden Arrows!');
						write_to_console('['+total+'] You suffer '+wounds+' wounds!');
						$('.encounter_text').html("You were hit by the Hidden Arrows! <br/> You suffer "+wounds+" wounds!");
						$('.dice_total').css("background-color", "#D1001C");
						$('.dice_total').css("color", "white");
						update_hero_health(-wounds);
						if (hero_dead()) {
							write_to_console('['+total+'] You were killed by the Hidden Arrows!');
							$('.encounter_text').html("You were killed by the Hidden Arrows!");
							game_over = true;
						}
					}
					break;
					
				case "wizardscurse":
				
					var total = dice1;
					var rotation_amount = 0;
					
					current_encounter.resolved = '1';
					current_encounter.success = '0';
					
					switch (total.toString()) {
						case '1':
						case '2':
							rotation_amount = 90;
							break;
						case '3':
						case '4':
							rotation_amount = 180;
							break;
						case '5':
						case '6':
							rotation_amount = 270;
							break;
					}
					
					play_audio("rotating_chamber");
					$('.encounter_text').html("Wizard's Curse: You feel the ground shaking");
					
					

					for (var i = 0; i < boardgame_height; i++) {
						for (var j = 0; j < boardgame_width; j++) {
							
							var current_hero_map = hero_map[(j+1)*100 + (i+1)];
								
							if (current_hero_map != undefined) {
								if (current_hero_map.type == "corridor") {
									//alert("rotation amount: " + rotation_amount);
									//alert('found corridor: '+j + " " + i + " " + current_hero_map.orientation);
									
									current_hero_map.orientation += rotation_amount;
									//alert('found corridor: '+j + " " + i + " " + current_hero_map.orientation);
									
									
									var temp_top = current_hero_map.top;
									current_hero_map.top = current_hero_map.left;
									current_hero_map.left = current_hero_map.bottom;
									current_hero_map.bottom = current_hero_map.right;
									current_hero_map.right = temp_top;
									
									$('#dungeon_cell_'+(j+1)+'_'+i).css("transform", "rotate("+current_hero_map.orientation+"deg)");
								}
							}
						}
					}
					
					update_mini_game_board();
					update_medium_game_board();
						
						// var current_hero_map = hero_map[hero1.x*100 + hero1.y];
						// current_hero_map.orientation += 90;
						
						// var temp_top = current_hero_map.top;
						// current_hero_map.top = current_hero_map.left;
						// current_hero_map.left = current_hero_map.bottom;
						// current_hero_map.bottom = current_hero_map.right;
						// current_hero_map.right = temp_top;
						
						// $('#dungeon_cell_'+hero1.x+1+'_'+hero1.y).css("transform", "rotate("+hero_map[hero1.x*100 + hero1.y].orientation+"deg)");
						// update_mini_game_board();
						// update_medium_game_board();
				
					break;
					
				case "centipede":
					
					play_audio("hero_wounded");
					
					total = dice1 + dice2;
					$('.dice_total').html(total);
					current_encounter.resolved = '1';
					current_encounter.success = '0';
					
					write_to_console('['+total+'] You were attacked by a Centipede!');
					write_to_console('['+total+'] You suffer '+total+' wounds!');
					$('.dice_total').css("background-color", "#D1001C");
					$('.dice_total').css("color", "white");
					$('.encounter_text').html("You suffer "+total+" wounds!");
					update_hero_health(-total);
					if (hero_dead()) {
						play_audio("hero_dying");
						write_to_console('['+total+'] You are killed by the Centipede!');
						$('.encounter_text').html("You are killed by the Centipede!");
						game_over = true;
					}
						
					break;
					
				case "bridge":
					
					total = dice1 + dice2;
					$('.dice_total').html(total);
					
					var hero_agility = hero1.agility - loot_weight();
					if (total <= hero_agility) {
						play_audio("bridge");
						current_encounter.resolved = '1';
						current_encounter.success = '1';
						write_to_console('['+total+'] You crossed the Bridge!');
						$('.encounter_text').html("You crossed the Bridge!");
						$('.dice_total').css("background-color", "#01E501");
						$('.dice_total').css("color", "white");
					} else {
						play_audio("hero_wounded");
						current_encounter.resolved = '0';
						current_encounter.success = '0';
						write_to_console('['+total+'] You fell off the Bridge!');
						$('.encounter_text').html("You fell off the Bridge!");
						$('.dice_total').css("background-color", "#D1001C");
						$('.dice_total').css("color", "white");
						update_hero_health(-total);
						if (hero_dead()) {
							play_audio("hero_dying");
							write_to_console('['+total+'] You are killed by the Fall!');
							$('.encounter_text').html("You are killed by the Fall!");
							game_over = true;
						}
						update_timer();
					}
					break;
					
				case "portcullis":
					
					total = dice1 + dice2;
					$('.dice_total').html(total);
					
					if (total <= hero1.strength) {
						play_audio('portcullis_open');
						current_encounter.resolved = '1';
						current_encounter.success = '1';
						write_to_console('['+total+'] You raised the Portcullis!');
						$('.encounter_text').html("You raised the Portcullis!");
						$('.dice_total').css("background-color", "#01E501");
						$('.dice_total').css("color", "white");
					} else {
						play_audio('locked_door');
						current_encounter.resolved = '0';
						current_encounter.success = '0';
						write_to_console('['+total+'] You failed to raise the Portcullis!');
						$('.encounter_text').html("You failed to raise the Portcullis!");
						$('.dice_total').css("background-color", "#D1001C");
						$('.dice_total').css("color", "white");
						update_timer();
					}
					break;
					
				case "pit":
					
					total = dice1 + dice2;
					$('.dice_total').html(total);
					
					if (total <= hero1.luck) {
						current_encounter.resolved = '1';
						current_encounter.success = '1';
						write_to_console('['+total+'] You jumped over the Pit!');
						$('.encounter_text').html("You jumped over the Pit!");
						$('.dice_total').css("background-color", "#01E501");
						$('.dice_total').css("color", "white");
						play_audio('jump');
					} else {
						play_audio("hero_dying");
						current_encounter.resolved = '1';
						current_encounter.success = '0';
						$('.dice_total').css("background-color", "#D1001C");
						$('.dice_total').css("color", "white");
						update_hero_health(-hero1.health);
						if (hero_dead()) {
							write_to_console('['+total+'] You fell into the Pit and died!');
							$('.encounter_text').html("You fell into the Pit and died!");
							game_over = true;
						}
					}
					break;
					
				case "rubble":
					
					play_audio("walking");
					
					total = dice1 + dice2;
					$('.dice_total').html(total);
					
					if (total <= hero1.agility) {
						current_encounter.resolved = '1';
						current_encounter.success = '1';
						write_to_console('['+total+'] You found passage through the Rubble!');
						$('.encounter_text').html("You found passage through the Rubble!");
						$('.dice_total').css("background-color", "#01E501");
						$('.dice_total').css("color", "white");
					} else {
						current_encounter.resolved = '0';
						current_encounter.success = '0';
						write_to_console('['+total+'] You failed to find passage!');
						$('.encounter_text').html("You failed to find passage!");
						$('.dice_total').css("background-color", "#D1001C");
						$('.dice_total').css("color", "white");
						update_timer();
					}
					break;
					
				case "spiderweb":
					
					total = dice1 + dice2;
					$('.dice_total').html(total);
					
					if (total <= hero1.strength) {
						play_audio("spiderweb");
						current_encounter.resolved = '1';
						current_encounter.success = '1';
						hero_map[hero1.x*100 + hero1.y].stuck_in_spiderweb = '0';
						write_to_console('['+total+'] You broke through the Spider Web!');
						$('.encounter_text').html("You broke through the Spider Web!");
						$('.dice_total').css("background-color", "#01E501");
						$('.dice_total').css("color", "white");
					} else {
						play_audio("spiderweb");
						current_encounter.resolved = '0';
						current_encounter.success = '0';
						hero_map[hero1.x*100 + hero1.y].stuck_in_spiderweb = '1';
						write_to_console('['+total+'] You are stuck in the Spider Web!');
						$('.encounter_text').html("You are stuck in the Spider Web!");
						$('.dice_total').css("background-color", "#D1001C");
						$('.dice_total').css("color", "white");
						update_timer();
					}
					break;
					
				case "darkness":
					
					total = dice1;
					$('.dice_total').html(total);
					current_encounter.resolved = '1';
					current_encounter.success = '1';
					move_through_darkness(current_encounter.direction, total);
					break;
					
				// Corpse
				case "scorpion":
					
					play_audio("hero_wounded");
					
					total = dice1;
					$('.dice_total').html(total);
					current_encounter.resolved = '1';
					current_encounter.success = '0';
					
					write_to_console('['+total+'] You were attacked by a Scorpion!');
					write_to_console('['+total+'] You suffer '+total+' wounds!');
					$('.dice_total').css("background-color", "#D1001C");
					$('.dice_total').css("color", "white");
					$('.encounter_text').html("You suffer "+total+" wounds!");
					update_hero_health(-total);
					if (hero_dead()) {
						write_to_console('['+total+'] You are killed by the Scorpion!');
						$('.encounter_text').html("You are killed by the Scorpion!");
						game_over = true;
					}
						
					break;
					
				// Crypt
				case "crypttrap":
					
					total = dice1;
					$('.dice_total').html(total);
					
					if (total - 3 <= 0) {
						play_audio("trap");
						current_encounter.resolved = '1';
						current_encounter.success = '1';
						$('.dice_total').css("background-color", "#01E501");
						$('.dice_total').css("color", "white");
						write_to_console('['+total+'] You avoided the Trap!');
						$('.encounter_text').html("You avoided the Trap!");
					} else {
						play_audio("hero_wounded");
						current_encounter.resolved = '1';
						current_encounter.success = '0';
						write_to_console('['+total+'] You suffer '+eval(total - 3)+' wounds!');
						$('.dice_total').css("background-color", "#D1001C");
						$('.dice_total').css("color", "white");
						$('.encounter_text').html("You suffer "+eval(total - 3)+" wounds!");
						update_hero_health(-eval(total - 3));
						if (hero_dead()) {
							write_to_console('['+total+'] You are killed by the Trap!');
							$('.encounter_text').html("You are killed by the Trap!");
							game_over = true;
						}
					}
						
					break;
					
				case "skeleton":
					
					total = dice1;
					$('.dice_total').html(total);
					
					if (total < 4) {
						current_encounter.resolved = '1';
						current_encounter.success = '1';
						$('.dice_total').css("background-color", "#01E501");
						$('.dice_total').css("color", "white");
						write_to_console('['+total+'] Nothing Happens!');
						$('.encounter_text').html("Nothing Happens!");
					} else {
						current_encounter.resolved = '1';
						current_encounter.success = '0';
						play_audio("monster_wounded");
						current_encounter.resolved = '1';
						current_encounter.success = '0';
						write_to_console('['+total+'] Fight Undead!');
						$('.dice_total').css("background-color", "#D1001C");
						$('.dice_total').css("color", "white");
						$('.encounter_text').html("Fight Undead!");
						
						add_encounter('undead','0');
					}
						
					break;
					
				case "vampirebats":
					
					
					total = dice1;
					$('.dice_total').html(total);
					
					wounds = total - 2;
					
					if (wounds <= 0) {
						current_encounter.resolved = '1';
						current_encounter.success = '1';
						write_to_console('['+total+'] You evaded the Vampire Bats!');
						$('.encounter_text').html("You evaded the Vampire Bats!");
						$('.dice_total').css("background-color", "#01E501");
						$('.dice_total').css("color", "white");
					} else {
						play_audio("hero_wounded");
						current_encounter.resolved = '1';
						current_encounter.success = '0';
						write_to_console('['+total+'] You were attacked by Vampire Bats!');
						write_to_console('['+total+'] You suffer '+wounds+' wounds!');
						$('.dice_total').css("background-color", "#D1001C");
						$('.dice_total').css("color", "white");
						$('.encounter_text').html("You suffer "+wounds+" wounds!");
						update_hero_health(-wounds);
						if (hero_dead()) {
							write_to_console('['+total+'] You are killed by Vampire Bats!');
							$('.encounter_text').html("You are killed by Vampire Bats!");
							game_over = true;
						}
					}
						
					break;
					
				case "giantspider":
					
					total = dice1;
					$('.dice_total').html(total);
					
					if (p_button == "flee") {
						
						if (total < 3) {
							current_encounter.resolved = '1';
							current_encounter.success = '1';
							current_encounter.can_flee = '0';
							
							$('.encounter_flee_button').attr("disabled", true);
							
							write_to_console('['+total+'] You flee from the Giant Spider!');
							$('.encounter_text').html("You flee from the Giant Spider!");
							
							flee();
							
						} else {
							current_encounter.resolved = '0';
							current_encounter.success = '0';
							current_encounter.can_flee = '0';
							
							$('.encounter_flee_button').attr("disabled", true);
							
							write_to_console('['+total+'] You failed to flee from the Giant Spider!');
							$('.encounter_text').html("You failed to flee from the Giant Spider!");
						}
						
					} else {
						
						current_encounter.can_flee = '0';
						$('.encounter_flee_button').attr("disabled", true);
							
						if (total <= 3) {
							play_audio("monster_wounded");
							current_encounter.resolved = '1';
							current_encounter.success = '1';
							write_to_console('['+total+'] You killed the Giant Spider!');
							$('.encounter_text').html("You killed the Giant Spider!");
							$('.dice_total').css("background-color", "#01E501");
							$('.dice_total').css("color", "white");
							update_timer();
						} else {
							play_audio("hero_wounded");
							wounds = 1;
							current_encounter.resolved = '0';
							current_encounter.success = '0';
							write_to_console('['+total+'] You were attacked by the Giant Spider!');
							write_to_console('['+total+'] You suffer '+wounds+' wounds!');
							$('.dice_total').css("background-color", "#D1001C");
							$('.dice_total').css("color", "white");
							$('.encounter_text').html("You suffer "+wounds+" wounds!");
							update_hero_health(-wounds);
							if (hero_dead()) {
								write_to_console('['+total+'] You are killed by the Giant Spider!');
								$('.encounter_text').html("You are killed by the Giant Spider!");
								game_over = true;
							}
							update_timer();
						}
					}
					
					if (current_encounter.show_flee == '1') { $('.encounter_flee_button').show(); }
					if (current_encounter.can_flee == '1') { $('.encounter_flee_button').attr("disabled", false); } else { $().attr("disabled", true); }
						
					break;
					
				// use potion
				case "potion":
					
					var wounds = 0;
					var heal = 0;
					
					total = dice1 + dice2;
					$('.dice_total').html(total);
					
					current_encounter.resolved = '1';
					current_encounter.success = '0';
					
					switch(total.toString()) {
						case "2":
							play_audio("drink_potion");
							write_to_console("You heal 4 wounds");
							heal = 4;
							update_hero_health(heal);
							break;
						case "3":
							play_audio("drink_potion");
							write_to_console("You heal 3 wounds");
							heal = 3;
							update_hero_health(heal);
							break;
						case "4":
							play_audio("drink_potion");
							write_to_console("You heal 2 wounds");
							heal = 2;
							update_hero_health(heal);
							break;
						case "5":
							play_audio("drink_potion");
							write_to_console("You heal 1 wound");
							heal = 1;
							update_hero_health(heal);
							break;
						case "6":
						case "7":
						case "8":
						case "9":
							play_audio("drink_potion");
							write_to_console("Nothing happens");
							$('.encounter_text').html("Nothing happens");
							break;
						case "10":
							play_audio("hero_wounded");
							write_to_console("Weak Poison: Lose 2 health");
							wounds = 2;
							update_hero_health(-wounds);
							break;
						case "11":
							play_audio("hero_wounded");
							write_to_console("Strong Poison: Lose half health");
							wounds = Math.floor(hero1.health/2);
							update_hero_health(-wounds);
							break;
						case "12":
							play_audio("hero_dying");
							write_to_console("Deadly Poison: Instant Death!");
							wounds = hero1.health;
							update_hero_health(-wounds);
							break;
					}
					
					if (heal > 0) {
						$('.dice_total').css("background-color", "#01E501");
						$('.dice_total').css("color", "white");
						$('.encounter_text').html("You heal "+heal+" wounds!");
					}
					if (wounds > 0) {
						$('.dice_total').css("background-color", "#D1001C");
						$('.dice_total').css("color", "white");
						$('.encounter_text').html("You suffer "+wounds+" wounds!");
					}
					if (hero_dead()) {
						write_to_console('['+total+'] You die from the potion!');
						$('.encounter_text').html("You die from the potion!");
						game_over = true;
					}
						
					break;
				
				// torch out
				case "torchout":
					
					total = dice1;
					$('.dice_total').html(total);
					
					if (total <= 3) {
						play_audio("torch_success");
						current_encounter.resolved = '1';
						current_encounter.success = '1';
						hero1.torch = '1';
						write_to_console('['+total+'] You lit your Torch!');
						$('.encounter_text').html("You lit your Torch!");
						$('.dice_total').css("background-color", "#01E501");
						$('.dice_total').css("color", "white");
					} else {
						play_audio("torch_fail");
						current_encounter.resolved = '0';
						current_encounter.success = '0';
						write_to_console('['+total+'] You failed to light your Torch!');
						$('.encounter_text').html("You failed to light your Torch!");
						$('.dice_total').css("background-color", "#000000");
						$('.dice_total').css("color", "white");
						update_timer();
					}
					break;
					
				case "dragonbreath":
					
					play_audio("hero_dying");
						
					total = dice1 + dice2;
					$('.dice_total').html(total);
					current_encounter.resolved = '1';
					current_encounter.success = '0';
					
					write_to_console('['+total+'] You were attacked by the Dragon!');
					write_to_console('['+total+'] You suffer '+total+' wounds!');
					$('.dice_total').css("background-color", "#D1001C");
					$('.dice_total').css("color", "white");
					$('.encounter_text').html("You suffer "+total+" wounds!");
					update_hero_health(-total);
					if (hero_dead()) {
						play_audio("gore");
						write_to_console('['+total+'] You are killed by the Dragon!');
						$('.encounter_text').html("You are killed by the Dragon!");
						game_over = true;
					} else {
						flee();
					}
						
					break;
					
					
				case "goblin":
				case "orc":
				case "troll":
				case "undead":
				case "blackknight":
				
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
					
					if (p_button == 'fight') { 
					
						switch (total.toString()) {
							case '1':
							case '2':
								$('.dice_total').css('background-color', '#D1001C');
								$('.dice_total').css('color', 'white');
								write_to_console("["+total+"] You received 1 wound");
								$('.encounter_text').html("["+total+"] You received 1 wound");
								update_hero_health(-1);
								play_audio("hero_wounded");
								break;
							case '3':
							case '4':
								$('.dice_total').css('background-color', '#01E501');
								$('.dice_total').css('color', 'white');
								write_to_console("["+total+"] You wounded "+current_encounter.title);
								$('.dice_total').css('background-color', '#D1001C');
								$('.dice_total').css('color', 'white');
								write_to_console("["+total+"] You received 1 wound");
								$('.encounter_text').html("["+total+"] You received 1 wound");
								update_hero_health(-1);
								update_monster_health(-1);
								play_audio("hero_wounded");
								play_audio("monster_wounded");
								break;
							case '5':
								$('.dice_total').css('background-color', '#D1001C');
								$('.dice_total').css('color', 'white');
								write_to_console("["+total+"] You wounded "+current_encounter.title);
								$('.encounter_text').html("["+total+"] You wounded "+current_encounter.title);
								update_monster_health(-1);
								play_audio("monster_wounded");
								break;
							case '6':
								$('.dice_total').css('background-color', '#D1001C');
								$('.dice_total').css('color', 'white');
								write_to_console("["+total+"] You wounded "+current_encounter.title);
								$('.encounter_text').html("["+total+"] You wounded "+current_encounter.title);
								update_monster_health(-2);
								play_audio("monster_wounded");
								break;
						}
						
						if (hero_dead()) {
							current_encounter.resolved = '1';
							current_encounter.success = '0';
							write_to_console("You were killed by the "+current_encounter.title+"!");
							$('.encounter_text').css("background-color", "#D1001C");
							$('.encounter_text').css("color", "white");
							$('.encounter_text').html("You were killed by the "+current_encounter.title+"!");
							game_over = true;
							$('#encounter_fight_button').attr("disabled", true);
							$('.encounter_flee_button').attr("disabled", true);
							//update_timer();
							return;
						}
						
						if (parseInt(monster.health) <= 0) {
							current_encounter.resolved = '1';
							current_encounter.success = '1';
							write_to_console("You killed the "+current_encounter.title+"!");
							$('.encounter_text').css("background-color", "#01E501");
							$('.encounter_text').css("color", "white");
							$('.encounter_text').html("You killed the "+current_encounter.title+"!");
							$('#encounter_fight_button').attr("disabled", true);
							$('.encounter_flee_button').attr("disabled", true);
							//update_timer();
							return;
						}
					
					} else if (p_button == 'flee') {
						
						if (total <= hero1.agility) {
							
							current_encounter.resolved = '1';
							current_encounter.success = '1';
							current_encounter.can_flee = '0';
							
							$('.encounter_flee_button').attr("disabled", true);
							
							$('.dice_total').css('background-color', '#01E501');
							$('.dice_total').css('color', 'white');
							write_to_console("["+total+"] You escaped");
							
							update_hero_health(-monster.escape_penalty);
							write_to_console("You escaped!");
							write_to_console("You received "+monster.escape_penalty+" wounds!");
							$('.encounter_text').html("You escaped! <br/> You received "+monster.escape_penalty+" wounds!");
							play_audio("hero_wounded");
						
							if (parseInt(hero1.health) <= 0) {
								write_to_console("You were killed by the "+current_encounter.title+"!");
								$('.encounter_text').css("background-color", "#D1001C");
								$('.encounter_text').css("color", "white");
								$('.encounter_text').html("You were killed by the "+current_encounter.title+"!");
								game_over = true;
								$('#encounter_fight_button').attr("disabled", true);
								$('.encounter_flee_button').attr("disabled", true);
								return;
							} else {
								flee();
							}
							
							monster_dead = true;
							
						} else {
							current_encounter.resolved = '0';
							current_encounter.success = '0';
							
							$('.dice_total').css('background-color', '#D1001C');
							$('.dice_total').css('color', 'white');
							write_to_console("["+total+"] You failed to escape");
							write_to_console("["+total+"] You received 1 wound");
							
							update_hero_health(-1);
							play_audio("hero_wounded");
						
							if (hero_dead()) {
								write_to_console("You were killed by the "+current_encounter.title+"!");
								$('.battle_attack_text').css("background-color", "#D1001C");
								$('.battle_attack_text').css("color", "white");
								$('.battle_attack_text').html("You were killed by the "+current_encounter.title+"!");
								game_over = true;
								$('#encounter_fight_button').attr("disabled", true);
								$('.encounter_flee_button').attr("disabled", true);
								return;
							}
							
							update_timer();
							

						}
					}
				
				
				
				
					break;
					
					
					
					
				case "alreadysearched":
				case "secretdoor":
				case "emptyroom":
				case "finditem":
				case "passagedown":
				case "cryptalreadysearched":
				case "findcryptitem":
				case "corpsealreadysearched":
				case "findcorpseitem":
				case "finditem":
				case "lingeringshade":
				case "catacombs":
				
					current_encounter.resolved = '1';
					current_encounter.success = '0';
					break;
					
				case "death":
				case "gameover":
				
					current_encounter.resolved = '0';
					current_encounter.success = '0';
					break;
			}
			
			if (current_encounter.num_dice_roll == 1) {
			} else {
				current_encounter.num_dice_roll--;
			}
			
			if (current_encounter.success == '1') {
				$('#encounter_dice_button').attr("disabled", true);
			}
			
		}
		
		function flee() {


			new_hero_movement = '0';
			
			switch (previous_direction) {
				case '1':
					new_hero_movement = '3';
					break;
				case '2':
					new_hero_movement = '4';
					break;
				case '3':
					new_hero_movement = '1';
					break;
				case '4':
					new_hero_movement = '2';
					break;
			}
			
			write_to_console('You flee to the previous Chamber!');
			force_hero_movement(new_hero_movement);
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
			if (p_loot.name == "Potion") {
				$('.loot_console').append( '<span class="loot_item" onclick="drink_potion('+p_item_index+')">'+p_loot.timer+ ': ' + p_loot.name + ' - ' +p_loot.value+' GP<br/></span>');
			} else {
				$('.loot_console').append( '<span class="loot_item" onclick="drop_loot('+p_item_index+')">'+p_loot.timer+ ': ' + p_loot.name + ' - ' +p_loot.value+' GP<br/></span>');
			}
		}
		
		function escape_closed() {
			var current_chamber = hero_map[hero1.x*100 + hero1.y];
			
			write_to_debugger("Current Chamber: " + current_chamber.type);
			write_to_debugger("Current Direction: " + previous_direction);
			write_to_debugger("Top: " + current_chamber.top + ", Right: " + current_chamber.right + ", Down: " + current_chamber.bottom + ", Left: " + current_chamber.left)
			
			switch (previous_direction) {
				case '1':
					if (current_chamber.bottom == '5' || current_chamber.bottom == '2') return true;
					break;
				case '2':
					if (current_chamber.left == '5' || current_chamber.left == '2') return true;
					break;
				case '3':
					if (current_chamber.top == '5' || current_chamber.top == '2') return true;
					break;
				case '4':
					if (current_chamber.right == '5' || current_chamber.right == '2') return true;
					break;
			}
			return false
		}
		
		function drink_potion(p_item_index){
			
			for(var i = 0; i<hero_loot.length; i++){
				if (i == p_item_index){
					if (confirm("Do you want to drink potion? "+hero_loot[i].name)) {
						//alert(p_item_index+" "+hero_loot[i].name);
						hero_loot.splice(i, 1);
						add_encounter("potion");
						force_encounter_modal();
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
			return (Math.ceil(hero_loot.length/4));
		}
		
		function have_rope() {
			
			for (var i = 0; i < hero_loot.length; i++) {
				if (hero_loot[i].name == "Rope") {
					return true;
				}
			}
			return false;
		}
		
		function have_potion() {
			
			for (var i = 0; i < hero_loot.length; i++) {
				if (hero_loot[i].name == "Pope") {
					return true;
				}
			}
			return false;
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
				force_encounter_modal();
				//roll_encounter_dice('');
				return true;
			}
			
			if (game_over) {
				encounter_queue = [];
				add_encounter('gameover', '0');
				force_encounter_modal();
				//roll_encounter_dice('');
				return true;
			}
			
			if (encounter_queue.length > 0) {
				write_to_debugger("Event Not Resolved: encounter queue length > 0: " + encounter_queue[0].type);
				//$('#encounter_modal').modal('toggle');
				force_encounter_modal();
				
				if ($('#encounter_modal').hasClass('in')) {
					$('.mini_dice_placeholder').attr("onclick", "").unbind("click");
					$(".mini_dice_placeholder").on('click', function(){ roll_encounter_dice(''); });
					$('.encounter_hero_sheet_placeholder').append($('.mini_hero_sheet'));
					$('#encounter_dice_button').attr("disabled", false);
				} else {
					$('.mini_dice_placeholder').attr("onclick", "").unbind("click");
					$('.mini_hero_sheet_placeholder').append($('.mini_hero_sheet'));
				}
				
				
				return true;
			}
			
			
			return false;
			
		}
		
		function print_encounter() {
			for (var i = 0; i < encounter_queue.length; i++) {
				write_to_debugger(encounter_queue[i].type + ", " + encounter_queue[i].title + ", " + encounter_queue[i].description_color + ", " + encounter_queue[i].description + ", " + encounter_queue[i].direction);
			}
		}
	
		
	</script>
	<style type="text/css">
		body { margin: 5px auto; }
		h5 { text-align: left; margin: 5px; }
		p {padding: 0px; margin: 0px; }
		
		.btn { display: inline-block; margin-top: 2px; margin-bottom: 2px; min-width: 70px;}
		.table-condensed>tbody>tr>td { padding: 3px 3px 0 3px; }
		.table>tbody>tr>td {}
		.table { margin-bottom: 0px; }
		
		.table>tbody>tr>td ,.table>tbody>tr>th { padding: 2px;}
		
		.character_selection_screen { text-align: center; }
		.character_selection { display: inline-block; margin: 5px; cursor: pointer; vertical-align: top; }
		.character_table { width: 170px; }
		.character_sheet_image { width: 60%; padding: 2px; }
		.character_image { width: 100%; }
		.character_sheet_stats { width: 20% }
		.character_sheet_values { width: 20% }
		.character_sheet_description { margin: 10px; font-weight: bold; font-size: 12px; overflow-wrap: break-word; word-wrap: break-word; }
		
		.character_dashboard { display: inline-block; width: 300px; vertical-align: top; }
		.hero_name_header { text-align: left; }
		
		.dice_sheet { position: relative; text-align: center; }
		.mini_dice_sheet { position: relative; text-align: center; }
		
		.dungeon_dashboard { display: inline-block; }
		
		.hero_table { width: 100%; }
		.dungeon_board { display: inline-block; padding: 0px; margin: 0 auto; }
		.dungeon_row { display: block flex; padding: 0px; margin: 0px; vertical-align: middle; }
		.dungeon_cell { display: inline-block; position: relative; padding: 0px; margin: 0px auto; vertical-align: middle; text-align: center; width: 60px; height: 60px; background-size: 100% 100%; }
		
		.mini_hero_table { width: 300px; }
		.mini_dungeon_board { display: inline-block; padding: 5px; margin: 0 auto; }
		.mini_dungeon_row { display: block; padding: 0px; margin: 0px; vertical-align: middle; }
		.mini_dungeon_cell { display: inline-block; position: relative; padding: 0px; margin: 0px auto; vertical-align: middle; text-align: center; width: 75px; height: 75px; background-size: 100% 100%; }
		
		
		.medium_hero_table { width: 800px; }
		.medium_dungeon_board { display: inline-block; padding: 0px; margin: 0 auto; }
		.medium_dungeon_row { display: block; padding: 0px; margin: 0px; vertical-align: middle; }
		.medium_dungeon_cell { display: inline-block; position: relative; padding: 0px; margin: 0px auto; vertical-align: middle; text-align: center; width: 130px; height: 130px; background-size: 100% 100%; }
		.movement_dashboard { width: 300px; margin: 20px;}
		
		.timer { display: inline-block; }
		
		.console_sheets { text-align: left; }
		.console_sheet { overflow-y:scroll; height: 400px; }
		.loot_sheet { overflow-y:scroll; height: 100px; }
		
		.mini_console_sheet { overflow-y:scroll; height: 80px; }
		.mini_loot_sheet { overflow-y:scroll; height: 80px; }
		
		
		
		.console { text-align: left; font-size: 12px; padding: 2px; }
		
		.loot_console { text-align: left; font-size: 12px; padding: 2px; }
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

<div style="max-width: 1000px; margin: auto;">

<!-- Splash Screen -->
<div class="splash_screen" style="display: none;">
	<div style="margin: auto; padding: 20px; cursor: pointer;" onclick="splash_click();">
		<img class="img img-responsive" src="/dungeonquest/images/second_edition/dungeonquest_title_name_800.jpg" />
	</div>
</div>


<!-- Introduction Screen -->
<div class="introduction_screen" style="display: none;">
	<div style="padding: 0px;">
	
		<div style="margin: auto; cursor: pointer; width: 100%; padding: 0px; margin: 0px;" onclick="introduction_click();">
			<img class="img img-responsive" style="margin: auto;" src="/dungeonquest/images/second_edition/dungeonquest_title_name_800.jpg"/>
		</div>
		
		<div style="margin: 10px 0 10px 0; text-align: justify;">
		<p>A thousand years have passed since the evil wizard T'Siraman fell, 
		but men still fear to enter his dark fortress of Dragonfire Castle, 
		which squats grim and brooding atop Wyrm's Crag. In the villages that huddle in its shadow, 
		stories are whispered of the fabulous treasures which fill the castle's dungeons, and of the things which guard them. 
		Old men draw closer to their fires and tell of the noises which echo across the valley at night, 
		when the castle seems to take on a malign life of its own. Few indeed return in daylight; 
		their eyes are troubled, and they are reluctant to tell of their adventures. 
		None has ever returned after nightfall.</p>
		</div>
		
		<div style="margin: 10px 0 10px 0; text-align: justify;">
		<p>The ruddy light of sunrise begins to burn off the autumn mist, 
		and four pairs of eyes look toward the looming keep. Four minds reflect on the villager's tales, 
		and four hands tighten their grip on four weapons: 
		Sir Rohan the Knight, 	with his shining armour and greatsword; 
		Ulf Grimhand, 	the Barbarian from the far north, with his huge double-headed axe; 
		El-Adoran the Ranger, 	with his deadly longbow and forester's shortsword; 
		and Volrik the Brave, the swaggering Adventurer. 
		Fools or Heroes? Only time will tell.</p>
		</div>
	
</div>
</div>

<!-- Character Selection Screen -->
<div class="character_selection_screen" id="character_selection_screen" style="display: none;">
	
	<div style="margin: auto; padding: 0px; margin: 0px;" onclick="introduction_click();">
		<img class="img img-responsive" style="margin: auto;" src="/dungeonquest/images/second_edition/dungeonquest_title_name_800.jpg"/>
	</div>
	<div style="padding-top: 0px;">
	<div id="character_selection_placeholder"></div>
	</div>
</div>


<!-- Dungeon Quest Game (Deskto and Mobile) -->
<div class="board_game_screen" id="game" style="width: 100%; display: none;">
	<!-- Desktop View -->
	<div class="div-desktop">

		<!-- Character Dashboard -->
		<div class="character_dashboard" id="character_dashboard" style="margin: 10px;">

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
				<input class="btn btn-primary catacombs_button" type="button" name="catacombs_button" value="Cata." onclick="enter_catacombs();" />
				<input class="btn btn-primary rules_button" type="button" name="rules_button" id="rules_button" value="Rules" onclick="show_rules();" />
				<input class="btn btn-primary about_button" type="button" name="about_button" id="about_button" value="About" onclick="about();" />
				<input class="btn btn-primary quit_button" type="button" name="quit_button" id="quit_button" value="Quit" onclick="quit_game();" />
			</div>
		</div>
	</div>

	<!-- Mobile View -->
	<div class="div-mobile" style="text-align: center;">

		<!-- Mobile Character Dashboard -->
		<div class="character_dashboard" id="character_dashboard" style="display: inline-block;">

			<!-- Mobile Hero Sheet -->
			<div class="mini_hero_sheet_placeholder">
			<div class="mini_hero_sheet" style="display: inline-block; width: 300px; padding: 0px;">
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
				<input class="btn btn-primary corpse_button" type="button" name="corpse_button" value="Corpse" onclick="search_corpse();" />
				<input class="btn btn-primary crypt_button" type="button" name="crypt_button" value="Crypt" onclick="search_crypt();" />
				<input class="btn btn-primary catacombs_button" type="button" name="catacombs_button" value="Cata." onclick="enter_catacombs();" />
				<input class="btn btn-primary treasure_button" type="button" name="treasure_button" value="Loot" onclick="search_dragon_chamber();" />
				<input class="btn btn-primary rules_button" type="button" name="rules_button" id="rules_button" value="Rules" onclick="show_rules();" />
				<input class="btn btn-primary about_button" type="button" name="about_button" id="about_button" value="About" onclick="about();" />
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




<!-- Encounter Modal-->
<div class="modal" id="encounter_modal" tabindex="-1" role="dialog" aria-labelledby="encounter_modal_label" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-body" style="vertical-align: top; margin: 0 auto; text-align: center;">
		
		<!-- Hero Sheet -->
		<div class="encounter_hero_sheet_placeholder"></div>
		
		<!-- Battle Sheet -->
		<div class="encounter_battle_sheet">
			<table class="table-bordered table-condensed" style="margin: 0 auto; width: 300px;">
				<tr>
					<td rowspan="4" style="padding: 0;"><img class="battle_monster_img img img-responsive" style="margin: 0 auto;" src="" /></td>
					<td style="width: 45%;"><span class="battle_monster_name"></span></td>
					<td style="width: 20%;">❤️ <span class="battle_monster_health"></span></td>
				</tr>
				<tr>
					<td style="width: 45%;"><span class="hero1_name"></span></td>
					<td style="width: 20%;">❤️ <span class="hero1_health"></span></td>
				</tr>
				<tr>
					<td style="width: 45%;">Escape Penalty</td>
					<td style="width: 20%;">❤️ <span class="battle_monster_escape_penalty"></span></td>
				</tr>
				<tr>
					<td colspan="3">
						<div class="battle_dice_placeholder" style="cursor: pointer; margin: 5px 0 5px 0;" onclick="roll_encounter_dice('fight')"></div>
					</td>
				</tr>
			</table>
		</div>
		
		<!-- Encounter Text -->
		<div class="encounter_text alert" style="margin: 5px auto; width: 300px;"></div>
		
		<!-- Console Sheet -->
		<div class="encounter_console_sheet table-bordered" style="width: 300px; margin: auto; overflow-y:scroll; height: 100px;">
			<div class="console" style="font-size: 12px; text-align: left;"></div>
		</div>
		
      </div>
      <div class="modal-footer">
		<button type="button" id="encounter_dice_button" class="encounter_dice_button btn btn-primary" value="Trap" onclick="roll_encounter_dice('')">Roll Dice</button>
		<button type="button" id="encounter_fight_button" class="encounter_fight_button btn btn-primary" value="Fight" onclick="roll_encounter_dice('fight')">Fight</button>
		<button type="button" id="encounter_shoot_button" class="encounter_shoot_button btn btn-primary" value="Shoot" onclick="roll_encounter_dice('shoot')">Shoot</button>
		<button type="button" id="encounter_flee_button" class="encounter_flee_button btn btn-primary" value="Flee" onclick="roll_encounter_dice('flee')">Flee</button>
		<button type="submit" id="encounter_close_button" class="btn btn-primary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>




<!-- Rules Modal-->
<div class="modal" id="rules_modal" tabindex="-1" role="dialog" aria-labelledby="rules_modal_label" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-body" style="vertical-align: top; margin: 0 auto;">
		
		<div>
			<div style="margin: 10px 0px 10px 0; font-size: 16px; font-weight: bold;">Goal</div>
			<table class="table table-condensed">
				<tr>
					<td style="text-align: justify;">Your task is the enter the dungeons beneath Dragonfire Castle, find your way to the Treasure Chamber, and escape with the treasures before the sun goes down.</td>
				</tr>
			</table>
			
			<div style="margin: 10px 0px 10px 0; text-align: left; font-size: 16px; font-weight: bold;">Movement</div>
			<table class="table table-condensed">
				<tr>
					<td style="width: 30%">Desktop</td>
					<td>Use Arrow Keys</td>
				</tr>
				<tr>
					<td>Mobile</td>
					<td>Swipe Game Board</td>
				</tr>
			</table>
			
			<div style="margin: 10px 0px 10px 0; text-align: left; font-size: 16px; font-weight: bold;">Chamber Description</div>
			<table class="table table-condensed">
				<tr>
					<td style="width: 30%">Normal</td>
					<td>Nothing Special</td>
				</tr>
				<tr>
					<td>Corridor</td>
					<td>Free Movement</td>
				</tr>
				<tr>
					<td>Door</td>
					<td>Normal / Jammed / Trap</td>
				</tr>
				<tr>
					<td>Bridge</td>
					<td>Test AGI to cross</td>
				</tr>
				<tr>
					<td>Portcullis</td>
					<td>Test STR to cross</td>
				</tr>
				<tr>
					<td>SpiderWeb</td>
					<td>Test STR to cross</td>
				</tr>
				<tr>
					<td>Rubbles</td>
					<td>Test AGI to cross</td>
				</tr>
				<tr>
					<td>Pit</td>
					<td>Test LUK to cross</td>
				</tr>
				<tr>
					<td>Rotating</td>
					<td>Rotates 180 Degrees</td>
				</tr>
				<tr>
					<td>Chasm</td>
					<td>[Not Implemented]</td>
				</tr>
				<tr>
					<td>Trap</td>
					<td>Encounter Trap</td>
				</tr>
				<tr>
					<td>Darkness</td>
					<td>Random Movement</td>
				</tr>
				<tr>
					<td>Catacombs</td>
					<td>[Not Implemented]</td>
				</tr>
			</table>
		</div>
      </div>
      <div class="modal-footer">
		<button type="submit" id="rules_close_button" class="btn btn-primary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>


</body>
</html>