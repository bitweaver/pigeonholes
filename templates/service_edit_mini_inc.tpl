{strip}
{if $editPigeonholesEnabled && $gBitUser->hasPermission( 'p_pigeonholes_insert_member' )}
	<div class="row">
		{formlabel label="Categorize" for="pigeonholes"}
		{if $pigeonPathList|@count ne 0}
			{forminput}
				{if $pigeonPathList|@count < $gBitSystem->getConfig( 'pigeonholes_scrolling_list_number' )}
					{foreach from=$pigeonPathList key=pigeonId item=path}
						<label>
							<input type="checkbox" value="{$pigeonId}" {if $path.0.selected}checked="checked" {/if}name="pigeonholes[pigeonhole][]" />
							{foreach from=$path item=node}
								{if $node.parent_id} &raquo;{/if} {$node.title|escape}
							{/foreach}
							<br />
						</label>
					{/foreach}
				{else}
					<select name="pigeonholes[pigeonhole][]" id="pigeonholes" multiple="multiple" size="6">
						{foreach from=$pigeonPathList key=pigeonId item=path}
							<option value="{$pigeonId}" {if $path.0.selected}selected="selected"{/if}>
								{foreach from=$path item=node}
									{if $node.parent_id} &raquo;{/if} {$node.title|escape}
								{/foreach}
							</option>
						{/foreach}
					</select>
				{/if}
			{/forminput}
		{else}
			{forminput}
				<p>{tr}There are no categories available at the moment.{/tr}</p>
				{if $gBitUser->isAdmin()}
					{smartlink ititle="Create Category" ipackage="pigeonholes" ifile="edit_pigeonholes.php"}
				{/if}
			{/forminput}
		{/if}
	</div>
{/if}
{/strip}
