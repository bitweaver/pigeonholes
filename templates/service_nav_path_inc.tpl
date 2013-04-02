{strip}
{if $gBitSystem->isFeatureActive( 'pigeonholes_display_path' ) && $pigeonData}
	<div class="structurebar pigeonholesbar">
		{foreach from=$pigeonData item=pigeonItem}
			<span class="path">{$pigeonItem.display_path}
				{if $gContent->hasUpdatePermission()}
					&nbsp;{smartlink ititle="Remove Category" booticon="icon-trash" ipackage=pigeonholes ifile=edit_pigeonholes.php action=dismember parent_id=$pigeonItem.content_id pigeonhole_content_id=$gContent->mContentId return_uri=$gContent->getDisplayUri()}
				{/if}
			</span>
		{/foreach}
	</div><!-- end .structurebar -->
{/if}
{/strip}
