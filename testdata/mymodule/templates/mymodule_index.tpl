<{include file='db:mymodule_header.tpl'}>

<{if $articlesCount == 0}>
<table class="table table-<{$table_type}>">
    <thead>
        <tr class="center">
            <th><{$smarty.const._MA_MYMODULE_TITLE}>  -  <{$smarty.const._MA_MYMODULE_DESC}></th>
        </tr>
    </thead>
    <tbody>
        <tr class="center">
            <td class="bold pad5">
                <ul class="menu text-center">
                    <li><a href="<{$mymodule_url}>"><{$smarty.const._MA_MYMODULE_INDEX}></a></li>
                    <li><a href="<{$mymodule_url}>/categories.php"><{$smarty.const._MA_MYMODULE_CATEGORIES}></a></li>
                    <li><a href="<{$mymodule_url}>/articles.php"><{$smarty.const._MA_MYMODULE_ARTICLES}></a></li>
                </ul>
				<div class="justify pad5"><{$smarty.const._MA_MYMODULE_INDEX_DESC}></div>
            </td>
        </tr>
    </tbody>
    <tfoot>
    <{if $adv != ''}>
        <tr class="center"><td class="center bold pad5"><{$adv}></td></tr>
    <{else}>
        <tr class="center"><td class="center bold pad5">&nbsp;</td></tr>
    <{/if}>
    </tfoot>
</table>
<{/if}>
<{if $articlesCount > 0}>
<div class="table-responsive">
    <table class="table table-<{$table_type}>">
		<thead>
			<tr>
				<th colspan="<{$numb_col}>"><{$smarty.const._MA_MYMODULE_ARTICLES}></th>
			</tr>
		</thead>
		<tbody>
			<tr>
				<{foreach item=article from=$articles}>
				<td>
					<{include file="db:mymodule_articles_list.tpl" article=$article}>
				</td>
			<{if $article.count is div by $numb_col}>
			</tr><tr>
			<{/if}>
				<{/foreach}>
			</tr>
		</tbody>
		<tfoot>
			<tr>
				<td colspan="<{$numb_col}>" class="article-thereare"><{$lang_thereare}></td>
			</tr>
		</tfoot>
	</table>
</div>
<{/if}>

<{if $articlesCount > 0}>
	<!-- Start Show new articles in index -->
	<div class="mymodule-linetitle"><{$smarty.const._MA_MYMODULE_INDEX_LATEST_LIST}></div>
	<table class="table table-<{$table_type}>">
		<tr>
			<!-- Start new link loop -->
			<{section name=i loop=$articles}>
				<td class="col_width<{$numb_col}> top center">
					<{include file="db:mymodule_articles_list.tpl" article=$articles[i]}>
				</td>
	<{if $articles[i].count is div by $divideby}>
		</tr><tr>
	<{/if}>
			<{/section}>
	<!-- End new link loop -->
		</tr>
	</table>
<!-- End Show new files in index -->
<{/if}>
<{include file='db:mymodule_footer.tpl'}>
