<?php
// $Header: /cvsroot/bitweaver/_bit_pigeonholes/admin/admin_pigeonholes_inc.php,v 1.7 2006/04/19 10:29:19 sylvieg Exp $

$pigeonholeSettings = array(
	'display_pigeonhole_path' => array(
		'label' => 'Display Path',
		'note' => 'Display category paths above the page leading to the object.',
	),
	'pigeonholes_display_members' => array(
		'label' => 'Display Members',
		'note' => 'Show the other members of the same categories at the bottom of the page.',
	),
	'display_pigeonhole_description' => array(
		'label' => 'Display Description',
		'note' => 'When showing the category members, you can display the category description as well.',
	),
	'pigeonholes_themes' => array(
		'label' => 'Enable variable theme selection',
		'note' => 'Allow the selection of a different theme to use for a category.',
	),
	'pigeonholes_permissions' => array(
		'label' => 'Enable variable permission selection',
		'note' => 'Allow the selection of different permissions to use for each category.',
	),
	'pigeonholes_groups' => array(
		'label' => 'Enable group management selection',
		'note' => 'Allow the selection of a different group to use for a category.',
	),
);
$gBitSmarty->assign( 'pigeonholeSettings', $pigeonholeSettings );

$listStyles = array(
	'dynamic' => tra( 'Dynamic list' ),
	'table' => tra( 'Table based list' ),
);
$gBitSmarty->assign( 'listStyles', $listStyles );

$memberLimit = array(
	'0' => tra( 'None' ),
	'10' => 10,
	'20' => 20,
	'30' => 30,
	'50' => 50,
	'100' => 100,
	'9999' => tra( 'Unlimited' ),
);
$gBitSmarty->assign( 'memberLimit', $memberLimit );

if( !empty( $_REQUEST['pigeonhole_settings'] ) ) {
	foreach( array_keys( $pigeonholeSettings ) as $item ) {
		simple_set_toggle( $item, PIGEONHOLES_PKG_NAME );
	}

	simple_set_value( 'pigeonholes_limit_member_number', PIGEONHOLES_PKG_NAME );
	simple_set_value( 'pigeonholes_list_style', PIGEONHOLES_PKG_NAME );
	simple_set_value( 'pigeonholes_scrolling_list_number', PIGEONHOLES_PKG_NAME );
}
?>
