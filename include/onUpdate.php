<?php

use XoopsModules\Tad_faq\Utility;

function xoops_module_update_tad_faq(&$module, $old_version)
{
    global $xoopsDB;

    if (!Utility::chk_chk1()) {
        Utility::go_update1();
    }

    $old_fckeditor = XOOPS_ROOT_PATH . "/modules/tad_faq/fckeditor";
    if (is_dir($old_fckeditor)) {
        Utility::delete_directory($old_fckeditor);
    }
    if (Utility::chk_uid()) {
        Utility::go_update_uid();
    }

    Utility::chk_tad_faq_block();

    return true;
}

