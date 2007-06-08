<?php
/**
 * $Header: /cvsroot/bitweaver/_bit_pigeonholes/modules/mod_category_suckerfish.php,v 1.2 2007/06/08 00:30:09 nickpalmer Exp $
 *
 * Copyright (c) 2007 bitweaver.org
 *
 * All Rights Reserved. See copyright.txt for details and a complete list of authors.
 * Licensed under the GNU LESSER GENERAL PUBLIC LICENSE. See license.txt for details
 *
 * $Id: mod_category_suckerfish.php,v 1.2 2007/06/08 00:30:09 nickpalmer Exp $
 * @package pigeonholes
 * @subpackage modules
 */

global $gBitSmarty, $gQueryUserId, $gBitThemes, $module_rows, $moduleParams, $gBitSystem;

$module_rows = $moduleParams['module_rows'];
$module_params = $moduleParams['module_params'];
$module_title = $moduleParams['module_title'];

$gBitSmarty->assign( 'moduleTitle', isset($module_title) ? $module_title: 'Categories');

$ns = array();
if($gBitSystem->isPackageActive('pigeonholes')) {
	require_once(PIGEONHOLES_PKG_PATH.'Pigeonholes.php');

	$p = new Pigeonholes();
	$s = new LibertyStructure();

	// Prep get list screws with us.
	$listHash = array('load_only_root'=> TRUE, 'sort_mode' => 'ls.structure_id_asc', 'offset' => 0, 'max_records' => '999999', 'find' => '');
	if (!empty($module_params['root_structure_id'])) {
	  $listHash['root_structure_id'] = $module_params['root_structure_id'];
	}

	$l = $p->getList($listHash);
	foreach ($l as $e) {
		$d = $s->getSubTree( $e['structure_id'] );
		$d_o = array();
		foreach ($d as $c) {
			$pos_var = &$d_o;
			if($c['level']!=0) {
				$pos = explode(".",$c['pos']);
				$pos_var = &$d_o;
				foreach ($pos as $pos_v) {
					if (!isset($pos_var['children'])) {
						$pos_var['children']=array();
					}
					if (!isset($pos_var['children'][$pos_v-1])) {
						$pos_var['children'][$pos_v-1]=array();
					}
					$pos_var = &$pos_var['children'][$pos_v-1];
				}
			}
			if (empty($pos_var['data'])) {
				$pos_var['children']=array();
				$c['display_url']=$p->getDisplayUrl($c['content_id']);
				$pos_var['data']=$c;
			}
		}
		$ns[]=$d_o;
	}

}

if (!defined('MENU_LEVELS_DEFINED')) {
	function menuLevels($levels, $l) {
		global $gContent, $module_rows;
		if (!$l) {
			$ret = '<ul class="menu ver">';
		}
		else {
			$ret = '<ul>';
		}
		foreach ($levels as $key => $level) {
			$ret .= '<li';
			if (!empty($gContent->mContentId) && $gContent->mContentId == $level['data']['content_id']) {
				$ret  .= ' class="selected"';
			}
			$ret .= '><a class="item';
			if (!empty($gContent->mContentId) && $gContent->mContentId == $level['data']['content_id']) {
				$ret .= ' selected';
			}
			$ret .= '" title="'.htmlspecialchars($level['data']['title']).'"';
			$ret .= ' href="'.$level['data']['display_url'].'">';
			$ret .= htmlspecialchars($level['data']['title']);
			$ret .= '</a>';
			if (!empty($level['children']) && $l < $module_rows) {
			$ret .= menuLevels($level['children'], $l + 1);
			}
			$ret .= '</li>';
		}
		$ret .= '</ul>';
		
		return $ret;
	}
	define('MENU_LEVELS_DEFINED', 1);
}

if (!empty($module_params['expand_root']) && $module_params['expand_root']) {
	if (isset($ns[0]) && !empty($ns[0]['children'])) {
        	$gBitSmarty->assign('pigeonMenu', menuLevels($ns[0]['children'], 0));
	}
	else if (!empty($ns[0]['children'])) {
        	$gBitSmarty->assign('pigeonMenu', menuLevels($ns['children'], 0));
	}
}
else {
	$gBitSmarty->assign('pigeonMenu', menuLevels($ns, 0));
}

$gBitSmarty->assign('pigeonholesPackageActive', $gBitSystem->isPackageActive('pigeonholes'));
?>