<?php

global $gBitSystem, $gUpgradeFrom, $gUpgradeTo;

$upgrades = array(
	'BWR1' => array(
		'BWR2' => array(
// transfer all the pigeonhole settings into liberty_content_prefs
array( 'PHP' => '
	global $gBitSystem;
	$pigeonhole_settings = $gBitSystem->mDb->getAssoc( "SELECT `content_id`, `name`, `value` FROM `".BIT_DB_PREFIX."bit_pigeonhole_settings`" );
	if( !empty( $pigeonhole_settings ) ) {
		foreach( $pigeonhole_settings as $store ) {
			$query = "INSERT INTO `".BIT_DB_PREFIX."liberty_content_prefs` (`content_id`,`pref_name`,`pref_value`) VALUES(?, ?, ?)";
			$result = $this->mDb->query( $query, $store );
		}
	}
'),

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
	// remove bit_ prefix
	array( 'RENAMETABLE' => array(
		'bit_pigeonholes' => 'pigeonholes',
		'bit_pigeonhole_members' => 'pigeonhole_members',
	)),
)),

// add constraints for pgsql
array( 'QUERY' =>
	array( 'PGSQL' => array(
		"ALTER TABLE `".BIT_DB_PREFIX."pigeonholes` ADD CONSTRAINT `pigeonholes_content_ref` FOREIGN KEY (`content_id`) REFERENCES `".BIT_DB_PREFIX."liberty_content`( `content_id` )",
		"ALTER TABLE `".BIT_DB_PREFIX."pigeonholes` ADD CONSTRAINT `pigeonholes_structure_ref` FOREIGN KEY (`structure_id`) REFERENCES `".BIT_DB_PREFIX."liberty_structures`( `structure_id` )",
		"ALTER TABLE `".BIT_DB_PREFIX."pigeonholes_members` ADD CONSTRAINT `pigeonhole_members_parent_ref` FOREIGN KEY (`parent_id`) REFERENCES `".BIT_DB_PREFIX."liberty_content`( `content_id` )",
		"ALTER TABLE `".BIT_DB_PREFIX."pigeonholes_members` ADD CONSTRAINT `pigeonhole_members_content_ref` FOREIGN KEY (`content_id`) REFERENCES `".BIT_DB_PREFIX."liberty_content`( `content_id` )",
	)),
),
		)
	)
);

if( isset( $upgrades[$gUpgradeFrom][$gUpgradeTo] ) ) {
	$gBitSystem->registerUpgrade( PIGEONHOLES_PKG_NAME, $upgrades[$gUpgradeFrom][$gUpgradeTo] );
}

?>
