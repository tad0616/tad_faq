<?php

namespace XoopsModules\Tad_faq;

use XoopsModules\Tadtools\Utility;

class Tools
{
    //判斷某人在哪些類別中有觀看或發表(upload)的權利
    public static function chk_faq_cate_power($kind = '')
    {
        global $xoopsDB, $xoopsUser;

        $moduleHandler = xoops_getHandler('module');
        $xoopsModule = $moduleHandler->getByDirname('tad_faq');
        $module_id = $xoopsModule->getVar('mid');

        $ok_cat = [];
        if (!empty($xoopsUser)) {
            if (isset($_SESSION['tad_faq_adm']) && $_SESSION['tad_faq_adm']) {
                $ok_cat[] = '0';
            }
            $user_array = $xoopsUser->getGroups();
            $gsn_arr = implode(',', $user_array);
        } else {
            $user_array = [3];
            unset($_SESSION['tad_faq_adm']);
            $gsn_arr = 3;
        }

        $sql = 'SELECT `gperm_itemid` FROM `' . $xoopsDB->prefix('group_permission') . '` WHERE `gperm_modid`=? AND `gperm_name`=? AND `gperm_groupid` IN (?)';
        $result = Utility::query($sql, 'iss', [$module_id, $kind, $gsn_arr]) or Utility::web_error($sql, __FILE__, __LINE__);

        while (list($gperm_itemid) = $xoopsDB->fetchRow($result)) {
            $ok_cat[] = $gperm_itemid;
        }

        return $ok_cat;
    }

    //取得個分類的文章數
    public static function get_cate_count()
    {
        global $xoopsDB;
        $counter = [];
        $sql = 'SELECT `fcsn`, COUNT(*) FROM `' . $xoopsDB->prefix('tad_faq_content') . '` GROUP BY `fcsn`';
        $result = Utility::query($sql) or Utility::web_error($sql, __FILE__, __LINE__);

        while (list($fcsn, $count) = $xoopsDB->fetchRow($result)) {
            $counter[$fcsn] = $count;
        }

        return $counter;
    }

}
