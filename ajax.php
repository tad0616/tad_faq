<?php
use XoopsModules\Tadtools\Utility;
require_once dirname(dirname(__DIR__)) . '/mainfile.php';

// 關閉除錯訊息
header('HTTP/1.1 200 OK');
$xoopsLogger->activated = false;

$sn = (int) $_POST['sn'];

$sql = 'SELECT `counter` FROM `' . $xoopsDB->prefix('tad_faq_content') . '` WHERE `fqsn`=?';
$result = Utility::query($sql, 'i', [$sn]) or Utility::web_error($sql, __FILE__, __LINE__);

list($counter) = $xoopsDB->fetchRow($result);

if (!isset($_SESSION['ok_sn']) || !in_array($sn, $_SESSION['ok_sn'])) {
    $counter++;

    $sql = 'update ' . $xoopsDB->prefix('tad_faq_content') . " set counter=? where fqsn=?";
    Utility::query($sql, 'ii', [$counter, $sn]);
    $_SESSION['ok_sn'][$sn] = $sn;
}
echo $counter;
