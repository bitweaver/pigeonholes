{tr}<strong>Summary</strong>: Display the most recently changed content - limited to a given category.{/tr}<br />
<table class="data">
	<tr>
		<th style="width:20%;">{tr}Parameter{/tr}</th>
		<th style="width:20%;">{tr}Value{/tr}</th>
		<th style="width:60%;">{tr}Description{/tr}</th>
	</tr>
	<tr class="odd">
		<td>content_type_guid</td>
		<td>bitpage<br />bituser<br />...</td>
		<td>{tr}Here you can specify what type of content you wish to show.{/tr}</td>
	</tr>
	<tr class="even">
		<td>show_date</td>
		<td>( {tr}boolean{/tr} )</td>
		<td>{tr}Specify if you want to display the date of the last modification.{/tr}</td>
	</tr>
	<tr class="odd">
		<td>hide_content_type</td>
		<td>( {tr}boolean{/tr} )</td>
		<td>{tr}Specify if you want to display the content type<br />(only applicable if you don't specify a content_type_guid).{/tr}</td>
	</tr>
	<tr class="even">
		<td>category_id</td>
		<td>( {tr}numeric{/tr} )</td>
		<td>{tr}Content ID of the pigeonhole you want to limit the listing to<br />(can be used instead of category).{/tr}</td>
	</tr>
	<tr class="odd">
		<td>category</td>
		<td>( {tr}string{/tr} )</td>
		<td>{tr}Title of a category you want to limit the listing to<br />(can be used instead of category_id).{/tr}</td>
	</tr>
</table>
