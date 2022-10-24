<?php
require_once dirname(dirname(__DIR__)) . '/mainfile.php';
require_once __DIR__ . '/function.php';

//判斷是否對該模組有管理權限
if (!isset($_SESSION['tad_faq_adm'])) {
    $_SESSION['tad_faq_adm'] = ($xoopsUser) ? $xoopsUser->isAdmin() : false;
}

$interface_menu[_MD_TADFAQ_ALL_FAQ] = 'index.php';
if ($_SESSION['tad_faq_adm']) {
    $interface_menu[_TAD_TO_ADMIN] = 'admin/main.php';
}
