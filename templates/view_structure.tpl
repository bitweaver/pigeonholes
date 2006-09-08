{strip}
<div class="display pigeonholes">
	<div class="header">
		<h1>{tr}Category Listing{/tr}</h1>
	</div>

	<div class="body">
		{if $gBitSystem->getConfig('pigeonholes_list_style') == "table"}
			{include file="bitpackage:pigeonholes/view_structure_inc.tpl" no_details=true no_edit=true}
			{formfeedback hash=$memberFeedback}
			{include file="bitpackage:pigeonholes/view_table_inc.tpl" no_details=true no_edit=true}
		{else}
			{include file="bitpackage:pigeonholes/view_structure_inc.tpl"}
		{/if}
	</div><!-- end .body -->
</div><!-- end .liberty -->
{/strip}
