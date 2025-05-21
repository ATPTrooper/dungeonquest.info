<html>
<head>
	<link type="text/css" rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/themes/smoothness/jquery-ui.css">
	<link type="text/css" rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
	<script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
	<script type="text/javascript">
		
		
		var hero1;
		var hero2;
		var hero3;
		var hero4;
		
		var hero_map = {};
		
		var CharactersJSON_string = '[{"Name": "Challara and Brightblaze", "Health":"12", "Strength":"5", "Agility":"6", "Defense":"5", "Luck":"7"}' +
								', {"Name": "Brother Gherinn", "Health":"13", "Strength":"5", "Agility":"6", "Defense":"5", "Luck":"7"}' +
								', {"Name": "Hugo the Glorious", "Health":"16", "Strength":"5", "Agility":"6", "Defense":"5", "Luck":"7"}' +
								', {"Name": "Krutzbeck", "Health":"20", "Strength":"5", "Agility":"6", "Defense":"5", "Luck":"7"}' +
								', {"Name": "Lindel", "Health":"15", "Strength":"5", "Agility":"6", "Defense":"5", "Luck":"7"}' +
								', {"Name": "Tatianna", "Health":"14", "Strength":"5", "Agility":"6", "Defense":"5", "Luck":"7"}]';
		
		var CharactersJSON = JSON.parse(CharactersJSON_string);
		
		var ChamberJSON_string = '' + 
								// Chambers
								'{"1": {"top": "1", "right": "1", "type": "1", "bottom": "1", "left": "1", "image_url": "1.jpg"}' +
								',"2": {"top": "1", "right": "2", "bottom": "1", "left": "1", "type": "1", "image_url": "2.jpg"}' +
								',"3": {"top": "1", "right": "1", "bottom": "1", "left": "2", "type": "1", "image_url": "3.jpg"}' +
								',"4": {"top": "2", "right": "1", "bottom": "1", "left": "1", "type": "1", "image_url": "4.jpg"}' +
								',"5": {"top": "1", "right": "2", "bottom": "1", "left": "2", "type": "1", "image_url": "5.jpg"}' +
								',"6": {"top": "2", "right": "2", "bottom": "1", "left": "1", "type": "1", "image_url": "6.jpg"}' +
								',"7": {"top": "2", "right": "1", "bottom": "1", "left": "2", "type": "1", "image_url": "7.jpg"}' +
								
								// Corridors
								',"11": {"top": "1", "right": "1", "bottom": "1", "left": "1", "type": "2", "image_url": "11.jpg"}' +
								',"12": {"top": "1", "right": "2", "bottom": "1", "left": "1", "type": "2", "image_url": "12.jpg"}' +
								',"13": {"top": "1", "right": "1", "bottom": "1", "left": "2", "type": "2", "image_url": "13.jpg"}' +
								',"14": {"top": "2", "right": "1", "bottom": "1", "left": "1", "type": "2", "image_url": "14.jpg"}' +
								',"15": {"top": "1", "right": "2", "bottom": "1", "left": "2", "type": "2", "image_url": "15.jpg"}' +
								',"16": {"top": "2", "right": "2", "bottom": "1", "left": "1", "type": "2", "image_url": "16.jpg"}' +
								',"17": {"top": "2", "right": "1", "bottom": "1", "left": "2", "type": "2", "image_url": "17.jpg"}' +
								
								// Doors
								',"21": {"top": "3", "right": "1", "bottom": "1", "left": "1", "type": "1", "image_url": "21.jpg"}' +
								',"22": {"top": "3", "right": "2", "bottom": "1", "left": "1", "type": "1", "image_url": "22.jpg"}' +
								',"23": {"top": "3", "right": "1", "bottom": "1", "left": "2", "type": "1", "image_url": "23.jpg"}' +
								//',"24": {"top": "2", "right": "1", "bottom": "1", "left": "3", "type": "1", "image_url": "24.jpg"}' +
								//',"25": {"top": "3", "right": "2", "bottom": "1", "left": "2", "type": "1", "image_url": "25.jpg"}' +
								//',"26": {"top": "2", "right": "2", "bottom": "1", "left": "3", "type": "1", "image_url": "26.jpg"}' +
								//',"27": {"top": "2", "right": "3", "bottom": "1", "left": "2", "type": "1", "image_url": "27.jpg"}' +
								
								// Doors
								',"31": {"top": "1", "right": "1", "bottom": "1", "left": "3", "type": "1", "image_url": "31.jpg"}' +
								//',"32": {"top": "1", "right": "2", "bottom": "1", "left": "3", "type": "1", "image_url": "32.jpg"}' +
								//',"33": {"top": "1", "right": "3", "bottom": "1", "left": "2", "type": "1", "image_url": "33.jpg"}' +
								//',"34": {"top": "2", "right": "3", "bottom": "1", "left": "1", "type": "1", "image_url": "34.jpg"}' +
								',"45": {"top": "1", "right": "3", "bottom": "1", "left": "1", "type": "1", "image_url": "35.jpg"}' +
								
								// Doors
								//',"41": {"top": "3", "right": "1", "bottom": "1", "left": "3", "type": "1", "image_url": "41.jpg"}' +
								//',"42": {"top": "3", "right": "2", "bottom": "1", "left": "3", "type": "1", "image_url": "42.jpg"}' +
								//',"43": {"top": "3", "right": "3", "bottom": "1", "left": "2", "type": "1", "image_url": "43.jpg"}' +
								//',"44": {"top": "3", "right": "3", "bottom": "1", "left": "1", "type": "1", "image_url": "44.jpg"}' +
								',"45": {"top": "1", "right": "3", "bottom": "1", "left": "3", "type": "1", "image_url": "45.jpg"}' +
								
								// Challenges/Abnormals
								',"51": {"top": "4", "right": "2", "bottom": "4", "left": "2", "type": "3", "image_url": "51.jpg"}' +
								',"52": {"top": "5", "right": "2", "bottom": "1", "left": "2", "type": "4", "image_url": "52.jpg"}' +
								',"53": {"top": "2", "right": "2", "bottom": "1", "left": "2", "type": "5", "image_url": "53.jpg"}' +
								',"54": {"top": "1", "right": "1", "bottom": "1", "left": "1", "type": "6", "image_url": "54.jpg"}' +
								',"55": {"top": "1", "right": "1", "bottom": "1", "left": "1", "type": "7", "image_url": "55.jpg"}' +
								
								// Traps/Darkness/SpiderWebs/CaveIns
								',"61": {"top": "1", "right": "1", "bottom": "1", "left": "1", "type": "9", "image_url": "61.jpg"}' +
								',"62": {"top": "1", "right": "2", "bottom": "1", "left": "1", "type": "9", "image_url": "62.jpg"}' +
								',"63": {"top": "1", "right": "1", "bottom": "1", "left": "2", "type": "9", "image_url": "63.jpg"}' +
								',"64": {"top": "2", "right": "1", "bottom": "1", "left": "1", "type": "10", "image_url": "64.jpg"}' +
								',"65": {"top": "2", "right": "2", "bottom": "1", "left": "1", "type": "10", "image_url": "64.jpg"}' +
								',"66": {"top": "2", "right": "1", "bottom": "1", "left": "2", "type": "10", "image_url": "66.jpg"}' +
								',"67": {"top": "1", "right": "1", "bottom": "1", "left": "1", "type": "11", "image_url": "67.jpg"}' +
								',"68": {"top": "1", "right": "2", "bottom": "1", "left": "2", "type": "12", "image_url": "68.jpg"}' +
								',"69": {"top": "1", "right": "1", "bottom": "1", "left": "1", "type": "12", "image_url": "69.jpg"}' +
								
								// Catacombs
								//',"71": {"top": "1", "right": "2", "bottom": "1", "left": "1", "type": "8", "image_url": "71.jpg"}' +
								//',"72": {"top": "2", "right": "2", "bottom": "1", "left": "2", "type": "8", "image_url": "72.jpg"}' +
								//',"73": {"top": "1", "right": "1", "bottom": "1", "left": "2", "type": "8", "image_url": "73.jpg"}' +
								//',"74": {"top": "2", "right": "2", "bottom": "1", "left": "1", "type": "8", "image_url": "74.jpg"}' +
								//',"75": {"top": "2", "right": "1", "bottom": "1", "left": "2", "type": "8", "image_url": "75.jpg"}' +
								//',"75": {"top": "1", "right": "1", "bottom": "1", "left": "1", "type": "8", "image_url": "76.jpg"}' +
								
								// Portcullis
								',"81": {"top": "2", "right": "2", "bottom": "5", "left": "2", "type": "1", "image_url": "81.jpg"}' +
								',"82": {"top": "2", "right": "1", "bottom": "5", "left": "2", "type": "1", "image_url": "82.jpg"}' +
								',"83": {"top": "2", "right": "2", "bottom": "5", "left": "1", "type": "1", "image_url": "83.jpg"}' +
								',"84": {"top": "3", "right": "1", "bottom": "5", "left": "1", "type": "1", "image_url": "84.jpg"}' +
								',"85": {"top": "3", "right": "2", "bottom": "5", "left": "2", "type": "1", "image_url": "85.jpg"}' +
								',"86": {"top": "2", "right": "1", "bottom": "5", "left": "1", "type": "1", "image_url": "86.jpg"}' +
								',"87": {"top": "1", "right": "2", "bottom": "5", "left": "1", "type": "1", "image_url": "87.jpg"}' +
								',"88": {"top": "1", "right": "1", "bottom": "5", "left": "2", "type": "1", "image_url": "88.jpg"}' +
								',"89": {"top": "1", "right": "1", "bottom": "5", "left": "1", "type": "1", "image_url": "89.jpg"}}';
		
		
		ChamberJSON_string_2 = '{' +
								'"0": {"top": "2", "right": "1", "bottom": "1", "left": "2", "type": "0", "image_url": "/dungeonquest/corner.jpg"}' +
								',"1": {"top": "1", "right": "1", "bottom": "1", "left": "1", "type": "1", "image_url": "/dungeonquest/1.jpg"}' +
								',"2": {"top": "1", "right": "2", "bottom": "1", "left": "1", "type": "1", "image_url": "/dungeonquest/2.jpg"}' +
								',"3": {"top": "1", "right": "1", "bottom": "1", "left": "2", "type": "1", "image_url": "/dungeonquest/3.jpg"}' +
								',"4": {"top": "2", "right": "1", "bottom": "1", "left": "1", "type": "1", "image_url": "/dungeonquest/4.jpg"}' +
								',"5": {"top": "1", "right": "2", "bottom": "1", "left": "2", "type": "1", "image_url": "/dungeonquest/5.jpg"}' +
								',"6": {"top": "2", "right": "2", "bottom": "1", "left": "1", "type": "1", "image_url": "/dungeonquest/6.jpg"}' +
								',"7": {"top": "2", "right": "1", "bottom": "1", "left": "2", "type": "1", "image_url": "/dungeonquest/7.jpg"}}';
								
		var ChamberJSON = JSON.parse(ChamberJSON_string);
		
		
		//chamber legend
		//1 open
		//2 wall
		//3 door
		//4 bridge
		//5 portcullis
		//6 open
		//7 open
		
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
		
		//alert("ChamberJSON length: " + ChamberJSON.length);
		
		var timer = 31;
		var game_over = false;
		
		$(document).ready(function() {
			
			$('#character_selection_screen').show();
			$('#dungeon_board').hide();
			$('#character_dashboard').hide();
			$('#movement_dashboard').hide();
			
			draw_game_board();
			load_characters();
			start_game();
			
		});
		
		function draw_game_board() {
			for (var i = 10; i > 0; i--){
				jQuery('<div/>', {
					id: 'dungeon_board_row_'+(i),
					'class': 'dungeon_row'
				}).appendTo('#dungeon_board');
				
				for (var j = 0; j < 13; j++) {
					jQuery('<div/>', {
						id: 'dungeon_board_cell_'+(j+1)+'_'+(i),
						'class': 'dungeon_cell',
					}).appendTo('#dungeon_board_row_'+(i));
				}
			}
		}
		
		function load_characters(){
			for (var i = 0; i < CharactersJSON.length; i++){
				var $character_selection = $('<div class="character_selection" onclick="load_character(\''+CharactersJSON[i].Name+'\')" />');
				var $character_name = $('<div><h4>'+CharactersJSON[i].Name+'</h4></div>');
				var $character_table = $('<table class="character_table table table-condensed table-bordered" />');
				var $character_tbody = $('<tbody/>');
				$character_tbody.append('<tr><td colspan="3"><b>'+CharactersJSON[i].Name+'</b></td></tr>');
				$character_tbody.append('<tr><td rowspan="5" style="width: 70%;"><img class="img img-responsive" src="" /></td><td style="width: 15%;">HP</td><td style="width: 15%;">'+CharactersJSON[i].Health+'</td></tr>');
				$character_tbody.append('<tr><td>Str</td><td>'+CharactersJSON[i].Strength+'</td></tr>');
				$character_tbody.append('<tr><td>Agi</td><td>'+CharactersJSON[i].Agility+'</td></tr>');
				$character_tbody.append('<tr><td>Def</td><td>'+CharactersJSON[i].Defense+'</td></tr>');
				$character_tbody.append('<tr><td>Luk</td><td>'+CharactersJSON[i].Luck+'</td></tr>');
				$character_table.append($character_tbody);
				//$character_selection.append($character_name);
				$character_selection.append($character_table);
				$('#character_selection_placeholder').append($character_selection);
			}
		}
		
		function start_game() {
			hero1 = new Hero(1,1, 'blue');
			//hero2 = new Hero(13,1, 'red');
			//hero3 = new Hero(1,10, 'yellow');
			//hero4 = new Hero(13,10, 'green');
			
			hero_map = {};
			
			reset_board();
			set_starting_tiles();
			
			remove_heros();
			draw_hero();
			update_timer();
		}
		
		function reset_board(){
			
			for (var i = 0; i < 10; i++){				
				for (var j = 0; j < 13; j++) {
					$('#dungeon_board_cell_'+(j+1)+'_'+(i+1)).css('background-image','');
					$('#dungeon_board_cell_'+(j+1)+'_'+(i+1)).empty();
				}
			}
			
			hero_map[705] = new Chamber(0, 1, 1, 1, 1, 1, 'url("/dungeonquest/treasure_room_top.jpg")');
			hero_map[706] = new Chamber(0, 1, 1, 1, 1, 1, 'url("/dungeonquest/treasure_room_top.jpg")');
			
			$('#dungeon_board_cell_7_5').css('background-image','url("/dungeonquest/treasure_room_bottom.jpg")');
			$('#dungeon_board_cell_7_6').css('background-image','url("/dungeonquest/treasure_room_top.jpg")');

		}
		
		function set_starting_tiles(){
			
			hero_map[101] = new Chamber(0,'1','1','2','2','0','3','/dungeonquest/corner.jpg');
			hero_map[1301] = new Chamber(0,'1','2','2','1','0','4','/dungeonquest/corner.jpg');
			hero_map[110] = new Chamber(0,'2','1','1','2','0','1','/dungeonquest/corner.jpg');
			hero_map[1310] = new Chamber(0,'2','2','1','1','0','2','/dungeonquest/corner.jpg');
			
			$('#dungeon_board_cell_'+(1)+'_'+(1)).css("background-image", "url('/dungeonquest/corner.jpg')");
			$('#dungeon_board_cell_'+(1)+'_'+(1)).css("transform", "rotate("+get_rotation_angle(4)+"deg)");
			$('#dungeon_board_cell_'+(13)+'_'+(1)).css("background-image", "url('/dungeonquest/corner.jpg')");
			$('#dungeon_board_cell_'+(13)+'_'+(1)).css("transform", "rotate("+get_rotation_angle(3)+"deg)");
			$('#dungeon_board_cell_'+(1)+'_'+(10)).css("background-image", "url('/dungeonquest/corner.jpg')");
			$('#dungeon_board_cell_'+(1)+'_'+(10)).css("transform", "rotate("+get_rotation_angle(1)+"deg)");
			$('#dungeon_board_cell_'+(13)+'_'+(10)).css("background-image", "url('/dungeonquest/corner.jpg')");
			$('#dungeon_board_cell_'+(13)+'_'+(10)).css("transform", "rotate("+get_rotation_angle(2)+"deg)");
		}
		
		function get_rotation_angle(p_Orientation){
			return (p_Orientation - 1) * 90;
		}
		
		function remove_heros() {
			for (var i = 0; i < 10; i++){				
				for (var j = 0; j < 13; j++) {
					$('#dungeon_board_cell_'+(j+1)+'_'+(i+1)).empty();
				}
			}
			
		}
		
		function draw_hero(){
			
			$('#dungeon_board_cell_'+(hero1.x)+'_'+(hero1.y)).append('<div style="height: 100%; width: 100%; display: flex; background-size: 30%; background-position: center; background-repeat: no-repeat; background-image: url(http://www.ragnarok-online.info/images_icon/ui_HP_1.png)"></div>');
		}
		
		function reset_game() {
			
			timer = 31;
			game_over = false;
			
			start_game();
		}
		
		function quit_game() {
			timer = 31;
			game_over = false;
			start_game();
			
			
			$('#character_selection_screen').show();
			$('#dungeon_board').hide();
			$('#character_dashboard').hide();
			$('#movement_dashboard').hide();
		}
		
		document.onkeydown = function(e) {
			switch(e.which) {
				case 37: // left
				move_hero('4');
				break;

				case 38: // up
				move_hero('1');
				break;

				case 39: // right
				move_hero('2');
				break;

				case 40: // down
				move_hero('3');
				break;

				default: return; // exit this handler for other keys
			}
			e.preventDefault(); // prevent the default action (scroll / move caret)
		};
		
		function move_hero(p_Direction){
			
			if (timer > 0 && !game_over) {
				
				switch(p_Direction) {
					case '4':
						if (valid_space(hero1.x - 1, hero1.y) && valid_movement(p_Direction)) { hero1.x--; } else { return; }
						break;
					case '1':
						if (valid_space(hero1.x, hero1.y + 1) && valid_movement(p_Direction)) { hero1.y++; } else { return; }
						break;
					case '2':
						if (valid_space(hero1.x + 1, hero1.y) && valid_movement(p_Direction)) { hero1.x++; } else { return; }
						break;
					case '3':
						if (valid_space(hero1.x, hero1.y - 1) && valid_movement(p_Direction)) { hero1.y--; } else { return; }
						break;
					default: return;
						
				}
				
				draw_chamber(p_Direction);
				remove_heros();
				draw_hero();
				
				if (hero_map[hero1.x*100 + hero1.y].type != '2') {
					update_timer();
				}
				
			}
		}
		
		function exit_dungeon() {
			if (check_hero_exit()) {
					
				setTimeout(function () {
					alert('Hero left the Dungeon');
				}, 100);
				
			}
		}
		
		function is_exit_chamber(p_Hero) {
			return ((p_Hero.x == 1 && p_Hero.y == 1) ||
				(p_Hero.x == 1 && p_Hero.y == 10) ||
				(p_Hero.x == 13 && p_Hero.y == 1) ||
				(p_Hero.x == 13 && p_Hero.y == 10));
		}
		
		function is_treasure_chamber(p_Hero) {
			return ((p_Hero.x == 7 && p_Hero.y == 5) || (p_Hero.x == 7 && p_Hero.y == 6));
		}
		
		function valid_space(x,y){
			if (x > 0 && x < 14 && y > 0 && y < 11) {
				if (hero1.x == x && hero1.y == y) {
					return false;
				}
				if (false && hero2.x == x && hero2.y == y) {
					return false;
				}
				if (false && hero3.x == x && hero3.y == y) {
					return false;
				}
				if (false && hero4.x == x && hero4.y == y) {
					return false;
				}
				
				return true;
			} else {
				return false;
			}
		}
		
		function open_door() {
			var now = new Date();
			$('#console').prepend(now.getHours() + ":" + now.getMinutes() + " You opened the door<br/>");
			return true;
		}
		
		function lift_portcullis() {
			var now = new Date();
			$('#console').prepend(now.getHours() + ":" + now.getMinutes() + " You lifted the portcullis<br/>");
			return true;
		}
		
		function cross_bridge() {
			var now = new Date();
			$('#console').prepend(now.getHours() + ":" + now.getMinutes() + " You crossed the bridge<br/>");
			return true;
		}
		
		function valid_movement(p_Direction) {
			
			var hero_chamber = hero_map[hero1.x*100 + hero1.y];
			
			var next_hero_chamber;

			if (p_Direction == 1) {
				next_hero_chamber = hero_map[hero1.x*100 + hero1.y+1];
			} else if (p_Direction == 2) {
				next_hero_chamber = hero_map[(hero1.x+1)*100 + hero1.y];
			} else if (p_Direction == 3) {
				next_hero_chamber = hero_map[hero1.x*100 + hero1.y-1];
			} else if (p_Direction == 4) {
				next_hero_chamber = hero_map[(hero1.x-1)*100 + hero1.y];
			}
			
			if (next_hero_chamber == undefined) {

				switch (p_Direction) {
					case '1':
						if (hero_chamber.top == 1) { return true; }
						if (hero_chamber.top == 3) { return open_door(); }
						if (hero_chamber.top == 4) { return cross_bridge(); }
						if (hero_chamber.top == 5) { return lift_portcullis(); }
						break;
					case '2':
						if (hero_chamber.right == 1) { return true; }
						if (hero_chamber.right == 3) { return open_door(); }
						if (hero_chamber.right == 4) { return cross_bridge(); }
						if (hero_chamber.right == 5) { return lift_portcullis(); }
						break;
					case '3':
						if (hero_chamber.bottom == 1) { return true; }
						if (hero_chamber.bottom == 3) { return open_door(); }
						if (hero_chamber.bottom == 4) { return cross_bridge(); }
						if (hero_chamber.bottom == 5) { return lift_portcullis(); }
						break;
					case '4':
						if (hero_chamber.left == 1) { return true; }
						if (hero_chamber.left == 3) { return open_door(); }
						if (hero_chamber.left == 4) { return cross_bridge(); }
						if (hero_chamber.left == 5) { return lift_portcullis(); }
						break;
					default:
						return false;
				}
			} else {
				
				switch (p_Direction) {
					case '1':
						if (next_hero_chamber.bottom == 1 || next_hero_chamber.bottom == 3 || next_hero_chamber.right == 4 || next_hero_chamber.bottom == 5) {
						if (hero_chamber.top == 1) { return true; }
						if (hero_chamber.top == 3) { return open_door(); }
						if (hero_chamber.top == 4) { return cross_bridge(); }
						if (hero_chamber.top == 5) { return lift_portcullis(); }
						}
						break;
					case '2':
						if (next_hero_chamber.left == 1 || next_hero_chamber.left == 3 || next_hero_chamber.right == 4 || next_hero_chamber.left == 5) {
						if (hero_chamber.right == 1) { return true; }
						if (hero_chamber.right == 3) { return open_door(); }
						if (hero_chamber.right == 4) { return cross_bridge(); }
						if (hero_chamber.right == 5) { return lift_portcullis(); }
						}
						break;
					case '3':
						if (next_hero_chamber.top == 1 || next_hero_chamber.top == 3 || next_hero_chamber.right == 4 || next_hero_chamber.top == 5) {
						if (hero_chamber.bottom == 1) { return true; }
						if (hero_chamber.bottom == 3) { return open_door(); }
						if (hero_chamber.bottom == 4) { return cross_bridge(); }
						if (hero_chamber.bottom == 5) { return lift_portcullis(); }
						}
						break;
					case '4':
						if (next_hero_chamber.right == 1 || next_hero_chamber.right == 3 || next_hero_chamber.right == 4 || next_hero_chamber.right == 5) {
						if (hero_chamber.left == 1) { return true; }
						if (hero_chamber.left == 3) { return open_door(); }
						if (hero_chamber.left == 4) { return cross_bridge(); }
						if (hero_chamber.left == 5) { return lift_portcullis(); }
						}
						break;
					default:
						return false;
				}
			}
			
		}
		
		class Hero {
		  constructor(x, y, color) {
			this.x = x;
			this.y = y;
			this.color = color;
			
			this.Health = "";
			this.Name = "";
			this.Strength = "";
			this.Agility = "";
			this.Defense = "";
			this.Luck = "";
		  }
		}
		
		class Chamber {
			constructor(id, top, right, bottom, left, type, orientation, image_url) {
				this.id = id;
				this.top = top;
				this.bottom = bottom;
				this.left = left;
				this.right = right;
				this.type = type
				this.orientation = orientation;
				this.image_url = image_url;
				
				this.cavein = "";
				this.spiderweb = "";
				this.corpse = "";
				this.pit = "";
				this.trap = "";
				this.darkness = "";
				this.monster = "";
				this.catacombs = "";
				
			}
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
						
					$('#new_chamber').html(randomNumber);

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
					
					hero_map[eval(hero1.x*100 + hero1.y)] = new Chamber(randomNumber, new_top, new_right, new_bottom, new_left, randomChamber.type, p_Direction, randomChamber.image_url);
					$('#dungeon_board_cell_'+(hero1.x)+'_'+(hero1.y)).css("background-image", "url('/dungeonquest/"+randomChamber.image_url+"')");
					$('#dungeon_board_cell_'+(hero1.x)+'_'+(hero1.y)).css("transform", "rotate("+eval((p_Direction-1)*90)+"deg)");
				}
				
			}
		}

		
		function update_timer(){
			timer--;
			$('#timer').html(timer);
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
		
		function load_character(p_Character){
			
			for (var i = 0; i < CharactersJSON.length; i++){
				if (p_Character == CharactersJSON[i].Name) {
					$('.character_name').html(CharactersJSON[i].Name);
					$('.character_health').html(CharactersJSON[i].Health);
					$('.character_strength').html(CharactersJSON[i].Strength);
					$('.character_agility').html(CharactersJSON[i].Agility);
					$('.character_defense').html(CharactersJSON[i].Defense);
					$('.character_luck').html(CharactersJSON[i].Luck);
				}
			}
			
			$('#character_selection_screen').hide();
			$('#dungeon_board').show();
			$('#character_dashboard').show();
			$('#movement_dashboard').show();
		}
		
		function crypt(){
		}
		function corpse(){
		}
		function search(){
		}
		
		function door(){
		}
		function treasure(){
		}
		function catacombs(){
		}
		function dragon(){
		}
		
		function fight(){
		}
		
		function check_room() {
		}
		
		function random_encounter() {
		}
		
		function random_trap() {
		}
		
	</script>
	<style type="text/css">
		body { margin: 20px; text-align: center;}
		
		
		.character_selection_screen { margin: 0 auto; max-width: 800px; }
		.character_selection { display: inline-block; width: 200px; margin: 10px; cursor: pointer; }
		.character_selection:hover { background-color: #EEEEEE }
		.character_table { margin: 0px; padding: 0px; }
		.character_image { display: inline-block; vertical-align: top; }
		.character_stats { display: inline-block; }
		
		
		.dungeon_board { display: inline-block; padding: 0px; margin: 0 auto; border-right: 1px solid gray; border-bottom: 1px solid gray; }
		.dungeon_row { display: block flex; padding: 0px; margin: 0px; vertical-align: middle; }
		.dungeon_cell { display: inline-block; padding: 0px; margin: 0px; vertical-align: middle; width: 60px; height: 60px; border-top: 1px solid gray; border-left: 1px solid gray; background-size: 100% 100%; }
		
		.character_dashboard { display: inline-block; vertical-align: top; margin: 20px;}
		.movement_dashboard { display: inline-block; vertical-align: top; margin: 20px;}
		.hero_sheet { display: block; vertical-align: top; width: 200px; margin-right: 20px; }
		.loot_sheet { display: block; vertical-align: top; width: 200px; margin-right: 20px; }
		.timer { display: inline-block; }
		.movement_controls { display: block; }
		
		.monster { font-weight: bold; }
		.character { font-weight: bold; }
		
		.btn { margin-top: 2px; width: 90px;}
		.table-condensed>tbody>tr>td { padding: 0px 5px 0px 5px; }
		
		.console { text-align: left; }
	</style>
</head>
<body>

<div class="container-fluid" style="">


<div class="character_selection_screen" id="character_selection_screen" style="display: none;">
	<h3>DungeonQuest</h3><br/><br/>
	
	
	<div id="character_selection_placeholder"></div><br/><br/>
	
	<a href="/dungeonquest/iteration_1.php">Iteration 1</a>
</div>

<div class="character_dashboard" id="character_dashboard">

	
	<div class="loot_sheet" id="loot_sheet">
		<table class="table table-condensed table-bordered">
			<tr><td colspan="2"><b>Timer</b></td><td class="col-sm-4"><span id="timer"></span></td></tr>
		</table>
	</div>
	
	<div class="hero_sheet" id="hero_sheet">
		<table class="table table-condensed table-bordered">
			<tr><td colspan="3"><span class="character character_name" /></td></tr>
			<tr><td rowspan="5" style="width: 70%"></td><td class="col-sm-8" style="width: 15%">HP</td><td class="col-sm-4" style="width: 15%"><span class="character_health" /></td></tr>
			<tr><td>Str</td><td><span class="character_strength" /></td></tr>
			<tr><td>Agi</td><td><span class="character_agility" /></td></tr>
			<tr><td>Def</td><td><span class="character_defense" /></td></tr>
			<tr><td>Luk</td><td><span class="character_luck" /></td></tr>
		</table>
	</div>
	
	<div class="loot_sheet" id="loot_sheet">
		<table class="table table-condensed table-bordered">
			<tr><th colspan="2">Loot</th></tr>
			<tr><td class="col-sm-8">Gold</td><td class="col-sm-4"><span name="character_gold" id="character_gold">0</span></td></tr>
		</table>
	</div>
</div>
<div class="dungeon_board responsive" id="dungeon_board" style="display: none;"></div>


<div class="movement_dashboard" id="movement_dashboard">

	<div class="movement_controls">
		<div><input class="btn btn-primary" type="button" name="up_button" id="up_button" value="Up" onclick="move_hero('up');" /></div>
		<div><input class="btn btn-primary" type="button" name="down_button" id="down_button" value="Down" onclick="move_hero('down');" /></div>
		<div><input class="btn btn-primary" type="button" name="left_button" id="left_button" value="Left" onclick="move_hero('left');" /></div>
		<div><input class="btn btn-primary" type="button" name="right_button" id="right_button" value="Right" onclick="move_hero('right');" /></div>
	</div>
	<br/>
	<div>
		<div style="display: none;"><input class="btn btn-primary" type="button" name="corpse_button" id="corpse_button" value="Corpse" onclick="corpse();" /></div>
		<div style="display: none;"><input class="btn btn-primary" type="button" name="crypt_button" id="crypt_button" value="Crypt" onclick="crypt();" /></div>
		<div style="display: none;"><input class="btn btn-primary" type="button" name="door_button" id="door_button" value="Door" onclick="door();" /></div>
		<div><input class="btn btn-primary" type="button" name="search_button" id="search_button" value="Search" onclick="search();" /></div>
		<div style="display: none;"><input class="btn btn-primary" type="button" name="trap_button" id="trap_button" value="Trap" onclick="trap();" /></div>
		<div><input class="btn btn-primary" type="button" name="treasure_button" id="treasure_button" value="Treasure" onclick="treasure();" /></div>
		<div><input class="btn btn-primary" type="button" name="catacombs_button" id="catacombs_button" value="Catacombs" onclick="catacombs();" /></div>
		<div style="display: none;"><input class="btn btn-primary" type="button" name="dragon_button" id="dragon_button" value="Dragon" onclick="dragon();" /></div>
		<div><input class="btn btn-primary" type="button" name="leave_dungeon_button" id="leave_dungeon_button" value="Leave" onclick="exit_dungeon();" /></div>
		<div><input class="btn btn-primary" type="button" name="reset_button" id="reset_button" value="Reset" onclick="reset_game();" /></div>
		<div><input class="btn btn-primary" type="button" name="quit_button" id="quit_button" value="Quit" onclick="quit_game();" /></div>
	</div>
	
	<br/>
	<div>
		<div><input class="btn btn-primary" type="button" name="fight_button" id="fight_button" value="Fight" onclick="fight();" data-toggle="modal" data-target="#exampleModal" /></div>
	</div>
	
</div>

<div style="display: block" id="current_chamber"></div>
<div style="display: block" id="new_chamber"></div>
<div style="display: block" id="save_chamber"></div>
<div style="display: block" id="console" class="console"></div>


</div>

<div>
</div>

<!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Encounter</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body" style="vertical-align: top;">
	  
		<div class="hero_sheet" id="hero_sheet">
		<table class="table table-condensed table-bordered">
				<tr><td colspan="2"><span class="monster">Sorcerer</span></td></tr>
			<tr><td class="col-sm-8">Health</td><td class="col-sm-4"><span id="character_health" /></td></tr>
		</table>
		</div>
		
		<div style="display: inline-block; margin: 50px;"><b>VS</b></div>
		
		<div class="hero_sheet" id="hero_sheet">
			<table class="table table-condensed table-bordered">
				<tr><td colspan="3"><span class="character character_name" /></td></tr>
				<tr><td rowspan="5" style="width: 70%"></td><td class="col-sm-8" style="width: 15%">HP</td><td class="col-sm-4" style="width: 15%"><span class="character_health" /></td></tr>
				<tr><td>Str</td><td><span class="character_strength" /></td></tr>
				<tr><td>Agi</td><td><span class="character_agility" /></td></tr>
				<tr><td>Def</td><td><span class="character_defense" /></td></tr>
				<tr><td>Luk</td><td><span class="character_luck" /></td></tr>
			</table>
		</div>
		
		
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