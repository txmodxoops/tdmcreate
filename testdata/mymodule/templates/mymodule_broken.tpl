<{include file='db:mymodule_header.tpl'}>
<table class='table table-bordered'>
	<thead class='outer'>
		<tr class='head'>
			<th class="center"><{$smarty.const._MA_MYMODULE_ART_ID}></th>
			<th class="center"><{$smarty.const._MA_MYMODULE_ART_CAT}></th>
			<th class="center"><{$smarty.const._MA_MYMODULE_ART_TITLE}></th>
			<th class="center"><{$smarty.const._MA_MYMODULE_ART_DESCR}></th>
			<th class="center"><{$smarty.const._MA_MYMODULE_ART_IMG}></th>
			<th class="center"><{$smarty.const._MA_MYMODULE_ART_ONLINE}></th>
			<th class="center"><{$smarty.const._MA_MYMODULE_ART_FILE}></th>
			<th class="center"><{$smarty.const._MA_MYMODULE_ART_CREATED}></th>
			<th class="center"><{$smarty.const._MA_MYMODULE_ART_SUBMITTER}></th>
		</tr>
	</thead>
	<tbody>
		<{foreach item=article from=$articles}>
		<tr class='<{cycle values="odd, even"}>'>
			<td class='center'><{$article.id}></td>
			<td class='center'><{$article.cat}></td>
			<td class='center'><{$article.title}></td>
			<td class='center'><{$article.descr}></td>
			<td class='center'><img src='<{$mymodule_upload_url}>/images/articles/<{$article.img}>' alt='articles' /></td>
			<td class='center'><{$article.online}></td>
			<td class='center'><{$article.file}></td>
			<td class='center'><{$article.created}></td>
			<td class='center'><{$article.submitter}></td>
		</tr>
		<{/foreach}>
	</tbody>
</table>
<{include file='db:mymodule_footer.tpl'}>
