{strip}
<ul class="toc">
	<li>
		{section name=ix loop=$subtree}
			{assign var=structId value=$subtree[ix].structure_id}
			{if $subtree[ix].pos eq ''}
				{if $plain}
					{$subtree[ix].title}
				{else}
					{include file="bitpackage:pigeonholes/structure_section_inc.tpl"}
				{/if}
			{else}
				{if $subtree[ix].first}<ul>{else}</li>{/if}
				{if $subtree[ix].last}</ul>{else}
					<li>
						{if $plain}
							{$subtree[ix].title}
						{else}
							{include file="bitpackage:pigeonholes/structure_section_inc.tpl"}
						{/if}
				{/if}
			{/if}
		{/section}
	</li>
</ul><!-- end outermost .toc -->
{/strip}
