<div class='panel-heading'>
	<h3 class='panel-title'><{$article.submitter}></h3>
</div>
<div class='panel-body'>
	<span class='col-sm-9 justify'><{$article.cat}></span>
	<span class='col-sm-9 justify'><{$article.title}></span>
	<span class='col-sm-9 justify'><{$article.descr}></span>
	<span class='col-sm-3'><img src='<{$mymodule_upload_url}>/images/articles/<{$article.img}>' alt='articles' /></span>
	<span class='col-sm-9 justify'><{$article.file}></span>
	<span class='col-sm-9 justify'><{$article.created}></span>
	<span class='col-sm-9 justify'><{$article.submitter}></span>
</div>
<div class='panel-foot'>
	<span class='block-pie justify'><{$smarty.const._MA_MYMODULE_ARTICLE_CAT}>: <{$article.cat}></span>
	<span class='block-pie justify'><{$smarty.const._MA_MYMODULE_ARTICLE_TITLE}>: <{$article.title}></span>
	<span class='block-pie justify'><{$smarty.const._MA_MYMODULE_ARTICLE_DESCR}>: <{$article.descr}></span>
	<span class='block-pie justify'><{$smarty.const._MA_MYMODULE_ARTICLE_IMG}>: <{$article.img}></span>
	<span class='block-pie justify'><{$smarty.const._MA_MYMODULE_ARTICLE_FILE}>: <{$article.file}></span>
	<span class='block-pie justify'><{$smarty.const._MA_MYMODULE_ARTICLE_CREATED}>: <{$article.created}></span>
	<span class='block-pie justify'><{$smarty.const._MA_MYMODULE_ARTICLE_SUBMITTER}>: <{$article.submitter}></span>
</div>
