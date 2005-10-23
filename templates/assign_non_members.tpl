{strip}
<div class="edit pigeonholes">
	<div class="header">
		<h1>{tr}Assign Content to Categories{/tr}</h1>
	</div>

	<div class="body">
		{if !$pigeonList}
			{formfeedback warning="No categories have been set up yet. You need to create some before you can assign content to them."}
		{else}
			{form legend="Assign Content"}
				<input type="hidden" name="sort_mode" value="{$smarty.request.sort_mode}" />
				<div class="row">
					{formlabel label="Restrict listing" for="content_type"}

					{forminput}
						<select name="max_rows">
							<option value="10"  {if $smarty.request.max_rows eq 10 or !$smarty.request.max_rows}selected="selected"{/if}>{tr}10{/tr}</option>
							<option value="50"  {if $smarty.request.max_rows eq 50}selected="selected"{/if}>{tr}50{/tr}</option>
							<option value="100" {if $smarty.request.max_rows eq 100}selected="selected"{/if}>{tr}100{/tr}</option>
							<option value="200" {if $smarty.request.max_rows eq 200}selected="selected"{/if}>{tr}200{/tr}</option>
							<option value="500" {if $smarty.request.max_rows eq 500}selected="selected"{/if}>{tr}500{/tr}</option>
						</select> {tr}Records{/tr}
					{/forminput}

					{forminput}
						<select name="include">
							<option value="">{tr}Hide assigned content{/tr}</option>
							<option value="members" {if $smarty.request.include eq 'members'}selected="selected"{/if}>{tr}Display assigned content{/tr}</option>
						</select>
					{/forminput}

					{forminput}
						{html_options values=$contentTypes options=$contentTypes name=content_type id=content_type selected=$contentSelect}
					{/forminput}

					{forminput}
						<input type="text" value="{$smarty.request.find_objects}" name="find_objects" />&nbsp;
						{formhelp note="You can restrict the content listing to a given content type or apply a filter."}
					{/forminput}
				</div>

				<div class="row">
					{formlabel label="Category" for="root_structure_id"}
					{forminput}
						{html_options values=$pigeonRoots options=$pigeonRoots name=root_structure_id id=root_structure_id selected=$smarty.request.root_structure_id}
						{formhelp note="Pick category you want to use to insert content into."}
					{/forminput}
				</div>

				<div class="row submit">
					<input type="submit" value="{tr}Restrict Listing{/tr}" name="search_objects" />
				</div>
			{/form}

			{formfeedback hash=$feedback}

			{form}
				<input type="hidden" name="sort_mode" value="{$smarty.request.sort_mode}" />
				<input type="hidden" name="include" value="{$smarty.request.include}" />
				<input type="hidden" name="find_objects" value="{$smarty.request.find_objects}" />
				<input type="hidden" name="content_type" value="{$contentSelect}" />

				{if $gBitSystem->isFeatureActive( 'custom_member_sorting' ) && $smarty.request.include eq 'members'}
					{formfeedback warning="Using this insertion method will reset any custom sorting you have done so far."}
				{/if}

				<table class="data">
					<caption>{tr}Available Content{/tr} <span class="total">[ {$contentCount} ]</span></caption>
					<tr>
						<th>{smartlink ititle="Title" isort=title idefault=1 max_rows=$smarty.request.max_rows content_type=$contentSelect find_objects=$find_objects include=$smarty.request.include page=$page}</th>
						<th>{smartlink ititle="Content Type" isort=content_type_guid max_rows=$smarty.request.max_rows content_type=$contentSelect find_objects=$find_objects include=$smarty.request.include page=$page}</th>
						{if $nonMembers}
							{foreach from=$pigeonList item=pigeon}
								<th><abbr title="{$pigeon.title}">{counter}</abbr></th>
							{/foreach}
						{/if}
					</tr>

					{foreach from=$nonMembers item=item}
						<tr class="{cycle values='odd,even'}">
							<td><a href="{$smarty.const.BIT_ROOT_URL}index.php?content_id={$item.content_id}">{$item.title}</a></td>
							<td>{assign var=content_type_guid value=`$item.content_type_guid`}{$contentTypes.$content_type_guid}</td>
							{foreach from=$pigeonList item=pigeon}
								<td style="text-align:center">
									<input type="checkbox" name="pigeonhole[{$item.content_id}][]" value="{$pigeon.content_id}"
										{foreach from=$item.assigned item=parent_id}
											{if $pigeon.content_id eq $parent_id}checked="checked"{/if}
										{/foreach}
									title="{$pigeon.title}" />
								</td>
							{/foreach}
						</tr>
					{foreachelse}
						<tr>
							<td colspan="2" class="norecords">{tr}No Content can be found with your selection criteria{/tr}</td>
						</tr>
					{/foreach}
				</table>

				{if $nonMembers}
					<div class="row submit">
						<input type="submit" name="insert_content" value="Insert Content into Categories" />
					</div>
				{/if}
			{/form}

			{if $nonMembers}
				{foreach from=$pigeonList item=pigeon}
					<dl>
						<dt>{counter name=dogEatsPigeon}</dt>
						<dd>{$pigeon.display_path}<br /><small>{$pigeon.data|escape}</small></dd>
					</dl>
				{/foreach}
			{/if}
		{/if}
	</div><!-- end .body -->
</div><!-- end .liberty -->
{/strip}

