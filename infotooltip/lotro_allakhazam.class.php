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

if(!class_exists('lotro_allakhazam')) {
	class lotro_allakhazam extends itt_parser {
		public static $shortcuts = array('puf' => 'urlfetcher');

		public $supported_games = array('lotro');
		public $av_langs = array('en' => 'en_US');#, 'de' => 'de_DE', 'fr' => 'fr_FR', 'ru' => 'ru_RU', 'jp' => 'ja_JP');

		public $settings = array(
			'itt_icon_loc' => array('name' => 'itt_icon_loc',
									'language' => 'pk_itt_icon_loc',
									'type' => 'text',
									'size' => false,
									'options' => false,
									'default' => 'http://lotro.allakhazam.com/images/icons/ItemIcons/'),
			'itt_icon_ext' => array('name' => 'itt_icon_ext',
									'language' => 'pk_itt_icon_ext',
									'type' => 'text',
									'size' => false,
									'options' => false,
									'default' => '.jpg'),
			'itt_default_icon' => array('name' => 'itt_default_icon',
										'language' => 'pk_itt_default_icon',
										'type' => 'text',
										'size' => false,
										'options' => false,
										'default' => 'unknown')
		);

		protected function u_construct() {}
		protected function u_destruct() {}

		protected function searchItemID($itemname, $lang){
			$encoded_name = urlencode($itemname);
			$link = 'http://lotro.allakhazam.com/search.html?q='.$encoded_name;
			$data = $this->puf->fetch($link);
			if (preg_match_all('#item\.html\?lotritem=(.*?)\" class=\"(.*?)\" id=\"(.*?)\">(.*?)\<\/a\>#', $data, $matches))
			{
				foreach ($matches[0] as $key => $match)
				{
					// Extract the item's ID from the match.
					$item_id = $matches[1][$key];
					$found_name = $matches[4][$key];

					if(strcasecmp($itemname, $found_name) == 0) {
						return array($item_id, 'items');
					}
				}
			}
			return false;
		}

		protected function getItemData($item_id, $lang, $itemname='', $type='items'){		
			if($item_id < 1) {
				return null;
			}
			$xml_link = 'http://lotro.allakhazam.com/cluster/item-xml.pl?lotritem='.$item_id;
			$xml_data = $this->puf->fetch($xml_link);
			$xml = simplexml_load_string($xml_data);

			//filter baditems
			if(!isset($xml->display_html) OR strlen($xml->display_html) < 5) {
				$item['baditem'] = true;
				return $item;
			}

			$item['link'] = 'http://lotro.allakhazam.com/db/item.html?lotritem='.$item_id;
			$item['id'] = 0; # dont store this id, since its an allkhazam internal id
			$item['name'] = (string) $xml->item_name;
			$item['icon'] = (string) $xml->icon;
			$item['lang'] = $lang;
			$item['color'] = 'item'.$xml->quality;
			$item['html'] = (string) $xml->display_html;
			$item['html'] = $item['html'];
			//reposition allakhazam-credit-stuff
			$alla_credit = '<br><span class="akznotice">Item display is courtesy <a href="http://lotro.allakhazam.com/">lotro.allakhazam.com</a>.</span>';
			$item['html'] = str_replace($alla_credit, "", $item['html']).$alla_credit;
			$item['html'] = str_replace('{ITEM_HTML}', $item['html'], file_get_contents($this->root_path.'games/lotro/infotooltip/templates/lotro_popup.tpl'));

			return $item;
		}
	}
}
?>