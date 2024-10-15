<div class="pull-right float-right pull-end">
    <a href="copysfaq.php?op=import_faq&categoryid=<{$categoryid|default:''}>" class="btn btn-lg btn-info"><i class="fa fa-cloud-download" aria-hidden="true"></i> Import All</a>
</div>
<div class="clearfix"></div>

<table class="table table-striped table-bordered table-hover">
<tr>
    <th>fqsn</th>
    <th>fcsn</th>
    <th>title</th>
    <th>sort</th>
    <th>uid</th>
    <th>post_date</th>
    <th>content</th>
    <th>enable</th>
    <th>counter</th>

</tr>

<tbody>
<{foreach item=faq from=$all_content}>
    <tr>
    <td><{$faq.faqid}></td>
    <td><{$faq.categoryid}></td>
    <td><{$faq.question}></td>
    <td><{$faq.weight}></td>
    <td><{$faq.uid}></td>
    <td><{$faq.datesub}></td>
    <td><{$faq.answer}></td>
    <td>1</td>
    <td><{$faq.counter}></td>
    </tr>
<{/foreach}>
</tbody>
</table>
<div class="clearfix"></div>