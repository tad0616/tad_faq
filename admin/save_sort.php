<?php
use XoopsModules\Tadtools\Utility;
require dirname(dirname(dirname(__DIR__))) . '/include/cp_header.php';
require dirname(__DIR__) . '/function.php';

error_reporting(0);
$xoopsLogger->activated = false;
$sort = 1;
foreach ($_POST['tr'] as $recordIDValue) {
    $sql = 'UPDATE `' . $xoopsDB->prefix('tad_faq_cate') . '` SET `sort`=? WHERE `fcsn`=?';
    Utility::query($sql, 'ii', [$sort, $recordIDValue]) or die('Save Sort Fail! (' . date('Y-m-d H:i:s') . ')');
    $sort++;
}

echo 'Save Sort OK! (' . date('Y-m-d H:i:s') . ')';
