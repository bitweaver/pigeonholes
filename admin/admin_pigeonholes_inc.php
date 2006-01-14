<?php
// $Header: /cvsroot/bitweaver/_bit_pigeonholes/admin/admin_pigeonholes_inc.php,v 1.2 2006/01/14 19:55:19 squareing Exp $

$pigeonholeSettings = array(
	'display_pigeonhole_path' => array(
		'label' => 'Display Path',
		'note' => 'Display category paths above the page leading to the object.',
	),
	'display_pigeonhole_members' => array(
		'label' => 'Display Members',
		'note' => 'Show the other members of the same categories at the bottom of the page.',
	),
	'display_pigeonhole_description' => array(
		'label' => 'Display Description',
		'note' => 'When showing the category members, you can display the category description as well.',
	),
	'custom_member_sorting' => array(
		'label' => 'Custom Sorting',
		'note' => 'This will change the way category members are displayed. It allows you to sort the members manually.',
	),
);
$gBitSmarty->assign( 'pigeonholeSettings', $pigeonholeSettings );

$listStyles = array(
	'dynamic' => tra( 'Dynamic list' ),
	'table' => tra( 'Table based list' ),
);
$gBitSmarty->assign( 'listStyles', $listStyles );

$memberLimit = array(
	'9999' => tra( 'Unlimited' ),
	'10' => 10,
	'20' => 20,
	'30' => 30,
	'50' => 50,
	'100' => 100,
);
$gBitSmarty->assign( 'memberLimit', $memberLimit );

if( !empty( $_REQUEST['pigeonhole_settings'] ) ) {
	foreach( array_keys( $pigeonholeSettings ) as $item ) {
		simple_set_toggle( $item, PIGEONHOLES_PKG_NAME );
	}

	simple_set_value( 'limit_member_number', PIGEONHOLES_PKG_NAME );
	simple_set_value( 'pigeonholes_list_style', PIGEONHOLES_PKG_NAME );
}
?>
