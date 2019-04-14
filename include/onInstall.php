<?php

use XoopsModules\Tad_faq\Utility;

include dirname(__DIR__) . '/preloads/autoloader.php';

function xoops_module_install_tad_faq(&$module)
{
    Utility::mk_dir(XOOPS_ROOT_PATH . '/uploads/tad_faq');
    Utility::mk_dir(XOOPS_ROOT_PATH . '/uploads/tad_faq/file');
    Utility::mk_dir(XOOPS_ROOT_PATH . '/uploads/tad_faq/image');
    Utility::mk_dir(XOOPS_ROOT_PATH . '/uploads/tad_faq/image/.thumbs');

    return true;
}
