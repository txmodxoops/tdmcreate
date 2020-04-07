<{include file='db:mymodule_header.tpl'}>

<div class='panel panel-<{$panel_type}>'>
<div class='panel-heading'>
<{$smarty.const._MA_MYMODULE_ARTICLES_TITLE}></div>

<{foreach item=article from=$articles}>
<div class='panel panel-body'>
<{include file='db:mymodule_articles_list.tpl' article=$article}>
<{if $article.count is div by $numb_col}>
<br>
<{/if}>

</div>

<{/foreach}>

</div>

<{include file='db:mymodule_footer.tpl'}>
