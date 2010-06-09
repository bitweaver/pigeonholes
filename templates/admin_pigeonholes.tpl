{* $Header$ *}
{strip}
{form}
	<input type="hidden" name="page" value="{$page}" />

	{legend legend="Display Settings"}
		{foreach from=$pigeonholeDisplaySettings key=feature item=output}
			<div class="row">
				{formlabel label=`$output.label` for=$feature}
				{forminput}
					{html_checkboxes name="$feature" values="y" checked=$gBitSystem->getConfig($feature) labels=false id=$feature}
					{formhelp note=`$output.note` page=`$output.page`}
				{/forminput}
			</div>
		{/foreach}

		<div class="row">
			{formlabel label="Number of Members" for="member_number"}
			{forminput}
				{html_options name="pigeonholes_limit_member_number" options=$memberLimit values=$memberLimit selected=$gBitSystem->getConfig('pigeonholes_limit_member_number') id=member_number}
				{formhelp note="Here you can specify what number of members are displayed at the bottom of a page."}
			{/forminput}
		</div>
	{/legend}

	{legend legend="Listing Settings"}
		{foreach from=$pigeonholeListSettings key=feature item=output}
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
			{formlabel label="Member Thumbnail"}
			{forminput}
				{html_options values=$imageSizes options=$imageSizes name="pigeonholes_member_thumb" selected=$gBitSystem->getConfig('pigeonholes_member_thumb')}
				{formhelp note="This is the size of category members with a primary attachment."}
			{/forminput}
		</div>

		<div class="row">
			{formlabel label="Table Columns" for="pigeonholes_display_columns"}
			{forminput}
				{html_options name="pigeonholes_display_columns" options=$tableColumns values=$tableColumns selected=$gBitSystem->getConfig('pigeonholes_display_columns',3) id=pigeonholes_display_columns}
				{formhelp note="Set the number of columns you want to display the table in."}
			{/forminput}
		</div>
	{/legend}

	{legend legend="Pigeonhole Edit Settings"}
		{foreach from=$pigeonholeEditSettings key=feature item=output}
			<div class="row">
				{formlabel label=`$output.label` for=$feature}
				{forminput}
					{html_checkboxes name="$feature" values="y" checked=$gBitSystem->getConfig($feature) labels=false id=$feature}
					{formhelp note=`$output.note` page=`$output.page`}
				{/forminput}
			</div>
		{/foreach}
	{/legend}

	{legend legend="Pigeonhole Content Edit Settings"}
		{foreach from=$pigeonholeContentEditSettings key=feature item=output}
			<div class="row">
				{formlabel label=`$output.label` for=$feature}
				{forminput}
					{html_checkboxes name="$feature" values="y" checked=$gBitSystem->getConfig($feature) labels=false id=$feature}
					{formhelp note=`$output.note` page=`$output.page`}
				{/forminput}
			</div>
		{/foreach}

		<div class="row">
			{formlabel label="Number of categories for a scrolling list" for="pigeonholes_scrolling_list_number"}
			{forminput}
				<input type="text" size="4" maxlength="4" name="pigeonholes_scrolling_list_number" id="pigeonholes_scrolling_list_number" value="{$gBitSystem->getConfig('pigeonholes_scrolling_list_number')}" />
			{formhelp note="If you have more than this number of categories, categories selection is displayed in a scrolling list instead of checkboxes"}
			{/forminput}
		</div>
	{/legend}

	{legend legend="Pigeonholeable Content"}
		<input type="hidden" name="page" value="{$page}" />
		<div class="row">
			{formlabel label="Pigeonholeable Content"}
			{forminput}
				{html_checkboxes options=$formPigeonholeable.guids value=y name=pigeonholeable_content separator="<br />" checked=$formPigeonholeable.checked}
				{formhelp note="Here you can select what content can be pigeonholed."}
			{/forminput}
		</div>
	{/legend}

	<div class="row submit">
		<input type="submit" name="pigeonhole_settings" value="{tr}Change preferences{/tr}" />
	</div>
{/form}
{/strip}
