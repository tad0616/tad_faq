<{$toolbar}>
<{if $now_op=="list_all"}>

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

<{elseif $now_op=="list_faq"}>
  <script type="text/javascript">
    function delete_tad_faq_content_func(fqsn){
      var sure = window.confirm("<{$smarty.const._TAD_DEL_CONFIRM}>");
      if (!sure)  return;
      location.href="index.php?op=delete_tad_faq_content&fcsn=<{$fcsn}>&fqsn=" + fqsn;
    }

    $(document).ready(function(){
      <{if $smarty.session.tad_faq_adm}>
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

  <{if $smarty.session.tad_faq_adm or $faq_edit_power}>
    <a href="index.php?op=tad_faq_content_form&fcsn=<{$fcsn}>" class="btn btn-primary" style="float:right"><{$smarty.const._TAD_ADD}></a>
  <{/if}>

  <h2><{$cate_title}></h2>

  <{assign var="n" value=1}>
  <div id="sort">
    <{foreach from=$faq item=faq}>
      <{if $faq.enable=="1" or $smarty.session.tad_faq_adm or $edit_power}>
        <div class="faq_title well card card-body bg-light m-1" id="tr_<{$faq.fqsn}>">
          <div class="row">
            <div class="col-sm-11">
              <a name="#<{$faq.fqsn}>" id="<{$faq.fqsn}>" class="<{if $faq.enable!="1"}>disabled<{/if}>" style="text-align:left;padding:4px 10px;">
                <{if $faq.enable=="1"}>
                <{$n}>.
                  <{assign var="n" value=$n+1}>
                <{else}>
                  ?.
                <{/if}>
                <{$faq.title}>
              </a>
            </div>

            <div class="col-sm-1 counter">
              <{if $smarty.session.tad_faq_adm or $edit_power}>
                <{if $faq.enable!="1"}><{$smarty.const._MD_TADFAQ_FAQ_UNABLE}><{/if}>
              <{/if}>
              <span id="counter_tr_<{$faq.fqsn}>"><{$faq.counter}></span>
            </div>
          </div>
        </div>


        <div id="tr_<{$faq.fqsn}>_ans" class="well card card-body m-1 faq_content" style="line-height: 1.8;">
          <{if $smarty.session.tad_faq_adm or $edit_power}>
            <div style="text-align:right;">
              <a href="index.php?op=update_status&fcsn=<{$fcsn}>&fqsn=<{$faq.fqsn}>&enable=<{$faq.update_enable}>" class="btn btn-sm btn-xs btn-info"><{$faq.enable_txt}></a>

              <a href="javascript:delete_tad_faq_content_func(<{$faq.fqsn}>);" class="btn btn-sm btn-xs btn-danger"><{$smarty.const._TAD_DEL}></a>

              <a href="index.php?op=tad_faq_content_form&fqsn=<{$faq.fqsn}>" class="btn btn-sm btn-xs btn-warning"><{$smarty.const._TAD_EDIT}></a>
            </div>
          <{/if}>
          <{$faq.content}>
        </div>
      <{/if}>
    <{/foreach}>
  </div>
  <div id="save_msg"></div>
<{elseif $now_op=="tad_faq_content_form"}>
  <h2><{$smarty.const._MD_TADFAQ_ADD_CONTENT}></h2>

    <form action="index.php" method="post" id="myForm" enctype="multipart/form-data" class="form-horizontal" role="form">
      <div class="form-group row mb-3">
        <label class="col-sm-1 col-form-label text-sm-right control-label">
          <{$smarty.const._MD_TADFAQ_CATE_MENU}>
        </label>
        <{if $faq_cate_opt}>
          <div class="col-sm-3">
            <select name="fcsn" size=1 class="form-control">
              <{$faq_cate_opt}>
            </select>
          </div>

          <{if $smarty.session.tad_faq_adm}>
            <div class="col-sm-8">
              <input type="text" name="new_cate" class="form-control" placeholder="<{$smarty.const._MD_TADFAQ_NEW_CATE}>">
            </div>
          <{/if}>
        <{else}>
          <{if $smarty.session.tad_faq_adm}>
            <div class="col-sm-11">
              <input type="text" name="new_cate" class="form-control" placeholder="<{$smarty.const._MD_TADFAQ_NEW_CATE}>">
            </div>
          <{/if}>
        <{/if}>
      </div>

      <div class="form-group row mb-3">
        <label class="col-sm-1 col-form-label text-sm-right control-label">
          <{$smarty.const._MD_TADFAQ_FAQ_TITLE}>
        </label>
        <div class="col-sm-7">
          <input type="text" name="title" value="<{$title}>" class="form-control">
        </div>
        <div class="col-sm-4">
            <div class="form-check-inline radio-inline">
                <label class="form-check-label">
                    <input class="form-check-input" type="radio" name="enable" value="1" <{if $enable=='1'}>checked<{/if}>>
                    <{$smarty.const._MD_TADFAQ_FAQ_ENABLE}>
                </label>
            </div>
            <div class="form-check-inline radio-inline">
                <label class="form-check-label">
                    <input class="form-check-input" type="radio" name="enable" value="0" <{if $enable=='0'}>checked<{/if}>>
                    <{$smarty.const._MD_TADFAQ_FAQ_UNABLE}>
                </label>
            </div>
        </div>
      </div>

      <div class="form-group row mb-3">
        <label class="col-sm-1 col-form-label text-sm-right control-label">
          <{$smarty.const._MD_TADFAQ_CONTENT}>
        </label>
        <div class="col-sm-11">
          <{$editor}>
        </div>
      </div>

      <div class="text-center">
        <input type="hidden" name="fqsn" value="<{$fqsn}>">
        <input type="hidden" name="op" value="<{$op}>">
        <button type="submit" class="btn btn-primary"><{$smarty.const._TAD_SAVE}></button>
      </div>

    </form>

<{/if}>
