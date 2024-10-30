<?php
//判斷是否對該模組有管理權限
if (!isset($_SESSION['tad_faq_adm'])) {
    $_SESSION['tad_faq_adm'] = ($xoopsUser) ? $xoopsUser->isAdmin() : false;
}

$interface_menu[_MD_TADFAQ_ALL_FAQ] = 'index.php';
$interface_icon[_MD_TADFAQ_ALL_FAQ] = 'fa-question-circle ';
