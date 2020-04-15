<{include file='db:mymodule2_header.tpl'}>

<{if $testfieldsCount > 0}>
<div class='table-responsive'>
	<table class='table table-<{$table_type}>'>
		<thead>
			<tr class='head'>
				<th colspan='<{$divideby}>'><{$smarty.const._MA_MYMODULE2_TESTFIELDS_TITLE}></th>
			</tr>
		</thead>
		<tbody>
			<tr>
				<{foreach item=testfield from=$testfields}>
				<td>
					<div class='panel panel-<{$panel_type}>'>
						<{include file='db:mymodule2_testfields_list.tpl' testfield=$testfield}>
					</div>
				</td>
				<{if $testfield.count is div by $divideby}>
					</tr><tr>
				<{/if}>
				<{/foreach}>
			</tr>
		</tbody>
		<tfoot><tr><td>&nbsp;</td></tr></tfoot>
	</table>
</div>
<{/if}>

<{include file='db:mymodule2_footer.tpl'}>
