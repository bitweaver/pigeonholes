{strip}
{if $gBitUser->hasPermission( 'p_pigeonholes_insert_member' )}
	{jstab title="Categorize"}
		{legend legend="Categorize"}
			{include file="bitpackage:pigeonholes/service_edit_mini_inc.tpl"}
		{/legend}
	{/jstab}
{/if}
{/strip}
