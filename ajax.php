<?php
include_once "../../mainfile.php";

$sql           = "select counter from " . $xoopsDB->prefix("tad_faq_content") . " where fqsn='{$_POST['sn']}'";
$result        = $xoopsDB->query($sql);
list($counter) = $xoopsDB->fetchRow($result);

$counter++;

$sql = "update " . $xoopsDB->prefix("tad_faq_content") . " set counter=$counter where fqsn='{$_POST['sn']}'";
$xoopsDB->queryF($sql);

echo $counter;
