<?php

//判斷某人在哪些類別中有觀看或發表(upload)的權利
if (!function_exists('chk_faq_cate_power')) {
    function chk_faq_cate_power($kind = "")
    {
        global $xoopsDB, $xoopsUser, $isAdmin;

        $modhandler  = xoops_gethandler('module');
        $xoopsModule = $modhandler->getByDirname("tad_faq");

        $module_id = $xoopsModule->getVar('mid');
        if (!empty($xoopsUser)) {
            if ($isAdmin) {
                $ok_cat[] = "0";
            }
            $user_array = $xoopsUser->getGroups();
            $gsn_arr    = implode(",", $user_array);
        } else {
            $user_array = array(3);
            $isAdmin    = 0;
            $gsn_arr    = 3;
        }

        $sql = "select gperm_itemid from " . $xoopsDB->prefix("group_permission") . " where gperm_modid='$module_id' and gperm_name='$kind' and gperm_groupid in ($gsn_arr)";

        $result = $xoopsDB->query($sql) or web_error($sql);

        while (list($gperm_itemid) = $xoopsDB->fetchRow($result)) {
            $ok_cat[] = $gperm_itemid;
        }

        return $ok_cat;
    }
}

//取得個分類的文章數
if (!function_exists('get_cate_count')) {
    function get_cate_count()
    {
        global $xoopsDB;
        $sql    = "select fcsn,count(*) from " . $xoopsDB->prefix("tad_faq_content") . " group by fcsn";
        $result = $xoopsDB->query($sql) or web_error($sql);
        while (list($fcsn, $count) = $xoopsDB->fetchRow($result)) {
            $counter[$fcsn] = $count;
        }
        return $counter;
    }
}
