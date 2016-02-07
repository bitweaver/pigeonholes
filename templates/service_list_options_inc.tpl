{strip}
{if $pigeonList}
	{form legend="Only view entries in the following Categories"}
		{html_checkboxes options=$pigeonList values=$pigeonList selected=$smarty.request.pigeonholes.filter name="pigeonholes[filter]" separator=" &nbsp; "}

		<div class="submit">
			<input type="submit" class="btn btn-default" name="apply" value="{tr}Apply Filter{/tr}" />
			<input type="submit" class="btn btn-default" name="pigeonholes[no_filter]" value="{tr}No Filter{/tr}" />
			<br />
			<label><input type="checkbox" {if $smarty.request.pigeonholes.sub_holes}checked="checked"{/if} name="pigeonholes[sub_holes]" /> {tr}Include subcategories{/tr}</label>
		</div>
	{/form}
{/if}
{/strip}
