{strip}
<ul class="toc">
	<li>
		{section name=ix loop=$subtree}
			{assign var=structId value=$subtree[ix].structure_id}
			{if $subtree[ix].pos eq ''}
				{include file="bitpackage:pigeonholes/section_inc.tpl"}
			{else}
				{if $subtree[ix].first}<ul>{else}</li>{/if}
				{if $subtree[ix].last}</ul>{else}
					<li>{include file="bitpackage:pigeonholes/section_inc.tpl"}
				{/if}
			{/if}
		{/section}
	</li>
</ul><!-- end outermost .toc -->
{/strip}
