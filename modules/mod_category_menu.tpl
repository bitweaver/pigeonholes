{* $Header: /cvsroot/bitweaver/_bit_pigeonholes/modules/mod_category_menu.tpl,v 1.1 2006/02/04 17:47:24 squareing Exp $ *}
{strip}
{if $gBitSystem->isFeatureActive( 'display_pigeonhole_members' ) and $modPigeonStructures}
	{bitmodule title="$moduleTitle" name="whats_related"}
		{foreach from=$modPigeonStructures item=subtree}
			{include file="bitpackage:pigeonholes/view_structure_inc.tpl" no_details=true no_edit=true}
		{/foreach}
	{/bitmodule}
{/if}
{/strip}
