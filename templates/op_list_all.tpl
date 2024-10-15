<div class="row">
    <div class="col-sm-6">
        <h2><{$module_title|default:''}></h2>
    </div>
    <div class="col-sm-6 text-right text-end">
        <{if $smarty.session.tad_faq_adm|default:false || $edit_power|default:false}>
            <a href="index.php?op=tad_faq_content_form" class="btn btn-primary"><i class="fa fa-plus" aria-hidden="true"></i> <{$smarty.const._TAD_ADD}></a>
        <{/if}>
    </div>
</div>

<{foreach from=$faq item=data}>
    <{if $data.counter|default:false}>
    <div class="well card card-body bg-light m-1">
        <a href="index.php?fcsn=<{$data.fcsn}>" class="text-left text-start">
        <{$data.title}>
        <span class="badge badge-info bg-info"><{$data.num}></span>
        </a>
    </div>
    <{/if}>
<{/foreach}>