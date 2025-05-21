
class Hero {
	constructor(p_heroJSON) {
		this.x = 1;
		this.y = 1;

		this.name = p_heroJSON.name;
		this.name_short = p_heroJSON.name_short;
		this.health = p_heroJSON.health;
		this.strength = p_heroJSON.strength;
		this.agility = p_heroJSON.agility;
		this.defense = p_heroJSON.defense;
		this.luck = p_heroJSON.luck;
		this.description = p_heroJSON.description;
		this.image_url = p_heroJSON.image_url;
		this.image_url_2 = p_heroJSON.image_url_2;
		
		this.have_range = p_heroJSON.have_range;
		this.arrows = p_heroJSON.arrows;
		
		this.max_health = p_heroJSON.health;
		
		this.torch = '1';
		this.fell_in_pit = '0';
		this.lingering_shade = '0';
	}
}

class LootItem {
	constructor(timer, id, name, value, image_url, image_url_2) {
		this.timer = timer;
		this.id = id;
		this.name = name;
		this.value = value;
		this.image_url = image_url;
		this.image_url_2 = image_url_2;
	}
}

class Encounter {
	constructor(type, title, background, color, description, direction) {
		
		this.type = type;
		this.title = title;
		this.background = background;
		this.color = color;
		this.description = description;
		this.direction = direction;
		
		this.image_url;
		
		this.item_name = "";
		this.item_value = "";
		this.item_image_url = "";
		
		this.resolved = '0';
		this.success = '0';
		
		this.show_flee = '0';
		this.can_flee = '0';
		this.flee = '0';
		
		this.show_fight = '0';
		this.can_fight = '0';

		
		this.before_combat = '0';
		
		this.show_combat_buttons = '0';
		this.ambushed = '0';
		this.can_wait = '1';
		this.can_shoot_first_arrow = '1';
		this.can_shoot_second_arrow = '0';
		
		this.num_dice_roll = 1;
	}
}


class Monster {
	constructor(p_monster_card) {
		this.x = 1;
		this.y = 1;

		this.name = p_monster_card.name;
		this.type = p_monster_card.type;
		
		this.combat = p_monster_card.combat;
		this.wait = p_monster_card.wait;
		this.escape = p_monster_card.escape;
		this.penalty_dice = p_monster_card.penalty_dice;
		this.penalty = p_monster_card.penalty;
		
		this.image_url = p_monster_card.image_url;
		
		this.name_short = p_monster_card.name;
		
		this.health = 0;
		this.strength = 0;
		this.agility = 0;
		this.defense = 0;
		this.luck = 0;
		this.description = "";
		
		this.image_url_2 = "";
		
		this.hit_by_arrow = 0;
		
		this.escape_penalty;
	}
}


class Chamber {
	constructor(id, top, right, bottom, left, type, orientation, image_url, description) {
		
		this.id = id;
		this.top = top;
		this.bottom = bottom;
		this.left = left;
		this.right = right;
		this.type = type
		this.orientation = orientation;
		this.image_url = image_url;
		this.description = description;
		
		// event
		this.stuck_in_spiderweb = '0';
		this.secret_door = '0';
		this.catacombs = "";
		
		// action
		this.searchable = '0';
		this.searched = 0;
		this.corpse = '0';
		this.corpse_searched = '0';
		this.crypt = '0';
		this.crypt_searched = '0';
		
	}
}


class CardDeck {
	
	constructor(p_deck, p_cardsJSON) {
		this._deck = p_deck
		this._cardsJSON = p_cardsJSON;
		this._used_card_list = [];
		this._items_list = [];
		this._dragon_list = [];
	}
	
	draw_monster(p_name) {
		if (Object.keys(this._cardsJSON).length > 0) {
			var randomNumber;
			var randomDraw;
			var i = 1;
			do {
				randomNumber = Math.floor(Math.random() * Object.keys(this._cardsJSON).length);
				randomDraw = this._cardsJSON[randomNumber];
				i++;
			} while (randomDraw.type != p_name && i < 1000);
			
			if (i == 999) {
				alert("Draw Unique Items: Infinite Loop");
			}
			
			return randomDraw;
		} else {
			return undefined;
		}
	}
		
	draw_card() {
		if (Object.keys(this._cardsJSON).length > 0) {
			var randomNumber;
			var randomDraw;
			randomNumber = Math.floor(Math.random() * Object.keys(this._cardsJSON).length);
			randomDraw = this._cardsJSON[randomNumber];
			return randomDraw;
		} else {
			return undefined;
		}
	}
		
	draw_card_with_unique_items() {
		if (Object.keys(this._cardsJSON).length > 0) {
			var randomNumber;
			var randomDraw;
			var i = 1;
			do {
				randomNumber = Math.floor(Math.random() * Object.keys(this._cardsJSON).length);
				randomDraw = this._cardsJSON[randomNumber];
				i++;
			} while (this.item_already_drawn(randomNumber) && i < 1000);
			
			if (i == 999) {
				alert("Draw Unique Items: Infinite Loop");
			}
			
			if (randomDraw.type == 'finditem') {
				this._items_list.push(randomNumber);
			}
			return randomDraw;
		} else {
			return undefined;
		}
	}
		
	draw_dragon_card() {
		if (Object.keys(this._cardsJSON).length > 0) {
			var randomNumber;
			var randomDraw;
			var i = 1;
			do {
				randomNumber = Math.floor(Math.random() * Object.keys(this._cardsJSON).length);
				randomDraw = this._cardsJSON[randomNumber];
				i++;
			} while (this.dragon_already_drawn(randomNumber) && i < 1000);
			
			if (i == 999) {
				alert("Draw Dragon: Infinite Loop");
			}
			
			if (randomDraw.type == 'sleeping') {
				this._dragon_list.push(randomNumber);
			}
			if (randomDraw.type == 'awake') {
				//this._dragon_list = [];
			}
			return randomDraw;
		} else {
			return undefined;
		}
	}
	
	draw_unique_card() {
		if (Object.keys(this._cardsJSON).length > 0 && this._used_card_list.length < Object.keys(this._cardsJSON).length) {
			var randomNumber;
			var randomDraw;
			var i = 1;
			do {
				randomNumber = Math.floor(Math.random() * Object.keys(this._cardsJSON).length);
				randomDraw = this._cardsJSON[randomNumber];
				i++;
			} while ((randomDraw == undefined || this.already_drawn(randomNumber)) && i < 1000);
			
			if (i == 999) {
				alert("Draw Unique: Infinite Loop");
			}
			
			this._used_card_list.push(randomNumber);
			//alert(this._deck + ": draw unique: " + randomDraw.name + ": " + randomNumber + " / " + Object.keys(this._cardsJSON).length);
			return randomDraw;
		} else {
			return undefined;
		}
	}
	
	already_drawn(p_card_id) {
		for (var i = 0; i < this._used_card_list.length; i++) {
			if (this._used_card_list[i] == p_card_id) {
				return true;
			}
		}
		return false;
	}
	
	item_already_drawn(p_card_id) {
		for (var i = 0; i < this._items_list.length; i++) {
			if (this._items_list[i] == p_card_id) {
				return true;
			}
		}
		return false;
	}
	
	dragon_already_drawn(p_card_id) {
		for (var i = 0; i < this._dragon_list.length; i++) {
			if (this._dragon_list[i] == p_card_id) {
				return true;
			}
		}
		return false;
	}
	
	empty() {
		return this._used_card_list.length >= Object.keys(this._cardsJSON).length;
	}
}