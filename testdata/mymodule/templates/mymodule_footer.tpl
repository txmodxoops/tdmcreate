<{if $bookmarks != 0}>
    <{include file="db:system_bookmarks.tpl"}>
<{/if}>

<{if $fbcomments != 0}>
    <{include file="db:system_fbcomments.tpl"}>
<{/if}>
<div class="pull-left"><{$copyright}></div>
<{if $pagenav != ''}>
    <div class="pull-right"><{$pagenav}></div>
<{/if}>
<br>
<{if $xoops_isadmin}>
   <div class="text-center bold"><a href="<{$admin}>"><{$smarty.const._MA_MYMODULE_ADMIN}></a></div><br>
<{/if}>
<div class="pad2 marg2">
    <{if $comment_mode == "flat"}>
        <{include file="db:system_comments_flat.tpl"}>
    <{elseif $comment_mode == "thread"}>
        <{include file="db:system_comments_thread.tpl"}>
    <{elseif $comment_mode == "nest"}>
        <{include file="db:system_comments_nest.tpl"}>
    <{/if}>
</div>

<br>
<{include file='db:system_notification_select.tpl'}>