
var CharactersJSON_string = '' +
	'[{"name": "Sir Rohan", "name_short": "Sir Rohan", "health":"17", "strength":"6", "agility":"4", "defense":"9", "luck":"4", "image_url": "sir_rohan.jpg", "image_url_2": "sir_rohan_model.jpg", "description": ""}' +
	', {"name": "Ulv Grimhand", "name_short": "Ulv Grimhand", "health":"16", "strength":"7", "agility":"5", "defense":"6", "luck":"5", "image_url": "ulv_grimhand.jpg", "image_url_2": "ulv_grimhand_model.jpg", "description": ""}' +
	', {"name": "El-Adoran Sureshot", "name_short": "El-Adoran", "health":"11", "strength":"3", "agility":"8", "defense":"5", "luck":"7", "image_url": "el_adoran_sureshot.jpg", "image_url_2": "el_adoran_sureshot_model.jpg", "description": ""}' +
	', {"name": "Volrik the Brave", "name_short": "Volrik", "health":"15", "strength":"4", "agility":"7", "defense":"4", "luck":"8", "image_url": "volrik_the_brave.jpg", "image_url_2": "volrik_the_brave_model.jpg", "description": ""}' +
	// ', {"name": "Azoth the Faceless", "name_short": "Azoth", "health":"14", "strength":"1", "agility":"4", "defense":"3", "luck":"6", "image_url": "azoth_the_faceless.jpg", "image_url_2": "azoth_the_faceless_model.jpg", "description": ""}' +
	// ', {"name": "Helena the Swift", "name_short": "Helena", "health":"12", "strength":"4", "agility":"8", "defense":"5", "luck":"6", "image_url": "helena_the_swift.jpg", "image_url_2": "helena_the_swift_model.jpg", "description": ""}' +
	// ', {"name": "Serellia of Zimendell", "name_short": "Serellia", "health":"6", "strength":"2", "agility":"6", "defense":"3", "luck":"9", "image_url": "serellia_of_zimendell.jpg", "image_url_2": "serellia_of_zimendell_model.jpg", "description": ""}' +
	// ', {"name": "Ironhand the Mighty", "name_short": "Ironhand", "health":"19", "strength":"6", "agility":"6", "defense":"6", "luck":"5", "image_url": "ironhand_the_mighty.jpg", "image_url_2": "ironhand_the_mighty_model.jpg", "description": ""}' +
	// ', {"name": "Rildo the Crafty", "name_short": "Rildo", "health":"10", "strength":"3", "agility":"9", "defense":"4", "luck":"7", "dagger":"4", "image_url": "rildo_the_crafty.jpg", "image_url_2": "rildo_the_crafty_model.jpg", "description": ""}' +
	// ', {"name": "Tori-Jima", "name_short": "Tori-Jima", "health":"10", "strength":"4", "agility":"10", "defense":"4", "luck":"5", "shuriken":"4", "image_url": "tori_jima.jpg", "image_url_2": "tori_jima_model.jpg", "description": ""}' +
	// ', {"name": "Farendil", "name_short": "Farendil", "health":"10", "strength":"3", "agility":"8", "defense":"5", "luck":"7", "arrow":"4", "image_url": "farendil.jpg", "image_url_2": "farendil_model.jpg", "description": ""}' +
	// ', {"name": "Siegfried Goldenhair", "name_short": "Siegfried", "health":"16", "strength":"7", "agility":"5", "defense":"6", "luck":"5", "image_url": "siegfried_goldenhair.jpg", "image_url_2": "siegfried_goldenhair_model.jpg", "description": ""}' +
	// ', {"name": "Sir Roland", "name_short": "Sir Roland", "health":"17", "strength":"6", "agility":"4", "defense":"9", "luck":"4", "image_url": "sir_roland.jpg", "image_url_2": "sir_roland_model.jpg", "description": ""}' +
	// ', {"name": "Vikas Swordmaster", "name_short": "Vikas", "health":"15", "strength":"4", "agility":"7", "defense":"4", "luck":"8", "image_url": "vikas_swordmaster.jpg", "image_url_2": "vikas_swordmaster_model.jpg", "description": ""}' +
	// ', {"name": "Thargrim the Dark Lord", "name_short": "Thargrim", "health":"13", "strength":"7", "agility":"4", "defense":"8", "luck":"5", "image_url": "thargrim_the_dark_lord.jpg", "image_url_2": "thargrim_the_dark_lord_model.jpg", "description": ""}' +
	// ', {"name": "Fhyll Madaxe", "name_short": "Fhyll", "health":"16", "strength":"8", "agility":"5", "defense":"6", "luck":"4", "image_url": "fhyll_madaxe.jpg", "image_url_2": "fhyll_madaxe_model.jpg", "description": ""}' +
	']';


var CharactersJSON = JSON.parse(CharactersJSON_string);

var ChamberJSON_string = '{' + 

	// All Tiles for 2nd Edition + Catacombs

	// Chambers
	  '"1": { "paperid": "1", "top": "1", "right": "1", "bottom": "1", "left": "1", "type": "chamber", "image_url": "1.jpg", "description": "Empty Chamber"}' +
	', "2": { "paperid": "1", "top": "1", "right": "1", "bottom": "1", "left": "1", "type": "chamber", "image_url": "1a.jpg", "description": "Empty Chamber"}' +
	', "3": { "paperid": "1", "top": "1", "right": "1", "bottom": "1", "left": "1", "type": "chamber", "image_url": "1b.jpg", "description": "Empty Chamber"}' +
	', "4": { "paperid": "1", "top": "1", "right": "1", "bottom": "1", "left": "1", "type": "chamber", "image_url": "1c.jpg", "description": "Empty Chamber"}' +
	', "5": { "paperid": "1", "top": "1", "right": "1", "bottom": "1", "left": "1", "type": "chamber", "image_url": "1d.jpg", "description": "Empty Chamber"}' +
	', "6": { "paperid": "1", "top": "1", "right": "1", "bottom": "1", "left": "1", "type": "chamber", "image_url": "1e.jpg", "description": "Empty Chamber"}' +
	', "7": { "paperid": "1", "top": "1", "right": "1", "bottom": "1", "left": "1", "type": "chamber", "image_url": "1f.jpg", "description": "Empty Chamber"}' +
	', "8": { "paperid": "1", "top": "1", "right": "1", "bottom": "1", "left": "1", "type": "chamber", "image_url": "1g.jpg", "description": "Empty Chamber"}' +
	', "9": { "paperid": "1", "top": "1", "right": "1", "bottom": "1", "left": "1", "type": "chamber", "image_url": "1h.jpg", "description": "Empty Chamber"}' +
	', "10": { "paperid": "1", "top": "1", "right": "1", "bottom": "1", "left": "1", "type": "chamber", "image_url": "1i.jpg", "description": "Empty Chamber"}' +
	', "11": { "paperid": "1", "top": "1", "right": "1", "bottom": "1", "left": "1", "type": "chamber", "image_url": "1j.jpg", "description": "Empty Chamber"}' +
	', "12": { "paperid": "1", "top": "1", "right": "1", "bottom": "1", "left": "1", "type": "chamber", "image_url": "1k.jpg", "description": "Empty Chamber"}' +
	', "13": { "paperid": "1", "top": "1", "right": "1", "bottom": "1", "left": "1", "type": "chamber", "image_url": "1l.jpg", "description": "Empty Chamber"}' +
	', "14": { "paperid": "1", "top": "1", "right": "1", "bottom": "1", "left": "1", "type": "chamber", "image_url": "1m.jpg", "description": "Empty Chamber"}' +
	', "15": { "paperid": "1", "top": "1", "right": "1", "bottom": "1", "left": "1", "type": "chamber", "image_url": "1n.jpg", "description": "Empty Chamber"}' +
	', "16": { "paperid": "2", "top": "1", "right": "2", "bottom": "1", "left": "1", "type": "chamber", "image_url": "2.jpg", "description": "Empty Chamber"}' +
	', "17": { "paperid": "2", "top": "1", "right": "2", "bottom": "1", "left": "1", "type": "chamber", "image_url": "2a.jpg", "description": "Empty Chamber"}' +
	', "18": { "paperid": "2", "top": "1", "right": "2", "bottom": "1", "left": "1", "type": "chamber", "image_url": "2b.jpg", "description": "Empty Chamber"}' +
	', "19": { "paperid": "2", "top": "1", "right": "2", "bottom": "1", "left": "1", "type": "chamber", "image_url": "2c.jpg", "description": "Empty Chamber"}' +
	', "20": { "paperid": "2", "top": "1", "right": "2", "bottom": "1", "left": "1", "type": "chamber", "image_url": "2d.jpg", "description": "Empty Chamber"}' +
	', "21": { "paperid": "2", "top": "1", "right": "2", "bottom": "1", "left": "1", "type": "chamber", "image_url": "2e.jpg", "description": "Empty Chamber"}' +
	', "22": { "paperid": "2", "top": "1", "right": "2", "bottom": "1", "left": "1", "type": "chamber", "image_url": "2f.jpg", "description": "Empty Chamber"}' +
	', "23": { "paperid": "2", "top": "1", "right": "2", "bottom": "1", "left": "1", "type": "chamber", "image_url": "2g.jpg", "description": "Empty Chamber"}' +
	', "135": { "paperid": "3", "top": "1", "right": "1", "bottom": "1", "left": "2", "type": "chamber", "image_url": "3.jpg", "description": "Empty Chamber"}' +
	', "24": { "paperid": "3", "top": "1", "right": "1", "bottom": "1", "left": "2", "type": "chamber", "image_url": "3a.jpg", "description": "Empty Chamber"}' +
	', "25": { "paperid": "3", "top": "1", "right": "1", "bottom": "1", "left": "2", "type": "chamber", "image_url": "3b.jpg", "description": "Empty Chamber"}' +
	', "26": { "paperid": "3", "top": "1", "right": "1", "bottom": "1", "left": "2", "type": "chamber", "image_url": "3c.jpg", "description": "Empty Chamber"}' +
	', "27": { "paperid": "3", "top": "1", "right": "1", "bottom": "1", "left": "2", "type": "chamber", "image_url": "3d.jpg", "description": "Empty Chamber"}' +
	', "28": { "paperid": "3", "top": "1", "right": "1", "bottom": "1", "left": "2", "type": "chamber", "image_url": "3e.jpg", "description": "Empty Chamber"}' +
	', "29": { "paperid": "3", "top": "1", "right": "1", "bottom": "1", "left": "2", "type": "chamber", "image_url": "3f.jpg", "description": "Empty Chamber"}' +
	', "30": { "paperid": "3", "top": "1", "right": "1", "bottom": "1", "left": "2", "type": "chamber", "image_url": "3g.jpg", "description": "Empty Chamber"}' +
	', "31": { "paperid": "4", "top": "2", "right": "1", "bottom": "1", "left": "1", "type": "chamber", "image_url": "4.jpg", "description": "Empty Chamber"}' +
	', "32": { "paperid": "4", "top": "2", "right": "1", "bottom": "1", "left": "1", "type": "chamber", "image_url": "4a.jpg", "description": "Empty Chamber"}' +
	', "33": { "paperid": "4", "top": "2", "right": "1", "bottom": "1", "left": "1", "type": "chamber", "image_url": "4b.jpg", "description": "Empty Chamber"}' +
	', "34": { "paperid": "4", "top": "2", "right": "1", "bottom": "1", "left": "1", "type": "chamber", "image_url": "4c.jpg", "description": "Empty Chamber"}' +
	', "35": { "paperid": "4", "top": "2", "right": "1", "bottom": "1", "left": "1", "type": "chamber", "image_url": "4d.jpg", "description": "Empty Chamber"}' +
	', "36": { "paperid": "4", "top": "2", "right": "1", "bottom": "1", "left": "1", "type": "chamber", "image_url": "4e.jpg", "description": "Empty Chamber"}' +
	', "37": { "paperid": "4", "top": "2", "right": "1", "bottom": "1", "left": "1", "type": "chamber", "image_url": "4f.jpg", "description": "Empty Chamber"}' +
	', "38": { "paperid": "4", "top": "2", "right": "1", "bottom": "1", "left": "1", "type": "chamber", "image_url": "4g.jpg", "description": "Empty Chamber"}' +
	', "39": { "paperid": "5", "top": "1", "right": "2", "bottom": "1", "left": "2", "type": "chamber", "image_url": "5.jpg", "description": "Empty Chamber"}' +
	', "40": { "paperid": "5", "top": "1", "right": "2", "bottom": "1", "left": "2", "type": "chamber", "image_url": "5a.jpg", "description": "Empty Chamber"}' +
	', "41": { "paperid": "5", "top": "1", "right": "2", "bottom": "1", "left": "2", "type": "chamber", "image_url": "5b.jpg", "description": "Empty Chamber"}' +
	', "42": { "paperid": "5", "top": "1", "right": "2", "bottom": "1", "left": "2", "type": "chamber", "image_url": "5c.jpg", "description": "Empty Chamber"}' +
	', "43": { "paperid": "5", "top": "1", "right": "2", "bottom": "1", "left": "2", "type": "chamber", "image_url": "5d.jpg", "description": "Empty Chamber"}' +
	', "44": { "paperid": "5", "top": "1", "right": "2", "bottom": "1", "left": "2", "type": "chamber", "image_url": "5e.jpg", "description": "Empty Chamber"}' +
	', "45": { "paperid": "5", "top": "1", "right": "2", "bottom": "1", "left": "2", "type": "chamber", "image_url": "5f.jpg", "description": "Empty Chamber"}' +
	', "46": { "paperid": "5", "top": "1", "right": "2", "bottom": "1", "left": "2", "type": "chamber", "image_url": "5g.jpg", "description": "Empty Chamber"}' +
	', "47": { "paperid": "6", "top": "2", "right": "2", "bottom": "1", "left": "1", "type": "chamber", "image_url": "6.jpg", "description": "Empty Chamber"}' +
	', "48": { "paperid": "6", "top": "2", "right": "2", "bottom": "1", "left": "1", "type": "chamber", "image_url": "6a.jpg", "description": "Empty Chamber"}' +
	', "49": { "paperid": "6", "top": "2", "right": "2", "bottom": "1", "left": "1", "type": "chamber", "image_url": "6b.jpg", "description": "Empty Chamber"}' +
	', "50": { "paperid": "6", "top": "2", "right": "2", "bottom": "1", "left": "1", "type": "chamber", "image_url": "6c.jpg", "description": "Empty Chamber"}' +
	', "51": { "paperid": "6", "top": "2", "right": "2", "bottom": "1", "left": "1", "type": "chamber", "image_url": "6d.jpg", "description": "Empty Chamber"}' +
	', "52": { "paperid": "6", "top": "2", "right": "2", "bottom": "1", "left": "1", "type": "chamber", "image_url": "6e.jpg", "description": "Empty Chamber"}' +
	', "53": { "paperid": "6", "top": "2", "right": "2", "bottom": "1", "left": "1", "type": "chamber", "image_url": "6f.jpg", "description": "Empty Chamber"}' +
	', "54": { "paperid": "7", "top": "2", "right": "1", "bottom": "1", "left": "2", "type": "chamber", "image_url": "7.jpg", "description": "Empty Chamber"}' +
	', "55": { "paperid": "7", "top": "2", "right": "1", "bottom": "1", "left": "2", "type": "chamber", "image_url": "7a.jpg", "description": "Empty Chamber"}' +
	', "56": { "paperid": "7", "top": "2", "right": "1", "bottom": "1", "left": "2", "type": "chamber", "image_url": "7b.jpg", "description": "Empty Chamber"}' +
	', "57": { "paperid": "7", "top": "2", "right": "1", "bottom": "1", "left": "2", "type": "chamber", "image_url": "7c.jpg", "description": "Empty Chamber"}' +
	', "58": { "paperid": "7", "top": "2", "right": "1", "bottom": "1", "left": "2", "type": "chamber", "image_url": "7d.jpg", "description": "Empty Chamber"}' +
	', "59": { "paperid": "7", "top": "2", "right": "2", "bottom": "1", "left": "2", "type": "chamber", "image_url": "8.jpg", "description": "Empty Chamber"}' +
	', "60": { "paperid": "7", "top": "2", "right": "2", "bottom": "1", "left": "2", "type": "chamber", "image_url": "8a.jpg", "description": "Empty Chamber"}' +
	', "61": { "paperid": "7", "top": "2", "right": "2", "bottom": "1", "left": "2", "type": "chamber", "image_url": "8b.jpg", "description": "Empty Chamber"}' +
	', "62": { "paperid": "7", "top": "2", "right": "2", "bottom": "1", "left": "2", "type": "chamber", "image_url": "8c.jpg", "description": "Empty Chamber"}' +
	', "63": { "paperid": "7", "top": "2", "right": "2", "bottom": "1", "left": "2", "type": "chamber", "image_url": "8d.jpg", "description": "Empty Chamber"}' +

	// Corridors
	', "64": { "paperid": "11", "top": "1", "right": "1", "bottom": "1", "left": "1", "type": "corridor", "image_url": "11.jpg", "description": "Narrow Corridor"}' +
	', "65": { "paperid": "11", "top": "1", "right": "1", "bottom": "1", "left": "1", "type": "corridor", "image_url": "11a.jpg", "description": "Narrow Corridor"}' +
	', "66": { "paperid": "12", "top": "1", "right": "2", "bottom": "1", "left": "1", "type": "corridor", "image_url": "12.jpg", "description": "Narrow Corridor"}' +
	', "67": { "paperid": "12", "top": "1", "right": "2", "bottom": "1", "left": "1", "type": "corridor", "image_url": "12a.jpg", "description": "Narrow Corridor"}' +
	', "68": { "paperid": "12", "top": "1", "right": "2", "bottom": "1", "left": "1", "type": "corridor", "image_url": "12b.jpg", "description": "Narrow Corridor"}' +
	', "69": { "paperid": "13", "top": "1", "right": "1", "bottom": "1", "left": "2", "type": "corridor", "image_url": "13.jpg", "description": "Narrow Corridor"}' +
	', "70": { "paperid": "13", "top": "1", "right": "1", "bottom": "1", "left": "2", "type": "corridor", "image_url": "13a.jpg", "description": "Narrow Corridor"}' +
	', "71": { "paperid": "13", "top": "1", "right": "1", "bottom": "1", "left": "2", "type": "corridor", "image_url": "13b.jpg", "description": "Narrow Corridor"}' +
	', "72": { "paperid": "14", "top": "2", "right": "1", "bottom": "1", "left": "1", "type": "corridor", "image_url": "14.jpg", "description": "Narrow Corridor"}' +
	', "73": { "paperid": "14", "top": "2", "right": "1", "bottom": "1", "left": "1", "type": "corridor", "image_url": "14a.jpg", "description": "Narrow Corridor"}' +
	', "74": { "paperid": "15", "top": "1", "right": "2", "bottom": "1", "left": "2", "type": "corridor", "image_url": "15.jpg", "description": "Narrow Corridor"}' +
	', "75": { "paperid": "15", "top": "1", "right": "2", "bottom": "1", "left": "2", "type": "corridor", "image_url": "15a.jpg", "description": "Narrow Corridor"}' +
	', "76": { "paperid": "16", "top": "2", "right": "2", "bottom": "1", "left": "1", "type": "corridor", "image_url": "16.jpg", "description": "Narrow Corridor"}' +
	', "77": { "paperid": "16", "top": "2", "right": "2", "bottom": "1", "left": "1", "type": "corridor", "image_url": "16a.jpg", "description": "Narrow Corridor"}' +
	', "78": { "paperid": "17", "top": "2", "right": "1", "bottom": "1", "left": "2", "type": "corridor", "image_url": "17.jpg", "description": "Narrow Corridor"}' +
	', "79": { "paperid": "17", "top": "2", "right": "1", "bottom": "1", "left": "2", "type": "corridor", "image_url": "17a.jpg", "description": "Narrow Corridor"}' +
	
	// Doors
	', "80": { "paperid": "21", "top": "3", "right": "1", "bottom": "1", "left": "1", "type": "door", "image_url": "21.jpg", "description": "Chamber with Door"}' +
	', "81": { "paperid": "21", "top": "3", "right": "1", "bottom": "1", "left": "1", "type": "door", "image_url": "21a.jpg", "description": "Chamber with Door"}' +
	', "82": { "paperid": "21", "top": "3", "right": "1", "bottom": "1", "left": "1", "type": "door", "image_url": "21b.jpg", "description": "Chamber with Door"}' +
	', "83": { "paperid": "21", "top": "3", "right": "1", "bottom": "1", "left": "1", "type": "door", "image_url": "21c.jpg", "description": "Chamber with Door"}' +
	', "84": { "paperid": "22", "top": "3", "right": "2", "bottom": "1", "left": "1", "type": "door", "image_url": "22.jpg", "description": "Chamber with Door"}' +
	', "85": { "paperid": "22", "top": "3", "right": "2", "bottom": "1", "left": "1", "type": "door", "image_url": "22a.jpg", "description": "Chamber with Door"}' +
	', "86": { "paperid": "23", "top": "3", "right": "1", "bottom": "1", "left": "2", "type": "door", "image_url": "23.jpg", "description": "Chamber with Door"}' +
	', "87": { "paperid": "23", "top": "3", "right": "1", "bottom": "1", "left": "2", "type": "door", "image_url": "23a.jpg", "description": "Chamber with Door"}' +
	', "88": { "paperid": "46", "top": "3", "right": "3", "bottom": "1", "left": "3", "type": "door", "image_url": "46.jpg", "description": "Chamber with Doors"}' +
	
	// Challenges/Abnormals
	', "89": { "paperid": "51", "top": "4", "right": "2", "bottom": "4", "left": "2", "type": "bridge", "image_url": "51.jpg", "description": "Chamber with Bridge"}' +
	', "90": { "paperid": "51", "top": "4", "right": "2", "bottom": "4", "left": "2", "type": "bridge", "image_url": "51a.jpg", "description": "Chamber with Bridge"}' +
	', "91": { "paperid": "51", "top": "4", "right": "2", "bottom": "4", "left": "2", "type": "bridge", "image_url": "51b.jpg", "description": "Chamber with Bridge"}' +
	', "92": { "paperid": "51", "top": "4", "right": "2", "bottom": "4", "left": "2", "type": "bridge", "image_url": "51c.jpg", "description": "Chamber with Bridge"}' +
	', "93": { "paperid": "52", "top": "6", "right": "2", "bottom": "6", "left": "2", "type": "pit", "image_url": "52.jpg", "description": "Chamber with a Pit"}' +
	', "94": { "paperid": "52", "top": "6", "right": "2", "bottom": "6", "left": "2", "type": "pit", "image_url": "52a.jpg", "description": "Chamber with a Pit"}' +
	', "95": { "paperid": "53", "top": "2", "right": "2", "bottom": "1", "left": "2", "type": "rotating", "image_url": "53.jpg", "description": "Rotating Chamber"}' +
	', "96": { "paperid": "53", "top": "2", "right": "2", "bottom": "1", "left": "2", "type": "rotating", "image_url": "53a.jpg", "description": "Rotating Chamber"}' +
	', "97": { "paperid": "53", "top": "2", "right": "2", "bottom": "1", "left": "2", "type": "rotating", "image_url": "53b.jpg", "description": "Rotating Chamber"}' +
	', "98": { "paperid": "53", "top": "2", "right": "2", "bottom": "1", "left": "2", "type": "rotating", "image_url": "53c.jpg", "description": "Rotating Chamber"}' +
	', "99": { "paperid": "53", "top": "2", "right": "2", "bottom": "1", "left": "2", "type": "rotating", "image_url": "53d.jpg", "description": "Rotating Chamber"}' +
	', "100": { "paperid": "54", "top": "1", "right": "1", "bottom": "1", "left": "1", "type": "leftchasm", "image_url": "54.jpg", "description": "Chamber with Chasm"}' +
	', "101": { "paperid": "55", "top": "1", "right": "1", "bottom": "1", "left": "1", "type": "rightchasm", "image_url": "55.jpg", "description": "Chamber with Chasm"}' +
	
	// Traps/Darkness/SpiderWebs/CaveIns
	', "102": { "paperid": "61", "top": "1", "right": "1", "bottom": "1", "left": "1", "type": "trap", "image_url": "61.jpg", "description": "Chamber with a Trap"}' +
	', "103": { "paperid": "61", "top": "1", "right": "1", "bottom": "1", "left": "1", "type": "trap", "image_url": "61a.jpg", "description": "Chamber with a Trap"}' +
	', "104": { "paperid": "61", "top": "1", "right": "1", "bottom": "1", "left": "1", "type": "trap", "image_url": "61b.jpg", "description": "Chamber with a Trap"}' +
	', "105": { "paperid": "62", "top": "1", "right": "2", "bottom": "1", "left": "1", "type": "trap", "image_url": "62.jpg", "description": "Chamber with a Trap"}' +
	', "106": { "paperid": "63", "top": "1", "right": "1", "bottom": "1", "left": "2", "type": "trap", "image_url": "63.jpg", "description": "Chamber with a Trap"}' +
	', "107": { "paperid": "64", "top": "2", "right": "9", "bottom": "9", "left": "9", "type": "darkness", "image_url": "64.jpg", "description": "Darkness"}' +
	', "108": { "paperid": "64", "top": "2", "right": "9", "bottom": "9", "left": "9", "type": "darkness", "image_url": "64a.jpg", "description": "Darkness"}' +
	', "109": { "paperid": "65", "top": "2", "right": "2", "bottom": "9", "left": "9", "type": "darkness", "image_url": "65.jpg", "description": "Darkness"}' +
	', "110": { "paperid": "66", "top": "2", "right": "9", "bottom": "9", "left": "2", "type": "darkness", "image_url": "66.jpg", "description": "Darkness"}' +
	', "111": { "paperid": "67", "top": "8", "right": "8", "bottom": "8", "left": "8", "type": "spiderweb", "image_url": "67.jpg", "description": "Giant Spider Web"}' +
	', "112": { "paperid": "68", "top": "7", "right": "2", "bottom": "7", "left": "2", "type": "rubble", "image_url": "68.jpg", "description": "Collapsed Chamber"}' +
	', "113": { "paperid": "68", "top": "7", "right": "2", "bottom": "7", "left": "2", "type": "rubble", "image_url": "68a.jpg", "description": "Collapsed Chamber"}' +
	', "114": { "paperid": "69", "top": "7", "right": "7", "bottom": "7", "left": "7", "type": "rubble", "image_url": "69.jpg", "description": "Collapsed Chamber"}' +
	', "115": { "paperid": "69", "top": "7", "right": "7", "bottom": "7", "left": "7", "type": "rubble", "image_url": "69a.jpg", "description": "Collapsed Chamber"}' +
	
	// Catacombs
	', "116": { "paperid": "71", "top": "1", "right": "2", "bottom": "1", "left": "1", "type": "catacombs", "image_url": "71.jpg", "description": "Chamber with Catacombs Entrance"}' +
	', "117": { "paperid": "72", "top": "2", "right": "2", "bottom": "1", "left": "2", "type": "catacombs", "image_url": "72.jpg", "description": "Chamber with Catacombs Entrance"}' +
	', "118": { "paperid": "73", "top": "1", "right": "1", "bottom": "1", "left": "2", "type": "catacombs", "image_url": "73.jpg", "description": "Chamber with Catacombs Entrance"}' +
	', "119": { "paperid": "74", "top": "2", "right": "2", "bottom": "1", "left": "1", "type": "catacombs", "image_url": "74.jpg", "description": "Chamber with Catacombs Entrance"}' +
	', "120": { "paperid": "75", "top": "2", "right": "1", "bottom": "1", "left": "2", "type": "catacombs", "image_url": "75.jpg", "description": "Chamber with Catacombs Entrance"}' +
	', "121": { "paperid": "77", "top": "2", "right": "2", "bottom": "1", "left": "2", "type": "catacombs", "image_url": "77.jpg", "description": "Chamber with Catacombs Entrance"}' +
	
	// Portcullis
	', "122": { "paperid": "80", "top": "1", "right": "2", "bottom": "5", "left": "2", "type": "portcullis", "image_url": "80.jpg", "description": "Chamber with Portcullis"}' +
	', "123": { "paperid": "80", "top": "1", "right": "2", "bottom": "5", "left": "2", "type": "portcullis", "image_url": "80a.jpg", "description": "Chamber with Portcullis"}'+
	', "124": { "paperid": "81", "top": "2", "right": "2", "bottom": "5", "left": "2", "type": "portcullis", "image_url": "81.jpg", "description": "Chamber with Portcullis"}' +
	', "125": { "paperid": "84", "top": "3", "right": "1", "bottom": "5", "left": "1", "type": "portcullis", "image_url": "84.jpg", "description": "Chamber with Portcullis"}' +
	', "126": { "paperid": "85", "top": "3", "right": "2", "bottom": "5", "left": "2", "type": "portcullis", "image_url": "85.jpg", "description": "Chamber with Portcullis"}' +
	', "127": { "paperid": "86", "top": "2", "right": "1", "bottom": "5", "left": "1", "type": "portcullis", "image_url": "86.jpg", "description": "Chamber with Portcullis"}' +
	', "128": { "paperid": "86", "top": "2", "right": "1", "bottom": "5", "left": "1", "type": "portcullis", "image_url": "86a.jpg", "description": "Chamber with Portcullis"}'+
	', "129": { "paperid": "87", "top": "1", "right": "2", "bottom": "5", "left": "1", "type": "portcullis", "image_url": "87.jpg", "description": "Chamber with Portcullis"}' +
	', "130": { "paperid": "87", "top": "1", "right": "2", "bottom": "5", "left": "1", "type": "portcullis", "image_url": "87a.jpg", "description": "Chamber with Portcullis"}' +
	', "131": { "paperid": "88", "top": "1", "right": "1", "bottom": "5", "left": "2", "type": "portcullis", "image_url": "88.jpg", "description": "Chamber with Portcullis"}' +
	', "132": { "paperid": "88", "top": "1", "right": "1", "bottom": "5", "left": "2", "type": "portcullis", "image_url": "88a.jpg", "description": "Chamber with Portcullis"}' +
	', "133": { "paperid": "89", "top": "1", "right": "1", "bottom": "5", "left": "1", "type": "portcullis", "image_url": "89.jpg", "description": "Chamber with Portcullis"}' +
	', "134": { "paperid": "89", "top": "1", "right": "1", "bottom": "5", "left": "1", "type": "portcullis", "image_url": "89a.jpg", "description": "Chamber with Portcullis"}' +

	'}';


var ChamberJSON = JSON.parse(ChamberJSON_string);

