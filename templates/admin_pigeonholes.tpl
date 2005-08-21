{* $Header: /cvsroot/bitweaver/_bit_pigeonholes/templates/admin_pigeonholes.tpl,v 1.1 2005/08/21 16:22:47 squareing Exp $ *}
{strip}
{form}
	{legend legend="Category Settings"}
		<input type="hidden" name="page" value="{$page}" />
		{foreach from=$pigeonholeSettings key=feature item=output}
			<div class="row">
				{formlabel label=`$output.label` for=$feature}
				{forminput}
					{html_checkboxes name="$feature" values="y" checked=`$gBitSystemPrefs.$feature` labels=false id=$feature}
					{formhelp note=`$output.note` page=`$output.page`}
				{/forminput}
			</div>
		{/foreach}

		<div class="row">
			{formlabel label="Number of Members" for="member_number"}
			{forminput}
				{html_options name="limit_member_number" options=$memberLimit values=$memberLimit selected=`$gBitSystemPrefs.limit_member_number` id=member_number}
				{formhelp note="Here you can specify what number of members are displayed at the bottom of a page."}
			{/forminput}
		</div>

		<div class="row submit">
			<input type="submit" name="pigeonhole_settings" value="{tr}Change preferences{/tr}" />
		</div>
	{/legend}
{/form}
{/strip}
