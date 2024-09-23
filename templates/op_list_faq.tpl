<script type="text/javascript">
function delete_tad_faq_content_func(fqsn){
    var sure = window.confirm("<{$smarty.const._TAD_DEL_CONFIRM}>");
    if (!sure)  return;
    location.href="index.php?op=delete_tad_faq_content&fcsn=<{$fcsn|default:''}>&fqsn=" + fqsn;
}

$(document).ready(function(){
    <{if $smarty.session.tad_faq_adm|default:false}>
        $("#sort").sortable({ opacity: 0.6, cursor: "move", update: function() {
            var order = $(this).sortable("serialize");
            $.post("save_sort.php", order, function(theResponse){
                $("#save_msg").html(theResponse);
            });
        }
        });
    <{/if}>



    $(".faq_content").hide();


    $(".faq_title").click(function () {
        var fqsn=$(this).attr("id");
        var content_id="#"+fqsn+"_ans";

        $(".faq_content").css("background-color: ","white");

        $(content_id).slideToggle(function(){
        $.post("ajax.php", { sn: fqsn}, function(data) {
            $("#counter_"+fqsn).html(data);
        });
        }).css("background-color","#F4F9EA");

    });

});
</script>

<{if $cates|is_array && $cates|@count > 0}>
    <div class="alert alert-info">
        <div class="input-group">
            <div class="input-group-prepend input-group-addon">
                <span class="input-group-text"><{$smarty.const._MD_TADFAQ_CHANGE_CATE}></span>
            </div>
            <select id="fcsn_select" class="form-control" placeholder="<{$smarty.const._MD_TADFAQ_SELECT_CATE}>" onchange="location.href='index.php?fcsn='+this.value">
            <{foreach from=$cates key=of_fcsn item=of_fcsn_cate}>
                <{foreach from=$of_fcsn_cate key=fcsn item=cate}>
                    <option value="<{$fcsn|default:''}>" <{if $smarty.get.fcsn == $fcsn}>selected<{/if}>><{$cate.title}></option>
                <{/foreach}>
            <{/foreach}>
            </select>
        </div>

    </div>
<{/if}>

<h2><{$cate_title|default:''}></h2>

<{if $smarty.session.tad_faq_adm or $faq_edit_power}>
    <a href="index.php?op=tad_faq_content_form&fcsn=<{$fcsn|default:''}>" class="btn btn-primary"><{$smarty.const._TAD_ADD}></a>
<{/if}>

<{assign var="n" value=1}>
<div id="sort">
<{foreach from=$faq item=data}>
    <{if $faq.enable=="1" or $smarty.session.tad_faq_adm or $edit_power}>
        <div class="faq_title well card card-body bg-light m-1" id="tr_<{$data.fqsn}>">
        <div class="row">
                <div class="col-sm-11">
                    <a name="#<{$data.fqsn}>" id="<{$data.fqsn}>" class="<{if $faq.enable!="1"}>disabled<{/if}>" style="text-align:left;padding:4px 10px;">
                        <{if $faq.enable=="1"}>
                        <{$n|default:''}>.
                        <{assign var="n" value=$n+1}>
                        <{else}>
                        ?.
                        <{/if}>
                        <{$data.title}>
                    </a>
                </div>

                <div class="col-sm-1 counter">
                    <{if $smarty.session.tad_faq_adm or $edit_power}>
                        <{if $faq.enable!="1"}><{$smarty.const._MD_TADFAQ_FAQ_UNABLE}><{/if}>
                    <{/if}>
                    <span id="counter_tr_<{$data.fqsn}>"><{$data.counter}></span>
                </div>
            </div>
        </div>


        <div id="tr_<{$data.fqsn}>_ans" class="well card card-body m-1 faq_content" style="line-height: 1.8;">
            <{if $smarty.session.tad_faq_adm or $edit_power}>
                <div style="text-align:right;">
                    <a href="index.php?op=update_status&fcsn=<{$fcsn|default:''}>&fqsn=<{$data.fqsn}>&enable=<{$data.update_enable}>" class="btn btn-sm btn-xs btn-info"><{$data.enable_txt}></a>

                    <a href="javascript:delete_tad_faq_content_func(<{$data.fqsn}>);" class="btn btn-sm btn-xs btn-danger"><{$smarty.const._TAD_DEL}></a>

                    <a href="index.php?op=tad_faq_content_form&fqsn=<{$data.fqsn}>" class="btn btn-sm btn-xs btn-warning"><{$smarty.const._TAD_EDIT}></a>
                </div>
            <{/if}>
            <{$data.content}>
        </div>
    <{/if}>
<{/foreach}>
</div>
<div id="save_msg"></div>