// Import the classes we want to test
const { Hero, LootItem, Encounter, Monster, Chamber, CardDeck } = require('../dungeonquest_classes.js');

describe('Hero Class', () => {
    let heroData;
    
    beforeEach(() => {
        heroData = {
            name: "Test Hero",
            name_short: "TH",
            health: 10,
            strength: 5,
            agility: 4,
            defense: 3,
            luck: 2,
            description: "A test hero",
            image_url: "hero.jpg",
            image_url_2: "hero2.jpg",
            have_range: true,
            arrows: 5
        };
    });

    test('should create a hero with correct initial values', () => {
        const hero = new Hero(heroData);
        expect(hero.x).toBe(1);
        expect(hero.y).toBe(1);
        expect(hero.name).toBe("Test Hero");
        expect(hero.name_short).toBe("TH");
        expect(hero.health).toBe(10);
        expect(hero.max_health).toBe(10);
        expect(hero.torch).toBe('1');
        expect(hero.fell_in_pit).toBe('0');
        expect(hero.lingering_shade).toBe('0');
    });

    test('should initialize with correct combat stats', () => {
        const hero = new Hero(heroData);
        expect(hero.strength).toBe(5);
        expect(hero.agility).toBe(4);
        expect(hero.defense).toBe(3);
        expect(hero.luck).toBe(2);
    });

    test('should initialize with correct range combat properties', () => {
        const hero = new Hero(heroData);
        expect(hero.have_range).toBe(true);
        expect(hero.arrows).toBe(5);
    });
});

describe('LootItem Class', () => {
    test('should create a loot item with correct values', () => {
        const loot = new LootItem(1, "sword", "Magic Sword", 100, "sword.jpg", "sword2.jpg");
        expect(loot.timer).toBe(1);
        expect(loot.id).toBe("sword");
        expect(loot.name).toBe("Magic Sword");
        expect(loot.value).toBe(100);
        expect(loot.image_url).toBe("sword.jpg");
        expect(loot.image_url_2).toBe("sword2.jpg");
    });
});

describe('Encounter Class', () => {
    test('should create an encounter with correct initial values', () => {
        const encounter = new Encounter("monster", "Dragon", "dragon_bg.jpg", "red", "A fearsome dragon!", "north");
        expect(encounter.type).toBe("monster");
        expect(encounter.title).toBe("Dragon");
        expect(encounter.background).toBe("dragon_bg.jpg");
        expect(encounter.color).toBe("red");
        expect(encounter.description).toBe("A fearsome dragon!");
        expect(encounter.direction).toBe("north");
        expect(encounter.resolved).toBe('0');
        expect(encounter.success).toBe('0');
        expect(encounter.show_flee).toBe('0');
        expect(encounter.can_flee).toBe('0');
        expect(encounter.flee).toBe('0');
        expect(encounter.show_fight).toBe('0');
        expect(encounter.can_fight).toBe('0');
        expect(encounter.before_combat).toBe('0');
        expect(encounter.show_combat_buttons).toBe('0');
        expect(encounter.ambushed).toBe('0');
        expect(encounter.can_wait).toBe('1');
        expect(encounter.can_shoot_first_arrow).toBe('1');
        expect(encounter.can_shoot_second_arrow).toBe('0');
        expect(encounter.num_dice_roll).toBe(1);
    });
});

describe('Monster Class', () => {
    let monsterData;
    
    beforeEach(() => {
        monsterData = {
            name: "Dragon",
            type: "dragon",
            combat: 8,
            wait: 6,
            escape: 4,
            penalty_dice: 2,
            penalty: 1,
            image_url: "dragon.jpg"
        };
    });

    test('should create a monster with correct initial values', () => {
        const monster = new Monster(monsterData);
        expect(monster.x).toBe(1);
        expect(monster.y).toBe(1);
        expect(monster.name).toBe("Dragon");
        expect(monster.type).toBe("dragon");
        expect(monster.combat).toBe(8);
        expect(monster.wait).toBe(6);
        expect(monster.escape).toBe(4);
        expect(monster.penalty_dice).toBe(2);
        expect(monster.penalty).toBe(1);
        expect(monster.image_url).toBe("dragon.jpg");
        expect(monster.name_short).toBe("Dragon");
        expect(monster.health).toBe(0);
        expect(monster.strength).toBe(0);
        expect(monster.agility).toBe(0);
        expect(monster.defense).toBe(0);
        expect(monster.luck).toBe(0);
        expect(monster.description).toBe("");
        expect(monster.image_url_2).toBe("");
        expect(monster.hit_by_arrow).toBe(0);
    });
});

describe('Chamber Class', () => {
    test('should create a chamber with correct initial values', () => {
        const chamber = new Chamber(1, "wall", "door", "wall", "door", "room", "north", "room.jpg", "A test room");
        expect(chamber.id).toBe(1);
        expect(chamber.top).toBe("wall");
        expect(chamber.right).toBe("door");
        expect(chamber.bottom).toBe("wall");
        expect(chamber.left).toBe("door");
        expect(chamber.type).toBe("room");
        expect(chamber.orientation).toBe("north");
        expect(chamber.image_url).toBe("room.jpg");
        expect(chamber.description).toBe("A test room");
        expect(chamber.stuck_in_spiderweb).toBe('0');
        expect(chamber.secret_door).toBe('0');
        expect(chamber.catacombs).toBe("");
        expect(chamber.searchable).toBe('0');
        expect(chamber.searched).toBe(0);
        expect(chamber.corpse).toBe('0');
        expect(chamber.corpse_searched).toBe('0');
        expect(chamber.crypt).toBe('0');
        expect(chamber.crypt_searched).toBe('0');
    });
});

describe('CardDeck Class', () => {
    let deck;
    let cardsJSON;
    
    beforeEach(() => {
        cardsJSON = {
            0: { type: "monster", name: "Dragon" },
            1: { type: "item", name: "Sword" },
            2: { type: "monster", name: "Goblin" }
        };
        deck = new CardDeck("monster", cardsJSON);
    });

    test('should initialize with correct values', () => {
        expect(deck._deck).toBe("monster");
        expect(deck._cardsJSON).toBe(cardsJSON);
        expect(deck._used_card_list).toEqual([]);
        expect(deck._items_list).toEqual([]);
        expect(deck._dragon_list).toEqual([]);
    });

    test('should draw a monster card', () => {
        const card = deck.draw_monster("monster");
        expect(card).toBeDefined();
        expect(["Dragon", "Goblin"]).toContain(card.name);
        expect(card.type).toBe("monster");
    });

    test('should draw a random card', () => {
        const card = deck.draw_card();
        expect(card).toBeDefined();
        expect(["Dragon", "Sword", "Goblin"]).toContain(card.name);
    });

    test('should draw a unique card', () => {
        // First draw should succeed
        const card1 = deck.draw_unique_card();
        expect(card1).toBeDefined();
        expect(["Dragon", "Sword", "Goblin"]).toContain(card1.name);
        expect(deck._used_card_list.length).toBe(1);
        
        // Second draw should also succeed
        const card2 = deck.draw_unique_card();
        expect(card2).toBeDefined();
        expect(["Dragon", "Sword", "Goblin"]).toContain(card2.name);
        expect(deck._used_card_list.length).toBe(2);
        
        // Third draw should succeed
        const card3 = deck.draw_unique_card();
        expect(card3).toBeDefined();
        expect(["Dragon", "Sword", "Goblin"]).toContain(card3.name);
        expect(deck._used_card_list.length).toBe(3);
        
        // Fourth draw should fail (all cards used)
        const card4 = deck.draw_unique_card();
        expect(card4).toBeUndefined();
    });

    test('should check if card is already drawn', () => {
        deck._used_card_list.push(0);
        expect(deck.already_drawn(0)).toBe(true);
        expect(deck.already_drawn(1)).toBe(false);
    });

    test('should check if item is already drawn', () => {
        deck._items_list.push(1);
        expect(deck.item_already_drawn(1)).toBe(true);
        expect(deck.item_already_drawn(0)).toBe(false);
    });

    test('should check if dragon is already drawn', () => {
        deck._dragon_list.push(0);
        expect(deck.dragon_already_drawn(0)).toBe(true);
        expect(deck.dragon_already_drawn(1)).toBe(false);
    });

    test('should check if deck is empty', () => {
        expect(deck.empty()).toBe(false);
        deck._used_card_list = [0, 1, 2];
        expect(deck.empty()).toBe(true);
    });
}); 