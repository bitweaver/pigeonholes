{* $Header$ *}
{strip}
{if $gBitSystem->isFeatureActive( 'pigeonholes_display_members' ) and $modPigeonStructures}
	{bitmodule title="$moduleTitle" name="whats_related"}
		{foreach from=$modPigeonStructures item=subtree}
			{include file="bitpackage:pigeonholes/view_structure_inc.tpl" no_details=true no_edit=true}
		{/foreach}
	{/bitmodule}
{/if}
{/strip}
