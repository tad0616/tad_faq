<?php
function xoops_module_install_tad_faq(&$module)
{

    tad_faq_mk_dir(XOOPS_ROOT_PATH . "/uploads/tad_faq");
    tad_faq_mk_dir(XOOPS_ROOT_PATH . "/uploads/tad_faq/file");
    tad_faq_mk_dir(XOOPS_ROOT_PATH . "/uploads/tad_faq/image");
    tad_faq_mk_dir(XOOPS_ROOT_PATH . "/uploads/tad_faq/image/.thumbs");

    return true;
}

//建立目錄
function tad_faq_mk_dir($dir = "")
{
    //若無目錄名稱秀出警告訊息
    if (empty($dir)) {
        return;
    }

    //若目錄不存在的話建立目錄
    if (!is_dir($dir)) {
        umask(000);
        //若建立失敗秀出警告訊息
        mkdir($dir, 0777);
    }
}
