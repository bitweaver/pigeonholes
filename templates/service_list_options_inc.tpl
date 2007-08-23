{strip}
{if $pigeonList}
	{form legend="Only view entries in the following Categories"}
		{html_checkboxes options=$pigeonList values=$pigeonList selected=$smarty.request.pigeonholes.filter name="pigeonholes[filter]" id=pigeonholes_filter separator=" &nbsp; "}

		<div class="submit">
			<input type="submit" name="apply" value="{tr}Apply Filter{/tr}" />
		</div>
	{/form}
{/if}
{/strip}
