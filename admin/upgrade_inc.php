<?php

global $gBitSystem, $gUpgradeFrom, $gUpgradeTo;

$upgrades = array(
	'BWR1' => array(
		'BWR2' => array(
			// STEP 1
			array( 'DATADICT' => array(
				array( 'DROPCOLUMN' => array(
					'bit_pigeonhole_members' => array( '`pos`' ),
				)),
			)),
		)
	)
);

if( isset( $upgrades[$gUpgradeFrom][$gUpgradeTo] ) ) {
	$gBitSystem->registerUpgrade( PIGEONHOLES_PKG_NAME, $upgrades[$gUpgradeFrom][$gUpgradeTo] );
}


?>
