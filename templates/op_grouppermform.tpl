<div class="container-fluid">
  <script type="text/javascript">
    $(document).ready(function() {
      var $tabs = $("#grouppermform-tabs").tabs({ cookie: { expires: 30 } , collapsible: true});
    });
  </script>

  <div id="grouppermform-tabs">
    <ul>
      <li><a href="#tabs-1"><{$smarty.const._MA_TADFAQ_SET_ACCESS_POWER}></a></li>
      <li><a href="#tabs-2"><{$smarty.const._MA_TADFAQ_SET_EDIT_POWER}></a></li>
    </ul>
    <div id="tabs-1">
      <{$main1|default:''}>
    </div>
    <div id="tabs-2">
      <{$main2|default:''}>
    </div>
  </div>
</div>
