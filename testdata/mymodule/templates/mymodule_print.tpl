<{include file="db:mymodule_header.tpl"}>
<table class="mymodule">
    <thead class="outer">
        <tr class="head">
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
        <{foreach item=list from=$articles}>
            <tr class="<{cycle values='odd, even'}>">
                <td class="center"><{$list.id}></td>
                <td class="center"><{$list.cat}></td>
                <td class="center"><{$list.title}></td>
                <td class="center"><{$list.descr}></td>
                <td class="center"><img src="<{$mymodule_upload_url}>/images/articles/<{$list.img}>" alt="articles"></td>
                <td class="center"><{$list.online}></td>
                <td class="center"><{$list.file}></td>
                <td class="center"><{$list.created}></td>
                <td class="center"><{$list.submitter}></td>
            </tr>
        <{/foreach}>
    </tbody>
</table>
<{include file="db:mymodule_footer.tpl"}>