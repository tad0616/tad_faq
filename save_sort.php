<?php
use XoopsModules\Tadtools\Utility;
require_once dirname(dirname(__DIR__)) . '/mainfile.php';

error_reporting(0);
$xoopsLogger->activated = false;

$updateRecordsArray = $_POST['tr'];

$sort = 1;
foreach ($updateRecordsArray as $recordIDValue) {
    $sql = 'update ' . $xoopsDB->prefix('tad_faq_content') . " set `sort`=? where `fqsn`=?";
    Utility::query($sql, 'ii', [$sort, $recordIDValue]) or die('Save Sort Fail! (' . date('Y-m-d H:i:s') . ')');
    $sort++;
}

echo 'Save Sort OK! (' . date('Y-m-d H:i:s') . ')';
