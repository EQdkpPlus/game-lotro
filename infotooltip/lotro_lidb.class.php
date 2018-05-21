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

if(!class_exists('lotro_lidb')) {
	class lotro_lidb extends itt_parser {
		public $supported_games = array('lotro');
		public $av_langs = array('de' => 'de_DE');#, 'en' => 'en_US', 'fr' => 'fr_FR', 'ru' => 'ru_RU', 'jp' => 'ja_JP');

		public $settings = array(
			'itt_icon_loc' => array('name' => 'itt_icon_loc',
									'language' => 'pk_itt_icon_loc',
									'type' => 'text',
									'size' => false,
									'options' => false,
									'default' => 'http://www.lidb.de/eqdkp_ico.php?name='),
			'itt_icon_ext' => array('name' => 'itt_icon_ext',
									'language' => 'pk_itt_icon_ext',
									'type' => 'text',
									'size' => false,
									'options' => false,
									'default' => ''),
			'itt_default_icon' => array('name' => 'itt_default_icon',
										'language' => 'pk_itt_default_icon',
										'type' => 'text',
										'size' => false,
										'options' => false,
										'default' => 'unknown')
		);

		protected function searchItemID($name, $lang){
			return array($name, 'items');
		}

		protected function getItemData($name, $lang, $itemname='', $type='items') {
			if(empty($name)) return array('baditem' => true);

			$item = array('name' => $name);
			$item['color'] = 'whitename';
			$item['lang'] = '';
			$item['icon'] = $name;
			$item['link'] = 'http://www.lidb.de/index.php?itemsuchen=1&name='.urlencode($name);
			$item['html'] = '<img src="http://www.lidb.de/eqdkp_stat.php?name='.$name.'" alt="'.$name.'" />';
			return $item;
		}
	}
}
?>