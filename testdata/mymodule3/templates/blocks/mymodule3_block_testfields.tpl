<table class='table table-<{$table_type}>'>
	<thead>
		<tr class='head'>
			<th>&nbsp;</th>
		</tr>
	</thead>
	<{if count($block)}>
	<tbody>
		<{foreach item=testfield from=$block}>
		<tr class='<{cycle values="odd, even"}>'>
			<td class='center'><{$testfield.id}></td>
		</tr>
		<{/foreach}>
	</tbody>
	<{/if}>
	<tfoot><tr><td>&nbsp;</td></tr></tfoot>
</table>
