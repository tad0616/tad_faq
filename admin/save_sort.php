<?php
use XoopsModules\Tadtools\Utility;
require dirname(dirname(dirname(__DIR__))) . '/include/cp_header.php';
require dirname(__DIR__) . '/function.php';

// 關閉除錯訊息
header('HTTP/1.1 200 OK');
$xoopsLogger->activated = false;
$sort = 1;
foreach ($_POST['tr'] as $recordIDValue) {
    $sql = 'UPDATE `' . $xoopsDB->prefix('tad_faq_cate') . '` SET `sort`=? WHERE `fcsn`=?';
    Utility::query($sql, 'ii', [$sort, $recordIDValue]) or die(_TAD_SORT_FAIL . ' (' . date('Y-m-d H:i:s') . ')');
    $sort++;
}

echo _TAD_SORTED . "(" . date("Y-m-d H:i:s") . ")";
