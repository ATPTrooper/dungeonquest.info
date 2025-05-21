<?


$CharactersJSON_string = '[' .
	'{"name": "Sir Rohan", "name_short": "Sir Rohan", "health":"17", "strength":"6", "agility":"4", "defense":"9", "luck":"4", "have_range": "0", "arrows": "0", "image_url": "sir_rohan.jpg", "image_url_2": "sir_rohan_model.jpg", "description": ""}' .
	', {"name": "Ulv Grimhand", "name_short": "Ulv Grimhand", "health":"16", "strength":"7", "agility":"5", "defense":"6", "luck":"5", "have_range": "0", "arrows": "0", "image_url": "ulv_grimhand.jpg", "image_url_2": "ulv_grimhand_model.jpg", "description": ""}' .
	', {"name": "El-Adoran Sureshot", "name_short": "El-Adoran", "health":"111", "strength":"3", "agility":"8", "defense":"5", "luck":"7", "have_range": "1", "arrows": "44", "image_url": "el_adoran_sureshot.jpg", "image_url_2": "el_adoran_sureshot_model.jpg", "description": ""}' .
	', {"name": "Volrik the Brave", "name_short": "Volrik", "health":"15", "strength":"4", "agility":"7", "defense":"4", "luck":"8", "have_range": "0", "arrows": "0", "image_url": "volrik_the_brave.jpg", "image_url_2": "volrik_the_brave_model.jpg", "description": ""}' .
	// ', {"name": "Azoth the Faceless", "name_short": "Azoth", "health":"14", "strength":"1", "agility":"4", "defense":"3", "luck":"6", "image_url": "azoth_the_faceless.jpg", "image_url_2": "azoth_the_faceless_model.jpg", "description": ""}' .
	// ', {"name": "Helena the Swift", "name_short": "Helena", "health":"12", "strength":"4", "agility":"8", "defense":"5", "luck":"6", "image_url": "helena_the_swift.jpg", "image_url_2": "helena_the_swift_model.jpg", "description": ""}' .
	// ', {"name": "Serellia of Zimendell", "name_short": "Serellia", "health":"6", "strength":"2", "agility":"6", "defense":"3", "luck":"9", "image_url": "serellia_of_zimendell.jpg", "image_url_2": "serellia_of_zimendell_model.jpg", "description": ""}' .
	// ', {"name": "Ironhand the Mighty", "name_short": "Ironhand", "health":"19", "strength":"6", "agility":"6", "defense":"6", "luck":"5", "image_url": "ironhand_the_mighty.jpg", "image_url_2": "ironhand_the_mighty_model.jpg", "description": ""}' .
	// ', {"name": "Rildo the Crafty", "name_short": "Rildo", "health":"10", "strength":"3", "agility":"9", "defense":"4", "luck":"7", "dagger":"4", "image_url": "rildo_the_crafty.jpg", "image_url_2": "rildo_the_crafty_model.jpg", "description": ""}' .
	// ', {"name": "Tori-Jima", "name_short": "Tori-Jima", "health":"10", "strength":"4", "agility":"10", "defense":"4", "luck":"5", "shuriken":"4", "image_url": "tori_jima.jpg", "image_url_2": "tori_jima_model.jpg", "description": ""}' .
	// ', {"name": "Farendil", "name_short": "Farendil", "health":"10", "strength":"3", "agility":"8", "defense":"5", "luck":"7", "arrow":"4", "image_url": "farendil.jpg", "image_url_2": "farendil_model.jpg", "description": ""}' .
	// ', {"name": "Siegfried Goldenhair", "name_short": "Siegfried", "health":"16", "strength":"7", "agility":"5", "defense":"6", "luck":"5", "image_url": "siegfried_goldenhair.jpg", "image_url_2": "siegfried_goldenhair_model.jpg", "description": ""}' .
	// ', {"name": "Sir Roland", "name_short": "Sir Roland", "health":"17", "strength":"6", "agility":"4", "defense":"9", "luck":"4", "image_url": "sir_roland.jpg", "image_url_2": "sir_roland_model.jpg", "description": ""}' .
	// ', {"name": "Vikas Swordmaster", "name_short": "Vikas", "health":"15", "strength":"4", "agility":"7", "defense":"4", "luck":"8", "image_url": "vikas_swordmaster.jpg", "image_url_2": "vikas_swordmaster_model.jpg", "description": ""}' .
	// ', {"name": "Thargrim the Dark Lord", "name_short": "Thargrim", "health":"13", "strength":"7", "agility":"4", "defense":"8", "luck":"5", "image_url": "thargrim_the_dark_lord.jpg", "image_url_2": "thargrim_the_dark_lord_model.jpg", "description": ""}' .
	// ', {"name": "Fhyll Madaxe", "name_short": "Fhyll", "health":"16", "strength":"8", "agility":"5", "defense":"6", "luck":"4", "image_url": "fhyll_madaxe.jpg", "image_url_2": "fhyll_madaxe_model.jpg", "description": ""}' .
	']';
$CharactersJSON = json_decode($CharactersJSON_string);

$ChamberJSON_string = '[' . 
	// All Tiles for 2nd Edition + Catacombs Chambers
	  '{ "id": "1", "paperid": "1", "top": "1", "right": "1", "bottom": "1", "left": "1", "name": "chamber", "type": "chamber", "image_url": "1.jpg", "description": "Empty Chamber"}' .
	', { "id": "2", "paperid": "1", "top": "1", "right": "1", "bottom": "1", "left": "1", "name": "chamber", "type": "chamber", "image_url": "1a.jpg", "description": "Empty Chamber"}' .
	', { "id": "3", "paperid": "1", "top": "1", "right": "1", "bottom": "1", "left": "1", "name": "chamber", "type": "chamber", "image_url": "1b.jpg", "description": "Empty Chamber"}' .
	', { "id": "4", "paperid": "1", "top": "1", "right": "1", "bottom": "1", "left": "1", "name": "chamber", "type": "chamber", "image_url": "1c.jpg", "description": "Empty Chamber"}' .
	', { "id": "5", "paperid": "1", "top": "1", "right": "1", "bottom": "1", "left": "1", "name": "chamber", "type": "chamber", "image_url": "1d.jpg", "description": "Empty Chamber"}' .
	', { "id": "6", "paperid": "1", "top": "1", "right": "1", "bottom": "1", "left": "1", "name": "chamber", "type": "chamber", "image_url": "1e.jpg", "description": "Empty Chamber"}' .
	', { "id": "7", "paperid": "1", "top": "1", "right": "1", "bottom": "1", "left": "1", "name": "chamber", "type": "chamber", "image_url": "1f.jpg", "description": "Empty Chamber"}' .
	', { "id": "8", "paperid": "1", "top": "1", "right": "1", "bottom": "1", "left": "1", "name": "chamber", "type": "chamber", "image_url": "1g.jpg", "description": "Empty Chamber"}' .
	', { "id": "9", "paperid": "1", "top": "1", "right": "1", "bottom": "1", "left": "1", "name": "chamber", "type": "chamber", "image_url": "1h.jpg", "description": "Empty Chamber"}' .
	', { "id": "10", "paperid": "1", "top": "1", "right": "1", "bottom": "1", "left": "1", "name": "chamber", "type": "chamber", "image_url": "1i.jpg", "description": "Empty Chamber"}' .
	', { "id": "11", "paperid": "1", "top": "1", "right": "1", "bottom": "1", "left": "1", "name": "chamber", "type": "chamber", "image_url": "1j.jpg", "description": "Empty Chamber"}' .
	', { "id": "12", "paperid": "1", "top": "1", "right": "1", "bottom": "1", "left": "1", "name": "chamber", "type": "chamber", "image_url": "1k.jpg", "description": "Empty Chamber"}' .
	', { "id": "13", "paperid": "1", "top": "1", "right": "1", "bottom": "1", "left": "1", "name": "chamber", "type": "chamber", "image_url": "1l.jpg", "description": "Empty Chamber"}' .
	', { "id": "14", "paperid": "1", "top": "1", "right": "1", "bottom": "1", "left": "1", "name": "chamber", "type": "chamber", "image_url": "1m.jpg", "description": "Empty Chamber"}' .
	', { "id": "15", "paperid": "1", "top": "1", "right": "1", "bottom": "1", "left": "1", "name": "chamber", "type": "chamber", "image_url": "1n.jpg", "description": "Empty Chamber"}' .
	', { "id": "16", "paperid": "2", "top": "1", "right": "2", "bottom": "1", "left": "1", "name": "chamber", "type": "chamber", "image_url": "2.jpg", "description": "Empty Chamber"}' .
	', { "id": "17", "paperid": "2", "top": "1", "right": "2", "bottom": "1", "left": "1", "name": "chamber", "type": "chamber", "image_url": "2a.jpg", "description": "Empty Chamber"}' .
	', { "id": "18", "paperid": "2", "top": "1", "right": "2", "bottom": "1", "left": "1", "name": "chamber", "type": "chamber", "image_url": "2b.jpg", "description": "Empty Chamber"}' .
	', { "id": "19", "paperid": "2", "top": "1", "right": "2", "bottom": "1", "left": "1", "name": "chamber", "type": "chamber", "image_url": "2c.jpg", "description": "Empty Chamber"}' .
	', { "id": "20", "paperid": "2", "top": "1", "right": "2", "bottom": "1", "left": "1", "name": "chamber", "type": "chamber", "image_url": "2d.jpg", "description": "Empty Chamber"}' .
	', { "id": "21", "paperid": "2", "top": "1", "right": "2", "bottom": "1", "left": "1", "name": "chamber", "type": "chamber", "image_url": "2e.jpg", "description": "Empty Chamber"}' .
	', { "id": "22", "paperid": "2", "top": "1", "right": "2", "bottom": "1", "left": "1", "name": "chamber", "type": "chamber", "image_url": "2f.jpg", "description": "Empty Chamber"}' .
	', { "id": "23", "paperid": "2", "top": "1", "right": "2", "bottom": "1", "left": "1", "name": "chamber", "type": "chamber", "image_url": "2g.jpg", "description": "Empty Chamber"}' .
	', { "id": "135", "paperid": "3", "top": "1", "right": "1", "bottom": "1", "left": "2", "name": "chamber", "type": "chamber", "image_url": "3.jpg", "description": "Empty Chamber"}' .
	', { "id": "24", "paperid": "3", "top": "1", "right": "1", "bottom": "1", "left": "2", "name": "chamber", "type": "chamber", "image_url": "3a.jpg", "description": "Empty Chamber"}' .
	', { "id": "25", "paperid": "3", "top": "1", "right": "1", "bottom": "1", "left": "2", "name": "chamber", "type": "chamber", "image_url": "3b.jpg", "description": "Empty Chamber"}' .
	', { "id": "26", "paperid": "3", "top": "1", "right": "1", "bottom": "1", "left": "2", "name": "chamber", "type": "chamber", "image_url": "3c.jpg", "description": "Empty Chamber"}' .
	', { "id": "27", "paperid": "3", "top": "1", "right": "1", "bottom": "1", "left": "2", "name": "chamber", "type": "chamber", "image_url": "3d.jpg", "description": "Empty Chamber"}' .
	', { "id": "28", "paperid": "3", "top": "1", "right": "1", "bottom": "1", "left": "2", "name": "chamber", "type": "chamber", "image_url": "3e.jpg", "description": "Empty Chamber"}' .
	', { "id": "29", "paperid": "3", "top": "1", "right": "1", "bottom": "1", "left": "2", "name": "chamber", "type": "chamber", "image_url": "3f.jpg", "description": "Empty Chamber"}' .
	', { "id": "30", "paperid": "3", "top": "1", "right": "1", "bottom": "1", "left": "2", "name": "chamber", "type": "chamber", "image_url": "3g.jpg", "description": "Empty Chamber"}' .
	', { "id": "31", "paperid": "4", "top": "2", "right": "1", "bottom": "1", "left": "1", "name": "chamber", "type": "chamber", "image_url": "4.jpg", "description": "Empty Chamber"}' .
	', { "id": "32", "paperid": "4", "top": "2", "right": "1", "bottom": "1", "left": "1", "name": "chamber", "type": "chamber", "image_url": "4a.jpg", "description": "Empty Chamber"}' .
	', { "id": "33", "paperid": "4", "top": "2", "right": "1", "bottom": "1", "left": "1", "name": "chamber", "type": "chamber", "image_url": "4b.jpg", "description": "Empty Chamber"}' .
	', { "id": "34", "paperid": "4", "top": "2", "right": "1", "bottom": "1", "left": "1", "name": "chamber", "type": "chamber", "image_url": "4c.jpg", "description": "Empty Chamber"}' .
	', { "id": "35", "paperid": "4", "top": "2", "right": "1", "bottom": "1", "left": "1", "name": "chamber", "type": "chamber", "image_url": "4d.jpg", "description": "Empty Chamber"}' .
	', { "id": "36", "paperid": "4", "top": "2", "right": "1", "bottom": "1", "left": "1", "name": "chamber", "type": "chamber", "image_url": "4e.jpg", "description": "Empty Chamber"}' .
	', { "id": "37", "paperid": "4", "top": "2", "right": "1", "bottom": "1", "left": "1", "name": "chamber", "type": "chamber", "image_url": "4f.jpg", "description": "Empty Chamber"}' .
	', { "id": "38", "paperid": "4", "top": "2", "right": "1", "bottom": "1", "left": "1", "name": "chamber", "type": "chamber", "image_url": "4g.jpg", "description": "Empty Chamber"}' .
	', { "id": "39", "paperid": "5", "top": "1", "right": "2", "bottom": "1", "left": "2", "name": "chamber", "type": "chamber", "image_url": "5.jpg", "description": "Empty Chamber"}' .
	', { "id": "40", "paperid": "5", "top": "1", "right": "2", "bottom": "1", "left": "2", "name": "chamber", "type": "chamber", "image_url": "5a.jpg", "description": "Empty Chamber"}' .
	', { "id": "41", "paperid": "5", "top": "1", "right": "2", "bottom": "1", "left": "2", "name": "chamber", "type": "chamber", "image_url": "5b.jpg", "description": "Empty Chamber"}' .
	', { "id": "42", "paperid": "5", "top": "1", "right": "2", "bottom": "1", "left": "2", "name": "chamber", "type": "chamber", "image_url": "5c.jpg", "description": "Empty Chamber"}' .
	', { "id": "43", "paperid": "5", "top": "1", "right": "2", "bottom": "1", "left": "2", "name": "chamber", "type": "chamber", "image_url": "5d.jpg", "description": "Empty Chamber"}' .
	', { "id": "44", "paperid": "5", "top": "1", "right": "2", "bottom": "1", "left": "2", "name": "chamber", "type": "chamber", "image_url": "5e.jpg", "description": "Empty Chamber"}' .
	', { "id": "45", "paperid": "5", "top": "1", "right": "2", "bottom": "1", "left": "2", "name": "chamber", "type": "chamber", "image_url": "5f.jpg", "description": "Empty Chamber"}' .
	', { "id": "46", "paperid": "5", "top": "1", "right": "2", "bottom": "1", "left": "2", "name": "chamber", "type": "chamber", "image_url": "5g.jpg", "description": "Empty Chamber"}' .
	', { "id": "47", "paperid": "6", "top": "2", "right": "2", "bottom": "1", "left": "1", "name": "chamber", "type": "chamber", "image_url": "6.jpg", "description": "Empty Chamber"}' .
	', { "id": "48", "paperid": "6", "top": "2", "right": "2", "bottom": "1", "left": "1", "name": "chamber", "type": "chamber", "image_url": "6a.jpg", "description": "Empty Chamber"}' .
	', { "id": "49", "paperid": "6", "top": "2", "right": "2", "bottom": "1", "left": "1", "name": "chamber", "type": "chamber", "image_url": "6b.jpg", "description": "Empty Chamber"}' .
	', { "id": "50", "paperid": "6", "top": "2", "right": "2", "bottom": "1", "left": "1", "name": "chamber", "type": "chamber", "image_url": "6c.jpg", "description": "Empty Chamber"}' .
	', { "id": "51", "paperid": "6", "top": "2", "right": "2", "bottom": "1", "left": "1", "name": "chamber", "type": "chamber", "image_url": "6d.jpg", "description": "Empty Chamber"}' .
	', { "id": "52", "paperid": "6", "top": "2", "right": "2", "bottom": "1", "left": "1", "name": "chamber", "type": "chamber", "image_url": "6e.jpg", "description": "Empty Chamber"}' .
	', { "id": "53", "paperid": "6", "top": "2", "right": "2", "bottom": "1", "left": "1", "name": "chamber", "type": "chamber", "image_url": "6f.jpg", "description": "Empty Chamber"}' .
	', { "id": "54", "paperid": "7", "top": "2", "right": "1", "bottom": "1", "left": "2", "name": "chamber", "type": "chamber", "image_url": "7.jpg", "description": "Empty Chamber"}' .
	', { "id": "55", "paperid": "7", "top": "2", "right": "1", "bottom": "1", "left": "2", "name": "chamber", "type": "chamber", "image_url": "7a.jpg", "description": "Empty Chamber"}' .
	', { "id": "56", "paperid": "7", "top": "2", "right": "1", "bottom": "1", "left": "2", "name": "chamber", "type": "chamber", "image_url": "7b.jpg", "description": "Empty Chamber"}' .
	', { "id": "57", "paperid": "7", "top": "2", "right": "1", "bottom": "1", "left": "2", "name": "chamber", "type": "chamber", "image_url": "7c.jpg", "description": "Empty Chamber"}' .
	', { "id": "58", "paperid": "7", "top": "2", "right": "1", "bottom": "1", "left": "2", "name": "chamber", "type": "chamber", "image_url": "7d.jpg", "description": "Empty Chamber"}' .
	', { "id": "59", "paperid": "7", "top": "2", "right": "2", "bottom": "1", "left": "2", "name": "chamber", "type": "chamber", "image_url": "8.jpg", "description": "Empty Chamber"}' .
	', { "id": "60", "paperid": "7", "top": "2", "right": "2", "bottom": "1", "left": "2", "name": "chamber", "type": "chamber", "image_url": "8a.jpg", "description": "Empty Chamber"}' .
	', { "id": "61", "paperid": "7", "top": "2", "right": "2", "bottom": "1", "left": "2", "name": "chamber", "type": "chamber", "image_url": "8b.jpg", "description": "Empty Chamber"}' .
	', { "id": "62", "paperid": "7", "top": "2", "right": "2", "bottom": "1", "left": "2", "name": "chamber", "type": "chamber", "image_url": "8c.jpg", "description": "Empty Chamber"}' .
	', { "id": "63", "paperid": "7", "top": "2", "right": "2", "bottom": "1", "left": "2", "name": "chamber", "type": "chamber", "image_url": "8d.jpg", "description": "Empty Chamber"}' .

	// Corridors
	', { "id": "64", "paperid": "11", "top": "1", "right": "1", "bottom": "1", "left": "1", "name": "corridor", "type": "corridor", "image_url": "11.jpg", "description": "Narrow Corridor"}' .
	', { "id": "65", "paperid": "11", "top": "1", "right": "1", "bottom": "1", "left": "1", "name": "corridor", "type": "corridor", "image_url": "11a.jpg", "description": "Narrow Corridor"}' .
	', { "id": "66", "paperid": "12", "top": "1", "right": "2", "bottom": "1", "left": "1", "name": "corridor", "type": "corridor", "image_url": "12.jpg", "description": "Narrow Corridor"}' .
	', { "id": "67", "paperid": "12", "top": "1", "right": "2", "bottom": "1", "left": "1", "name": "corridor", "type": "corridor", "image_url": "12a.jpg", "description": "Narrow Corridor"}' .
	', { "id": "68", "paperid": "12", "top": "1", "right": "2", "bottom": "1", "left": "1", "name": "corridor", "type": "corridor", "image_url": "12b.jpg", "description": "Narrow Corridor"}' .
	', { "id": "69", "paperid": "13", "top": "1", "right": "1", "bottom": "1", "left": "2", "name": "corridor", "type": "corridor", "image_url": "13.jpg", "description": "Narrow Corridor"}' .
	', { "id": "70", "paperid": "13", "top": "1", "right": "1", "bottom": "1", "left": "2", "name": "corridor", "type": "corridor", "image_url": "13a.jpg", "description": "Narrow Corridor"}' .
	', { "id": "71", "paperid": "13", "top": "1", "right": "1", "bottom": "1", "left": "2", "name": "corridor", "type": "corridor", "image_url": "13b.jpg", "description": "Narrow Corridor"}' .
	', { "id": "72", "paperid": "14", "top": "2", "right": "1", "bottom": "1", "left": "1", "name": "corridor", "type": "corridor", "image_url": "14.jpg", "description": "Narrow Corridor"}' .
	', { "id": "73", "paperid": "14", "top": "2", "right": "1", "bottom": "1", "left": "1", "name": "corridor", "type": "corridor", "image_url": "14a.jpg", "description": "Narrow Corridor"}' .
	', { "id": "74", "paperid": "15", "top": "1", "right": "2", "bottom": "1", "left": "2", "name": "corridor", "type": "corridor", "image_url": "15.jpg", "description": "Narrow Corridor"}' .
	', { "id": "75", "paperid": "15", "top": "1", "right": "2", "bottom": "1", "left": "2", "name": "corridor", "type": "corridor", "image_url": "15a.jpg", "description": "Narrow Corridor"}' .
	', { "id": "76", "paperid": "16", "top": "2", "right": "2", "bottom": "1", "left": "1", "name": "corridor", "type": "corridor", "image_url": "16.jpg", "description": "Narrow Corridor"}' .
	', { "id": "77", "paperid": "16", "top": "2", "right": "2", "bottom": "1", "left": "1", "name": "corridor", "type": "corridor", "image_url": "16a.jpg", "description": "Narrow Corridor"}' .
	', { "id": "78", "paperid": "17", "top": "2", "right": "1", "bottom": "1", "left": "2", "name": "corridor", "type": "corridor", "image_url": "17.jpg", "description": "Narrow Corridor"}' .
	', { "id": "79", "paperid": "17", "top": "2", "right": "1", "bottom": "1", "left": "2", "name": "corridor", "type": "corridor", "image_url": "17a.jpg", "description": "Narrow Corridor"}' .
	
	// Doors
	', { "id": "80", "paperid": "21", "top": "3", "right": "1", "bottom": "1", "left": "1", "name": "type", "type": "door", "image_url": "21.jpg", "description": "Chamber with Door"}' .
	', { "id": "81", "paperid": "21", "top": "3", "right": "1", "bottom": "1", "left": "1", "name": "type", "type": "door", "image_url": "21a.jpg", "description": "Chamber with Door"}' .
	', { "id": "82", "paperid": "21", "top": "3", "right": "1", "bottom": "1", "left": "1", "name": "type", "type": "door", "image_url": "21b.jpg", "description": "Chamber with Door"}' .
	', { "id": "83", "paperid": "21", "top": "3", "right": "1", "bottom": "1", "left": "1", "name": "type", "type": "door", "image_url": "21c.jpg", "description": "Chamber with Door"}' .
	', { "id": "84", "paperid": "22", "top": "3", "right": "2", "bottom": "1", "left": "1", "name": "type", "type": "door", "image_url": "22.jpg", "description": "Chamber with Door"}' .
	', { "id": "85", "paperid": "22", "top": "3", "right": "2", "bottom": "1", "left": "1", "name": "type", "type": "door", "image_url": "22a.jpg", "description": "Chamber with Door"}' .
	', { "id": "86", "paperid": "23", "top": "3", "right": "1", "bottom": "1", "left": "2", "name": "type", "type": "door", "image_url": "23.jpg", "description": "Chamber with Door"}' .
	', { "id": "87", "paperid": "23", "top": "3", "right": "1", "bottom": "1", "left": "2", "name": "type", "type": "door", "image_url": "23a.jpg", "description": "Chamber with Door"}' .
	', { "id": "88", "paperid": "46", "top": "3", "right": "3", "bottom": "1", "left": "3", "name": "type", "type": "door", "image_url": "46.jpg", "description": "Chamber with Doors"}' .
	
	// Challenges/Abnormals
	', { "id": "89", "paperid": "51", "top": "4", "right": "2", "bottom": "4", "left": "2", "name": "bridge", "type": "bridge", "image_url": "51.jpg", "description": "Chamber with Bridge"}' .
	', { "id": "90", "paperid": "51", "top": "4", "right": "2", "bottom": "4", "left": "2", "name": "bridge", "type": "bridge", "image_url": "51a.jpg", "description": "Chamber with Bridge"}' .
	', { "id": "91", "paperid": "51", "top": "4", "right": "2", "bottom": "4", "left": "2", "name": "bridge", "type": "bridge", "image_url": "51b.jpg", "description": "Chamber with Bridge"}' .
	', { "id": "92", "paperid": "51", "top": "4", "right": "2", "bottom": "4", "left": "2", "name": "bridge", "type": "bridge", "image_url": "51c.jpg", "description": "Chamber with Bridge"}' .
	', { "id": "93", "paperid": "52", "top": "6", "right": "2", "bottom": "6", "left": "2", "name": "pit", "type": "pit", "image_url": "52.jpg", "description": "Chamber with a Pit"}' .
	', { "id": "94", "paperid": "52", "top": "6", "right": "2", "bottom": "6", "left": "2", "name": "pit", "type": "pit", "image_url": "52a.jpg", "description": "Chamber with a Pit"}' .
	', { "id": "95", "paperid": "53", "top": "2", "right": "2", "bottom": "1", "left": "2", "name": "rotating", "type": "rotating", "image_url": "53.jpg", "description": "Rotating Chamber"}' .
	', { "id": "96", "paperid": "53", "top": "2", "right": "2", "bottom": "1", "left": "2", "name": "rotating", "type": "rotating", "image_url": "53a.jpg", "description": "Rotating Chamber"}' .
	', { "id": "97", "paperid": "53", "top": "2", "right": "2", "bottom": "1", "left": "2", "name": "rotating", "type": "rotating", "image_url": "53b.jpg", "description": "Rotating Chamber"}' .
	', { "id": "98", "paperid": "53", "top": "2", "right": "2", "bottom": "1", "left": "2", "name": "rotating", "type": "rotating", "image_url": "53c.jpg", "description": "Rotating Chamber"}' .
	', { "id": "99", "paperid": "53", "top": "2", "right": "2", "bottom": "1", "left": "2", "name": "rotating", "type": "rotating", "image_url": "53d.jpg", "description": "Rotating Chamber"}' .
	', { "id": "100", "paperid": "54", "top": "1", "right": "1", "bottom": "1", "left": "1", "name": "leftchasm", "type": "leftchasm", "image_url": "54.jpg", "description": "Chamber with Chasm"}' .
	', { "id": "101", "paperid": "55", "top": "1", "right": "1", "bottom": "1", "left": "1", "name": "rightchasm", "type": "rightchasm", "image_url": "55.jpg", "description": "Chamber with Chasm"}' .
	
	// Traps/Darkness/SpiderWebs/CaveIns
	', { "id": "102", "paperid": "61", "top": "1", "right": "1", "bottom": "1", "left": "1", "name": "trap", "type": "trap", "image_url": "61.jpg", "description": "Chamber with a Trap"}' .
	', { "id": "103", "paperid": "61", "top": "1", "right": "1", "bottom": "1", "left": "1", "name": "trap", "type": "trap", "image_url": "61a.jpg", "description": "Chamber with a Trap"}' .
	', { "id": "104", "paperid": "61", "top": "1", "right": "1", "bottom": "1", "left": "1", "name": "trap", "type": "trap", "image_url": "61b.jpg", "description": "Chamber with a Trap"}' .
	', { "id": "105", "paperid": "62", "top": "1", "right": "2", "bottom": "1", "left": "1", "name": "trap", "type": "trap", "image_url": "62.jpg", "description": "Chamber with a Trap"}' .
	', { "id": "106", "paperid": "63", "top": "1", "right": "1", "bottom": "1", "left": "2", "name": "trap", "type": "trap", "image_url": "63.jpg", "description": "Chamber with a Trap"}' .
	', { "id": "107", "paperid": "64", "top": "2", "right": "9", "bottom": "9", "left": "9", "name": "darkness", "type": "darkness", "image_url": "64.jpg", "description": "Darkness"}' .
	', { "id": "108", "paperid": "64", "top": "2", "right": "9", "bottom": "9", "left": "9", "name": "darkness", "type": "darkness", "image_url": "64a.jpg", "description": "Darkness"}' .
	', { "id": "109", "paperid": "65", "top": "2", "right": "2", "bottom": "9", "left": "9", "name": "darkness", "type": "darkness", "image_url": "65.jpg", "description": "Darkness"}' .
	', { "id": "110", "paperid": "66", "top": "2", "right": "9", "bottom": "9", "left": "2", "name": "darkness", "type": "darkness", "image_url": "66.jpg", "description": "Darkness"}' .
	', { "id": "111", "paperid": "67", "top": "8", "right": "8", "bottom": "8", "left": "8", "name": "spiderweb", "type": "spiderweb", "image_url": "67.jpg", "description": "Giant Spider Web"}' .
	', { "id": "112", "paperid": "68", "top": "7", "right": "2", "bottom": "7", "left": "2", "name": "rubble", "type": "rubble", "image_url": "68.jpg", "description": "Collapsed Chamber"}' .
	', { "id": "113", "paperid": "68", "top": "7", "right": "2", "bottom": "7", "left": "2", "name": "rubble", "type": "rubble", "image_url": "68a.jpg", "description": "Collapsed Chamber"}' .
	', { "id": "114", "paperid": "69", "top": "7", "right": "7", "bottom": "7", "left": "7", "name": "rubble", "type": "rubble", "image_url": "69.jpg", "description": "Collapsed Chamber"}' .
	', { "id": "115", "paperid": "69", "top": "7", "right": "7", "bottom": "7", "left": "7", "name": "rubble", "type": "rubble", "image_url": "69a.jpg", "description": "Collapsed Chamber"}' .
	
	// Catacombs
	', { "id": "116", "paperid": "71", "top": "1", "right": "2", "bottom": "1", "left": "1", "name": "catacombs", "type": "catacombs", "image_url": "71.jpg", "description": "Chamber with Catacombs Entrance"}' .
	', { "id": "117", "paperid": "72", "top": "2", "right": "2", "bottom": "1", "left": "2", "name": "catacombs", "type": "catacombs", "image_url": "72.jpg", "description": "Chamber with Catacombs Entrance"}' .
	', { "id": "118", "paperid": "73", "top": "1", "right": "1", "bottom": "1", "left": "2", "name": "catacombs", "type": "catacombs", "image_url": "73.jpg", "description": "Chamber with Catacombs Entrance"}' .
	', { "id": "119", "paperid": "74", "top": "2", "right": "2", "bottom": "1", "left": "1", "name": "catacombs", "type": "catacombs", "image_url": "74.jpg", "description": "Chamber with Catacombs Entrance"}' .
	', { "id": "120", "paperid": "75", "top": "2", "right": "1", "bottom": "1", "left": "2", "name": "catacombs", "type": "catacombs", "image_url": "75.jpg", "description": "Chamber with Catacombs Entrance"}' .
	', { "id": "121", "paperid": "77", "top": "2", "right": "2", "bottom": "1", "left": "2", "name": "catacombs", "type": "catacombs", "image_url": "77.jpg", "description": "Chamber with Catacombs Entrance"}' .
	
	// Portcullis
	', { "id": "122", "paperid": "80", "top": "1", "right": "2", "bottom": "5", "left": "2", "name": "portcullis", "type": "portcullis", "image_url": "80.jpg", "description": "Chamber with Portcullis"}' .
	', { "id": "123", "paperid": "80", "top": "1", "right": "2", "bottom": "5", "left": "2", "name": "portcullis", "type": "portcullis", "image_url": "80a.jpg", "description": "Chamber with Portcullis"}' .
	', { "id": "124", "paperid": "81", "top": "2", "right": "2", "bottom": "5", "left": "2", "name": "portcullis", "type": "portcullis", "image_url": "81.jpg", "description": "Chamber with Portcullis"}' .
	', { "id": "125", "paperid": "84", "top": "3", "right": "1", "bottom": "5", "left": "1", "name": "portcullis", "type": "portcullis", "image_url": "84.jpg", "description": "Chamber with Portcullis"}' .
	', { "id": "126", "paperid": "85", "top": "3", "right": "2", "bottom": "5", "left": "2", "name": "portcullis", "type": "portcullis", "image_url": "85.jpg", "description": "Chamber with Portcullis"}' .
	', { "id": "127", "paperid": "86", "top": "2", "right": "1", "bottom": "5", "left": "1", "name": "portcullis", "type": "portcullis", "image_url": "86.jpg", "description": "Chamber with Portcullis"}' .
	', { "id": "128", "paperid": "86", "top": "2", "right": "1", "bottom": "5", "left": "1", "name": "portcullis", "type": "portcullis", "image_url": "86a.jpg", "description": "Chamber with Portcullis"}' .
	', { "id": "129", "paperid": "87", "top": "1", "right": "2", "bottom": "5", "left": "1", "name": "portcullis", "type": "portcullis", "image_url": "87.jpg", "description": "Chamber with Portcullis"}' .
	', { "id": "130", "paperid": "87", "top": "1", "right": "2", "bottom": "5", "left": "1", "name": "portcullis", "type": "portcullis", "image_url": "87a.jpg", "description": "Chamber with Portcullis"}' .
	', { "id": "131", "paperid": "88", "top": "1", "right": "1", "bottom": "5", "left": "2", "name": "portcullis", "type": "portcullis", "image_url": "88.jpg", "description": "Chamber with Portcullis"}' .
	', { "id": "132", "paperid": "88", "top": "1", "right": "1", "bottom": "5", "left": "2", "name": "portcullis", "type": "portcullis", "image_url": "88a.jpg", "description": "Chamber with Portcullis"}' .
	', { "id": "133", "paperid": "89", "top": "1", "right": "1", "bottom": "5", "left": "1", "name": "portcullis", "type": "portcullis", "image_url": "89.jpg", "description": "Chamber with Portcullis"}' .
	', { "id": "134", "paperid": "89", "top": "1", "right": "1", "bottom": "5", "left": "1", "name": "portcullis", "type": "portcullis", "image_url": "89a.jpg", "description": "Chamber with Portcullis"}' .

	']';					
$ChamberJSON = json_decode($ChamberJSON_string);

$ChamberEventJSON_string = '[' . 
	'  {"id": "1", "name": "Empty", "type": "emptychamber", "value": "0", "image_url": "chamber_empty.jpg", "description": ""}' .
	', {"id": "2", "name": "Empty", "type": "emptychamber", "value": "0", "image_url": "chamber_empty.jpg", "description": ""}' .
	', {"id": "3", "name": "Empty", "type": "emptychamber", "value": "0", "image_url": "chamber_empty.jpg", "description": ""}' .
	', {"id": "4", "name": "Empty", "type": "emptychamber", "value": "0", "image_url": "chamber_empty.jpg", "description": ""}' .
	', {"id": "5", "name": "Empty", "type": "emptychamber", "value": "0", "image_url": "chamber_empty.jpg", "description": ""}' .
	', {"id": "6", "name": "Empty", "type": "emptychamber", "value": "0", "image_url": "chamber_empty.jpg", "description": ""}' .
	', {"id": "7", "name": "Empty", "type": "emptychamber", "value": "0", "image_url": "chamber_empty.jpg", "description": ""}' .
	', {"id": "8", "name": "Empty", "type": "emptychamber", "value": "0", "image_url": "chamber_empty.jpg", "description": ""}' .
	', {"id": "9", "name": "Empty", "type": "emptychamber", "value": "0", "image_url": "chamber_empty.jpg", "description": ""}' .
	', {"id": "10", "name": "Empty", "type": "emptychamber", "value": "0", "image_url": "chamber_empty.jpg", "description": ""}' .
	', {"id": "11", "name": "Empty", "type": "emptychamber", "value": "0", "image_url": "chamber_empty.jpg", "description": ""}' .
	', {"id": "12", "name": "Empty", "type": "emptychamber", "value": "0", "image_url": "chamber_empty.jpg", "description": ""}' .
	', {"id": "13", "name": "Empty", "type": "emptychamber", "value": "0", "image_url": "chamber_empty.jpg", "description": ""}' .
	', {"id": "14", "name": "Empty", "type": "emptychamber", "value": "0", "image_url": "chamber_empty.jpg", "description": ""}' .
	', {"id": "15", "name": "Empty", "type": "emptychamber", "value": "0", "image_url": "chamber_empty.jpg", "description": ""}' .
	', {"id": "16", "name": "Empty", "type": "emptychamber", "value": "0", "image_url": "chamber_empty.jpg", "description": ""}' .
	', {"id": "17", "name": "Empty", "type": "emptychamber", "value": "0", "image_url": "chamber_empty.jpg", "description": ""}' .
	', {"id": "18", "name": "Empty", "type": "emptychamber", "value": "0", "image_url": "chamber_empty.jpg", "description": ""}' .
	', {"id": "19", "name": "Empty", "type": "emptychamber", "value": "0", "image_url": "chamber_empty.jpg", "description": ""}' .
	', {"id": "20", "name": "Empty", "type": "emptychamber", "value": "0", "image_url": "chamber_empty.jpg", "description": ""}' .
	', {"id": "21", "name": "Empty", "type": "emptychamber", "value": "0", "image_url": "chamber_empty.jpg", "description": ""}' .
	', {"id": "22", "name": "Empty", "type": "emptychamber", "value": "0", "image_url": "chamber_empty.jpg", "description": ""}' .
	', {"id": "23", "name": "Empty", "type": "emptychamber", "value": "0", "image_url": "chamber_empty.jpg", "description": ""}' .
	', {"id": "24", "name": "Empty", "type": "emptychamber", "value": "0", "image_url": "chamber_empty.jpg", "description": ""}' .
	', {"id": "25", "name": "Empty", "type": "emptychamber", "value": "0", "image_url": "chamber_empty.jpg", "description": ""}' .
	', {"id": "26", "name": "Empty", "type": "emptychamber", "value": "0", "image_url": "chamber_empty.jpg", "description": ""}' .
	', {"id": "27", "name": "Empty", "type": "emptychamber", "value": "0", "image_url": "chamber_empty.jpg", "description": ""}' .
	', {"id": "28", "name": "Empty", "type": "emptychamber", "value": "0", "image_url": "chamber_empty.jpg", "description": ""}' .
	', {"id": "29", "name": "Empty", "type": "emptychamber", "value": "0", "image_url": "chamber_empty.jpg", "description": ""}' .
	', {"id": "30", "name": "Empty", "type": "emptychamber", "value": "0", "image_url": "chamber_empty.jpg", "description": ""}' .
	', {"id": "31", "name": "Empty", "type": "emptychamber", "value": "0", "image_url": "chamber_empty.jpg", "description": ""}' .
	', {"id": "32", "name": "Empty", "type": "emptychamber", "value": "0", "image_url": "chamber_empty.jpg", "description": ""}' .
	', {"id": "33", "name": "Empty", "type": "emptychamber", "value": "0", "image_url": "chamber_empty.jpg", "description": ""}' .
	', {"id": "34", "name": "Empty", "type": "emptychamber", "value": "0", "image_url": "chamber_empty.jpg", "description": ""}' .
	
	', {"id": "35", "name": "Cave-In", "type": "collapse", "value": "0", "image_url": "chamber_cavein.jpg", "description": ""}' .
	', {"id": "36", "name": "Crossfire Trap", "type": "collapse", "value": "0", "image_url": "chamber_crossfire.jpg", "description": ""}' .
	', {"id": "37", "name": "Trap Door", "type": "collapse", "value": "0", "image_url": "chamber_trapdoor.jpg", "description": ""}' .
	
	', {"id": "38", "name": "Dead Adventurer", "type": "deadadventurer", "value": "0", "image_url": "chamber_dead_adventurer.jpg", "description": ""}' .
	', {"id": "39", "name": "Dead Adventurer", "type": "deadadventurer", "value": "0", "image_url": "chamber_dead_adventurer.jpg", "description": ""}' .
	', {"id": "40", "name": "Dead Adventurer", "type": "deadadventurer", "value": "0", "image_url": "chamber_dead_adventurer.jpg", "description": ""}' .
	', {"id": "41", "name": "Dead Adventurer", "type": "deadadventurer", "value": "0", "image_url": "chamber_dead_adventurer.jpg", "description": ""}' .
	', {"id": "42", "name": "Dead Adventurer", "type": "deadadventurer", "value": "0", "image_url": "chamber_dead_adventurer.jpg", "description": ""}' .
	
	', {"id": "43", "name": "Bracelet", "type": "finditem", "value": "100", "image_url": "chamber_item_bracelet_40gp.jpg", "description": ""}' .
	', {"id": "44", "name": "Jewellery", "type": "finditem", "value": "30", "image_url": "chamber_item_jewellery_30gp.jpg", "description": ""}' .
	', {"id": "45", "name": "Potion", "type": "finditem", "value": "0", "image_url": "chamber_item_potion.jpg", "description": ""}' .
	
	', {"id": "46", "name": "Curse of the Wizard", "type": "wizardscurse", "value": "0", "image_url": "chamber_wizards_curse.jpg", "description": ""}' .
	
	', {"id": "47", "name": "Vampire Bats", "type": "vampirebats", "value": "0", "image_url": "chamber_monster_vampire_bats.jpg", "description": "Damage D6-2"}' .
	', {"id": "48", "name": "Vampire Bats", "type": "vampirebats", "value": "0", "image_url": "chamber_monster_vampire_bats.jpg", "description": "Damage D6-2"}' .
	', {"id": "49", "name": "Vampire Bats", "type": "vampirebats", "value": "0", "image_url": "chamber_monster_vampire_bats.jpg", "description": "Damage D6-2"}' .
	
	', {"id": "50", "name": "Giant Spider", "type": "giantspider", "value": "0", "image_url": "chamber_monster_giant_spider.jpg", "description": ""}' .
	', {"id": "51", "name": "Giant Spider", "type": "giantspider", "value": "0", "image_url": "chamber_monster_giant_spider.jpg", "description": ""}' .
	', {"id": "52", "name": "Giant Spider", "type": "giantspider", "value": "0", "image_url": "chamber_monster_giant_spider.jpg", "description": ""}' .
	
	', {"id": "53", "name": "Torch Goes Out", "type": "torchout", "value": "0", "image_url": "chamber_torchout.jpg", "description": ""}' .
	', {"id": "54", "name": "Torch Goes Out", "type": "torchout", "value": "0", "image_url": "chamber_torchout.jpg", "description": ""}' .
	', {"id": "55", "name": "Torch Goes Out", "type": "torchout", "value": "0", "image_url": "chamber_torchout.jpg", "description": ""}' .
	
	', {"id": "56", "name": "Crypt", "type": "crypt", "value": "0", "image_url": "chamber_crypt.jpg", "description": ""}' .
	', {"id": "57", "name": "Crypt", "type": "crypt", "value": "0", "image_url": "chamber_crypt.jpg", "description": ""}' .
	', {"id": "58", "name": "Crypt", "type": "crypt", "value": "0", "image_url": "chamber_crypt.jpg", "description": ""}' .
	', {"id": "59", "name": "Crypt", "type": "crypt", "value": "0", "image_url": "chamber_crypt.jpg", "description": ""}' .
	', {"id": "60", "name": "Crypt", "type": "crypt", "value": "0", "image_url": "chamber_crypt.jpg", "description": ""}' .
	', {"id": "61", "name": "Crypt", "type": "crypt", "value": "0", "image_url": "chamber_crypt.jpg", "description": ""}' .
	
	', {"id": "62", "name": "Goblin", "type": "goblin", "value": "0", "image_url": "chamber_monster_goblin.jpg", "description": ""}' .
	', {"id": "63", "name": "Goblin", "type": "goblin", "value": "0", "image_url": "chamber_monster_goblin.jpg", "description": ""}' .
	', {"id": "64", "name": "Goblin", "type": "goblin", "value": "0", "image_url": "chamber_monster_goblin.jpg", "description": ""}' .
	', {"id": "65", "name": "Goblin", "type": "goblin", "value": "0", "image_url": "chamber_monster_goblin.jpg", "description": ""}' .
	', {"id": "66", "name": "Goblin", "type": "goblin", "value": "0", "image_url": "chamber_monster_goblin.jpg", "description": ""}' .
	
	', {"id": "67", "name": "Orc", "type": "orc", "value": "0", "image_url": "chamber_monster_orc.jpg", "description": ""}' .
	', {"id": "68", "name": "Orc", "type": "orc", "value": "0", "image_url": "chamber_monster_orc.jpg", "description": ""}' .
	', {"id": "69", "name": "Orc", "type": "orc", "value": "0", "image_url": "chamber_monster_orc.jpg", "description": ""}' .
	', {"id": "70", "name": "Orc", "type": "orc", "value": "0", "image_url": "chamber_monster_orc.jpg", "description": ""}' .
	
	', {"id": "71", "name": "Troll", "type": "troll", "value": "0", "image_url": "chamber_monster_troll.jpg", "description": ""}' .
	', {"id": "72", "name": "Troll", "type": "troll", "value": "0", "image_url": "chamber_monster_troll.jpg", "description": ""}' .
	', {"id": "73", "name": "Troll", "type": "troll", "value": "0", "image_url": "chamber_monster_troll.jpg", "description": ""}' .
	
	', {"id": "74", "name": "Skeleton", "type": "undead", "value": "0", "image_url": "chamber_monster_skeleton.jpg", "description": ""}' .
	', {"id": "75", "name": "Skeleton", "type": "undead", "value": "0", "image_url": "chamber_monster_skeleton.jpg", "description": ""}' .
	', {"id": "76", "name": "Skeleton", "type": "undead", "value": "0", "image_url": "chamber_monster_skeleton.jpg", "description": ""}' .
	
	', {"id": "77", "name": "Black Knight", "type": "blackknight", "value": "0", "image_url": "chamber_champion_of_chaos.jpg", "description": ""}' .
	', {"id": "78", "name": "Black Knight", "type": "blackknight", "value": "0", "image_url": "chamber_champion_of_chaos.jpg", "description": ""}' .
	
	', {"id": "79", "name": "Ambush Goblin", "type": "ambushgoblin", "value": "0", "image_url": "chamber_ambush_goblin.jpg", "description": ""}' .
	', {"id": "80", "name": "Ambush Goblin", "type": "ambushgoblin", "value": "0", "image_url": "chamber_ambush_goblin.jpg", "description": ""}' .
	', {"id": "81", "name": "Ambush Skeleton", "type": "ambushskeleton", "value": "0", "image_url": "chamber_ambush_skeleton.jpg", "description": ""}' .
	', {"id": "82", "name": "Ambush Skeleton", "type": "ambushskeleton", "value": "0", "image_url": "chamber_ambush_skeleton.jpg", "description": ""}' .
	', {"id": "83", "name": "Ambush Troll", "type": "ambushtroll", "value": "0", "image_url": "chamber_ambush_troll.jpg", "description": ""}' .

	']';
$ChamberEventJSON = json_decode($ChamberEventJSON_string);

$CatacombsJSON_string = '[' .
	'  {"id": "1", "name": "Exit", "value": "0", "image_url": "", "description": "You have found a way out! You may immediately exit the catacombs."}' .
	', {"id": "2", "name": "Exit", "value": "0", "image_url": "", "description": "You have found a way out! You may immediately exit the catacombs."}' .
	', {"id": "3", "name": "Exit", "value": "0", "image_url": "", "description": "You have found a way out! You may immediately exit the catacombs."}' .
	', {"id": "4", "name": "Exit", "value": "0", "image_url": "", "description": "You have found a way out! You may immediately exit the catacombs."}' .
	', {"id": "5", "name": "Exit", "value": "0", "image_url": "", "description": "You have found a way out! You may immediately exit the catacombs."}' .
	', {"id": "6", "name": "Exit", "value": "0", "image_url": "", "description": "You have found a way out! You may immediately exit the catacombs."}' .
	', {"id": "7", "name": "Empty", "value": "0", "image_url": "", "description": ""}' .
	', {"id": "8", "name": "Empty", "value": "0", "image_url": "", "description": ""}' .
	', {"id": "9", "name": "Empty", "value": "0", "image_url": "", "description": ""}' .
	', {"id": "10", "name": "Empty", "value": "0", "image_url": "", "description": ""}' .
	', {"id": "11", "name": "Empty", "value": "0", "image_url": "", "description": ""}' .
	', {"id": "12", "name": "Empty", "value": "0", "image_url": "", "description": ""}' .
	', {"id": "13", "name": "Empty", "value": "0", "image_url": "", "description": ""}' .
	', {"id": "14", "name": "Empty", "value": "0", "image_url": "", "description": ""}' .
	', {"id": "15", "name": "Hole in the Roof", "value": "0", "image_url": "", "description": "Test agility. If you succeed, you may exit the catacombs. If you fail, end your turn; on your next turn, you may test agility again instead of drawing a catacomb card."}' .
	', {"id": "16", "name": "Hole in the Roof", "value": "0", "image_url": "", "description": "Test agility. If you succeed, you may exit the catacombs. If you fail, end your turn; on your next turn, you may test agility again instead of drawing a catacomb card."}' .
	', {"id": "17", "name": "Hidden Trap", "value": "0", "image_url": "", "description": "Draw a trap card."}' .
	', {"id": "18", "name": "Hidden Trap", "value": "0", "image_url": "", "description": "Draw a trap card."}' .
	', {"id": "19", "name": "Hidden Trap", "value": "0", "image_url": "", "description": "Draw a trap card."}' .
	', {"id": "20", "name": "Hidden Trap", "value": "0", "image_url": "", "description": "Draw a trap card."}' .
	', {"id": "21", "name": "Treasure Chest", "value": "0", "image_url": "", "description": "When you exit Dragonfire Dungeon, roll 2 dice. The chest has a gold value equal to the result multiplied by 100."}' .
	', {"id": "22", "name": "Treasure Chest", "value": "0", "image_url": "", "description": "When you exit Dragonfire Dungeon, roll 2 dice. The chest has a gold value equal to the result multiplied by 100."}' .
	', {"id": "23", "name": "Gigantic Diamond", "value": "4000", "image_url": "", "description": ""}' .
	', {"id": "24", "name": "Torch Goes Out", "value": "0", "image_url": "", "description": "While you are in the catacombs, test luck at the start of your status phase. If you succeed, you relight your torch; take your turn as normal. If you fail, end your turn."}' .
	', {"id": "25", "name": "Torch Goes Out", "value": "0", "image_url": "", "description": "While you are in the catacombs, test luck at the start of your status phase. If you succeed, you relight your torch; take your turn as normal. If you fail, end your turn."}' .
	', {"id": "26", "name": "Horde of Rats", "value": "0", "image_url": "", "description": "You are overwhelmed by a horde of verminous rats! Test armor. If you fail, suffer 4 wounds."}' .
	', {"id": "27", "name": "Horde of Rats", "value": "0", "image_url": "", "description": "You are overwhelmed by a horde of verminous rats! Test armor. If you fail, suffer 4 wounds."}' .
	', {"id": "28", "name": "Spider\'s Venom", "value": "0", "image_url": "", "description": "You are bitten by a spider.  While you are in the catacombs, roll 1 die at the start of your phase: 1-2) Suffer 1 wound. 3-6) Nothing happens."}' .
	', {"id": "29", "name": "Spider\'s Venom", "value": "0", "image_url": "", "description": "You are bitten by a spider.  While you are in the catacombs, roll 1 die at the start of your phase: 1-2) Suffer 1 wound. 3-6) Nothing happens."}' .
	', {"id": "30", "name": "Razorwing Attack", "value": "0", "image_url": "", "description": "Roll 1 die and suffer a number of wounds equal to the result."}' .
	', {"id": "31", "name": "Razorwing Attack", "value": "0", "image_url": "", "description": "Roll 1 die and suffer a number of wounds equal to the result."}' .
	', {"id": "32", "name": "Demon Flame", "value": "0", "image_url": "", "description": "A demon bursts forth in a ball of flame! Test luck. You cannot discard determination tokens to pass this test. If you succeed, suffer 1 wound. If you fail, suffer 5 wounds."}' .
	', {"id": "33", "name": "Skeleton Volley", "value": "0", "image_url": "", "description": "A group of skeletons launches a volley of arrows down the hall toward you! Test luck. If you succeed, end your turn. If you fail, draw 2 monster cards and suffer a number of wounds equal to the sum of  the escape penalties on both cards. Then, discard the cards."}' .
	', {"id": "34", "name": "Golem Grab", "value": "0", "image_url": "", "description": "A golem lunges at you out of the darkness, grabbing at your gear! Test luck. If you succeed, end your turn. If you fail, suffer 2 wounds and discard a loot card (if you have any)."}' .
	', {"id": "35", "name": "Troll Smash", "value": "0", "image_url": "", "description": "A troll swings his mighty club directly at you! Test luck. If you succeed, end your turn. If you fail subtract your armor value from 10 and suffer than many wounds."}' .
	', {"id": "36", "name": "Sorcerer\'s Bolt", "value": "0", "image_url": "", "description": "A sorcerer hurls a magic bolt in your direction! Test luck. If you succeed, end your turn. If you fail, suffer a number of wounds equal to the amount by which you failed."}' .
	', {"id": "37", "name": "Vampire Bite", "value": "0", "image_url": "", "description": "Test agility or armor (whichever is lower). If you fail, keep this card when you exit the catacombs. While you have this card, suffer 1 wound at the start of your status phase."}' .
	', {"id": "38", "name": "Greedy Deep Elf", "value": "0", "image_url": "", "description": "A suspecious-looking deep elf beckons you near. You may discard any of your loot cards as a bribe. If you do, roll 1 die and multiply the result by 100. If this total is less than the gold value of the loot cards you discarded, she show syou the way out and you may immediately exit the catacombs. If the total is higher, she betrays you with a sudden attack! Suffer 4 wounds."}' .
	', {"id": "39", "name": "Giant Naga", "value": "0", "image_url": "", "description": "The passage is blocked by a giant naga and you must turn around. Follow the procedure for exiting the catacombs. Then, place your travel marker in that space and rotate it 180 degrees. Discard this card and all of your catacomb cards."}' .
	']';
$CatacombsJSON = json_decode($CatacombsJSON_string);

$MonsterJSON_string = '[' .
	'  {"id": "1", "name": "Goblin", "type": "goblin", "health": "3", "escape_penalty": "4", "combat": "0", "wait": "0", "escape": "1", "penalty_dice": "1", "penalty": "-1", "image_url": "chamber_monster_goblin.jpg"}' .
	', {"id": "2", "name": "Goblin", "type": "goblin", "health": "4", "escape_penalty": "2", "combat": "0", "wait": "0", "escape": "0", "penalty_dice": "0", "penalty": "0", "image_url": "chamber_monster_goblin.jpg"}' .
	', {"id": "3", "name": "Goblin", "type": "goblin", "health": "4", "escape_penalty": "3", "combat": "0", "wait": "0", "escape": "1", "penalty_dice": "1", "penalty": "-1", "image_url": "chamber_monster_goblin.jpg"}' .
	', {"id": "4", "name": "Goblin", "type": "goblin", "health": "5", "escape_penalty": "0", "combat": "0", "wait": "0", "escape": "0", "penalty_dice": "0", "penalty": "0", "image_url": "chamber_monster_goblin.jpg"}' .
	', {"id": "5", "name": "Goblin", "type": "goblin", "health": "5", "escape_penalty": "2", "combat": "4", "wait": "2", "escape": "0", "penalty_dice": "0", "penalty": "0", "image_url": "chamber_monster_goblin.jpg"}' .
	', {"id": "5", "name": "Goblin", "type": "goblin", "health": "5", "escape_penalty": "2", "combat": "1", "wait": "1", "escape": "1", "penalty_dice": "0", "penalty": "0", "image_url": "chamber_monster_goblin.jpg"}' .
	', {"id": "5", "name": "Goblin", "type": "goblin", "health": "5", "escape_penalty": "2", "combat": "0", "wait": "0", "escape": "0", "penalty_dice": "0", "penalty": "0", "image_url": "chamber_monster_goblin.jpg"}' .
	', {"id": "5", "name": "Goblin", "type": "goblin", "health": "5", "escape_penalty": "2", "combat": "0", "wait": "0", "escape": "0", "penalty_dice": "0", "penalty": "0", "image_url": "chamber_monster_goblin.jpg"}' .
	', {"id": "5", "name": "Goblin", "type": "goblin", "health": "5", "escape_penalty": "2", "combat": "0", "wait": "0", "escape": "1", "penalty_dice": "0", "penalty": "-1", "image_url": "chamber_monster_goblin.jpg"}' .
	', {"id": "5", "name": "Goblin", "type": "goblin", "health": "5", "escape_penalty": "2", "combat": "0", "wait": "0", "escape": "0", "penalty_dice": "0", "penalty": "0", "image_url": "chamber_monster_goblin.jpg"}' .
	', {"id": "5", "name": "Goblin", "type": "goblin", "health": "5", "escape_penalty": "2", "combat": "0", "wait": "0", "escape": "1", "penalty_dice": "0", "penalty": "0", "image_url": "chamber_monster_goblin.jpg"}' .
	', {"id": "5", "name": "Goblin", "type": "goblin", "health": "5", "escape_penalty": "2", "combat": "0", "wait": "0", "escape": "2", "penalty_dice": "0", "penalty": "-2", "image_url": "chamber_monster_goblin.jpg"}' .
	', {"id": "5", "name": "Goblin", "type": "goblin", "health": "5", "escape_penalty": "2", "combat": "0", "wait": "0", "escape": "1", "penalty_dice": "0", "penalty": "0", "image_url": "chamber_monster_goblin.jpg"}' .
	', {"id": "5", "name": "Goblin", "type": "goblin", "health": "5", "escape_penalty": "2", "combat": "2", "wait": "1", "escape": "1", "penalty_dice": "0", "penalty": "-1", "image_url": "chamber_monster_goblin.jpg"}' .
	', {"id": "5", "name": "Goblin", "type": "goblin", "health": "5", "escape_penalty": "2", "combat": "3", "wait": "2", "escape": "2", "penalty_dice": "0", "penalty": "-1", "image_url": "chamber_monster_goblin.jpg"}' .
	', {"id": "15", "name": "Mountain Troll", "type": "troll", "health": "3", "escape_penalty": "3", "combat": "2", "wait": "0", "escape": "0", "penalty_dice": "0", "penalty": "0", "image_url": "chamber_monster_troll.jpg"}' .
	', {"id": "16", "name": "Mountain Troll", "type": "troll", "health": "3", "escape_penalty": "4", "combat": "2", "wait": "0", "escape": "0", "penalty_dice": "0", "penalty": "0", "image_url": "chamber_monster_troll.jpg"}' .
	', {"id": "17", "name": "Mountain Troll", "type": "troll", "health": "4", "escape_penalty": "2", "combat": "2", "wait": "0", "escape": "0", "penalty_dice": "0", "penalty": "0", "image_url": "chamber_monster_troll.jpg"}' .
	', {"id": "17", "name": "Mountain Troll", "type": "troll", "health": "4", "escape_penalty": "2", "combat": "1", "wait": "0", "escape": "0", "penalty_dice": "0", "penalty": "0", "image_url": "chamber_monster_troll.jpg"}' .
	', {"id": "17", "name": "Mountain Troll", "type": "troll", "health": "4", "escape_penalty": "2", "combat": "2", "wait": "2", "escape": "0", "penalty_dice": "0", "penalty": "0", "image_url": "chamber_monster_troll.jpg"}' .
	', {"id": "17", "name": "Mountain Troll", "type": "troll", "health": "4", "escape_penalty": "2", "combat": "1", "wait": "0", "escape": "0", "penalty_dice": "0", "penalty": "0", "image_url": "chamber_monster_troll.jpg"}' .
	', {"id": "17", "name": "Mountain Troll", "type": "troll", "health": "4", "escape_penalty": "2", "combat": "2", "wait": "3", "escape": "0", "penalty_dice": "0", "penalty": "0", "image_url": "chamber_monster_troll.jpg"}' .
	', {"id": "17", "name": "Mountain Troll", "type": "troll", "health": "4", "escape_penalty": "2", "combat": "0", "wait": "0", "escape": "0", "penalty_dice": "0", "penalty": "0", "image_url": "chamber_monster_troll.jpg"}' .
	', {"id": "17", "name": "Mountain Troll", "type": "troll", "health": "4", "escape_penalty": "2", "combat": "0", "wait": "0", "escape": "0", "penalty_dice": "0", "penalty": "0", "image_url": "chamber_monster_troll.jpg"}' .
	', {"id": "17", "name": "Mountain Troll", "type": "troll", "health": "4", "escape_penalty": "2", "combat": "3", "wait": "4", "escape": "0", "penalty_dice": "0", "penalty": "0", "image_url": "chamber_monster_troll.jpg"}' .
	', {"id": "17", "name": "Mountain Troll", "type": "troll", "health": "4", "escape_penalty": "2", "combat": "2", "wait": "3", "escape": "0", "penalty_dice": "0", "penalty": "0", "image_url": "chamber_monster_troll.jpg"}' .
	', {"id": "17", "name": "Mountain Troll", "type": "troll", "health": "4", "escape_penalty": "2", "combat": "4", "wait": "5", "escape": "4", "penalty_dice": "2", "penalty": "4", "image_url": "chamber_monster_troll.jpg"}' .
	', {"id": "17", "name": "Mountain Troll", "type": "troll", "health": "4", "escape_penalty": "2", "combat": "2", "wait": "3", "escape": "0", "penalty_dice": "0", "penalty": "0", "image_url": "chamber_monster_troll.jpg"}' .
	', {"id": "17", "name": "Mountain Troll", "type": "troll", "health": "4", "escape_penalty": "2", "combat": "0", "wait": "0", "escape": "0", "penalty_dice": "0", "penalty": "0", "image_url": "chamber_monster_troll.jpg"}' .
	', {"id": "17", "name": "Mountain Troll", "type": "troll", "health": "4", "escape_penalty": "2", "combat": "3", "wait": "4", "escape": "3", "penalty_dice": "2", "penalty": "0", "image_url": "chamber_monster_troll.jpg"}' .
	', {"id": "10", "name": "Undead", "type": "undead", "health": "2", "escape_penalty": "2", "combat": "4", "wait": "4", "escape": "3", "penalty_dice": "1", "penalty": "2", "image_url": "chamber_monster_skeleton.jpg"}' .
	', {"id": "11", "name": "Undead", "type": "undead", "health": "3", "escape_penalty": "0", "combat": "5", "wait": "4", "escape": "0", "penalty_dice": "0", "penalty": "0", "image_url": "chamber_monster_skeleton.jpg"}' .
	', {"id": "12", "name": "Undead", "type": "undead", "health": "3", "escape_penalty": "1", "combat": "5", "wait": "4", "escape": "0", "penalty_dice": "1", "penalty": "3", "image_url": "chamber_monster_skeleton.jpg"}' .
	', {"id": "12", "name": "Undead", "type": "undead", "health": "3", "escape_penalty": "1", "combat": "4", "wait": "4", "escape": "0", "penalty_dice": "0", "penalty": "0", "image_url": "chamber_monster_skeleton.jpg"}' .
	', {"id": "12", "name": "Undead", "type": "undead", "health": "3", "escape_penalty": "1", "combat": "3", "wait": "3", "escape": "0", "penalty_dice": "0", "penalty": "0", "image_url": "chamber_monster_skeleton.jpg"}' .
	', {"id": "12", "name": "Undead", "type": "undead", "health": "3", "escape_penalty": "1", "combat": "0", "wait": "0", "escape": "0", "penalty_dice": "0", "penalty": "0", "image_url": "chamber_monster_skeleton.jpg"}' .
	', {"id": "12", "name": "Undead", "type": "undead", "health": "3", "escape_penalty": "1", "combat": "3", "wait": "3", "escape": "2", "penalty_dice": "0", "penalty": "-3", "image_url": "chamber_monster_skeleton.jpg"}' .
	', {"id": "12", "name": "Undead", "type": "undead", "health": "3", "escape_penalty": "1", "combat": "2", "wait": "3", "escape": "0", "penalty_dice": "1", "penalty": "3", "image_url": "chamber_monster_skeleton.jpg"}' .
	', {"id": "12", "name": "Undead", "type": "undead", "health": "3", "escape_penalty": "1", "combat": "2", "wait": "2", "escape": "4", "penalty_dice": "1", "penalty": "3", "image_url": "chamber_monster_skeleton.jpg"}' .
	', {"id": "12", "name": "Undead", "type": "undead", "health": "3", "escape_penalty": "1", "combat": "5", "wait": "4", "escape": "0", "penalty_dice": "1", "penalty": "3", "image_url": "chamber_monster_skeleton.jpg"}' .
	', {"id": "12", "name": "Undead", "type": "undead", "health": "3", "escape_penalty": "1", "combat": "5", "wait": "5", "escape": "0", "penalty_dice": "1", "penalty": "3", "image_url": "chamber_monster_skeleton.jpg"}' .
	', {"id": "12", "name": "Undead", "type": "undead", "health": "3", "escape_penalty": "1", "combat": "5", "wait": "5", "escape": "3", "penalty_dice": "0", "penalty": "-2", "image_url": "chamber_monster_skeleton.jpg"}' .
	', {"id": "12", "name": "Undead", "type": "undead", "health": "3", "escape_penalty": "1", "combat": "2", "wait": "3", "escape": "0", "penalty_dice": "0", "penalty": "0", "image_url": "chamber_monster_skeleton.jpg"}' .
	', {"id": "12", "name": "Undead", "type": "undead", "health": "3", "escape_penalty": "1", "combat": "0", "wait": "2", "escape": "0", "penalty_dice": "0", "penalty": "0", "image_url": "chamber_monster_skeleton.jpg"}' .
	', {"id": "12", "name": "Undead", "type": "undead", "health": "3", "escape_penalty": "1", "combat": "4", "wait": "4", "escape": "4", "penalty_dice": "0", "penalty": "-3", "image_url": "chamber_monster_skeleton.jpg"}' .
	', {"id": "6", "name": "Orc", "type": "orc", "health": "6", "escape_penalty": "3", "combat": "0", "wait": "6", "escape": "5", "penalty_dice": "1", "penalty": "0", "image_url": "chamber_monster_orc.jpg"}' .
	', {"id": "7", "name": "Orc", "type": "orc", "health": "6", "escape_penalty": "4", "combat": "0", "wait": "0", "escape": "2", "penalty_dice": "1", "penalty": "4", "image_url": "chamber_monster_orc.jpg"}' .
	', {"id": "8", "name": "Orc", "type": "orc", "health": "8", "escape_penalty": "6", "combat": "0", "wait": "0", "escape": "0", "penalty_dice": "0", "penalty": "0", "image_url": "chamber_monster_orc.jpg"}' .
	', {"id": "9", "name": "Orc", "type": "orc", "health": "2", "escape_penalty": "1", "combat": "4", "wait": "3", "escape": "3", "penalty_dice": "1", "penalty": "3", "image_url": "chamber_monster_orc.jpg"}' .
	', {"id": "9", "name": "Orc", "type": "orc", "health": "2", "escape_penalty": "1", "combat": "3", "wait": "3", "escape": "0", "penalty_dice": "1", "penalty": "1", "image_url": "chamber_monster_orc.jpg"}' .
	', {"id": "9", "name": "Orc", "type": "orc", "health": "2", "escape_penalty": "1", "combat": "5", "wait": "0", "escape": "3", "penalty_dice": "0", "penalty": "0", "image_url": "chamber_monster_orc.jpg"}' .
	', {"id": "9", "name": "Orc", "type": "orc", "health": "2", "escape_penalty": "1", "combat": "2", "wait": "3", "escape": "0", "penalty_dice": "1", "penalty": "0", "image_url": "chamber_monster_orc.jpg"}' .
	', {"id": "9", "name": "Orc", "type": "orc", "health": "2", "escape_penalty": "1", "combat": "0", "wait": "2", "escape": "0", "penalty_dice": "0", "penalty": "0", "image_url": "chamber_monster_orc.jpg"}' .
	', {"id": "9", "name": "Orc", "type": "orc", "health": "2", "escape_penalty": "1", "combat": "0", "wait": "0", "escape": "0", "penalty_dice": "0", "penalty": "0", "image_url": "chamber_monster_orc.jpg"}' .
	', {"id": "9", "name": "Orc", "type": "orc", "health": "2", "escape_penalty": "1", "combat": "3", "wait": "3", "escape": "4", "penalty_dice": "1", "penalty": "3", "image_url": "chamber_monster_orc.jpg"}' .
	', {"id": "9", "name": "Orc", "type": "orc", "health": "2", "escape_penalty": "1", "combat": "0", "wait": "1", "escape": "0", "penalty_dice": "0", "penalty": "0", "image_url": "chamber_monster_orc.jpg"}' .
	', {"id": "9", "name": "Orc", "type": "orc", "health": "2", "escape_penalty": "1", "combat": "4", "wait": "4", "escape": "5", "penalty_dice": "1", "penalty": "2", "image_url": "chamber_monster_orc.jpg"}' .
	', {"id": "9", "name": "Orc", "type": "orc", "health": "2", "escape_penalty": "1", "combat": "2", "wait": "2", "escape": "2", "penalty_dice": "1", "penalty": "2", "image_url": "chamber_monster_orc.jpg"}' .
	', {"id": "9", "name": "Orc", "type": "orc", "health": "2", "escape_penalty": "1", "combat": "4", "wait": "4", "escape": "5", "penalty_dice": "1", "penalty": "2", "image_url": "chamber_monster_orc.jpg"}' .
	', {"id": "9", "name": "Orc", "type": "orc", "health": "2", "escape_penalty": "1", "combat": "3", "wait": "3", "escape": "2", "penalty_dice": "1", "penalty": "3", "image_url": "chamber_monster_orc.jpg"}' .
	', {"id": "13", "name": "Black Knight", "type": "blackknight", "health": "2", "escape_penalty": "1", "combat": "4", "wait": "5", "escape": "3", "penalty_dice": "1", "penalty": "3", "image_url": "chamber_monster_champion_of_chaos.jpg"}' .
	', {"id": "14", "name": "Black Knight", "type": "blackknight", "health": "2", "escape_penalty": "2", "combat": "4", "wait": "5", "escape": "2", "penalty_dice": "1", "penalty": "2", "image_url": "chamber_monster_champion_of_chaos.jpg"}' .
	', {"id": "13", "name": "Black Knight", "type": "blackknight", "health": "2", "escape_penalty": "1", "combat": "5", "wait": "5", "escape": "6", "penalty_dice": "1", "penalty": "2", "image_url": "chamber_monster_champion_of_chaos.jpg"}' .
	', {"id": "14", "name": "Black Knight", "type": "blackknight", "health": "2", "escape_penalty": "2", "combat": "5", "wait": "5", "escape": "6", "penalty_dice": "1", "penalty": "2", "image_url": "chamber_monster_champion_of_chaos.jpg"}' .
	', {"id": "13", "name": "Black Knight", "type": "blackknight", "health": "2", "escape_penalty": "1", "combat": "4", "wait": "5", "escape": "4", "penalty_dice": "1", "penalty": "1", "image_url": "chamber_monster_champion_of_chaos.jpg"}' .
	', {"id": "14", "name": "Black Knight", "type": "blackknight", "health": "2", "escape_penalty": "2", "combat": "5", "wait": "5", "escape": "4", "penalty_dice": "1", "penalty": "2", "image_url": "chamber_monster_champion_of_chaos.jpg"}' .
	', {"id": "13", "name": "Black Knight", "type": "blackknight", "health": "2", "escape_penalty": "1", "combat": "3", "wait": "4", "escape": "6", "penalty_dice": "1", "penalty": "2", "image_url": "chamber_monster_champion_of_chaos.jpg"}' .
	', {"id": "14", "name": "Black Knight", "type": "blackknight", "health": "2", "escape_penalty": "2", "combat": "7", "wait": "8", "escape": "8", "penalty_dice": "1", "penalty": "0", "image_url": "chamber_monster_champion_of_chaos.jpg"}' .
	', {"id": "13", "name": "Black Knight", "type": "blackknight", "health": "2", "escape_penalty": "1", "combat": "7", "wait": "5", "escape": "6", "penalty_dice": "1", "penalty": "2", "image_url": "chamber_monster_champion_of_chaos.jpg"}' .
	', {"id": "14", "name": "Black Knight", "type": "blackknight", "health": "2", "escape_penalty": "2", "combat": "0", "wait": "3", "escape": "0", "penalty_dice": "0", "penalty": "0", "image_url": "chamber_monster_champion_of_chaos.jpg"}' .
	', {"id": "13", "name": "Black Knight", "type": "blackknight", "health": "2", "escape_penalty": "1", "combat": "5", "wait": "4", "escape": "5", "penalty_dice": "0", "penalty": "0", "image_url": "chamber_monster_champion_of_chaos.jpg"}' .
	', {"id": "14", "name": "Black Knight", "type": "blackknight", "health": "2", "escape_penalty": "2", "combat": "0", "wait": "0", "escape": "0", "penalty_dice": "0", "penalty": "0", "image_url": "chamber_monster_champion_of_chaos.jpg"}' .
	', {"id": "13", "name": "Black Knight", "type": "blackknight", "health": "2", "escape_penalty": "1", "combat": "2", "wait": "3", "escape": "8", "penalty_dice": "1", "penalty": "0", "image_url": "chamber_monster_champion_of_chaos.jpg"}' .
	', {"id": "14", "name": "Black Knight", "type": "blackknight", "health": "2", "escape_penalty": "2", "combat": "5", "wait": "6", "escape": "0", "penalty_dice": "0", "penalty": "0", "image_url": "chamber_monster_champion_of_chaos.jpg"}' .
	', {"id": "14", "name": "Black Knight", "type": "blackknight", "health": "2", "escape_penalty": "2", "combat": "3", "wait": "4", "escape": "6", "penalty_dice": "1", "penalty": "2", "image_url": "chamber_monster_champion_of_chaos.jpg"}' .
	']';
$MonsterJSON = json_decode($MonsterJSON_string);

$DoorJSON_string = '[' .
	'  {"id": "1", "name": "Door Opens", "type": "dooropen", "value": "1", "image_url": "", "Description": ""}' .
	', {"id": "2", "name": "Door Opens", "type": "dooropen", "value": "1", "image_url": "", "Description": ""}' .
	', {"id": "3", "name": "Door Opens", "type": "dooropen", "value": "1", "image_url": "", "Description": ""}' .
	', {"id": "4", "name": "Door Opens", "type": "dooropen", "value": "1", "image_url": "", "Description": ""}' .
	', {"id": "5", "name": "Door Opens", "type": "dooropen", "value": "1", "image_url": "", "Description": ""}' .
	', {"id": "6", "name": "Door Jammed", "type": "doorjammed", "value": "2", "image_url": "", "Description": ""}' .
	', {"id": "7", "name": "Door Jammed", "type": "doorjammed", "value": "2", "image_url": "", "Description": ""}' .
	', {"id": "8", "name": "Door Jammed", "type": "doorjammed", "value": "3", "image_url": "", "Description": ""}' .
	', {"id": "9", "name": "Door Jammed", "type": "doorjammed", "value": "3", "image_url": "", "Description": ""}' .
	', {"id": "10", "name": "Door Jammed", "type": "doorjammed", "value": "3", "image_url": "", "Description": ""}' .
	', {"id": "11", "name": "Door Jammed", "type": "doorjammed", "value": "3", "image_url": "", "Description": ""}' .
	', {"id": "12", "name": "Trap Door", "type": "doortrap", "value": "3", "image_url": "", "Description": "Damage: D12 - Luck"}' .
	', {"id": "13", "name": "Trap Door", "type": "doortrap", "value": "3", "image_url": "", "Description": "Damage: D6"}' .
	', {"id": "14", "name": "Trap Door", "type": "doortrap", "value": "3", "image_url": "", "Description": "Damage: D6 . 3 - Luck"}' .
	']';			
$DoorJSON = json_decode($DoorJSON_string);

$CryptJSON_string = '[' .
	'  {"id": "1", "name": "Potion", "type": "finditem", "value": "0", "image_url": "", "Description": ""}' .
	', {"id": "2", "name": "Potion", "type": "finditem", "value": "0", "image_url": "", "Description": ""}' .
	', {"id": "3", "name": "Potion", "type": "finditem", "value": "0", "image_url": "", "Description": ""}' .
	', {"id": "4", "name": "Jeweled Dagger", "type": "finditem", "value": "250", "image_url": "", "Description": ""}' .
	', {"id": "5", "name": "Golden Guineas", "type": "finditem", "value": "50", "image_url": "", "Description": ""}' .
	', {"id": "6", "name": "Bracelet", "type": "finditem", "value": "120", "image_url": "", "Description": ""}' .
	', {"id": "7", "name": "Brooch", "type": "finditem", "value": "100", "image_url": "", "Description": ""}' .
	', {"id": "8", "name": "Empty", "type": "emptycrypt", "value": "0", "image_url": "", "Description": ""}' .
	', {"id": "9", "name": "Empty", "type": "emptycrypt", "value": "0", "image_url": "", "Description": ""}' .
	', {"id": "10", "name": "Empty", "type": "emptycrypt", "value": "0", "image_url": "", "Description": ""}' .
	', {"id": "11", "name": "Empty", "type": "emptycrypt", "value": "0", "image_url": "", "Description": ""}' .
	', {"id": "12", "name": "Trap", "type": "crypttrap", "value": "0", "image_url": "", "Description": "Damage: D6-3"}' .
	', {"id": "13", "name": "Trap", "type": "crypttrap", "value": "0", "image_url": "", "Description": "Damage: D12 -3 -Luck"}' .
	', {"id": "14", "name": "Skeleton", "type": "skeleton", "value": "0", "image_url": "", "Description": ""}' .
	']';
$CryptJSON = json_decode($CryptJSON_string);

$CorpseJSON_string = '[' .
	' {"id": "1", "name": "Rope", "type": "finditem", "value": "0", "image_url": "", "Description": ""}' .
	', {"id": "2", "name": "Rope", "type": "finditem", "value": "0", "image_url": "", "Description": ""}' .
	', {"id": "3", "name": "Rope", "type": "finditem", "value": "0", "image_url": "", "Description": ""}' .
	', {"id": "4", "name": "Potion", "type": "finditem", "value": "0", "image_url": "", "Description": ""}' .
	', {"id": "5", "name": "Empty", "type": "emptycorpse", "value": "0", "image_url": "", "Description": ""}' .
	', {"id": "6", "name": "Empty", "type": "emptycorpse", "value": "0", "image_url": "", "Description": ""}' .
	', {"id": "7", "name": "Empty", "type": "emptycorpse", "value": "0", "image_url": "", "Description": ""}' .
	', {"id": "8", "name": "Empty", "type": "emptycorpse", "value": "0", "image_url": "", "Description": ""}' .
	', {"id": "9", "name": "Empty", "type": "emptycorpse", "value": "0", "image_url": "", "Description": ""}' .
	', {"id": "10", "name": "Necklace", "type": "finditem", "value": "150", "image_url": "", "Description": ""}' .
	', {"id": "11", "name": "Golden Guineas", "type": "finditem", "value": "60", "image_url": "", "Description": ""}' .
	', {"id": "12", "name": "Golden Guineas", "type": "finditem", "value": "20", "image_url": "", "Description": ""}' .
	', {"id": "13", "name": "Scorpion", "type": "scorpion", "value": "0", "image_url": "", "Description": "Damage: D6"}' .
	', {"id": "14", "name": "Scorpion", "type": "scorpion", "value": "0", "image_url": "", "Description": "Damage: D6-2"}' .
	']';
$CorpseJSON = json_decode($CorpseJSON_string);

$SearchJSON_string = '[' .
	'  {"id": "1", "name": "Secret Door", "type":"secretdoor", "value": "0", "image_url": "", "Description": ""}' .
	', {"id": "2", "name": "Secret Door", "type":"secretdoor", "value": "0", "image_url": "", "Description": ""}' .
	', {"id": "3", "name": "Secret Door", "type":"secretdoor", "value": "0", "image_url": "", "Description": ""}' .
	', {"id": "4", "name": "Secret Door", "type":"secretdoor", "value": "0", "image_url": "", "Description": ""}' .
	', {"id": "5", "name": "Secret Door", "type":"secretdoor", "value": "0", "image_url": "", "Description": ""}' .
	', {"id": "6", "name": "Trap", "type":"trap", "value": "0", "image_url": "", "Description": "Take a Trap card"}' .
	', {"id": "7", "name": "Ring", "type":"finditem", "value": "90", "image_url": "", "Description": ""}' .
	', {"id": "8", "name": "Jewellery", "type":"finditem", "value": "200", "image_url": "", "Description": ""}' .
	', {"id": "9", "name": "Potion", "type":"finditem", "value": "0", "image_url": "", "Description": ""}' .
	', {"id": "10", "name": "Golden Guineas", "type":"finditem", "value": "10", "image_url": "", "Description": ""}' .
	', {"id": "11", "name": "Empty Room", "type":"emptyroom", "value": "0", "image_url": "", "Description": ""}' .
	', {"id": "12", "name": "Empty Room", "type":"emptyroom", "value": "0", "image_url": "", "Description": ""}' .
	', {"id": "13", "name": "Empty Room", "type":"emptyroom", "value": "0", "image_url": "", "Description": ""}' .
	', {"id": "14", "name": "Giant Centipede", "type":"centipede", "value": "0", "image_url": "", "Description": "Damage: D12"}' .
	']';
$SearchJSON = json_decode($SearchJSON_string);

$TrapsJSON_string = '[' .
	'  {"id": "1", "name": "Trap Door", "type": "trapdoor", "image_url": "", "description": ""}' .
	', {"id": "2", "name": "Trap Door", "type": "trapdoor", "image_url": "", "description": ""}' .
	', {"id": "3", "name": "Trap Door", "type": "trapdoor", "image_url": "", "description": ""}' .
	', {"id": "4", "name": "Trap Door", "type": "trapdoor", "image_url": "", "description": ""}' .
	', {"id": "5", "name": "Poisonous Snakes", "type": "snakes", "image_url": "", "description": "Damage: D6"}' .
	', {"id": "6", "name": "Poisonous Snakes", "type": "snakes", "image_url": "", "description": "Damage: D6"}' .
	', {"id": "7", "name": "Poisonous Gas", "type": "gas", "image_url": "", "description": "Damage: D6-3 HP and D6-3 turns"}' .
	', {"id": "8", "name": "Poisonous Gas", "type": "gas", "image_url": "", "description": "Damage: D6-3 HP and D6-3 turns"}' .
	', {"id": "9", "name": "Cave-In", "type": "collapse", "image_url": "", "description": ""}' .
	', {"id": "10", "name": "Cave-In", "type": "collapse", "image_url": "", "description": ""}' .
	', {"id": "11", "name": "Explosion", "type": "explosion", "image_url": "", "description": "Damage: 4HP and miss a turn"}' .
	', {"id": "12", "name": "Explosion", "type": "explosion", "image_url": "", "description": "Damage: 4HP and miss a turn"}' .
	', {"id": "7", "name": "Crossfire Trap", "type": "crossfire", "image_url": "", "description": "Damage: D12-Armor"}' .
	', {"id": "7", "name": "Crossfire Trap", "type": "crossfire", "image_url": "", "description": "Damage: D12-Armor"}' .
	']';
$TrapsJSON = json_decode($TrapsJSON_string);


$DragonJSON_string = '[' .
	' {"id": "1", "name": "Sleeping", "type": "sleeping", "awake": "0", "image_url": "", "description": ""}' .
	', {"id": "2", "name": "Sleeping", "type": "sleeping", "awake": "0", "image_url": "", "description": ""}' .
	', {"id": "3", "name": "Sleeping", "type": "sleeping", "awake": "0", "image_url": "", "description": ""}' .
	', {"id": "4", "name": "Sleeping", "type": "sleeping", "awake": "0", "image_url": "", "description": ""}' .
	', {"id": "5", "name": "Sleeping", "type": "sleeping", "awake": "0", "image_url": "", "description": ""}' .
	', {"id": "6", "name": "Sleeping", "type": "sleeping", "awake": "0", "image_url": "", "description": ""}' .
	', {"id": "7", "name": "Sleeping", "type": "sleeping", "awake": "0", "image_url": "", "description": ""}' .
	', {"id": "8", "name": "Dragon Rage", "type": "awake", "awake": "1", "image_url": "", "description": ""}' .
	']';
$DragonJSON = json_decode($DragonJSON_string);

$TreasureJSON_string = '[' .
	' {"id": "1", "name": "Golden Guineas", "value": "100", "image_url": "", "description": ""}' .
	', {"id": "2", "name": "Golden Guineas", "value": "110", "image_url": "", "description": ""}' .
	', {"id": "3", "name": "Golden Guineas", "value": "120", "image_url": "", "description": ""}' .
	', {"id": "4", "name": "Golden Guineas", "value": "130", "image_url": "", "description": ""}' .
	', {"id": "5", "name": "Golden Guineas", "value": "140", "image_url": "", "description": ""}' .
	', {"id": "6", "name": "Golden Guineas", "value": "150", "image_url": "", "description": ""}' .
	', {"id": "7", "name": "Golden Guineas", "value": "160", "image_url": "", "description": ""}' .
	', {"id": "8", "name": "Golden Guineas", "value": "170", "image_url": "", "description": ""}' .
	', {"id": "9", "name": "Golden Guineas", "value": "180", "image_url": "", "description": ""}' .
	', {"id": "10", "name": "Golden Guineas", "value": "190", "image_url": "", "description": ""}' .
	', {"id": "11", "name": "Golden Guineas", "value": "200", "image_url": "", "description": ""}' .
	', {"id": "12", "name": "Golden Guineas", "value": "200", "image_url": "", "description": ""}' .
	', {"id": "13", "name": "Golden Guineas", "value": "220", "image_url": "", "description": ""}' .
	', {"id": "14", "name": "Golden Guineas", "value": "230", "image_url": "", "description": ""}' .
	', {"id": "15", "name": "Golden Guineas", "value": "240", "image_url": "", "description": ""}' .
	', {"id": "16", "name": "Golden Guineas", "value": "250", "image_url": "", "description": ""}' .
	', {"id": "17", "name": "Golden Guineas", "value": "260", "image_url": "", "description": ""}' .
	', {"id": "18", "name": "Golden Guineas", "value": "270", "image_url": "", "description": ""}' .
	', {"id": "19", "name": "Golden Guineas", "value": "280", "image_url": "", "description": ""}' .
	', {"id": "20", "name": "Golden Guineas", "value": "300", "image_url": "", "description": ""}' .
	', {"id": "21", "name": "Golden Crown", "value": "4000", "image_url": "", "description": ""}' .
	', {"id": "22", "name": "Gold Necklace", "value": "500", "image_url": "", "description": ""}' .
	', {"id": "23", "name": "Gold Necklace", "value": "700", "image_url": "", "description": ""}' .
	', {"id": "24", "name": "Golden Chalice", "value": "2000", "image_url": "", "description": ""}' .
	', {"id": "25", "name": "Jeweled Bracelet", "value": "800", "image_url": "", "description": ""}' .
	', {"id": "26", "name": "Pearl Necklace", "value": "1800", "image_url": "", "description": ""}' .
	', {"id": "27", "name": "Golden Mug", "value": "1500", "image_url": "", "description": ""}' .
	', {"id": "28", "name": "Scepter", "value": "1100", "image_url": "", "description": ""}' .
	', {"id": "29", "name": "Giant Ruby", "value": "4500", "image_url": "", "description": ""}' .
	', {"id": "30", "name": "Magic Crystal Ball", "value": "2200", "image_url": "", "description": ""}' .
	', {"id": "31", "name": "Treasure Sack", "value": "3800", "image_url": "", "description": ""}' .
	', {"id": "32", "name": "Treasure Sack", "value": "3200", "image_url": "", "description": ""}' .
	']';
$TreasureJSON = json_decode($TreasureJSON_string);

// echo "<br/>CharactersJSON: ".count($CharactersJSON);
// echo "<br/>ChamberJSON: ".count($ChamberJSON);
// echo "<br/>ChamberEventJSON: ".count($ChamberEventJSON);
// echo "<br/>CatacombsJSON: ".count($CatacombsJSON);
// echo "<br/>MonsterJSON: ".count($MonsterJSON);
// echo "<br/>DoorJSON: ".count($DoorJSON);
// echo "<br/>CryptJSON: ".count($CryptJSON);
// echo "<br/>CorpseJSON: ".count($CorpseJSON);
// echo "<br/>SearchJSON: ".count($SearchJSON);
// echo "<br/>TrapsJSON: ".count($TrapsJSON);
// echo "<br/>DragonJSON: ".count($DragonJSON);
// echo "<br/>TreasureJSON: ".count($TreasureJSON);


// echo rand(0, count($CharactersJSON)-1);

// echo json_encode($CharactersJSON[1]);


$type = $_GET["type"];
$random_draw = "";

switch ($type) {
	case "chamber":
		$random_draw = $ChamberJSON[rand(0, count($ChamberJSON)-1)];
		echo json_encode($random_draw);
		break;
	case "chamber_event":
		$random_draw = $ChamberEventJSON[rand(0, count($ChamberEventJSON)-1)];
		echo json_encode($random_draw);
		break;
	case "catacombs":
		$random_draw = $CatacombsJSON[rand(0, count($CatacombsJSON)-1)];
		echo json_encode($random_draw);
		break;
	case "monster":
		$random_draw = $MonsterJSON[rand(0, count($MonsterJSON)-1)];
		echo json_encode($random_draw);
		break;
	case "door":
		$random_draw = $DoorJSON[rand(0, count($DoorJSON)-1)];
		echo json_encode($random_draw);
		break;
	case "crypt":
		$random_draw = $CryptJSON[rand(0, count($CryptJSON)-1)];
		echo json_encode($random_draw);
		break;
	case "corpse":
		$random_draw = $CorpseJSON[rand(0, count($CorpseJSON)-1)];
		echo json_encode($random_draw);
		break;
	case "search":
		$random_draw = $SearchJSON[rand(0, count($SearchJSON)-1)];
		echo json_encode($random_draw);
		break;
	case "trap":
		$random_draw = $TrapsJSON[rand(0, count($TrapsJSON)-1)];
		echo json_encode($random_draw);
		break;
	case "dragon":
		$random_draw = $DragonJSON[rand(0, count($DragonJSON)-1)];
		echo json_encode($random_draw);
		break;
	case "treasure":
		$random_draw = $TreasureJSON[rand(0, count($TreasureJSON)-1)];
		echo json_encode($random_draw);
		break;
	default:
		break;
}


?>
