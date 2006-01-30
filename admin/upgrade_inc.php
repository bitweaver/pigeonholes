<?php

global $gBitSystem, $gUpgradeFrom, $gUpgradeTo;

$upgrades = array(
	'BWR1' => array(
		'BWR2' => array(

// transfer all the settings into liberty_content_prefs
array( 'PHP' => '
	global $gBitSystem;
	$pigeonhole_settings = $gBitSystem->mDb->getAssoc( "SELECT `content_id`, `name`, `value` FROM `".BIT_DB_PREFIX."bit_pigeonhole_settings` );
	if( !empty( $pigeonhole_settings ) ) {
		foreach( $pigeonhole_settings as $store ) {
			$query = "INSERT INTO `".BIT_DB_PREFIX."liberty_content_prefs` (`content_id`,`name`,`value`) VALUES(?, ?, ?)";
			$result = $this->mDb->query( $query, $store );
		}
	}
'),

// add constraints for pgsql
array( 'QUERY' =>
	array( 'PGSQL' => array(
		"ALTER TABLE `".BIT_DB_PREFIX."bit_pigeonholes` ADD CONSTRAINT `bit_pigeonholes_ref` FOREIGN KEY (`content_id`) REFERENCES `".BIT_DB_PREFIX."tiki_content`( `content_id` )",
		"ALTER TABLE `".BIT_DB_PREFIX."bit_pigeonholes` ADD CONSTRAINT `bit_pigeonholes_ref` FOREIGN KEY (`structure_id`) REFERENCES `".BIT_DB_PREFIX."tiki_structures`( `structure_id` )",
		"ALTER TABLE `".BIT_DB_PREFIX."bit_pigeonhole_members` ADD CONSTRAINT `bit_pigeonhole_members_ref` FOREIGN KEY (`parent_id`) REFERENCES `".BIT_DB_PREFIX."bit_pigeonholes`( `content_id` )",
		"ALTER TABLE `".BIT_DB_PREFIX."bit_pigeonhole_members` ADD CONSTRAINT `bit_pigeonhole_members_ref` FOREIGN KEY (`content_id`) REFERENCES `".BIT_DB_PREFIX."tiki_content`( `content_id` )",
	)),
),

// drop unused columns and tables
array( 'DATADICT' => array(
	// positional data for categories is just odd
	array( 'DROPCOLUMN' => array(
		'bit_pigeonhole_members' => array( '`pos`' ),
	)),
	// bw now has liberty_content_prefs for this
	array( 'DROPTABLE' => array(
		'bit_pigeonhole_settings',
	)),
//	// remove bit_ prefix
//	array( 'RENAMETABLE' => array(
//		'bit_pigeonholes' => 'pigeonholes',
//		'bit_pigeonhole_members' => 'pigeonhole_members',
//	)),
)),
		)
	)
);

if( isset( $upgrades[$gUpgradeFrom][$gUpgradeTo] ) ) {
	$gBitSystem->registerUpgrade( PIGEONHOLES_PKG_NAME, $upgrades[$gUpgradeFrom][$gUpgradeTo] );
}

?>
