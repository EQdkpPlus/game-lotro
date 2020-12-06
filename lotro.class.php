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

if(!class_exists('lotro')) {
	class lotro extends game_generic {
		protected static $apiLevel	= 20;
		public $version	= '3.1.3';
		protected $this_game	= 'lotro';
		protected $types		= array('classes', 'races', 'factions', 'filters', 'roles', 'realmlist');
		protected $classes		= array();
		protected $races		= array();
		protected $factions		= array();
		protected $filters		= array();
		public  $langs			= array('english', 'german');
		
		
		public function __construct() {
			
			parent::__construct();
		}
		
		
		protected $class_dependencies = array(
			array(
				'name'		=> 'faction',
				'type'		=> 'factions',
				'admin' 	=> true,
				'decorate'	=> false,
				'parent'	=> false,
			),
			array(
				'name'		=> 'race',
				'type'		=> 'races',
				'admin'		=> false,
				'decorate'	=> true,
				'parent'	=> false,
			),
			array(
				'name'		=> 'class',
				'type'		=> 'classes',
				'admin'		=> false,
				'decorate'	=> true,
				'primary'	=> true,
				'colorize'	=> true,
				'roster'	=> true,
				'recruitment' => true,
				'parent'	=> array(
					'race' => array(
						0 	=> 'all',		// Unknown
						1 	=> array(1,2,3,4,5,6,7,9),		// Man
						2 	=> array(5,6,3,1,9),		// Hobbit
						3 	=> array(1,3,4,6,7,8,9),		// Elf
						4 	=> array(5,7,6,3,1,8),		// Dwarf
						5 	=> array(10),		// Beorning
						6	=> array(1,2,3,4,6,7,8,9), // High-Elf
						7	=> array(5,7,6,3,1,8), // Stout-Axe
					),
				),
			),
		);
		
		public $default_roles = array(
			1	=> array(1,8),			// Healer
			2	=> array(6,7,9,10),		// Tank
			3	=> array(4,5),			// CC
			4	=> array(3,7,8,9,10),	// Damage Dealer
			5	=> array(2,10)			// Supporter
		);
		
		protected $class_colors = array(
			1	=> '#FFCC33',
			2	=> '#0033CC',
			3	=> '#006600',
			4	=> '#00CCFF',
			5	=> '#444444',
			6	=> '#990000',
			7	=> '#CC3300',
			8	=> '#1A3CAA',
			9	=> '#FFF468',
			10	=> '#4d1900',
		);

		protected $glang		= array();
		protected $lang_file	= array();
		protected $path			= '';
		public  $lang			= false;
		

		public function profilefields(){
			$xml_fields = array(
				'level'	=> array(
					'type'			=> 'spinner',
					'category'		=> 'character',
					'lang'			=> 'uc_level',
					'max'			=> 100,
					'min'			=> 1,
					'undeletable'	=> true,
					'sort'			=> 4
				),
				'vocation'	=> array(
					'type'			=> 'dropdown',
					'category'		=> 'profession',
					'lang'			=> 'vocation',
					'options'		=> array('armourer' => 'Armourer', 'armsman' => 'Armsman', 'explorer' => 'Explorer', 'historian' => 'Historian', 'tinker' => 'Tinker', 'woodsman' => 'Woodsman', 'yeoman' => 'Yeoman'),
					'undeletable'	=> true,
					'tolang'		=> true,
				),
				'profession1'	=> array(
					'type'			=> 'dropdown',
					'category'		=> 'profession',
					'lang'			=> 'profession1',
					'options'		=> array('farmer' => 'Farmer', 'forester' => 'Forester', 'prospector' => 'Prospector', 'cook' => 'Cook', 'jeweller' => 'Jeweller', 'metalsmith' => 'Metalsmith', 'scholar' => 'Scholar', 'tailor' => 'Tailor', 'weaponsmith' => 'Weaponsmith', 'woodworker' => 'Woodworker'),
					'undeletable'	=> true,
					'tolang'		=> true,
				),
				'profession2'	=> array(
					'type'			=> 'dropdown',
					'category'		=> 'profession',
					'lang'			=> 'profession2',
					'options'		=> array('farmer' => 'Farmer', 'forester' => 'Forester', 'prospector' => 'Prospector', 'cook' => 'Cook', 'jeweller' => 'Jeweller', 'metalsmith' => 'Metalsmith', 'scholar' => 'Scholar', 'tailor' => 'Tailor', 'weaponsmith' => 'Weaponsmith', 'woodworker' => 'Woodworker'),
					'undeletable'	=> true,
					'tolang'		=> true,
				),
				'profession3'	=> array(
					'type'			=> 'dropdown',
					'category'		=> 'profession',
					'lang'			=> 'profession3',
					'options'		=> array('farmer' => 'Farmer', 'forester' => 'Forester', 'prospector' => 'Prospector', 'cook' => 'Cook', 'jeweller' => 'Jeweller', 'metalsmith' => 'Metalsmith', 'scholar' => 'Scholar', 'tailor' => 'Tailor', 'weaponsmith' => 'Weaponsmith', 'woodworker' => 'Woodworker'),
					'undeletable'	=> true,
					'tolang'		=> true,
				),
				'profession1_mastery'	=> array(
					'type'			=> 'int',
					'category'		=> 'profession',
					'lang'			=> 'profession1_mastery',
					'size'			=> 3,
					'undeletable'	=> true,
				),
				'profession2_mastery'	=> array(
					'type'			=> 'int',
					'category'		=> 'profession',
					'lang'			=> 'profession2_mastery',
					'size'			=> 3,
					'undeletable'	=> true,
				),
				'profession3_mastery'	=> array(
					'type'			=> 'int',
					'category'		=> 'profession',
					'lang'			=> 'profession3_mastery',
					'size'			=> 3,
					'undeletable'	=> true,
				),
				'profession1_proficiency'	=> array(
					'type'			=> 'int',
					'category'		=> 'profession',
					'lang'			=> 'profession1_proficiency',
					'size'			=> 3,
					'undeletable'	=> true,
				),
				'profession2_proficiency'	=> array(
					'type'			=> 'int',
					'category'		=> 'profession',
					'lang'			=> 'profession2_proficiency',
					'size'			=> 3,
					'undeletable'	=> true,
				),
				'profession3_proficiency'	=> array(
					'type'			=> 'int',
					'category'		=> 'profession',
					'lang'			=> 'profession3_proficiency',
					'size'			=> 3,
					'undeletable'	=> true,
				),
			);
			return $xml_fields;
		}

		public function admin_settings() {
			$settingsdata_admin = array(
				'uc_server_loc'	=> array(
					'lang'		=> 'uc_server_loc',
					'type' 		=> 'dropdown',
					'options'	=> array('eu' => 'EU', 'us' => 'US'),
				),
				// TODO: check if apostrophe is saved correctly
				'servername'	=> array(
					'lang'			=> 'servername',
					'type'			=> 'text',
					'size'			=> '21',
					'autocomplete'	=> $this->game->get('realmlist'),
				),
				'uc_lockserver'	=> array(
					'lang'		=> 'uc_lockserver',
					'type'		=> 'radio',
				)
			);
			return $settingsdata_admin;
		}

		protected function load_filters($langs){
			if(empty($this->classes)) {
				$this->load_type('classes', $langs);
			}
			foreach($langs as $lang) {
				$names = $this->classes[$lang];
				$this->filters[$lang][] = array('name' => '-----------', 'value' => false);
				foreach($names as $id => $name) {
					$this->filters[$lang][] = array('name' => $name, 'value' => array($id => 'class'));
				}
				$this->filters[$lang] = array_merge($this->filters[$lang], array(
					array('name' => '-----------', 'value' => false),
                    array('name' => $this->glang('heavy', true, $lang), 'value' => array(2 => 'class', 6 => 'class', 7 => 'class')),
                    array('name' => $this->glang('medium', true, $lang), 'value' => array(3 => 'class', 5 => 'class', 9 => 'class')),
                    array('name' => $this->glang('light', true, $lang), 'value' => array(1 => 'class', 4 => 'class', 8 => 'class')),
				));
			}
		}

		public function install($install=false){

			$arrEventIDs = array();
			$arrEventIDs[] = $this->game->addEvent($this->glang('event1'), 0, "annuminas_glinghant.png");
			$arrEventIDs[] = $this->game->addEvent($this->glang('event2'), 0, "annuminas_feste_elendil.png");
			$arrEventIDs[] = $this->game->addEvent($this->glang('event3'), 0, "annuminas_haudh_valabdil.png");
			$arrEventIDs[] = $this->game->addEvent($this->glang('event4'), 0, "fornost.png");
			$arrEventIDs[] = $this->game->addEvent($this->glang('event5'), 0, "fornost.png");
			$arrEventIDs[] = $this->game->addEvent($this->glang('event6'), 0, "fornost.png");
			$arrEventIDs[] = $this->game->addEvent($this->glang('event7'), 0, "fornost.png");
			$arrEventIDs[] = $this->game->addEvent($this->glang('event8'), 0, "huegelgrab.png");
			$arrEventIDs[] = $this->game->addEvent($this->glang('event9'), 0, "huegelgrab.png");
			$arrEventIDs[] = $this->game->addEvent($this->glang('event10'), 0, "huegelgrab.png");
			$arrEventIDs[] = $this->game->addEvent($this->glang('event11'), 0, "helegrod.png");
			$arrEventIDs[] = $this->game->addEvent($this->glang('event12'), 0, "helegrod.png");
			$arrEventIDs[] = $this->game->addEvent($this->glang('event13'), 0, "helegrod.png");
			$arrEventIDs[] = $this->game->addEvent($this->glang('event14'), 0, "helegrod.png");
			$arrEventIDs[] = $this->game->addEvent($this->glang('event15'), 0, "sonstiges_halle_der_nacht.png");
			$arrEventIDs[] = $this->game->addEvent($this->glang('event16'), 0, "sonstiges_herberge_der_verlassenen.png");
			$arrEventIDs[] = $this->game->addEvent($this->glang('event17'), 0, "tham_mirdain_bibliothek.png");
			$arrEventIDs[] = $this->game->addEvent($this->glang('event18'), 0, "tham_mirdain_schule.png");
			$arrEventIDs[] = $this->game->addEvent($this->glang('event19'), 0, "isengard_turm_orthanc.png");
			$arrEventIDs[] = $this->game->addEvent($this->glang('event20'), 0, "isengard_giesserei.png");
			$arrEventIDs[] = $this->game->addEvent($this->glang('event21'), 0, "isengard_dargnakh.png");
			$arrEventIDs[] = $this->game->addEvent($this->glang('event22'), 0, "isengard_grube.png");
			$arrEventIDs[] = $this->game->addEvent($this->glang('event23'), 0, "isengard_rande_des_fangorn.png");
			$arrEventIDs[] = $this->game->addEvent($this->glang('event24'), 0, "isengard_draigoch.png");
			$arrEventIDs[] = $this->game->addEvent($this->glang('event25'), 0, "isengard_wdf.png");
			$arrEventIDs[] = $this->game->addEvent($this->glang('event26'), 0, "skirmish_bruinenfurt.png");
			$arrEventIDs[] = $this->game->addEvent($this->glang('event27'), 0, "skirmish_gondamon.png");
			$arrEventIDs[] = $this->game->addEvent($this->glang('event28'), 0, "skirmish_amon_sul.png");
			$arrEventIDs[] = $this->game->addEvent($this->glang('event29'), 0, "skirmish.png");
			$arrEventIDs[] = $this->game->addEvent($this->glang('event30'), 0, "skirmish_thangulhad.png");
			$arrEventIDs[] = $this->game->addEvent($this->glang('event31'), 0, "skirmish_tiefweg.png");
			$arrEventIDs[] = $this->game->addEvent($this->glang('event32'), 0, "skirmish_schmiedeweg.png");
			$arrEventIDs[] = $this->game->addEvent($this->glang('event33'), 0, "skirmish_21_halle.png");
			$arrEventIDs[] = $this->game->addEvent($this->glang('event34'), 0, "skirmish_buckelstadt.png");
			$arrEventIDs[] = $this->game->addEvent($this->glang('event35'), 0, "skirmish_dannenglor.png");
			$arrEventIDs[] = $this->game->addEvent($this->glang('event36'), 0, "skirmish_geisterbeschwoerer.png");
			$arrEventIDs[] = $this->game->addEvent($this->glang('event37'), 0, "skirmish_ringgeister.png");
			$arrEventIDs[] = $this->game->addEvent($this->glang('event38'), 0, "skirmish_kamp_im_turm.png");
			$arrEventIDs[] = $this->game->addEvent($this->glang('event39'), 0, "skirmish_dieberei_und_unheil.png");
			$arrEventIDs[] = $this->game->addEvent($this->glang('event40'), 0, "skirmish_nurz_gashu.png");
			$arrEventIDs[] = $this->game->addEvent($this->glang('event41'), 0, "skirmish_eisige_kluft.png");
			$arrEventIDs[] = $this->game->addEvent($this->glang('event42'), 0, "skirmish_morgengrauen.png");
			$arrEventIDs[] = $this->game->addEvent($this->glang('event43'), 0, "skirmish_methedras.png");
			$arrEventIDs[] = $this->game->addEvent($this->glang('event44'), 0, "skirmish_huegelgraeberhoehen.png");
			$arrEventIDs[] = $this->game->addEvent($this->glang('event45'), 0, "angmar_carnd_dum.png");
			$arrEventIDs[] = $this->game->addEvent($this->glang('event46'), 0, "angmar_urugarth.png");
			$arrEventIDs[] = $this->game->addEvent($this->glang('event47'), 0, "angmar_barad_gularan.png");
			$arrEventIDs[] = $this->game->addEvent($this->glang('event48'), 0, "angmar_nurz_gashu.png");
			$arrEventIDs[] = $this->game->addEvent($this->glang('event49'), 0, "dol_guldur_barad_guldur.png");
			$arrEventIDs[] = $this->game->addEvent($this->glang('event50'), 0, "dol_guldur_sammath_gul.png");
			$arrEventIDs[] = $this->game->addEvent($this->glang('event51'), 0, "dol_guldur_warggehege.png");
			$arrEventIDs[] = $this->game->addEvent($this->glang('event52'), 0, "dol_guldur_verliese.png");
			$arrEventIDs[] = $this->game->addEvent($this->glang('event53'), 0, "dol_guldur_schwerthalle.png");
			$arrEventIDs[] = $this->game->addEvent($this->glang('event54'), 0, "garth_agarwen.png");
			$arrEventIDs[] = $this->game->addEvent($this->glang('event55'), 0, "garth_agarwen.png");
			$arrEventIDs[] = $this->game->addEvent($this->glang('event56'), 0, "garth_agarwen.png");
			$arrEventIDs[] = $this->game->addEvent($this->glang('event57'), 0, "in_ihrer_abw_feste_dunoth.png");
			$arrEventIDs[] = $this->game->addEvent($this->glang('event58'), 0, "in_ihrer_abw_sari_surma.png");
			$arrEventIDs[] = $this->game->addEvent($this->glang('event59'), 0, "in_ihrer_abw_verlorener_tempel.png");
			$arrEventIDs[] = $this->game->addEvent($this->glang('event60'), 0, "in_ihrer_abw_nhh.png");
			$arrEventIDs[] = $this->game->addEvent($this->glang('event61'), 0, "in_ihrer_abw_steinhoehe.png");
			$arrEventIDs[] = $this->game->addEvent($this->glang('event62'), 0, "lothlorien_dar_nurbugud.png");
			$arrEventIDs[] = $this->game->addEvent($this->glang('event63'), 0, "lothlorien_hallen_der_handwerkskunst.png");
			$arrEventIDs[] = $this->game->addEvent($this->glang('event64'), 0, "lothlorien_spiegelhallen.png");
			$arrEventIDs[] = $this->game->addEvent($this->glang('event65'), 0, "lothlorien_wasserraeder.png");
			$arrEventIDs[] = $this->game->addEvent($this->glang('event66'), 0, "moria_abscheulicher_schlund.png");
			$arrEventIDs[] = $this->game->addEvent($this->glang('event67'), 0, "moria_filikul.png");
			$arrEventIDs[] = $this->game->addEvent($this->glang('event68'), 0, "moria_die_grosse_treppe.png");
			$arrEventIDs[] = $this->game->addEvent($this->glang('event69'), 0, "moria_skumfil.png");
			$arrEventIDs[] = $this->game->addEvent($this->glang('event70'), 0, "moria_die_schmieden_von_khazad_dum.png");
			$arrEventIDs[] = $this->game->addEvent($this->glang('event71'), 0, "moria_fil_gashan.png");
			$arrEventIDs[] = $this->game->addEvent($this->glang('event72'), 0, "moria_schattenbinge.png");
			$arrEventIDs[] = $this->game->addEvent($this->glang('event73'), 0, "moria_sechzehnte_halle.png");
			$arrEventIDs[] = $this->game->addEvent($this->glang('event74'), 0, "moria_der_vergessene_schatz.png");
			$arrEventIDs[] = $this->game->addEvent($this->glang('event75'), 0, "bilwissdorf_thronsaal.png");
			$arrEventIDs[] = $this->game->addEvent($this->glang('event76'), 0, "skirmish.png");
			$arrEventIDs[] = $this->game->addEvent($this->glang('event77'), 0, "skirmish.png");

			$this->game->updateDefaultMultiDKPPool('Default', 'Default MultiDKPPool', $arrEventIDs);
		}
		//Guildbank
		public function guildbank_money(){
		return 	$money_data = array(
				'gold'		=> array(
			'icon'			=> array(
				'type'		=> 'default',
				'name'		=> 'gold'
			),
			'factor'		=> 100000,
			'size'			=> 'unlimited',
			'language'		=> $this->user->lang(array('gb_currency', 'gold')),
			'short_lang'	=> $this->user->lang(array('gb_currency', 'gold_s')),
		),
		'silver'	=> array(
			'icon'			=> array(
				'type'		=> 'default',
				'name'		=> 'silver'
			),
			'factor'		=> 100,
			'size'			=> 3,
			'language'		=> $this->user->lang(array('gb_currency', 'silver')),
			'short_lang'	=> $this->user->lang(array('gb_currency', 'silver_s')),
		),
		'copper'	=> array(
			'icon'			=> array(
				'type'		=> 'default',
				'name'		=> 'bronze'
			),
			'factor'		=> 1,
			'size'			=> 2,
			'language'		=> $this->user->lang(array('gb_currency', 'copper')),
			'short_lang'	=> $this->user->lang(array('gb_currency', 'copper_s')),
		)
		);	
		}
	}
}
?>
