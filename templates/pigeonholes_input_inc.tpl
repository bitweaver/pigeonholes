{strip}
<div class="row">
	{formlabel label="Pick Categories"}
	{forminput}
		{foreach from=$pigeonPathList key=pigeonId item=path}
			<label>
				<input type="checkbox" value="{$pigeonId}" {if $path.0.selected}checked="checked" {/if}name="pigeonholes[pigeonhole][]" />
				{foreach from=$path item=node}
					{if $node.parent_id} &raquo;{/if} {$node.title}
				{/foreach}
				<br />
			</label>
		{foreachelse}
			{tr}There are no available categories available at the moment.{/tr}
			{if $gBitUser->isAdmin()}
				<br />
				{smartlink ititle="Create Category" ipackage="pigeonholes" ifile="edit_pigeonholes.php"}
			{/if}
		{/foreach}
	{/forminput}
</div>
{/strip}
