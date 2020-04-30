<table class='table table-<{$table_type}>'>
	<thead>
		<tr class='head'>
			<th class='center'><{$smarty.const._MB_MYMODULE_ART_ID}></th>
			<th class='center'><{$smarty.const._MB_MYMODULE_ART_CAT}></th>
			<th class='center'><{$smarty.const._MB_MYMODULE_ART_TITLE}></th>
			<th class='center'><{$smarty.const._MB_MYMODULE_ART_DESCR}></th>
			<th class='center'><{$smarty.const._MB_MYMODULE_ART_IMG}></th>
			<th class='center'><{$smarty.const._MB_MYMODULE_ART_ONLINE}></th>
			<th class='center'><{$smarty.const._MB_MYMODULE_ART_FILE}></th>
			<th class='center'><{$smarty.const._MB_MYMODULE_ART_CREATED}></th>
			<th class='center'><{$smarty.const._MB_MYMODULE_ART_SUBMITTER}></th>
		</tr>
	</thead>
	<{if count($block)}>
	<tbody>
		<{foreach item=article from=$block}>
		<tr class="<{cycle values="odd, even"}>"><td class="center"><{$article.id}></td>
			<td class="center"><{$article.cat}></td>
			<td class="center"><{$article.title}></td>
			<td class="center"><{$article.descr}></td>
			<td class="center"><img src="<{$mymodule_upload_url}>/images/articles/<{$article.img}>" alt="articles" /></td>
			<td class="center"><{$article.online}></td>
			<td class="center"><{$article.file}></td>
			<td class="center"><{$article.created}></td>
			<td class="center"><{$article.submitter}></td>
			<td class="center">
				<a href="articles.php?op=edit&amp;art_id=<{$article.id}>" title="<{$smarty.const._EDIT}>"><img src="<{xoModuleIcons32 edit.png}>" alt="articles" /></a>
				<a href="articles.php?op=delete&amp;art_id=<{$article.id}>" title="<{$smarty.const._DELETE}>"><img src="<{xoModuleIcons32 delete.png}><{$article.id}>" alt="articles" /></a>
			</td>
		</tr>
		<{/foreach}>
	</tbody>
	<{/if}>
	<tfoot><tr><td>&nbsp;</td></tr></tfoot>
</table>
