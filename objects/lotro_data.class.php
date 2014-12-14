<?php
/*	Project:	EQdkp-Plus
 *	Package:	Lord of the rings online game package
 *	Link:		http://eqdkp-plus.eu
 *
 *	Copyright (C) 2006-2015 EQdkp-Plus Developer Team
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

class lotro_data {

	protected $apiurl			= 'http://data.lotro.com/';
	
	protected $convert		= array(
		'classes' => array(
			31		=> 1,		// Minstrel
			24		=> 2,		// Captain
			162		=> 3,		// Hunter
			185		=> 4,		// Lore-master
			40		=> 5,		// Burglar
			23		=> 6,		// Guardian
			172		=> 7,		// Champion
			193		=> 8,		// Runekeeper
			194		=> 9,		// Warden
		),
		'races' => array(
			23		=> 1,	//Man	
			81		=> 2,	//Hobbit
			65		=> 3,	//Elf
			73		=> 4,	//Dwarf
		),
	);
	
	public $slots = array(
		'Earring1'	=> 14,
		'Earring2'	=> 15,
		'Necklace'	=> 11,
		'Pocket1' 	=> 16,
		'Bracelet1'	=> 9,
		'Bracelet2'	=> 10,
		'Ring1'		=> 12,
		'Ring2'		=> 13,
		'Head'		=> 2,
		'Shoulder'	=> 7,
		'Chest'		=> 3,
		'Back'		=> 8,
		'Gloves'	=> 5,
		'Legs'		=> 4,
		'Boots'		=> 6,
		'Weapon_Primary' => 17,
		'Weapon_Secondary' => 18,
		'Weapon_Ranged' => 19,
		'CraftTool'	=> 20,
		'Last'		=> 21,
	);

	private $converts		= array();
	
	private $_config		= array(
		'caching'				=> true,
		'caching_time'			=> 24,
	);

	/**
	* Initialize the Class
	* 
	* @param $serverloc		Location of Server
	* @param $locale		The Language of the data
	* @return bool
	*/
	public function __construct(){
		
	}
	
	public function __get($name) {
		if(class_exists('registry')) {
			if($name == 'pfh') return registry::register('file_handler');
			if($name == 'puf') return registry::register('urlfetcher');
		}
		return null;
	}

	/**
	* Fetch guild information
	* 
	* @param $user		Character Name
	* @param $realm		Realm Name
	* @param $force		Force the cache to update?
	* @return bol
	*/
	public function guild($guild, $realm, $force=false){
		$guild	= rawurlencode(unsanitize($guild));
		$realm = unsanitize($realm);
		$url	= $this->apiurl.base64_decode("Z29kbW9kLzkzNGY1ZDY4ZGUwOTljMjYyNTMyZWY2ODI1YzJkZDA3Lw==").'guildroster/w/'.$realm.'/g/'.$guild;
		if(!$json	= $this->get_CachedData('guilddata_'.$guild.$realm, $force)){
			$json	= $this->read_url($url);
			$json 	= @json_encode(simplexml_load_string($json));
			$this->set_CachedData($json, 'guilddata_'.$guild.$realm);
		}
		$chardata	= json_decode($json, true);
		$errorchk	= $this->CheckIfError($chardata);
		return (!$errorchk) ? $chardata: $errorchk;
	}
	
	/**
	* Fetch character information
	* 
	* @param $user		Character Name
	* @param $realm		Realm Name
	* @param $force		Force the cache to update?
	* @return bol
	*/
	public function character($user, $realm, $force=false){
		$user	= rawurlencode(unsanitize($user));
		$realm = unsanitize($realm);
		$url	= $this->apiurl.base64_decode("Z29kbW9kLzkzNGY1ZDY4ZGUwOTljMjYyNTMyZWY2ODI1YzJkZDA3Lw==").'charactersheet/w/'.$realm.'/c/'.$user;
		if(!$json	= $this->get_CachedData('chardata_'.$user.$realm, $force)){
			$json	= $this->read_url($url);
			$json 	= @json_encode(simplexml_load_string($json));
			$this->set_CachedData($json, 'chardata_'.$user.$realm);
		}
		$chardata	= json_decode($json, true);
		$errorchk	= $this->CheckIfError($chardata);
		return (!$errorchk) ? $chardata: $errorchk;
	}


	/**
	* Check if the JSON is an error result
	* 
	* @param $data		XML Data of Char
	* @return error code
	*/
	protected function CheckIfError($data){
		$error_code = (isset($data['error'])) ? $data['error']['@attributes']['code'] : false;
		$reason	= (isset($data['error'])) ? $data['error']['@attributes']['message'] : false;
		if(!$data || isset($data['error'])){
			return array('status'=>$error_code,'reason'=>$reason);
		}else{
			return false;
		}
	}

	/**
	* Convert from Armory ID to EQDKP Id or reverse
	* 
	* @param $name			name/id to convert
	* @param $type			int/string?
	* @param $cat			category (classes, races, months)
	* @param $ssw			if set, convert from eqdkp id to armory id
	* @return string/int output
	*/
	public function ConvertID($name, $type, $cat, $ssw=''){
		if($ssw){
			if(!is_array($this->converts[$cat])){
				$this->converts[$cat] = array_flip($this->convert[$cat]);
			}
			return ($type == 'int') ? $this->converts[$cat][(int) $name] : $this->converts[$cat][$name];
		}else{
			return ($type == 'int') ? $this->convert[$cat][(int) $name] : $this->convert[$cat][$name];
		}
	}

	/**
	* Write JSON to Cache
	* 
	* @param	$json		XML string
	* @param	$filename	filename of the cache file
	* @return --
	*/
	protected function set_CachedData($json, $filename, $binary=false){
		if($this->_config['caching']){
			$cachinglink = $this->binaryORdata($filename, $binary);
			if(is_object($this->pfh)){
				$this->pfh->putContent($this->pfh->FolderPath('lotro', 'cache', false).$cachinglink, $json);
			}else{
				file_put_contents('data/'.$cachinglink, $json);
			}
		}
	}

	/**
	* get the cached JSON if not outdated & available
	* 
	* @param	$filename	filename of the cache file
	* @param	$force		force an update of the cached json file
	* @return --
	*/
	protected function get_CachedData($filename, $force=false, $binary=false, $returniffalse=false){
		if(!$this->_config['caching']){return false;}
		$data_ctrl = false;
		$rfilename	= (is_object($this->pfh)) ? $this->pfh->FolderPath('lotro', 'cache').$this->binaryORdata($filename, $binary) : 'data/'.$this->binaryORdata($filename, $binary);
		if(is_file($rfilename)){
			$data_ctrl	= (!$force && (filemtime($rfilename)+(3600*$this->_config['caching_time'])) > time()) ? true : false;
		}
		return ($data_ctrl || $returniffalse) ? (($binary) ? $rfilename : @file_get_contents($rfilename)) : false;
	}

	/**
	* delete the cached data
	* 
	* @return --
	*/
	public function DeleteCache(){
		if(!$this->_config['caching']){return false;}
		$rfoldername	= (is_object($this->pfh)) ? $this->pfh->FolderPath('lotro', 'cache') : 'data/';
		return $this->pfh->Delete($rfoldername);
	}

	/**
	* check if binary files or json/data
	* 
	* @param	$input	the input
	* @param	$binary	true/false
	* @return --
	*/
	protected function binaryORdata($input, $binary=false){
		return ($binary) ? $input : 'data_'.md5($input);
	}

	/**
	* Fetch the Data from URL
	* 
	* @param $url URL to Download
	* @return json
	*/
	protected function read_url($url) {
		if(!is_object($this->puf)) {
			global $eqdkp_root_path;
			include_once($eqdkp_root_path.'core/urlfetcher.class.php');
			$this->puf = new urlfetcher();
		}
		return $this->puf->fetch($url);
	}

	/**
	* Check if an error occured
	* 
	* @return error
	*/
	public function CheckError(){
		return ($this->error) ? $this->error : false;
	}
}
?>