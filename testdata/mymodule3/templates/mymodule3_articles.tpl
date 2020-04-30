<{include file='db:mymodule3_header.tpl'}>

<{if $articlesCount > 0}>
<div class='table-responsive'>
	<table class='table table-<{$table_type}>'>
		<thead>
			<tr class='head'>
				<th colspan='<{$divideby}>'><{$smarty.const._MA_MYMODULE3_ARTICLES_TITLE}></th>
			</tr>
		</thead>
		<tbody>
			<tr>
				<{foreach item=article from=$articles}>
				<td>
					<div class='panel panel-<{$panel_type}>'>
						<{include file='db:mymodule3_articles_list.tpl' article=$article}>
					</div>
				</td>
				<{if $article.count is div by $divideby}>
					</tr><tr>
				<{/if}>
				<{/foreach}>
			</tr>
		</tbody>
		<tfoot><tr><td>&nbsp;</td></tr></tfoot>
	</table>
</div>
<{/if}>

<{include file='db:mymodule3_footer.tpl'}>
