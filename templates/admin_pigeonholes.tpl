{* $Header: /cvsroot/bitweaver/_bit_pigeonholes/templates/admin_pigeonholes.tpl,v 1.7 2006/04/19 10:29:20 sylvieg Exp $ *}
{strip}
{form}
	{legend legend="Category Settings"}
		<input type="hidden" name="page" value="{$page}" />
		{foreach from=$pigeonholeSettings key=feature item=output}
			<div class="row">
				{formlabel label=`$output.label` for=$feature}
				{forminput}
					{html_checkboxes name="$feature" values="y" checked=$gBitSystem->getConfig($feature) labels=false id=$feature}
					{formhelp note=`$output.note` page=`$output.page`}
				{/forminput}
			</div>
		{/foreach}

		<div class="row">
			{formlabel label="List style" for="pigeonholes_list_style"}
			{forminput}
				{html_options name="pigeonholes_list_style" options=$listStyles values=$listStyles selected=$gBitSystem->getConfig('pigeonholes_list_style') id=pigeonholes_list_style}
				{formhelp note="Select the display method. Table listing is better suited for large categories.<br />Custom sorting only works with the dynamic list method."}
			{/forminput}
		</div>

		<div class="row">
			{formlabel label="Number of Members" for="member_number"}
			{forminput}
				{html_options name="pigeonholes_limit_member_number" options=$memberLimit values=$memberLimit selected=$gBitSystem->getConfig('pigeonholes_limit_member_number') id=member_number}
				{formhelp note="Here you can specify what number of members are displayed at the bottom of a page."}
			{/forminput}
		</div>

		<div class="row">
			{formlabel label="Number of categories for a scrolling list" for="pigeonholes_scrolling_list_number"}
			{forminput}
				<input type="text" size="4" maxlength="4" name="pigeonholes_scrolling_list_number" id="pigeonholes_scrolling_list_number" value="{$gBitSystem->getConfig('pigeonholes_scrolling_list_number')}" />
			{formhelp note="If you have more than this number of categories, categories selection is displayed in a scrolling list instead of checkboxes"}
			{/forminput}
		</div>

		<div class="row submit">
			<input type="submit" name="pigeonhole_settings" value="{tr}Change preferences{/tr}" />
		</div>
	{/legend}
{/form}
{/strip}
