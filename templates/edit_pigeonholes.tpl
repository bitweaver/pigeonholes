<div class="floaticon">{bithelp}</div>

<div class="edit pigeonholes">
	<div class="header">
		<h1>{tr}Edit Categories{/tr}</h1>
	</div>

	<div class="body">
		{$feedback.success}
		{formfeedback hash=$feedback}
		{form legend="Create / Edit Category"}
			{if $gPigeonholes->mStructureId}
				<input type="hidden" name="structure_id" value="{$gPigeonholes->mStructureId}" />
				<input type="hidden" name="content_id" value="{$pigeonInfo.content_id}" />
				<input type="hidden" name="action" value="{$smarty.request.action}" />

				<div class="row">
					{formlabel label="Parent" for="pigeonhole-parent"}
					{forminput}
						{* we need to disable dropdown when editing since it might confus users when nothing happens *}
						{if $pigeonInfo.content_id}
							{html_options id="pigeonhole-parent" name="pigeonhole[parent_id]" values=$pigeonStructure options=$pigeonStructure selected=$pigeonInfo.parent_id disabled=disabled}
						{else}
							{html_options id="pigeonhole-parent" name="pigeonhole[parent_id]" values=$pigeonStructure options=$pigeonStructure selected=$pigeonInfo.parent_id}
						{/if}
						{formhelp note="Pick where you would like to create a new sub-category. To change the hierarchy of the categories, please visit the change structure page."}
					{/forminput}
				</div>
			{/if}

			<div class="row">
				{formlabel label="Title" for="pigeonhole-title"}
				{forminput}
					<input type="text" size="50" id="pigeonhole-title" name="pigeonhole[title]" value="{$pigeonInfo.title}" />
				{/forminput}
			</div>

			<div class="row">
				{formlabel label="Description" for="pigeonhole-desc"}
				{forminput}
					<textarea id="pigeonhole-desc" name="pigeonhole[edit]" rows="3" cols="50">{$pigeonInfo.data|escape}</textarea>
					{formhelp note="A description of the category. This will be visible when users view this particular category."}
				{/forminput}
			</div>

			<div class="row">
				{formlabel label="Theme" for="pigeonhole-style"}
				{forminput}
					{html_options id="pigeonhole-style" name="pigeonhole[settings][style]" output=$styles values=$styles selected=$pigeonInfo.settings.style}
					{formhelp note="This theme will be applied when viewing any page belonging to this category."}
				{/forminput}
			</div>

			<div class="row">
				{formlabel label="Content" for="pigeonhole-content"}
				{forminput}
					{html_options values=$contentTypes options=$contentTypes name=content_type_guid selected=$contentSelect}
				{/forminput}

				{forminput}
					{html_options multiple="multiple" size="12" name="pigeonhole[members][]" id="pigeonhole-content" values=$contentList options=$contentList selected=$pigeonInfo.selected_members}
				{/forminput}

				{forminput}
					<input type="text" size="30" name="find_objects" value="{$smarty.request.find_objects}" /> 
					<input type="submit" value="{tr}Apply filter{/tr}" name="search_objects" />
				{/forminput}
			</div>

			<div class="row submit">
				<input type="submit" name="pigeonhole_store" value="{tr}Save Category{/tr}" />
			</div>
		{/form}

		{include file="bitpackage:pigeonholes/view_structure_inc.tpl"}
	</div><!-- end .body -->
</div><!-- end .edit -->
