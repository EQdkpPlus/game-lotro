<?php
 /*
 * Project:		EQdkp-Plus
 * License:		Creative Commons - Attribution-Noncommercial-Share Alike 3.0 Unported
 * Link:		http://creativecommons.org/licenses/by-nc-sa/3.0/
 * -----------------------------------------------------------------------
 * Began:		2011
 * Date:		$Date$
 * -----------------------------------------------------------------------
 * @author		$Author$
 * @copyright	2006-2011 EQdkp-Plus Developer Team
 * @link		http://eqdkp-plus.com
 * @package		eqdkp-plus
 * @version		$Rev$
 * 
 * $Id$
 */
 
define('EQDKP_INC', true);
$eqdkp_root_path = './../../../';
include_once ($eqdkp_root_path . 'common.php');

class charImporter extends page_generic {

	public function __construct() {
		$handler = array(
			'massupdate'		=> array('process' => 'perform_massupdate'),
			'resetcache'		=> array('process' => 'perform_resetcache'),
			'ajax_massupdate'	=> array('process' => 'ajax_massupdate'),
			'ajax_mudate'		=> array('process' => 'ajax_massupdatedate'),
		);
		parent::__construct(false, $handler, array());
		$this->user->check_auth('u_member_man');
		$this->user->check_auth('u_member_add');
		$this->game->new_object('lotro_data', 'ldata', array());
		$this->process();
	}

	public function perform_resetcache(){
		// delete the cache folder
		$this->game->obj['ldata']-> DeleteCache();

		// Output the success message
		$hmtlout = '<div id="guildimport_dataset">
						<div id="controlbox">
							<fieldset class="settings">
								<dl>
									'.$this->game->glang('uc_importcache_cleared').'
								</dl>
							</fieldset>
						</div>
					</div>';

		$this->tpl->assign_vars(array(
			'DATA'		=> $hmtlout,
			'STEP'		=> ''
		));

		$this->core->set_vars(array(
			'page_title'		=> $this->user->lang('raidevent_raid_guests'),
			'header_format'		=> 'simple',
			'template_file'		=> 'importer.html',
			'display'			=> true
		));
	}

	public function perform_massupdate(){
		// check permission again, cause this is for admins only
		$this->user->check_auth('a_members_man');

		$memberArry	= array();
		$members	= $this->pdh->get('member', 'names', array());
		if(is_array($members)){
			asort($members);
			foreach($members as $membernames){
				if($membernames != ''){
					$charid = $this->pdh->get('member', 'id', array($membernames));
					if($charid){
						$memberArry[] = array(
							'charname'	=> $membernames,
							'charid'	=> $charid,
						);
					}
				}
			}
		}
		$hmtlout = '<div id="guildimport_dataset">
						<div id="controlbox">
							<fieldset class="settings">
								<dl>
									'.$this->game->glang('uc_massupd_loading').'
									<div id="progressbar"></div>
								</dl>
							</fieldset>
						</div>
						<fieldset class="settings data">
						</fieldset>
					</div>';

		$this->tpl->add_js('$( "#progressbar" ).progressbar({ value: 0 }); getData();', 'docready');
		$this->tpl->add_js('
			var chardataArry = $.parseJSON(\''.json_encode($memberArry).'\');
			function getData(i){
				if (!i)
					i=0;
	
				if (chardataArry.length >= i){
					$.post("charimporter.php?ajax_massupdate=true&totalcount="+chardataArry.length+"&actcount="+i, chardataArry[i], function(data){
						chardata = $.parseJSON(data);
						if(chardata.success == "imported"){
							successdata = "<span style=\"color:green;\">'.$this->game->glang('uc_armory_updated').'</span>";
						}else{
							successdata = "<span style=\"color:red;\">'.$this->game->glang('uc_armory_updfailed').'<br/>"+
							((chardata.error) ? "'.$this->game->glang('uc_armory_impfail_reason').' "+chardata.error : "")+"</span>";
						}
						$("#guildimport_dataset fieldset.data").prepend("<dl><dt><label><img src=\""+ chardata.image +"\" alt=\"charicon\" height=\"84\" width=\"84\" /></label></dt><dd>"+ chardata.name+"<br/>"+ successdata +"</dd></dl>").children(":first").hide().fadeIn("slow");
						$("#progressbar").progressbar({ value: ((i/chardataArry.length)*100) })
						if(chardataArry.length > i+1){
							getData(i+1);
						}else{
							$.post("charimporter.php?ajax_mudate=true");
							$("#controlbox").html("<dl><div class=\"infobox infobox-large infobox-green clearfix\"><i class=\"fa fa-check fa-4x pull-left\"></i> '.$this->game->glang('uc_cupdt_header_fnsh').'</div></dl>").fadeIn("slow");
							return;
						}
					});
				}
			}');

		$this->tpl->assign_vars(array(
			'DATA'		=> $hmtlout,
			'STEP'		=> ''
		));

		$this->core->set_vars(array(
			'page_title'		=> $this->user->lang('raidevent_raid_guests'),
			'header_format'		=> 'simple',
			'template_file'		=> 'importer.html',
			'display'			=> true
		));
	}

	public function ajax_massupdatedate(){
		$this->config->set(array('uc_profileimported'=> $this->time->time));
	}

	public function ajax_massupdate(){
		$chardata	= $this->game->obj['ldata']->character($this->in->get('charname', ''), $this->config->get('uc_servername'), true);

		if(!isset($chardata['status'])){
			$errormsg	= '';
			$charname	= $chardata['character']['@attributes']['name'];
			$cdata 		= $chardata['character']['@attributes'];
			
			$arrUpdateData = array(
				'name'				=> $this->in->get('charname', ''),
				'lvl'				=> $cdata['level'],
				'raceid'			=> $this->game->obj['ldata']->ConvertID($cdata['race_id'], 'int', 'races'),
				'classid'			=> $this->game->obj['ldata']->ConvertID($cdata['class_id'], 'int', 'classes'),
				'guild'				=> $chardata['character']['guild']['@attributes']['name'],
			);
			
			if (isset($chardata['character']['vocation'])){
				$arrVocationData = array(
					'vocation'			=> strtolower($chardata['character']['vocation']['@attributes']['name']),
					'profession1'		=> strtolower($chardata['character']['vocation']['professions']['profession'][0]['@attributes']['name']),
					'profession2'		=> strtolower($chardata['character']['vocation']['professions']['profession'][1]['@attributes']['name']),
					'profession3'		=> strtolower($chardata['character']['vocation']['professions']['profession'][2]['@attributes']['name']),
					'profession1_proficiency' => intval($chardata['character']['vocation']['professions']['profession'][0]['@attributes']['proficiency']),
					'profession2_proficiency' => intval($chardata['character']['vocation']['professions']['profession'][1]['@attributes']['proficiency']),
					'profession3_proficiency' => intval($chardata['character']['vocation']['professions']['profession'][2]['@attributes']['proficiency']),
					'profession1_mastery' => intval($chardata['character']['vocation']['professions']['profession'][0]['@attributes']['mastery']),
					'profession2_mastery' => intval($chardata['character']['vocation']['professions']['profession'][1]['@attributes']['mastery']),
					'profession3_mastery' => intval($chardata['character']['vocation']['professions']['profession'][2]['@attributes']['mastery']),
				);
				$arrUpdateData = array_merge($arrUpdateData, $arrVocationData);
			}
			
			// insert into database
			$info		= $this->pdh->put('member', 'addorupdate_member', array($this->in->get('charid', 0), $arrUpdateData, $this->in->get('overtakeuser')));
			$this->pdh->process_hook_queue();
			$successmsg	= ($info) ? 'imported' : 'error';
		}else{
			$successmsg	= 'error';
			$errormsg	= $chardata['reason'];
			$charname	= $this->in->get('charname', '');
		}
		$charicon	= $this->root_path.'images/global/avatar-default.svg';

		die(json_encode(array(
			'image'		=> $charicon,
			'name'		=> $charname,
			'success'	=> $successmsg,
			'error'		=> $errormsg
		)));
	}

	public function perform_step0(){
		$tmpmemname = '';
		if($this->in->get('member_id', 0) > 0){
			$tmpmemname = $this->pdh->get('member', 'name', array($this->in->get('member_id', 0)));
		}

		// generate output
		$hmtlout = '<fieldset class="settings mediumsettings">
			<dl>
				<dt><label>'.$this->game->glang('uc_charname').'</label></dt>
				<dd>'.new htext('charname', array('value' => (($tmpmemname) ? $tmpmemname : ''), 'size' => '25')).'</dd>
			</dl>';
		
		// Server Name
		$hmtlout .= '<dl>
				<dt><label>'.$this->game->glang('uc_servername').'</label></dt>
				<dd>';
		if($this->config->get('uc_lockserver') == 1){
			$hmtlout .= ' @'.stripslashes($this->config->get('uc_servername')).'<br/>';
			$hmtlout .= new hhidden('servername', array('value' => stripslashes($this->config->get('uc_servername'))));
		}else{
			$hmtlout .= new htext('servername', array('value' => (($this->config->get('uc_servername')) ? stripslashes($this->config->get('uc_servername')) : ''), 'size' => '25'));
		}
		$hmtlout .= '</dd>
			</dl>';
		
		$hmtlout .= '</fieldset>';
		$hmtlout .= '<br/><input type="submit" name="submiti" value="'.$this->game->glang('uc_import_forw').'" class="mainoption" />';
		return $hmtlout;
	}

	public function perform_step1(){
		$hmtlout = '';
		if($this->in->get('member_id', 0) > 0){
			// We'll update an existing one...
			$isindatabase	= $this->in->get('member_id', 0);
			$isMemberName	= $this->pdh->get('member', 'name', array($isindatabase));
			$isServerName	= $this->config->get('uc_servername');
			$is_mine		= ($this->pdh->get('member', 'userid', array($isindatabase)) == $this->user->data['user_id']) ? true : false;
		}else{
			// Check for existing member name
			$isindatabase	= $this->pdh->get('member', 'id', array($this->in->get('charname')));
			$hasuserid		= ($isindatabase > 0) ? $this->pdh->get('member', 'userid', array($isindatabase)) : 0;
			$isMemberName	= $this->in->get('charname');
			$isServerName	= $this->in->get('servername');
			if($this->user->check_auth('a_charmanager_config', false)){
				$is_mine	= true;			// We are an administrator, its always mine..
			}else{
				$is_mine	= (($hasuserid > 0) ? (($hasuserid == $this->user->data['user_id']) ? true : false) : true);	// we are a normal user
			}
		}

		if($is_mine){
			// Load the Armory Data
			$chardata	= $this->game->obj['ldata']->character($isMemberName, $isServerName, true);
			$cdata = $chardata['character']['@attributes'];
			$arrStats = array();
			foreach ($chardata['character']['stats']['stat'] as $value){
				$arrStats[$value['@attributes']['name']] = $value['@attributes']['value'];
			}

			// Basics
			$hmtlout	.= new hhidden('member_id', array('value' => $isindatabase));
			$hmtlout	.= new hhidden('member_name', array('value' => $isMemberName));
			$hmtlout	.= new hhidden('member_level', array('value' => $cdata['level']));

			$hmtlout	.= new hhidden('member_race_id', array('value' => $this->game->obj['ldata']->ConvertID($cdata['race_id'], 'int', 'races')));
			$hmtlout	.= new hhidden('member_class_id', array('value' => $this->game->obj['ldata']->ConvertID($cdata['class_id'], 'int', 'classes')));
			$hmtlout	.= new hhidden('guild', array('value' => $chardata['character']['guild']['@attributes']['name']));

			if (isset($chardata['character']['vocation'])){
				$hmtlout	.= new hhidden('vocation', array('value' => strtolower($chardata['character']['vocation']['@attributes']['name'])));
				$hmtlout	.= new hhidden('server_loc', array('value' => $this->config->get('uc_server_loc')));
				$hmtlout	.= new hhidden('profession1', array('value' => strtolower($chardata['character']['vocation']['professions']['profession'][0]['@attributes']['name'])));
				$hmtlout	.= new hhidden('profession2', array('value' => strtolower($chardata['character']['vocation']['professions']['profession'][1]['@attributes']['name'])));
				$hmtlout	.= new hhidden('profession3', array('value' => strtolower($chardata['character']['vocation']['professions']['profession'][2]['@attributes']['name'])));
				$hmtlout	.= new hhidden('profession1_proficiency', array('value' => intval($chardata['character']['vocation']['professions']['profession'][0]['@attributes']['proficiency'])));
				$hmtlout	.= new hhidden('profession2_proficiency', array('value' => intval($chardata['character']['vocation']['professions']['profession'][1]['@attributes']['proficiency'])));
				$hmtlout	.= new hhidden('profession3_proficiency', array('value' => intval($chardata['character']['vocation']['professions']['profession'][2]['@attributes']['proficiency'])));
				$hmtlout	.= new hhidden('profession1_mastery', array('value' => intval($chardata['character']['vocation']['professions']['profession'][0]['@attributes']['mastery'])));
				$hmtlout	.= new hhidden('profession2_mastery', array('value' => intval($chardata['character']['vocation']['professions']['profession'][1]['@attributes']['mastery'])));
				$hmtlout	.= new hhidden('profession3_mastery', array('value' => intval($chardata['character']['vocation']['professions']['profession'][2]['@attributes']['mastery'])));
			}
			
			// viewable Output
			if(!isset($chardata['status'])){
				$hmtlout	.= '
					<div class="infobox infobox-large infobox-red clearfix">
						<i class="fa fa-exclamation-triangle fa-4x pull-left"></i> '.$this->game->glang('uc_charfound3').'
					</div>

				<fieldset class="settings mediumsettings">
					<dl>
						<dt><label><img src="'.$this->root_path.'images/global/avatar-default.svg" name="char_icon" alt="icon" width="44px" height="44px" align="middle" /></label></dt>
						<dd>
							'.sprintf($this->game->glang('uc_charfound'), $isMemberName).'
						</dd>
					</dl>
					<dl>';
				if(!$isindatabase){
					if($this->user->check_auth('u_member_conn', false)){
						$hmtlout	.= $this->user->lang('overtake_char').' '.new hradio('overtakeuser', array('value' => 1));
					}else{
						$hmtlout	.= $this->user->lang('overtake_char').' '.new hradio('overtakeuser', array('value' => 1, 'disabled' => true));
						$hmtlout	.= new hhidden('overtakeuser', array('value' => '1'));
					}
				}
				$hmtlout	.= '
					</dl>
					</fieldset>';
				$hmtlout		.= '<center><input type="submit" name="submiti" value="'.$this->game->glang('uc_prof_import').'" class="mainoption" /></center>';
			}else{
				$hmtlout		.= '<div class="infobox infobox-large infobox-red clearfix">
										<i class="fa fa-exclamation-triangle fa-4x pull-left"></i> <b>WARNING: </b> '.$chardata['reason'].'
									</div>';
			}
		}else{
			$hmtlout	.= '<div class="infobox infobox-large infobox-red clearfix">
								<i class="fa fa-exclamation-triangle fa-4x pull-left"></i> '.$this->game->glang('uc_notyourchar').'
							</div>';
		}
		return $hmtlout;
	}

	public function perform_step2(){
		$data = array(
			'name'				=> $this->in->get('member_name'),
			'lvl'				=> $this->in->get('member_level', 0),
			'raceid'			=> $this->in->get('member_race_id', 0),
			'classid'			=> $this->in->get('member_class_id', 0),
			'guild'				=> $this->in->get('guild',''),
			'vocation'			=> $this->in->get('vocation', ''),
			'profession1'		=> $this->in->get('profession1', ''),
			'profession2'		=> $this->in->get('profession2', ''),
			'profession3'		=> $this->in->get('profession3', ''),
			'profession1_proficiency' => $this->in->get('profession1_proficiency', 0),
			'profession2_proficiency' => $this->in->get('profession2_proficiency', 0),
			'profession3_proficiency' => $this->in->get('profession3_proficiency', 0),
			'profession1_mastery' => $this->in->get('profession1_mastery', 0),
			'profession2_mastery' => $this->in->get('profession2_mastery', 0),
			'profession3_mastery' => $this->in->get('profession3_mastery', 0),
		);
		$info		= $this->pdh->put('member', 'addorupdate_member', array($this->in->get('member_id', 0), $data, $this->in->get('overtakeuser')));
		$this->pdh->process_hook_queue();
		if($info){
			$hmtlout	= '<div class="infobox infobox-large infobox-green clearfix">
								<i class="fa fa-check fa-4x pull-left"></i> '.$this->game->glang('uc_armory_updated').'
							</div>';
		}else{
			$hmtlout	= '<div class="infobox infobox-large infobox-red clearfix">
								<i class="fa fa-exclamation-triangle fa-4x pull-left"></i> '.$this->game->glang('uc_armory_updfailed').'
							</div>';
		}
		return $hmtlout;
	}

	public function display(){
		$stepnumber		= ($this->config->get('uc_servername') && $this->config->get('uc_server_loc') && $this->in->get('member_id',0) > 0 && $this->in->get('step',0) == 0) ? 1 : $this->in->get('step',0);
		$urladdition	 = ($this->in->get('member_id',0)) ? '&amp;member_id='.$this->in->get('member_id',0) : '';
		$funcname		 = 'perform_step'.$stepnumber;
		$this->tpl->assign_vars(array(
			'DATA'		=> $this->$funcname(),
			'STEP'		=> ($stepnumber+1).$urladdition
		));

		$this->core->set_vars(array(
			'page_title'		=> $this->user->lang('raidevent_raid_guests'),
			'header_format'		=> 'simple',
			'template_file'		=> 'importer.html',
			'display'			=> true
		));
	}
}
registry::register('charImporter');
?>