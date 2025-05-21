
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
		
		this.torch = '1';
		this.lingering_shade = '0';
	}
}

class LootItem {
	constructor(timer, id, name, value, image_url, image_url_2) {
		this.id = id;
		this.name = name;
		this.value = value;
		this.image_url = image_url;
		this.image_url_2 = image_url_2;
		this.timer = timer;
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
		
		this.item_name = "";
		this.item_value = "";
		this.item_image_url = "";
		
		this.success = '0';
		this.resolved = '0';
		
		this.force_success = '0';
		this.force_fail = '0';
	}
}


class Ferrox {
	constructor() {
		this.x = 1;
		this.y = 1;

		this.name = "Ferrox";
		this.name_short = "Ferrox";
		this.health = 0;
		this.strength = 0;
		this.agility = 0;
		this.defense = 0;
		this.luck = 0;
		this.description = "Ferrox is very stinky";
		this.image_url = "monster_ferrox.jpg";
		this.image_url_2 = "";
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
		this.pit = "";
		this.trap = "";
		this.darkness = "";
		this.monster = "";
		
		this.catacombs = "";
		
		this.stuck_in_spiderweb = '0';
		
		this.searchable = '0';
		this.searched = '0';
		this.secret_door = '0';
		this.corpse = '0';
		this.corpse_searched = '0';
		this.crypt = '0';
		this.crypt_searched = '0';
		
		this.chamber_description = "";
		
	}
}