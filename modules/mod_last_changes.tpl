{* $Header: /cvsroot/bitweaver/_bit_pigeonholes/modules/Attic/mod_last_changes.tpl,v 1.1 2005/10/26 17:46:45 squareing Exp $ *}
{strip}
{if $pigeonLastMod}
	{bitmodule title="$moduleTitle" name="last_changes"}
		<ol>
			{foreach from=$pigeonLastMod item=item}
				<li>
					{if !$contentType }
						<strong>{tr}{$item.content_description}{/tr}: </strong>
					{/if}
					{$item.display_link}
					{if $showDate}
						<br/><span class="date">{$item.last_modified|bit_short_date}</span>
					{/if}
				</li>
			{foreachelse}
				<li></li>
			{/foreach}
		</ol>
	{/bitmodule}
{/if}
{/strip}
