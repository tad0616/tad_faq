<div class="container-fluid">
  <div class="row">
    <div class="col-sm-12">
      <h2><{$smarty.const._MA_TADFAQ_CATE_INPUT_FORM}></h2>
      <{if $all_content|default:false}>
      <{$jquery|default:''}>
      <script type='text/javascript'>
        $(document).ready(function(){
            $('#sort').sortable({ opacity: 0.6, cursor: 'move', update: function() {
                var order = $(this).sortable('serialize');
                $.post('save_sort.php', order, function(theResponse){
                    $('#save_msg').html(theResponse);
                });
            }
            });
        });

      </script>
      <div id='save_msg'></div>
      <table class="table table-striped table-bordered table-hover">
        <tr>
          <th><{$smarty.const._MA_TADFAQ_SORT}></th>
          <th><{$smarty.const._MA_TADFAQ_TITLE}></th>
          <th><{$smarty.const._MA_TADFAQ_DESCRIPTION}></th>
          <th><{$smarty.const._MA_TADFAQ_ACCESS_POWER}></th>
          <th><{$smarty.const._MA_TADFAQ_EDIT_POWER}></th>
          <th><{$smarty.const._TAD_FUNCTION}></th>
        </tr>
        <tbody id='sort' >
        <{foreach from=$all_content item=faq}>
          <tr id='tr_<{$faq.fcsn}>'>
            <td><img src='<{$xoops_url}>/modules/tadtools/treeTable/images/updown_s.png' style='cursor: s-resize;margin:0px 4px;' alt='' title=''><{$faq.sort}></td>
            <td><{$faq.title}></td>
            <td><{$faq.description}></td>
            <td><{$faq.faq_read}></td>
            <td><{$faq.faq_edit}></td>
            <td>
              <a href="javascript:delete_tad_faq_cate_func(<{$faq.fcsn}>);" class="btn btn-sm btn-xs btn-danger"><i class="fa fa-trash-o" aria-hidden="true"></i> <{$smarty.const._TAD_DEL}></a>
              <a href="main.php?op=tad_faq_cate_form&fcsn=<{$faq.fcsn}>" class="btn btn-sm btn-xs btn-warning"><i class="fa fa-pencil-square-o" aria-hidden="true"></i> <{$smarty.const._TAD_EDIT}></a>
            </td>
          </tr>
        <{/foreach}>
        </tbody>
        <tr>
      </table>
      <{/if}>


      <form action="main.php" method="post" id="myForm" enctype="multipart/form-data" class="form-horizontal" role="form">
        <div class="row">
          <div class="col-sm-9">
            <div class="form-group row mb-3">
              <label class="col-sm-2 col-form-label text-sm-right text-sm-end control-label">
                <{$smarty.const._MA_TADFAQ_TITLE}>
              </label>
              <div class="col-sm-10">
                <input type="text" name="title" value="<{$title|default:''}>" class="form-control">
              </div>
            </div>

            <div class="form-group row mb-3">
              <label class="col-sm-2 col-form-label text-sm-right text-sm-end control-label">
                <{$smarty.const._MA_TADFAQ_CONTENT}>
              </label>
              <div class="col-sm-10">
                <{$editor|default:''}>
              </div>
            </div>
          </div>

          <div class="col-sm-3">
            <div class="row">
              <label><{$smarty.const._MA_TADFAQ_SET_ACCESS_POWER}></label>
              <{$faq_read_group|default:''}>
            </div>
            <div class="row">
              <label><{$smarty.const._MA_TADFAQ_SET_EDIT_POWER}></label>
              <{$faq_edit_group|default:''}>
            </div>
            <div class="row text-center">
              <input type="hidden" name="fcsn" value="<{$fcsn|default:''}>">
              <input type="hidden" name="op" value="<{$op|default:''}>">
              <button type="submit" class="btn btn-primary"><i class="fa fa-floppy-o" aria-hidden="true"></i> <{$smarty.const._TAD_SAVE}></button>
            </div>
          </div>
        </div>
      </form>

    </div>
  </div>

</div>