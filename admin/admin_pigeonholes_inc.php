<?php
// $Header: /cvsroot/bitweaver/_bit_pigeonholes/admin/admin_pigeonholes_inc.php,v 1.20 2007/10/21 08:34:35 squareing Exp $

$pigeonholeDisplaySettings = array(
	'pigeonholes_display_path' => array(
		'label' => 'Display Path',
		'note' => 'Display category paths above the page leading to the object.',
	),
	'pigeonholes_display_description' => array(
		'label' => 'Display Description',
		'note' => 'When showing the category members, you can display the category description as well.',
	),
	'pigeonholes_list_filter' => array(
		'label' => 'Listing Filter',
		'note' => 'When viewing a listing of content items, users can limit the listing based on category.',
	),
	'pigeonholes_display_members' => array(
		'label' => 'Display Members',
		'note' => 'Show the other members of the same categories at the bottom of the page.',
	),
);
$gBitSmarty->assign( 'pigeonholeDisplaySettings', $pigeonholeDisplaySettings );

$pigeonholeListSettings = array(
	'pigeonholes_display_subtree' => array(
		'label' => 'Display Subtree',
		'note' => 'When viewing the category list, you can display the subcategories as well.',
	),
	'pigeonholes_display_content_type' => array(
		'label' => 'Display Content Type',
		'note' => 'When viewing the category members, you can display the content type alongside the item.',
	),
);
$gBitSmarty->assign( 'pigeonholeListSettings', $pigeonholeListSettings );

$pigeonholeEditSettings = array(
	'pigeonholes_themes' => array(
		'label' => 'Theme selection',
		'note' => 'Allow the selection of a different theme to use for a category.',
	),
	'pigeonholes_permissions' => array(
		'label' => 'Permission gating',
		'note' => 'Limit category access to users with a given permission. Permission settings are inhertied by child categories.',
	),
	'pigeonholes_groups' => array(
		'label' => 'Group gating',
		'note' => 'Limit category access to specific groups. Group settings are inhertied by child categories.',
	),
	'pigeonholes_reverse_assign_table' => array(
		'label' => 'Assign Categories in Rows',
		'note' => 'The assign categories page will have categories in rows instead of columns and content in columns instead of rows. This is better if you have lots of categories that do not fit on a single page easily.',
	),
	'pigeonholes_allow_forbid_insertion' => array(
		'label' => 'Allow Forbid Insertion',
		'note' => 'Allows pigeonholes to be set to forbid insertion of new members. This is good for heirarchical categories where only leaf categories should have members.'
	),
);
$gBitSmarty->assign( 'pigeonholeEditSettings', $pigeonholeEditSettings );

$pigeonholeContentEditSettings = array(
	'pigeonholes_use_jstab' => array(
		'label' => 'Use separate Tab',
		'note' => 'When editing content use a separate tab to categorise.',
	),
);
$gBitSmarty->assign( 'pigeonholeContentEditSettings', $pigeonholeContentEditSettings );

$listStyles = array(
	'dynamic' => tra( 'Dynamic list' ),
	'table' => tra( 'Table based list' ),
);
$gBitSmarty->assign( 'listStyles', $listStyles );

// sensible table column numbers
$tableColumns = range( 0, 6 );
unset( $tableColumns[0] );
$gBitSmarty->assign( 'tableColumns', $tableColumns );

$memberLimit = array(
	'0'    => tra( 'Only display category title' ),
	'10'   => 10,
	'20'   => 20,
	'30'   => 30,
	'50'   => 50,
	'100'  => 100,
	'9999' => tra( 'Unlimited' ),
);
$gBitSmarty->assign( 'memberLimit', $memberLimit );

// various image sizes
$gBitSmarty->assign( 'imageSizes', get_image_size_options() );

if( !empty( $_REQUEST['pigeonhole_settings'] ) ) {
	$pigeonholeSettings = array_merge( $pigeonholeDisplaySettings, $pigeonholeContentEditSettings );
	foreach( array_keys( $pigeonholeSettings ) as $item ) {
		simple_set_toggle( $item, PIGEONHOLES_PKG_NAME );
	}

	simple_set_int( 'pigeonholes_display_columns', PIGEONHOLES_PKG_NAME );
	simple_set_int( 'pigeonholes_limit_member_number', PIGEONHOLES_PKG_NAME );
	simple_set_int( 'pigeonholes_scrolling_list_number', PIGEONHOLES_PKG_NAME );
	simple_set_value( 'pigeonholes_member_thumb', PIGEONHOLES_PKG_NAME );
	simple_set_value( 'pigeonholes_list_style', PIGEONHOLES_PKG_NAME );
}
?>
