{* $Header: /cvsroot/bitweaver/_bit_pigeonholes/modules/mod_category_menu.tpl,v 1.2 2006/04/14 20:25:52 squareing Exp $ *}
{strip}
{if $gBitSystem->isFeatureActive( 'pigeonholes_display_members' ) and $modPigeonStructures}
	{bitmodule title="$moduleTitle" name="whats_related"}
		{foreach from=$modPigeonStructures item=subtree}
			{include file="bitpackage:pigeonholes/view_structure_inc.tpl" no_details=true no_edit=true}
		{/foreach}
	{/bitmodule}
{/if}
{/strip}
