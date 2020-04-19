<!-- Header -->
<{include file='db:mymodule2_admin_header.tpl'}>

<{if $articles_list}>
	<table class='table table-bordered'>
		<thead>
			<tr class='head'>
				<th class="center"><{$smarty.const._AM_MYMODULE2_ARTICLE_ID}></th>
				<th class="center"><{$smarty.const._AM_MYMODULE2_ARTICLE_CAT}></th>
				<th class="center"><{$smarty.const._AM_MYMODULE2_ARTICLE_TITLE}></th>
				<th class="center"><{$smarty.const._AM_MYMODULE2_ARTICLE_DESCR}></th>
				<th class="center"><{$smarty.const._AM_MYMODULE2_ARTICLE_IMG}></th>
				<th class="center"><{$smarty.const._AM_MYMODULE2_ARTICLE_STATUS}></th>
				<th class="center"><{$smarty.const._AM_MYMODULE2_ARTICLE_FILE}></th>
				<th class="center"><{$smarty.const._AM_MYMODULE2_ARTICLE_CREATED}></th>
				<th class="center"><{$smarty.const._AM_MYMODULE2_ARTICLE_SUBMITTER}></th>
				<th class="center width5"><{$smarty.const._AM_MYMODULE2_FORM_ACTION}></th>
			</tr>
		</thead>
		<{if $articles_count}>
		<tbody>
			<{foreach item=article from=$articles_list}>
			<tr class='<{cycle values='odd, even'}>'>
				<td class="center"><{$article.id}></td>
				<td class="center"><{$article.cat}></td>
				<td class="center"><{$article.title}></td>
				<td class="center"><{$article.descr}></td>
				<td class="center"><img src="<{$mymodule2_upload_url}>/images/articles/<{$article.img}>" alt="articles" style="max-width:100px" /></td>
				<td class="center"><img src="<{$modPathIcon16}>/status<{$article.status}>.png" alt="articles" /></td>
				<td class="center"><{$article.file}></td>
				<td class="center"><{$article.created}></td>
				<td class="center"><{$article.submitter}></td>
				<td class="center  width5">
					<a href="articles.php?op=edit&amp;art_id=<{$article.id}>" title="<{$smarty.const._EDIT}>"><img src="<{xoModuleIcons16 edit.png}>" alt="articles" /></a>
					<a href="articles.php?op=delete&amp;art_id=<{$article.id}>" title="<{$smarty.const._DELETE}>"><img src="<{xoModuleIcons16 delete.png}>" alt="articles" /></a>
				</td>
			</tr>
			<{/foreach}>
		</tbody>
		<{/if}>
	</table>
	<div class="clear">&nbsp;</div>
	<{if $pagenav}>
		<div class="xo-pagenav floatright"><{$pagenav}></div>
		<div class="clear spacer"></div>
	<{/if}>
<{/if}>
<{if $form}>
	<{$form}>
<{/if}>
<{if $error}>
	<div class="errorMsg"><strong><{$error}></strong></div>
<{/if}>

<!-- Footer -->
<{include file='db:mymodule2_admin_footer.tpl'}>
