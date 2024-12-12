<?php
use Xmf\Request;
use XoopsModules\Tadtools\Utility;
/*-----------引入檔案區--------------*/
require_once dirname(dirname(__DIR__)) . '/mainfile.php';

// 關閉除錯訊息
header('HTTP/1.1 200 OK');
$xoopsLogger->activated = false;

$updateRecordsArray = Request::getVar('tr', [], null, 'array', 4);

$sort = 1;
foreach ($updateRecordsArray as $recordIDValue) {
    $sql = 'UPDATE `' . $xoopsDB->prefix('tad_faq_content') . '` SET `sort`=? WHERE `fqsn`=?';
    Utility::query($sql, 'ii', [$sort, $recordIDValue]) or die(_TAD_SORT_FAIL . ' (' . date('Y-m-d H:i:s') . ')');

    $sort++;
}

echo _TAD_SORTED . "(" . date("Y-m-d H:i:s") . ")";
