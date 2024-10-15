<div class="container-fluid">

  <{if $show_error=='1'}>
    <{$error|default:''}>
    <div class="jumbotron bg-light p-5 rounded-lg m-3">
      <h1><{$smarty.const._MA_TADFAQ_NO_SFAQ}></h1>
    </div>
  <{else}>
    <a href="copysfaq.php?op=copyfaq" class="btn btn-lg btn-info pull-right float-right pull-end"><i class="fa fa-files-o" aria-hidden="true"></i> copy all</a>
    <table class="table table-striped table-bordered table-hover">
    <tr>
      <th>#</th>
      <th>Function</th>
      <th>categoryid</th>
      <th>parentid</th>
      <th>name</th>
      <th>description</th>
      <th>total</th>
      <th>weight</th>
      <th>created</th>
      <th>tools</th>
      <th>FAQs</th>
    </tr>

    <tbody>

    <{foreach item=faq from=$all_content}>

    <tr>
      <td><{$faq.i}></td>
      <td><a href="copysfaq.php?op=delsfaq&categoryid=<{$faq.categoryid}>" class="btn btn-sm btn-xs btn-danger"><i class="fa fa-trash-o" aria-hidden="true"></i> <{$smarty.const._TAD_DEL}></a></td>
      <td><{$faq.categoryid}></td>
      <td><{$faq.parentid}></td>
      <td><{$faq.name}></td>
      <td><{$faq.description}></td>
      <td><{$faq.total}></td>
      <td><{$faq.weight}></td>
      <td><{$faq.created}></td>
      <td>
        <{if $faq.exist==0}>
        done
        <{elseif $faq.exist > 0}>
          <a href="copysfaq.php?op=listfaq&categoryid=<{$faq.categoryid}>"<{if $faq.exist > 0 and $faq.faq_number==0}>class="btn btn-sm btn-xs btn-danger"<{/if}>><{$faq.exist}> FAQs</a>
        <{else}>
          <a href="copysfaq.php?op=copyfaq&categoryid=<{$faq.categoryid}>">copy</a>
        <{/if}>
      </td>
      <td><{$faq.faq_number}></td>
      </tr>
    <{/foreach}>



    </tbody>

    <tr>
      <td colspan=6 class='bar'>
      <{$add_button|default:''}>
      <{$bar|default:''}>
      </td>
    </tr>
    </table>
  <{/if}>
</div>