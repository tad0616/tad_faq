<?php
use XoopsModules\Tadtools\Utility;
require_once dirname(dirname(__DIR__)) . '/mainfile.php';

error_reporting(0);
$xoopsLogger->activated = false;

$sn = (int) $_POST['sn'];

$sql = 'select counter from ' . $xoopsDB->prefix('tad_faq_content') . " where fqsn=?";
$result = Utility::query($sql, 'i', [$sn]);
list($counter) = $xoopsDB->fetchRow($result);

if (!isset($_SESSION['ok_sn']) || !in_array($sn, $_SESSION['ok_sn'])) {
    $counter++;

    $sql = 'update ' . $xoopsDB->prefix('tad_faq_content') . " set counter=? where fqsn=?";
    Utility::query($sql, 'ii', [$counter, $sn]);
    $_SESSION['ok_sn'][$sn] = $sn;
}
echo $counter;
