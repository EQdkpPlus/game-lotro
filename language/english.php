<?php
/*	Project:	EQdkp-Plus
 *	Package:	Lord of the rings online game package
 *	Link:		http://eqdkp-plus.eu
 *
 *	Copyright (C) 2006-2016 EQdkp-Plus Developer Team
 *
 *	This program is free software: you can redistribute it and/or modify
 *	it under the terms of the GNU Affero General Public License as published
 *	by the Free Software Foundation, either version 3 of the License, or
 *	(at your option) any later version.
 *
 *	This program is distributed in the hope that it will be useful,
 *	but WITHOUT ANY WARRANTY; without even the implied warranty of
 *	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *	GNU Affero General Public License for more details.
 *
 *	You should have received a copy of the GNU Affero General Public License
 *	along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

if ( !defined('EQDKP_INC') ){
	header('HTTP/1.0 404 Not Found');exit;
}
$english_array = array(
	'classes' => array(
		0	=> 'Unknown',
		1	=> 'Minstrel',
		2	=> 'Captain',
		3	=> 'Hunter',
		4	=> 'Lore-master',
		5	=> 'Burglar',
		6	=> 'Guardian',
		7	=> 'Champion',
		8	=> 'Runekeeper',
		9	=> 'Warden',
		10	=> 'Beorning',
	),
	'races' => array(
		0	=> 'Unknown',
		1	=> 'Man',
		2	=> 'Hobbit',
		3	=> 'Elf',
		4	=> 'Dwarf',
		5	=> 'Beorning',
		6	=> 'High Elf',
		7	=> 'Stout-Axe',
	),
	'factions' => array(
		'free'			=>'Free People',
		'monsterplay'	=> 'MonsterPlay'
	),
	'roles' => array(
		1 => 'Healer',
		2 => 'Tank',
		3 => 'Crowd Control',
		4 => 'Damage Dealer',
		5 => 'Supporter',
	),
	'realmlist' => array(
		// EU Servers
		'Gwaihir',
		'Morthond',
		'Anduin',
		'Maiar',
		'Vanyar',
		'Belegaer',
		'Snowbourn',
		'Evernight',
		'Eldar',
		'Gilrain',
		'Laurelin',
		'Sirannon',
		'Estel',
	
		// US Servers
		'Arkenstone',
		'Brandywine',
		'Crickhollow',
		'Dwarrowdelf',
		'Elendilmir',
		'Firefoot',
		'Gladden',
		'Imladris',
		'Landroval',
		'Meneldor',
		'Nimrodel',
		'Riddermark',
		'Silverlode',
		'Vilya',
		'Windfola',
	
		// other Servers
		'Bullroarer',
		
		//Legendary Servers
		'Anor',
		'Ithil'
	),
	'lang' => array(
		'lotro' 						=> 'The Lord of the Rings Online',
		'heavy'							=> 'Heavy Armour',
		'medium'						=> 'Medium Armour',
		'light'							=> 'Light Armour',

		// Profile Admin area
		'core_sett_fs_gamesettings'		=> 'LOTRO Settings',
		'uc_faction'					=> 'Faction',
		'uc_faction_help'				=> 'Free People / MonsterPlay',
		'uc_fact_pvp'					=> 'MonsterPlay',
		'uc_fact_pve'					=> 'Free People',
		'uc_server_loc'					=> 'Server location',
		'uc_server_loc_help'			=> 'Location of your LOTRO-server',
		'servername'					=> 'Server name',
		'servername_help'			=> 'Name of your LOTRO-server (p.e. Bullroarer)',
		'uc_lockserver'					=> 'Lock the server name for users',
		'uc_lockserver_help'			=> '',
		
		'uc_import_guild'				=> 'Import Guild',
		'uc_import_guild_help'			=> 'Import all characters of a guild',
		'uc_update_all'					=> 'Update all profile information with data from MyLotro',
		'uc_importer_cache'				=> 'Reset importer cache',
		'uc_importer_cache_help'		=> 'Delete all the cached data of the import class.',
		'uc_bttn_update'				=> 'Update',
		
		'uc_class_filter'				=> 'Only character of the class',
		'uc_class_nofilter'				=> 'No filter',
		'uc_guild_name'					=> 'Name of the guild',
		'uc_filter_name'				=> 'Filter',
		'uc_level_filter'				=> 'All characters with a level higher than',
		'uc_imp_novariables'			=> 'You first have to set a realmserver and it\'s location in the settings.',
		'uc_imp_noguildname'			=> 'The name of the guild has not been given.',
		'uc_gimp_loading'				=> 'Loading guild characters, please wait...',
		'uc_gimp_header_fnsh'			=> 'Guild import finished',
		'uc_importcache_cleared'		=> 'The cache of the importer was successfully cleared.',
		"uc_updat_armory" 				=> "Refresh from armory",
		'uc_delete_chars_onimport'		=> 'Delete Chars that have left the guild',
		
		'uc_noprofile_found'			=> 'No profile found',
		'uc_profiles_complete'			=> 'Profiles updated successfully',
		'uc_notyetupdated'				=> 'No new data (inactive character)',
		'uc_notactive'					=> 'This character will be skipped because it is set to inactive',
		'uc_error_with_id'				=> 'Error with this character\'s id, it has been left out',
		'uc_notyourchar'				=> 'ATTENTION: You currently try to import a character that already exists in the database but is not owned by you. For security reasons, this action is not permitted. Please contact an administrator for solving this problem or try to use another character name.',
		'uc_lastupdate'					=> 'Last Update',
		
		'uc_prof_import'				=> 'import',
		'uc_import_forw'				=> 'continue',
		'uc_imp_succ'					=> 'The data has been imported successfully',
		'uc_upd_succ'					=> 'The data has been updated successfully',
		'uc_imp_failed'					=> 'An error occured while updating the data. Please try again.',
		
		'uc_charname'					=> 'Character\'s name',
		'servername'					=> 'Server\'s name',
		'uc_charfound'					=> "The character <b>%1\$s</b> has been found in the armory.",
		'uc_charfound2'					=> "This character was updated on <b>%1\$s</b>.",
		'uc_charfound3'					=> 'ATTENTION: Importing will overwrite the existing data!',
		'uc_armory_confail'				=> 'No connection to the armory. Data could not be transmitted.',
		'uc_armory_imported'			=> 'Imported',
		'uc_armory_impfailed'			=> 'Failed',
		'uc_armory_impduplex'			=> 'already existing',

		'no_data'						=> 'There is no data available for this character. Please check if the right server was chosen in the administration settings.',

		'vocation'						=> 'Vocation',
		'profession1'					=> 'First profession',
		'profession2'					=> 'Second profession',
		'profession3'					=> 'Third profession',
		'profession1_proficiency'		=> 'Proficiency-level first profession',
		'profession2_proficiency'		=> 'Proficiency-level second profession',
		'profession3_proficiency'		=> 'Proficiency-level third profession',
		'profession1_mastery'			=> 'Mastery-level first profession',
		'profession2_mastery'			=> 'Mastery-level second profession',
		'profession3_mastery'			=> 'Mastery-level third profession',
		
		// Profile Translation
		'uc_of'							=>	'of',
		'uc_stat_image'					=>	'en',
		'uc_might'						=>	'Might',
		'uc_agility'					=>  'Agility',	
		'uc_vitality'					=>	'Vitality',
		'uc_will'						=>	'Will',
		'uc_fate'						=>	'Fate',
		'uc_critical_hit'				=>	'Critical Hit',
		'uc_finesse'					=>	'Finesse',
		'uc_block'						=>	'Block',
		'uc_parry'						=>	'Parry',
		'uc_evade'						=>	'Evade',
		'uc_resistance'					=>	'Resistance',
		'uc_crit_avoid'					=>	'Crit Avoid',
		'uc_physical_mit'				=>	'Physical Mit.',
		'uc_tactical_mit'				=>	'Tactical Mit.',
		'uc_proficiency'				=>	'Proficiency',
		'uc_mastery'					=>	'Mastery',
		'uc_race'						=> 'Race',
		'uc_class'						=> 'Class',
		
		//Events
		'event1' => 'Annùminas: Glinghant',
		'event2' => 'Annùminas: Ost Elendil',
		'event3' => 'Annùminas: Haudh Valandil',
		'event4' => 'Fornost: Wrath of Water',
		'event5' => 'Fornost: Wrath of Earth',
		'event6' => 'Fornost: Wrath of Fire',
		'event7' => 'Fornost: Wrath of Shadow',
		'event8' => 'Great Barrow: The Maze',
		'event9' => 'Great Barrow: Thadúr',
		'event10' => 'Great Barrow: Sambrog',
		'event11' => 'Helegrod: Dragon Wing',
		'event12' => 'Helegrod: Drake Wing',
		'event13' => 'Helegrod: Giant Wing',
		'event14' => 'Helegrod: Spider Wing',
		'event15' => 'Other instances: Halls of Nights',
		'event16' => 'Other instances: Inn of the Forsaken',
		'event17' => 'Other instances: Library of Tham Mírdain',
		'event18' => 'Other instances: School at Tham Mírdain',
		'event19' => 'Isengard: The Tower of Orthanc',
		'event20' => 'Isengard: The Foundry',
		'event21' => 'Isengard: Dargnákh Unleashed',
		'event22' => 'Isengard: Pits of Isengard',
		'event23' => 'Isengard: Fangorn\'s Edge',
		'event24' => 'Isengard: Draigoch\'s Lair',
		'event25' => 'Limlight Gorge: Roots of Fangorn',
		'event26' => 'Skirmish (defensive): Ford of Bruinen',
		'event27' => 'Skirmish (defensive): Siege of Gondamon',
		'event28' => 'Skirmish (defensive): Stand at Amon Súl',
		'event29' => 'Skirmish (defensive): Defence of The Prancing Pony',
		'event30' => 'Skirmish (defensive): Protectors of Thangúlhad',
		'event31' => 'Skirmish (defensive): Battle of the Deep-Way',
		'event32' => 'Skirmish (defensive): Battle of the Way of Smiths',
		'event33' => 'Skirmish (defensive): Battle of the Twenty-first Hall',
		'event34' => 'Skirmish (offensive): Trouble in Tuckborough',
		'event35' => 'Skirmish (offensive): Strike against Dannanglor',
		'event36' => 'Skirmish (offensive): Breaching the Nocromancer\'s Gate',
		'event37' => 'Skirmish (offensive): Assault on the Ringwraiths\' Lair',
		'event38' => 'Skirmish (offensive): The Battel in the Tower',
		'event39' => 'Skirmish (offensive): Thievery and Mischief',
		'event40' => 'Skirmish (offensive): Rescue in Núrz Gháshu',
		'event41' => 'Skirmish (offensive): The Icy Crevasse',
		'event42' => 'Skirmish (offensive): Attack At Dawn',
		'event43' => 'Skirmish (offensive): Storm on Methedras',
		'event44' => 'Skirmish (survival): Barrow-downs',
		'event45' => 'Angmar: Carn Dúm',
		'event46' => 'Angmar: Urugarth',
		'event47' => 'Angmar: Barad Gularan',
		'event48' => 'Angmar: The Rift of Núrz Gháshu',
		'event49' => 'Dol Guldur: Barad Guldur',
		'event50' => 'Dol Guldur: Sammath Gúl',
		'event51' => 'Dol Guldur: Warg-pens of Dol Guldur',
		'event52' => 'Dol Guldur: Dungeons of Dol Guldur',
		'event53' => 'Dol Guldur: Sword-hall of Dol Guldur',
		'event54' => 'Garth Agarwen: Fortress',
		'event55' => 'Garth Agarwen: Arboretum',
		'event56' => 'Garth Agarwen: Barrows',
		'event57' => 'In Their Absence: Ost Dunoth',
		'event58' => 'In Their Absence: Sári-Surma',
		'event59' => 'In Their Absence: Lost Temple',
		'event60' => 'In Their Absence: The Northcotton Farm',
		'event61' => 'In Their Absence: Stoneheight',
		'event62' => 'Lothlórien: Dár Narbugud',
		'event63' => 'Lothlórien: Halls of Crafting',
		'event64' => 'Lothlórien: The Mirror-halls of Lumul-nar',
		'event65' => 'Lothlórien: The Water Wheels: Nalá-dúm',
		'event66' => 'Moria: The Vile Maw',
		'event67' => 'Moria: Filikul',
		'event68' => 'Moria: The Grand Stair',
		'event69' => 'Moria: Skúmfíl',
		'event70' => 'Moria: The Forges of Khazad-dúm',
		'event71' => 'Moria: Fil Gashan',
		'event72' => 'Moria: Dark Delving',
		'event73' => 'Moria: The Sixteenth Hall',
		'event74' => 'Moria: The Forgotten Treasury',
		'event75' => 'Goblins Town: Goblin-town Throne Room',
		'event76' => 'Skirmish (12)',
		'event77' => 'Skirmish (6)',
		'event78' => 'Skirmish (3)',
		'event79' => 'Summer: Boss from the Vaults: Thrâng',
		'event80' => 'Summer: The Perfect Picnic',
		'event81' => 'Yule: Boss from the Vaults: Storvâgûn',
		'event82' => 'Yule: The Battle of Forstbluff',
		'event83' => 'Road to Erebor: Flight to Lonely Mountain',
		'event84' => 'Road to Erebor: Iorbar\'s Peak',
		'event85' => 'Road to Erebor: Seat of the Great Goblin',
		'event86' => 'Road to Erebor: The Battle for Erebor',
		'event87' => 'Road to Erebor: The Bell of Dale',
		'event88' => 'Road to Erebor: The Fires of Smaug',
		'event89' => 'Road to Erebor: Webs of the Scuttledells',
		'event90' => 'Epic Battles: Helms Dike',
		'event91' => 'Epic Battles: Deeping Wall',
		'event92' => 'Epic Battles: Deeping Coomb',
		'event93' => 'Epic Battles: Glittering Caves',
		'event94' => 'Epic Battles: The Hornburg',
		'event95' => 'Epic Battles: Retaking Pelargir',
		'event96' => 'Epic Battles: The Defence of Minas Tirith',
		'event97' => 'Epic Battles: Hammer of the Underworld',
		'event98' => 'Osgiliath: Sunken Labyrinth',
		'event99' => 'Osgiliath: The Dome of Stars',
		'event100' => 'Osgiliath: The Ruined City',
		'event101' => 'Battle of the Pelennor Fields: Blood of the Black Serpent',
		'event102' => 'Battle of the Pelennor Fields: The Quays of the Harlond',
		'event103' => 'Battle of the Pelennor Fields: The Silent Street',
		'event104' => 'Battle of the Pelennor Fields: Throne of the Dread Terror',
		'event105' => 'Plateau of Gorgoroth: The Court of Seregost',
		'event106' => 'Plateau of Gorgoroth: The Dungeons of Naerband',
		'event107' => 'Plateau of Gorgoroth: The Abyss of Mordath',
	),
);
?>
