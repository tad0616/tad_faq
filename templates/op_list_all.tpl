<div class="row">
    <div class="col-sm-6">
        <h2><{$module_title}></h2>
    </div>
    <div class="col-sm-6 text-right text-end">
        <{if $smarty.session.tad_faq_adm or $edit_power}>
            <a href="index.php?op=tad_faq_content_form" class="btn btn-primary"><{$smarty.const._TAD_ADD}></a>
        <{/if}>
    </div>
</div>

<{foreach from=$faq item=faq}>
    <{if $faq.counter}>
    <div class="well card card-body bg-light m-1">
        <a href="index.php?fcsn=<{$faq.fcsn}>" class="text-left text-start">
        <{$faq.title}>
        <span class="badge"><{$faq.num}></span>
        </a>
    </div>
    <{/if}>
<{/foreach}>