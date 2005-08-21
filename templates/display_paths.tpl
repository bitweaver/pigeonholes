{strip}
{if $gBitSystem->isFeatureActive( 'display_pigeonhole_path' )}
	<div class="structurebar pigeonholesbar">
		{foreach from=$pigeonData item=pigeonItem}
			<span class="path">{$pigeonItem.display_path}</span>
		{/foreach}
	</div><!-- end .structurebar -->
{/if}
{/strip}
