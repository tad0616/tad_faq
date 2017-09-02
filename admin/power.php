<?php
/*-----------引入檔案區--------------*/
$xoopsOption['template_main'] = "tad_faq_adm_power.tpl";
include_once "header.php";
include_once "../function.php";
include_once XOOPS_ROOT_PATH . "/Frameworks/art/functions.php";
include_once XOOPS_ROOT_PATH . "/Frameworks/art/functions.admin.php";
include_once XOOPS_ROOT_PATH . '/class/xoopsform/grouppermform.php';

/*-----------function區--------------*/
$module_id = $xoopsModule->getVar('mid');

$jquery_path = get_jquery(true); //TadTools引入jquery ui
$xoopsTpl->assign('jquery_path', $jquery_path);

//抓取所有資料夾

$sql = "SELECT fcsn,title FROM " . $xoopsDB->prefix("tad_faq_cate");
$result = $xoopsDB->query($sql) or web_error($sql);
while (list($fcsn, $title) = $xoopsDB->fetchRow($result)) {
    $item_list[$fcsn] = $title;
}

$perm_desc = "";
$formi     = new XoopsGroupPermForm("", $module_id, 'faq_read', $perm_desc);
foreach ($item_list as $item_id => $item_name) {
    $formi->addItem($item_id, $item_name);
}

$main1 = $formi->render();
$xoopsTpl->assign('main1', $main1);

$formi = new XoopsGroupPermForm("", $module_id, 'faq_edit', $perm_desc);
foreach ($item_list as $item_id => $item_name) {
    $formi->addItem($item_id, $item_name);
}

$main2 = $formi->render();
$xoopsTpl->assign('main2', $main2);

/*-----------秀出結果區--------------*/
include_once 'footer.php';
