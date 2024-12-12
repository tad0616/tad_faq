<h2><{$smarty.const._MD_TADFAQ_ADD_CONTENT}></h2>

<form action="index.php" method="post" id="myForm" enctype="multipart/form-data" class="form-horizontal" role="form">
  <div class="form-group row mb-3">
    <label class="col-sm-1 col-form-label text-sm-right text-sm-end control-label">
      <{$smarty.const._MD_TADFAQ_CATE_MENU}>
    </label>
    <{if $faq_cate_opt|default:false}>
      <div class="col-sm-3">
        <select name="fcsn" size=1 class="form-control form-select">
          <{$faq_cate_opt|default:''}>
        </select>
      </div>

      <{if $smarty.session.tad_faq_adm|default:false}>
        <div class="col-sm-8">
          <input type="text" name="new_cate" class="form-control" placeholder="<{$smarty.const._MD_TADFAQ_NEW_CATE}>">
        </div>
      <{/if}>
    <{else}>
      <{if $smarty.session.tad_faq_adm|default:false}>
        <div class="col-sm-11">
          <input type="text" name="new_cate" class="form-control" placeholder="<{$smarty.const._MD_TADFAQ_NEW_CATE}>">
        </div>
      <{/if}>
    <{/if}>
  </div>

  <div class="form-group row mb-3">
    <label class="col-sm-1 col-form-label text-sm-right text-sm-end control-label">
      <{$smarty.const._MD_TADFAQ_FAQ_TITLE}>
    </label>
    <div class="col-sm-7">
      <input type="text" name="title" value="<{$title|default:''}>" class="form-control">
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
    <label class="col-sm-1 col-form-label text-sm-right text-sm-end control-label">
      <{$smarty.const._MD_TADFAQ_CONTENT}>
    </label>
    <div class="col-sm-11">
      <{$editor|default:''}>
    </div>
  </div>

  <div class="text-center">
    <input type="hidden" name="fqsn" value="<{$fqsn|default:''}>">
    <input type="hidden" name="op" value="<{$op|default:''}>">
    <button type="submit" class="btn btn-primary"><i class="fa fa-floppy-disk" aria-hidden="true"></i> <{$smarty.const._TAD_SAVE}></button>
  </div>

</form>
