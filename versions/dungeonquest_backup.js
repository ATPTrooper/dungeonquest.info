var CharactersJSON_string = '' +
	'[{"name": "Challara", "name_short": "Challara", "health":"12", "strength":"5", "agility":"7", "defense":"5", "luck":"7", "image_url": "challara.png", "image_url_2": "hero_challara.jpg", "description": "The wrath of my ancestors shall strike down those who would deny me my birthright."}' +
	', {"name": "Brother Gherinn", "name_short": "Gherinn", "health":"13", "strength":"3", "agility":"5", "defense":"4", "luck":"9", "image_url": "gherinn.png", "image_url_2": "hero_gherinn.jpg", "description": "I shall never be free from the transgressions of my past if I do not face this fearsome place."}' +
	', {"name": "Hugo the Glorious", "name_short": "Hugo", "health":"16", "strength":"7", "agility":"5", "defense":"9", "luck":"4", "image_url": "hugo.png", "image_url_2": "hero_hugo.jpg", "description": "For the glories I accomplish here, my name shall be revered for all time."}' +
	', {"name": "Krutzbeck", "name_short": "Krutzbeck", "health":"20", "strength":"9", "agility":"4", "defense":"5", "luck":"4", "image_url": "krutzbeck.png", "image_url_2": "hero_krutzbeck.jpg", "description": "You are too weak. Go and find someone stronger to defend these treasures from me!"}' +
	', {"name": "Lindel", "name_short": "Lindel", "health":"15", "strength":"6", "agility":"6", "defense":"4", "luck":"5", "image_url": "lindel.png", "image_url_2": "hero_lindel.jpg", "description": "Too many have suffered for these cursed treasures."}' +
	', {"name": "Tatianna", "name_short": "Tatianna", "health":"14", "strength":"4", "agility":"9", "defense":"4", "luck":"6", "image_url": "tatianna.png", "image_url_2": "hero_tatianna.jpg", "description": "I must not fail in this quest. The survival of my tribe depends on what I accomplish here."}]';

var CharactersJSON = JSON.parse(CharactersJSON_string);

var ChamberJSON_string = '{' + 

	// Chambers
	'"1": {"top": "1", "right": "1", "bottom": "1", "left": "1", "type": "chamber", "image_url": "1.jpg"}' +
	',"2": {"top": "1", "right": "2", "bottom": "1", "left": "1", "type": "chamber", "image_url": "2.jpg"}' +
	',"3": {"top": "1", "right": "1", "bottom": "1", "left": "2", "type": "chamber", "image_url": "3.jpg"}' +
	',"4": {"top": "2", "right": "1", "bottom": "1", "left": "1", "type": "chamber", "image_url": "4.jpg"}' +
	',"5": {"top": "1", "right": "2", "bottom": "1", "left": "2", "type": "chamber", "image_url": "5.jpg"}' +
	',"6": {"top": "2", "right": "2", "bottom": "1", "left": "1", "type": "chamber", "image_url": "6.jpg"}' +
	',"7": {"top": "2", "right": "1", "bottom": "1", "left": "2", "type": "chamber", "image_url": "7.jpg"}' +
	
	// Corridors
	',"11": {"top": "1", "right": "1", "bottom": "1", "left": "1", "type": "corridor", "image_url": "11.jpg"}' +
	',"12": {"top": "1", "right": "2", "bottom": "1", "left": "1", "type": "corridor", "image_url": "12.jpg"}' +
	',"13": {"top": "1", "right": "1", "bottom": "1", "left": "2", "type": "corridor", "image_url": "13.jpg"}' +
	',"14": {"top": "2", "right": "1", "bottom": "1", "left": "1", "type": "corridor", "image_url": "14.jpg"}' +
	',"15": {"top": "1", "right": "2", "bottom": "1", "left": "2", "type": "corridor", "image_url": "15.jpg"}' +
	',"16": {"top": "2", "right": "2", "bottom": "1", "left": "1", "type": "corridor", "image_url": "16.jpg"}' +
	',"17": {"top": "2", "right": "1", "bottom": "1", "left": "2", "type": "corridor", "image_url": "17.jpg"}' +
	
	// Doors
	',"21": {"top": "3", "right": "1", "bottom": "1", "left": "1", "type": "door", "image_url": "21.jpg"}' +
	',"22": {"top": "3", "right": "2", "bottom": "1", "left": "1", "type": "door", "image_url": "22.jpg"}' +
	',"23": {"top": "3", "right": "1", "bottom": "1", "left": "2", "type": "door", "image_url": "23.jpg"}' +
	//',"24": {"top": "2", "right": "1", "bottom": "1", "left": "3", "type": "door", "image_url": "24.jpg"}' +
	//',"25": {"top": "3", "right": "2", "bottom": "1", "left": "2", "type": "door", "image_url": "25.jpg"}' +
	//',"26": {"top": "2", "right": "2", "bottom": "1", "left": "3", "type": "door", "image_url": "26.jpg"}' +
	//',"27": {"top": "2", "right": "3", "bottom": "1", "left": "2", "type": "door", "image_url": "27.jpg"}' +
	
	// Doors
	////',"31": {"top": "1", "right": "1", "bottom": "1", "left": "3", "type": "door", "image_url": "31.jpg"}' +
	//',"32": {"top": "1", "right": "2", "bottom": "1", "left": "3", "type": "door", "image_url": "32.jpg"}' +
	//',"33": {"top": "1", "right": "3", "bottom": "1", "left": "2", "type": "door", "image_url": "33.jpg"}' +
	//',"34": {"top": "2", "right": "3", "bottom": "1", "left": "1", "type": "door", "image_url": "34.jpg"}' +
	////',"35": {"top": "1", "right": "3", "bottom": "1", "left": "1", "type": "door", "image_url": "35.jpg"}' +
	
	// Doors
	//',"41": {"top": "3", "right": "1", "bottom": "1", "left": "3", "type": "door", "image_url": "41.jpg"}' +
	//',"42": {"top": "3", "right": "2", "bottom": "1", "left": "3", "type": "door", "image_url": "42.jpg"}' +
	//',"43": {"top": "3", "right": "3", "bottom": "1", "left": "2", "type": "door", "image_url": "43.jpg"}' +
	//',"44": {"top": "3", "right": "3", "bottom": "1", "left": "1", "type": "door", "image_url": "44.jpg"}' +
	////',"45": {"top": "1", "right": "3", "bottom": "1", "left": "3", "type": "door", "image_url": "45.jpg"}' +
	
	// Challenges/Abnormals
	',"51": {"top": "4", "right": "2", "bottom": "4", "left": "2", "type": "bridge", "image_url": "51.jpg"}' +
	',"52": {"top": "6", "right": "2", "bottom": "6", "left": "2", "type": "pit", "image_url": "52.jpg"}' +
	//',"53": {"top": "2", "right": "2", "bottom": "1", "left": "2", "type": "rotating", "image_url": "53.jpg"}' +
	//',"54": {"top": "1", "right": "1", "bottom": "1", "left": "1", "type": "leftchasm", "image_url": "54.jpg"}' +
	//',"55": {"top": "1", "right": "1", "bottom": "1", "left": "1", "type": "rightchasm", "image_url": "55.jpg"}' +
	
	// Traps/Darkness/SpiderWebs/CaveIns
	',"61": {"top": "1", "right": "1", "bottom": "1", "left": "1", "type": "trap", "image_url": "61.jpg"}' +
	',"62": {"top": "1", "right": "2", "bottom": "1", "left": "1", "type": "trap", "image_url": "62.jpg"}' +
	',"63": {"top": "1", "right": "1", "bottom": "1", "left": "2", "type": "trap", "image_url": "63.jpg"}' +
	',"64": {"top": "2", "right": "9", "bottom": "9", "left": "9", "type": "darkness", "image_url": "64.jpg"}' +
	',"65": {"top": "2", "right": "2", "bottom": "9", "left": "9", "type": "darkness", "image_url": "65.jpg"}' +
	',"66": {"top": "2", "right": "9", "bottom": "9", "left": "2", "type": "darkness", "image_url": "66.jpg"}' +
	',"67": {"top": "8", "right": "8", "bottom": "8", "left": "8", "type": "spiderweb", "image_url": "67.jpg"}' +
	',"68": {"top": "7", "right": "2", "bottom": "7", "left": "2", "type": "rubble", "image_url": "68.jpg"}' +
	',"69": {"top": "7", "right": "7", "bottom": "7", "left": "7", "type": "rubble", "image_url": "69.jpg"}' +
	
	// Catacombs
	',"71": {"top": "1", "right": "2", "bottom": "1", "left": "1", "type": "catacombs", "image_url": "71.jpg"}' +
	//',"72": {"top": "2", "right": "2", "bottom": "1", "left": "2", "type": "catacombs", "image_url": "72.jpg"}' +
	',"73": {"top": "1", "right": "1", "bottom": "1", "left": "2", "type": "catacombs", "image_url": "73.jpg"}' +
	',"74": {"top": "2", "right": "2", "bottom": "1", "left": "1", "type": "catacombs", "image_url": "74.jpg"}' +
	',"75": {"top": "2", "right": "1", "bottom": "1", "left": "2", "type": "catacombs", "image_url": "75.jpg"}' +
	//',"76": {"top": "1", "right": "1", "bottom": "1", "left": "1", "type": "catacombs", "image_url": "76.jpg"}' +
	',"77": {"top": "2", "right": "2", "bottom": "1", "left": "2", "type": "catacombs", "image_url": "77.jpg"}' +
	
	// Portcullis
	',"80": {"top": "1", "right": "2", "bottom": "5", "left": "2", "type": "portcullis", "image_url": "80.jpg"}' +
	',"81": {"top": "2", "right": "2", "bottom": "5", "left": "2", "type": "portcullis", "image_url": "81.jpg"}' +
	//',"82": {"top": "2", "right": "1", "bottom": "5", "left": "2", "type": "portcullis", "image_url": "82.jpg"}' +
	//',"83": {"top": "2", "right": "2", "bottom": "5", "left": "1", "type": "portcullis", "image_url": "83.jpg"}' +
	',"84": {"top": "3", "right": "1", "bottom": "5", "left": "1", "type": "portcullis", "image_url": "84.jpg"}' +
	',"85": {"top": "3", "right": "2", "bottom": "5", "left": "2", "type": "portcullis", "image_url": "85.jpg"}' +
	',"86": {"top": "2", "right": "1", "bottom": "5", "left": "1", "type": "portcullis", "image_url": "86.jpg"}' +
	',"87": {"top": "1", "right": "2", "bottom": "5", "left": "1", "type": "portcullis", "image_url": "87.jpg"}' +
	',"88": {"top": "1", "right": "1", "bottom": "5", "left": "2", "type": "portcullis", "image_url": "88.jpg"}' +
	',"89": {"top": "1", "right": "1", "bottom": "5", "left": "1", "type": "portcullis", "image_url": "89.jpg"}' +
	',"90": {"top": "1", "right": "1", "bottom": "5", "left": "1", "type": "portcullis", "image_url": "90.jpg"}' +
	'}';

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

var ChamberEventJSON_string = '' + 

	'{"1": {"name": "Dead Adventurer", "type": "deadadventurer", "value": "0", "image_url": "", "description": "You may loot the body for treasure. If you choose to do so, draw 1 corpse card."}' +
	// ',"2": {"name": "Dead Adventurer", "type": "deadadventurer", "value": "0", "image_url": "", "description": "You may loot the body for treasure. If you choose to do so, draw 1 corpse card."}' +
	// ',"3": {"name": "Dead Adventurer", "type": "deadadventurer", "value": "0", "image_url": "", "description": "You may loot the body for treasure. If you choose to do so, draw 1 corpse card."}' +
	// ',"4": {"name": "Dead Adventurer", "type": "deadadventurer", "value": "0", "image_url": "", "description": "You may loot the body for treasure. If you choose to do so, draw 1 corpse card."}' +
	// ',"5": {"name": "Dead Adventurer", "type": "deadadventurer", "value": "0", "image_url": "", "description": "You may loot the body for treasure. If you choose to do so, draw 1 corpse card."}' +
	// ',"6": {"name": "Dead Adventurer", "type": "deadadventurer", "value": "0", "image_url": "", "description": "You may loot the body for treasure. If you choose to do so, draw 1 corpse card."}' +
	// ',"7": {"name": "Dead Adventurer", "type": "deadadventurer", "value": "0", "image_url": "", "description": "You may loot the body for treasure. If you choose to do so, draw 1 corpse card."}' +
	// ',"8": {"name": "Dead Adventurer", "type": "deadadventurer", "value": "0", "image_url": "", "description": "You may loot the body for treasure. If you choose to do so, draw 1 corpse card."}' +
	
	// ',"11": {"name": "Crypt", "type": "crypt", "value": "0", "image_url": "", "description": "You may explore the crypt for treasure. If you choose to do so, draw 1 crypt card."}' +
	// ',"12": {"name": "Crypt", "type": "crypt", "value": "0", "image_url": "", "description": "You may explore the crypt for treasure. If you choose to do so, draw 1 crypt card."}' +
	// ',"13": {"name": "Crypt", "type": "crypt", "value": "0", "image_url": "", "description": "You may explore the crypt for treasure. If you choose to do so, draw 1 crypt card."}' +
	// ',"14": {"name": "Crypt", "type": "crypt", "value": "0", "image_url": "", "description": "You may explore the crypt for treasure. If you choose to do so, draw 1 crypt card."}' +
	// ',"15": {"name": "Crypt", "type": "crypt", "value": "0", "image_url": "", "description": "You may explore the crypt for treasure. If you choose to do so, draw 1 crypt card."}' +
	// ',"16": {"name": "Crypt", "type": "crypt", "value": "0", "image_url": "", "description": "You may explore the crypt for treasure. If you choose to do so, draw 1 crypt card."}' +
	// ',"17": {"name": "Crypt", "type": "crypt", "value": "0", "image_url": "", "description": "You may explore the crypt for treasure. If you choose to do so, draw 1 crypt card."}' +
	// ',"18": {"name": "Crypt", "type": "crypt", "value": "0", "image_url": "", "description": "You may explore the crypt for treasure. If you choose to do so, draw 1 crypt card."}' +
	
	// ',"20": {"name": "Empty", "type": "emptychamber", "value": "0", "image_url": "", "description": "The room is empty; nothing happens."}' +
	// ',"21": {"name": "Empty", "type": "emptychamber", "value": "0", "image_url": "", "description": "The room is empty; nothing happens."}' +
	// ',"22": {"name": "Empty", "type": "emptychamber", "value": "0", "image_url": "", "description": "The room is empty; nothing happens."}' +
	// ',"23": {"name": "Empty", "type": "emptychamber", "value": "0", "image_url": "", "description": "The room is empty; nothing happens."}' +
	// ',"24": {"name": "Empty", "type": "emptychamber", "value": "0", "image_url": "", "description": "The room is empty; nothing happens."}' +
	// ',"25": {"name": "Empty", "type": "emptychamber", "value": "0", "image_url": "", "description": "The room is empty; nothing happens."}' +
	// ',"26": {"name": "Empty", "type": "emptychamber", "value": "0", "image_url": "", "description": "The room is empty; nothing happens."}' +
	// ',"27": {"name": "Empty", "type": "emptychamber", "value": "0", "image_url": "", "description": "The room is empty; nothing happens."}' +
	// ',"28": {"name": "Empty", "type": "emptychamber", "value": "0", "image_url": "", "description": "The room is empty; nothing happens."}' +
	// ',"29": {"name": "Empty", "type": "emptychamber", "value": "0", "image_url": "", "description": "The room is empty; nothing happens."}' +
	// ',"30": {"name": "Empty", "type": "emptychamber", "value": "0", "image_url": "", "description": "The room is empty; nothing happens."}' +
	
	// ',"31": {"name": "Ambush", "type": "ambush", "value": "0", "image_url": "", "description": "Encounter a monster."}' +
	// ',"32": {"name": "Ambush", "type": "ambush", "value": "0", "image_url": "", "description": "Encounter a monster."}' +
	// ',"33": {"name": "Ambush", "type": "ambush", "value": "0", "image_url": "", "description": "Encounter a monster."}' +
	// ',"34": {"name": "Ambush", "type": "ambush", "value": "0", "image_url": "", "description": "Encounter a monster."}' +
	// ',"35": {"name": "Ambush", "type": "ambush", "value": "0", "image_url": "", "description": "Encounter a monster."}' +
	// ',"36": {"name": "Ambush", "type": "ambush", "value": "0", "image_url": "", "description": "Encounter a monster."}' +
	
	// ',"41": {"name": "Hidden Trap", "type": "hiddentrap", "value": "0", "image_url": "", "description": "Draw a trap card."}' +
	// ',"42": {"name": "Hidden Trap", "type": "hiddentrap", "value": "0", "image_url": "", "description": "Draw a trap card."}' +
	// ',"43": {"name": "Hidden Trap", "type": "hiddentrap", "value": "0", "image_url": "", "description": "Draw a trap card."}' +
	
	',"51": {"name": "Passage Down", "type": "passagedown", "value": "0", "image_url": "", "description": "Place a catacomb entrance marker on this chamber."}' +
	',"52": {"name": "Passage Down", "type": "passagedown", "value": "0", "image_url": "", "description": "Place a catacomb entrance marker on this chamber."}' +
	',"53": {"name": "Ceiling Collapses", "type": "collapse", "value": "0", "image_url": "", "description": "The ceiling gives way! Some paths are blocked by debris, while other new paths are revealed. Rotate the chamber tile you are in 90 degrees clockwise."}' +
	',"54": {"name": "Ceiling Collapses", "type": "collapse", "value": "0", "image_url": "", "description": "The ceiling gives way! Some paths are blocked by debris, while other new paths are revealed. Rotate the chamber tile you are in 90 degrees clockwise."}' +
	',"55": {"name": "Torch Goes Out", "type": "torchout", "value": "0", "image_url": "", "description": "Keep this card. While you have this card, test luck at the start of your status phase. If you succeed, you relight your torch; discard this card and take your turn. If you fail, end your turn."}' +
	',"56": {"name": "Secret Door", "type": "secretdoor", "value": "0", "image_url": "", "description": "You may immediately move to any adjacent space. If the space is unexplored, place a chamber tile as normal. If you encounter a monster, you cannot escape."}' +
	',"57": {"name": "Razorwing Attack", "type": "razorwingattack", "value": "0", "image_url": "", "description": "Roll 1 die and suffer a number of wounds equal to the result."}' +
	',"58": {"name": "Curse of the Wizard", "type": "wizardscurse", "value": "0", "image_url": "", "description": "Roll 1 die and rotate all corridors in the following directions: 1-2) 90 degrees clockwise 3-4) 180 degrees 5-6) 90 degrees counterclockwise"}' +
	',"59": {"name": "Unstable Potion", "type": "findchamberitem", "value": "0", "image_url": "", "description": ""}' +
	',"60": {"name": "Bottle Imp", "type": "findchamberitem", "value": "0", "image_url": "", "description": "If you exit Dragonfire Dungeon, you may roll 1 die at the end of the game: 1-2) Discard one of your other loot cards at random. 4-6) Draw a treasure card."}' +
	',"61": {"name": "Lingering Shade", "type": "lingeringshade", "value": "0", "image_url": "", "description": "Keep this card. While you have this card, roll 2 dice at the start of your status phase: 2-3) You die. 4-12) Take your turn as normal. Discard this card if you enter the catacombs or exit Dragonfire Dungeon."}' +
	',"62": {"name": "Gold Coins", "type": "findchamberitem", "value": "30", "image_url": "", "description": ""}' +
	',"63": {"name": "Gold Coins", "type": "findchamberitem", "value": "40", "image_url": "", "description": ""}}';
						
var ChamberEventJSON = JSON.parse(ChamberEventJSON_string);



CatacombsJSON_string = '{' +
	'"1": {"name": "Exit", "value": "0", "image_url": "", "description": "You have found a way out! You may immediately exit the catacombs."}' +
	',"2": {"name": "Exit", "value": "0", "image_url": "", "description": "You have found a way out! You may immediately exit the catacombs."}' +
	',"3": {"name": "Exit", "value": "0", "image_url": "", "description": "You have found a way out! You may immediately exit the catacombs."}' +
	',"4": {"name": "Exit", "value": "0", "image_url": "", "description": "You have found a way out! You may immediately exit the catacombs."}' +
	',"5": {"name": "Exit", "value": "0", "image_url": "", "description": "You have found a way out! You may immediately exit the catacombs."}' +
	',"6": {"name": "Exit", "value": "0", "image_url": "", "description": "You have found a way out! You may immediately exit the catacombs."}' +
	',"7": {"name": "Empty", "value": "0", "image_url": "", "description": "The room is empty; nothing happens."}' +
	',"8": {"name": "Empty", "value": "0", "image_url": "", "description": "The room is empty; nothing happens."}' +
	',"9": {"name": "Empty", "value": "0", "image_url": "", "description": "The room is empty; nothing happens."}' +
	',"10": {"name": "Empty", "value": "0", "image_url": "", "description": "The room is empty; nothing happens."}' +
	',"11": {"name": "Empty", "value": "0", "image_url": "", "description": "The room is empty; nothing happens."}' +
	',"12": {"name": "Empty", "value": "0", "image_url": "", "description": "The room is empty; nothing happens."}' +
	',"13": {"name": "Empty", "value": "0", "image_url": "", "description": "The room is empty; nothing happens."}' +
	',"14": {"name": "Empty", "value": "0", "image_url": "", "description": "The room is empty; nothing happens."}' +
	',"15": {"name": "Hole in the Roof", "value": "0", "image_url": "", "description": "Test agility. If you succeed, you may exit the catacombs. If you fail, end your turn; on your next turn, you may test agility again instead of drawing a catacomb card."}' +
	',"16": {"name": "Hole in the Roof", "value": "0", "image_url": "", "description": "Test agility. If you succeed, you may exit the catacombs. If you fail, end your turn; on your next turn, you may test agility again instead of drawing a catacomb card."}' +
	',"17": {"name": "Hidden Trap", "value": "0", "image_url": "", "description": "Draw a trap card."}' +
	',"18": {"name": "Hidden Trap", "value": "0", "image_url": "", "description": "Draw a trap card."}' +
	',"19": {"name": "Hidden Trap", "value": "0", "image_url": "", "description": "Draw a trap card."}' +
	',"20": {"name": "Hidden Trap", "value": "0", "image_url": "", "description": "Draw a trap card."}' +
	',"21": {"name": "Treasure Chest", "value": "0", "image_url": "", "description": "When you exit Dragonfire Dungeon, roll 2 dice. The chest has a gold value equal to the result multiplied by 100."}' +
	',"22": {"name": "Treasure Chest", "value": "0", "image_url": "", "description": "When you exit Dragonfire Dungeon, roll 2 dice. The chest has a gold value equal to the result multiplied by 100."}' +
	',"23": {"name": "Gigantic Diamond", "value": "4000", "image_url": "", "description": ""}' +
	',"24": {"name": "Torch Goes Out", "value": "0", "image_url": "", "description": "While you are in the catacombs, test luck at the start of your status phase. If you succeed, you relight your torch; take your turn as normal. If you fail, end your turn."}' +
	',"25": {"name": "Torch Goes Out", "value": "0", "image_url": "", "description": "While you are in the catacombs, test luck at the start of your status phase. If you succeed, you relight your torch; take your turn as normal. If you fail, end your turn."}' +
	',"26": {"name": "Horde of Rats", "value": "0", "image_url": "", "description": "You are overwhelmed by a horde of verminous rats! Test armor. If you fail, suffer 4 wounds."}' +
	',"27": {"name": "Horde of Rats", "value": "0", "image_url": "", "description": "You are overwhelmed by a horde of verminous rats! Test armor. If you fail, suffer 4 wounds."}' +
	',"28": {"name": "Spider\'s Venom", "value": "0", "image_url": "", "description": "You are bitten by a spider.  While you are in the catacombs, roll 1 die at the start of your phase: 1-2) Suffer 1 wound. 3-6) Nothing happens."}' +
	',"29": {"name": "Spider\'s Venom", "value": "0", "image_url": "", "description": "You are bitten by a spider.  While you are in the catacombs, roll 1 die at the start of your phase: 1-2) Suffer 1 wound. 3-6) Nothing happens."}' +
	',"30": {"name": "Razorwing Attack", "value": "0", "image_url": "", "description": "Roll 1 die and suffer a number of wounds equal to the result."}' +
	',"31": {"name": "Razorwing Attack", "value": "0", "image_url": "", "description": "Roll 1 die and suffer a number of wounds equal to the result."}' +
	',"32": {"name": "Demon Flame", "value": "0", "image_url": "", "description": "A demon bursts forth in a ball of flame! Test luck. You cannot discard determination tokens to pass this test. If you succeed, suffer 1 wound. If you fail, suffer 5 wounds."}' +
	',"33": {"name": "Skeleton Volley", "value": "0", "image_url": "", "description": "A group of skeletons launches a volley of arrows down the hall toward you! Test luck. If you succeed, end your turn. If you fail, draw 2 monster cards and suffer a number of wounds equal to the sum of  the escape penalties on both cards. Then, discard the cards."}' +
	',"34": {"name": "Golem Grab", "value": "0", "image_url": "", "description": "A golem lunges at you out of the darkness, grabbing at your gear! Test luck. If you succeed, end your turn. If you fail, suffer 2 wounds and discard a loot card (if you have any)."}' +
	',"35": {"name": "Troll Smash", "value": "0", "image_url": "", "description": "A troll swings his mighty club directly at you! Test luck. If you succeed, end your turn. If you fail subtract your armor value from 10 and suffer than many wounds."}' +
	',"36": {"name": "Sorcerer\'s Bolt", "value": "0", "image_url": "", "description": "A sorcerer hurls a magic bolt in your direction! Test luck. If you succeed, end your turn. If you fail, suffer a number of wounds equal to the amount by which you failed."}' +
	',"37": {"name": "Vampire Bite", "value": "0", "image_url": "", "description": "Test agility or armor (whichever is lower). If you fail, keep this card when you exit the catacombs. While you have this card, suffer 1 wound at the start of your status phase."}' +
	',"38": {"name": "Greedy Deep Elf", "value": "0", "image_url": "", "description": "A suspecious-looking deep elf beckons you near. You may discard any of your loot cards as a bribe. If you do, roll 1 die and multiply the result by 100. If this total is less than the gold value of the loot cards you discarded, she show syou the way out and you may immediately exit the catacombs. If the total is higher, she betrays you with a sudden attack! Suffer 4 wounds."}' +
	',"39": {"name": "Giant Naga", "value": "0", "image_url": "", "description": "The passage is blocked by a giant naga and you must turn around. Follow the procedure for exiting the catacombs. Then, place your travel marker in that space and rotate it 180 degrees. Discard this card and all of your catacomb cards."}}';
						
var CatacombsJSON = JSON.parse(CatacombsJSON_string);

MonsterJSON_string = '{' +
	'"1": {"name": "Troll", "type": "Monster", "health": "3", "escape_penalty": "4", "image_url": ""}' +
	',"2": {"name": "Troll", "type": "Monster", "health": "4", "escape_penalty": "2", "image_url": ""}' +
	',"3": {"name": "Troll", "type": "Monster", "health": "4", "escape_penalty": "3", "image_url": ""}' +
	',"4": {"name": "Troll", "type": "Monster", "health": "5", "escape_penalty": "0", "image_url": ""}' +
	',"5": {"name": "Demon", "type": "Monster", "health": "5", "escape_penalty": "2", "image_url": ""}' +
	',"6": {"name": "Demon", "type": "Monster", "health": "6", "escape_penalty": "3", "image_url": ""}' +
	',"7": {"name": "Demon", "type": "Monster", "health": "6", "escape_penalty": "4", "image_url": ""}' +
	',"8": {"name": "Demon", "type": "Monster", "health": "8", "escape_penalty": "6", "image_url": ""}' +
	',"9": {"name": "Skeleton", "type": "Monster", "health": "2", "escape_penalty": "1", "image_url": ""}' +
	',"10": {"name": "Skeleton", "type": "Monster", "health": "2", "escape_penalty": "2", "image_url": ""}' +
	',"11": {"name": "Skeleton", "type": "Monster", "health": "3", "escape_penalty": "0", "image_url": ""}' +
	',"12": {"name": "Skeleton", "type": "Monster", "health": "3", "escape_penalty": "1", "image_url": ""}' +
	',"13": {"name": "Sorcerer", "type": "Monster", "health": "2", "escape_penalty": "1", "image_url": ""}' +
	',"14": {"name": "Sorcerer", "type": "Monster", "health": "2", "escape_penalty": "2", "image_url": ""}' +
	',"15": {"name": "Sorcerer", "type": "Monster", "health": "3", "escape_penalty": "3", "image_url": ""}' +
	',"16": {"name": "Sorcerer", "type": "Monster", "health": "3", "escape_penalty": "4", "image_url": ""}' +
	',"17": {"name": "Golem", "type": "Monster", "health": "4", "escape_penalty": "2", "image_url": ""}' +
	',"18": {"name": "Golem", "type": "Monster", "health": "5", "escape_penalty": "2", "image_url": ""}' +
	',"19": {"name": "Golem", "type": "Monster", "health": "5", "escape_penalty": "3", "image_url": ""}' +
	',"20": {"name": "Golem", "type": "Monster", "health": "6", "escape_penalty": "4", "image_url": ""}}';
						
var MonsterJSON = JSON.parse(MonsterJSON_string);

DoorJSON_string = '{' +
	'"1": {"name": "Door Opens", "type": "Event", "value": "1", "image_url": "", "Description": "The door opens; move into the adjacent chamber."}' +
	',"2": {"name": "Door Opens", "type": "Event", "value": "1", "image_url": "", "Description": "The door opens; move into the adjacent chamber."}' +
	',"3": {"name": "Door Opens", "type": "Event", "value": "1", "image_url": "", "Description": "The door opens; move into the adjacent chamber."}' +
	',"4": {"name": "Door Opens", "type": "Event", "value": "1", "image_url": "", "Description": "The door opens; move into the adjacent chamber."}' +
	',"5": {"name": "Door Opens", "type": "Event", "value": "1", "image_url": "", "Description": "The door opens; move into the adjacent chamber."}' +
	',"6": {"name": "Door Opens", "type": "Event", "value": "1", "image_url": "", "Description": "The door opens; move into the adjacent chamber."}' +
	',"7": {"name": "Door Opens", "type": "Event", "value": "1", "image_url": "", "Description": "The door opens; move into the adjacent chamber."}' +
	',"8": {"name": "Door Opens", "type": "Event", "value": "1", "image_url": "", "Description": "The door opens; move into the adjacent chamber."}' +
	',"9": {"name": "Door Jammed", "type": "Event", "value": "2", "image_url": "", "Description": "The door does not open; remain in your current chamber."}' +
	',"10": {"name": "Door Jammed", "type": "Event", "value": "2", "image_url": "", "Description": "The door does not open; remain in your current chamber."}' +
	',"11": {"name": "Door Jammed", "type": "Event", "value": "3", "image_url": "", "Description": "The door does not open; remain in your current chamber."}' +
	',"12": {"name": "Spear Trap", "type": "Event", "value": "3", "image_url": "", "Description": "Roll 1 die and suffer a number of wounds equal to the result; remain in your current chamber."}' +
	',"13": {"name": "Spear Trap", "type": "Event", "value": "3", "image_url": "", "Description": "Roll 1 die and suffer a number of wounds equal to the result; remain in your current chamber."}' +
	',"14": {"name": "Spear Trap", "type": "Event", "value": "3", "image_url": "", "Description": "Roll 1 die and suffer a number of wounds equal to the result; remain in your current chamber."}}';
						
var DoorJSON = JSON.parse(DoorJSON_string);

CryptJSON_string = '{' +
	'"1": {"name": "Gold Coins", "type": "findcryptitem", "value": "20", "image_url": "", "Description": "you find 20 gold coins"}' +
	',"2": {"name": "Gold Coins", "type": "findcryptitem", "value": "90", "image_url": "", "Description": "you find 90 gold coins"}' +
	',"3": {"name": "Gold Coins", "type": "findcryptitem", "value": "120", "image_url": "", "Description": "you find 120 gold coins"}' +
	',"4": {"name": "Gold Coins", "type": "findcryptitem", "value": "250", "image_url": "", "Description": "you find 250 gold coins"}' +
	',"5": {"name": "Unstable Potion", "type": "findcryptitem", "value": "0", "image_url": "", "Description": ""}' +
	',"6": {"name": "Unstable Potion", "type": "findcryptitem", "value": "0", "image_url": "", "Description": ""}' +
	',"7": {"name": "Unstable Potion", "type": "findcryptitem", "value": "0", "image_url": "", "Description": ""}' +
	',"8": {"name": "Empty", "type": "emptycrypt", "value": "0", "image_url": "", "Description": "This crypt is empty; nothing happens."}' +
	',"9": {"name": "Empty", "type": "emptycrypt", "value": "0", "image_url": "", "Description": "This crypt is empty; nothing happens."}' +
	',"10": {"name": "Empty", "type": "emptycrypt", "value": "0", "image_url": "", "Description": "This crypt is empty; nothing happens."}' +
	',"11": {"name": "Old Bones", "type": "oldbones", "value": "0", "image_url": "", "Description": "Roll 1 die: 1-3) Nothing happens. 4-6) The bones spring to life and attack you. You encounter a monster. During this encounter, you cannot escape."}' +
	',"12": {"name": "Old Bones", "type": "oldbones", "value": "0", "image_url": "", "Description": "Roll 1 die: 1-3) Nothing happens. 4-6) The bones spring to life and attack you. You encounter a monster. During this encounter, you cannot escape."}' +
	',"13": {"name": "Spectral Storm", "type": "spectralstorm", "value": "0", "image_url": "", "Description": "You are assailed by an army of undead. Test strength. If you fail, suffer a number of wounds equal to half your remaining health (rounded up)."}' +
	',"14": {"name": "Hidden Trap", "type": "hiddentrap", "value": "0", "image_url": "", "Description": "Draw a trap card."}}';

var CryptJSON = JSON.parse(CryptJSON_string);

CorpseJSON_string = '{' +
	'"1": {"name": "Gold Coins", "type": "findcorpseitem", "value": "50", "image_url": "", "Description": "you find 50 gold coins"}' +
	',"2": {"name": "Gold Coins", "type": "findcorpseitem", "value": "100", "image_url": "", "Description": "you find 100 gold coins"}' +
	',"3": {"name": "Gold Coins", "type": "findcorpseitem", "value": "200", "image_url": "", "Description": "you find 200 gold coins"}' +
	',"4": {"name": "Unstable Potion", "type": "findcorpseitem", "value": "0", "image_url": "", "Description": ""}' +
	',"5": {"name": "Empty", "type": "emptycorpse", "value": "0", "image_url": "", "Description": "This corpse possesses nothing of value; nothing happens."}' +
	',"6": {"name": "Empty", "type": "emptycorpse", "value": "0", "image_url": "", "Description": "This corpse possesses nothing of value; nothing happens."}' +
	',"7": {"name": "Empty", "type": "emptycorpse", "value": "0", "image_url": "", "Description": "This corpse possesses nothing of value; nothing happens."}' +
	',"8": {"name": "Rope", "type": "findcorpseitem", "value": "0", "image_url": "", "Description": ""}' +
	',"9": {"name": "Rope", "type": "findcorpseitem", "value": "0", "image_url": "", "Description": ""}' +
	',"10": {"name": "Rope", "type": "findcorpseitem", "value": "0", "image_url": "", "Description": ""}' +
	',"11": {"name": "Old Bones", "type": "oldbones", "value": "0", "image_url": "", "Description": "Roll 1 die: 1-3) Nothing happens. 4-6) The bones spring to life and attack you. You encounter a monster. During this encounter, you cannot escape."}' +
	',"12": {"name": "Scorpion", "type": "scorpion", "value": "0", "image_url": "", "Description": "Roll 1 die, subtract 1 from the result, and suffer that many wounds."}' +
	',"13": {"name": "Small Scorpion", "type": "scorpion", "value": "0", "image_url": "", "Description": "Roll 1 die, subtract 2 from the result, and suffer that many wounds."}' +
	',"14": {"name": "Deadly Scorpion", "type": "scorpion", "value": "0", "image_url": "", "Description": "Roll 1 die and suffer that many wounds."}}';
						
var CorpseJSON = JSON.parse(CorpseJSON_string);


TrapsJSON_string = '{' +
	'"1": {"name": "Trapdoor", "type": "trapdoor", "description": "Test agility. If you fail, suffer 1 wound and enter the catacombs.", "image_url": ""}' +
	',"2": {"name": "Poisonous Snakes", "type": "snakes", "description": "Roll 1 die and suffer a number of wounds equal to the result.", "image_url": ""}' +
	',"3": {"name": "Poisonous Gas", "type": "gas", "description": "Roll 1 die, subtract 3 from th result, and suffer that many sounds.  Then, roll 1 die, sutract 3 from the result, and lose that many turns.", "image_url": ""}' +
	',"4": {"name": "Ceiling Collapses", "type": "collapse", "description": "The ceiling gives way! Some paths are blocked by debris, while other new paths are revealed. Rotate the chamber tile you are in 90 degrees clockwise.", "image_url": ""}' +
	',"5": {"name": "Explosion", "type": "explosion", "description": "Suffer 4 wounds and lose your next turn.", "image_url": ""}' +
	',"6": {"name": "Swinging Blade", "type": "blade", "description": "Test armor. If you fail, you die!", "image_url": ""}' +
	',"7": {"name": "Crossfire Trap", "type": "crossfire", "description": "Arrows fire from the walls. Test armor. If you fail, suffer a number of wounds equal to the amount by which you failed.", "image_url": ""}}';
						
var TrapsJSON = JSON.parse(TrapsJSON_string);


SearchJSON_string = '{' +
	'"1": {"name": "Secret Door", "type":"secretdoor", "value": "0", "image_url": ""}' +
	',"2": {"name": "Secret Door", "type":"secretdoor", "value": "0", "image_url": ""}' +
	',"3": {"name": "Secret Door", "type":"secretdoor", "value": "0", "image_url": ""}' +
	',"4": {"name": "Secret Door", "type":"secretdoor", "value": "0", "image_url": ""}' +
	',"5": {"name": "Secret Door", "type":"secretdoor", "value": "0", "image_url": ""}' +
	',"6": {"name": "Secret Door", "type":"secretdoor", "value": "0", "image_url": ""}' +
	',"7": {"name": "Secret Door", "type":"secretdoor", "value": "0", "image_url": ""}' +
	',"8": {"name": "Secret Door", "type":"secretdoor", "value": "0", "image_url": ""}' +
	
	',"11": {"name": "Empty Room", "type":"emptyroom", "value": "0", "image_url": ""}' +
	',"12": {"name": "Empty Room", "type":"emptyroom", "value": "0", "image_url": ""}' +
	',"13": {"name": "Empty Room", "type":"emptyroom", "value": "0", "image_url": ""}' +
	',"14": {"name": "Empty Room", "type":"emptyroom", "value": "0", "image_url": ""}' +
	',"15": {"name": "Empty Room", "type":"emptyroom", "value": "0", "image_url": ""}' +
	',"16": {"name": "Empty Room", "type":"emptyroom", "value": "0", "image_url": ""}' +
	
	',"21": {"name": "Passage Down", "type":"passagedown", "value": "0", "image_url": ""}' +
	',"22": {"name": "Passage Down", "type":"passagedown", "value": "0", "image_url": ""}' +
	',"23": {"name": "Passage Down", "type":"passagedown", "value": "0", "image_url": ""}' +
	',"24": {"name": "Passage Down", "type":"passagedown", "value": "0", "image_url": ""}' +
	
	',"31": {"name": "Gold Coins", "type":"finditem", "value": "10", "image_url": ""}' +
	',"32": {"name": "Gold Coins", "type":"finditem", "value": "60", "image_url": ""}' +
	',"33": {"name": "Gold Coins", "type":"finditem", "value": "70", "image_url": ""}' +
	',"34": {"name": "Gold Coins", "type":"finditem", "value": "150", "image_url": ""}' +
	
	',"41": {"name": "Giant Centipede", "type":"centipede", "value": "0", "image_url": ""}' +
	',"42": {"name": "Ferrox", "type":"ferrox", "value": "0", "image_url": ""}' +
	',"43": {"name": "Ferrox", "type":"ferrox", "value": "0", "image_url": ""}' +
	
	',"51": {"name": "Unstable Potion", "type":"finditem", "value": "0", "image_url": ""}' +
	',"52": {"name": "Unstable Potion", "type":"finditem", "value": "0", "image_url": ""}' +
	
	',"61": {"name": "Hidden Trap", "type":"trap", "value": "0", "image_url": ""}' +
	',"62": {"name": "Hidden Trap", "type":"trap", "value": "0", "image_url": ""}' +
	'}';
						
var SearchJSON = JSON.parse(SearchJSON_string);


DragonJSON_string = '{' +
	'"1": {"name": "Sleeping", "awake": "0", "image_url": ""}' +
	',"2": {"name": "Sleeping", "awake": "0", "image_url": ""}' +
	',"3": {"name": "Sleeping", "awake": "0", "image_url": ""}' +
	',"4": {"name": "Sleeping", "awake": "0", "image_url": ""}' +
	',"5": {"name": "Sleeping", "awake": "0", "image_url": ""}' +
	',"6": {"name": "Sleeping", "awake": "0", "image_url": ""}' +
	',"7": {"name": "Sleeping", "awake": "0", "image_url": ""}' +
	',"8": {"name": "Dragon Rage", "awake": "1", "image_url": ""}}';
						
var DragonJSON = JSON.parse(DragonJSON_string);

TreasureJSON_string = '{' +
	'"1": {"name": "Obsidian Ring", "value": "110", "image_url": ""}' +
	',"2": {"name": "Golden Claws", "value": "290", "image_url": ""}' +
	',"3": {"name": "Belt of Strength", "value": "260", "image_url": ""}' +
	',"4": {"name": "Jinn Lamp", "value": "270", "image_url": ""}' +
	',"5": {"name": "Pearl Ring", "value": "130", "image_url": ""}' +
	',"6": {"name": "Jeweled Chakra", "value": "350", "image_url": ""}' +
	',"7": {"name": "Gold Ring", "value": "190", "image_url": ""}' +
	',"8": {"name": "Loadstone", "value": "1", "image_url": ""}' +
	',"9": {"name": "Quicksilver Potion", "value": "1500", "image_url": ""}' +
	',"10": {"name": "Ruby Ring", "value": "140", "image_url": ""}' +
	',"11": {"name": "Charmer\'s Flute", "value": "230", "image_url": ""}' +
	',"12": {"name": "Mirror of Shael", "value": "550", "image_url": ""}' +
	',"13": {"name": "Staff of Light", "value": "700", "image_url": ""}' +
	',"14": {"name": "Rune Scarab", "value": "400", "image_url": ""}' +
	',"15": {"name": "Ebon Amulet", "value": "300", "image_url": ""}' +
	',"16": {"name": "Silver Ring", "value": "160", "image_url": ""}' +
	',"17": {"name": "Flying Carpet", "value": "500", "image_url": ""}' +
	',"18": {"name": "Harp of Tranquility", "value": "2000", "image_url": ""}' +
	',"19": {"name": "Chalice of Tamalir", "value": "900", "image_url": ""}' +
	',"20": {"name": "Crystal of Tival", "value": "240", "image_url": ""}' +
	',"21": {"name": "Sun Spear", "value": "1100", "image_url": ""}' +
	',"22": {"name": "Crystal of Orris", "value": "320", "image_url": ""}' +
	',"23": {"name": "Bag of Gems", "value": "1200", "image_url": ""}' +
	',"24": {"name": "Loadstone", "value": "1", "image_url": ""}' +
	',"25": {"name": "Star of Kellos", "value": "800", "image_url": ""}' +
	',"26": {"name": "Giant Ruby", "value": "3000", "image_url": ""}' +
	',"27": {"name": "Eyes of Avra", "value": "420", "image_url": ""}' +
	',"28": {"name": "Ebon Ring", "value": "80", "image_url": ""}' +
	',"29": {"name": "Vestments of Kellos", "value": "600", "image_url": ""}' +
	',"30": {"name": "Arcane Ring", "value": "210", "image_url": ""}' +
	',"31": {"name": "Artifact Eater", "value": "0", "image_url": ""}' +
	',"32": {"name": "Forbidden Tome", "value": "0", "image_url": ""}}';
						
var TreasureJSON = JSON.parse(TreasureJSON_string);