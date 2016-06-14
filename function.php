<?php
//引入TadTools的函式庫
if (!file_exists(XOOPS_ROOT_PATH . "/modules/tadtools/tad_function.php")) {
    redirect_header("http://campus-xoops.tn.edu.tw/modules/tad_modules/index.php?module_sn=1", 3, _TAD_NEED_TADTOOLS);
}
include_once XOOPS_ROOT_PATH . "/modules/tadtools/tad_function.php";
include_once "function_block.php";

//以流水號取得某筆tad_faq_cate資料
function get_tad_faq_cate($fcsn = "")
{
    global $xoopsDB;
    if (empty($fcsn)) {
        return;
    }

    $sql    = "select * from " . $xoopsDB->prefix("tad_faq_cate") . " where fcsn='$fcsn'";
    $result = $xoopsDB->query($sql) or web_error($sql);
    $data   = $xoopsDB->fetchArray($result);
    return $data;
}

//以流水號取得某筆tad_faq_content資料
function get_tad_faq_content($fqsn = "")
{
    global $xoopsDB;
    if (empty($fqsn)) {
        return;
    }

    $sql    = "select * from " . $xoopsDB->prefix("tad_faq_content") . " where fqsn='$fqsn'";
    $result = $xoopsDB->query($sql) or web_error($sql);
    $data   = $xoopsDB->fetchArray($result);
    return $data;
}

//新增資料到tad_faq_cate中
function insert_tad_faq_cate($new_title = "")
{
    global $xoopsDB;

    $myts                 = MyTextSanitizer::getInstance();
    $title                = $new_title ? $myts->addSlashes($new_title) : $myts->addSlashes($_POST['title']);
    $_POST['description'] = $myts->addSlashes($_POST['description']);

    $sql = "insert into " . $xoopsDB->prefix("tad_faq_cate") . " (`of_fcsn`,`title`,`description`,`sort`,`cate_pic`) values('{$_POST['of_fcsn']}','{$title}','{$_POST['description']}','{$_POST['sort']}','{$_POST['cate_pic']}')";
    $xoopsDB->query($sql) or web_error($sql);
    //取得最後新增資料的流水編號
    $fcsn = $xoopsDB->getInsertId();

    $faq_read = empty($_POST['faq_read']) ? array(1, 2, 3) : $_POST['faq_read'];
    $faq_edit = empty($_POST['faq_edit']) ? array(1) : $_POST['faq_edit'];

    //寫入權限
    saveItem_Permissions($faq_read, $fcsn, 'faq_read');
    saveItem_Permissions($faq_edit, $fcsn, 'faq_edit');
    return $fcsn;
}

//儲存權限
function saveItem_Permissions($groups, $itemid, $perm_name)
{
    global $xoopsModule;
    $module_id     = $xoopsModule->getVar('mid');
    $gperm_handler = &xoops_gethandler('groupperm');

    // First, if the permissions are already there, delete them
    $gperm_handler->deleteByModule($module_id, $perm_name, $itemid);

    // Save the new permissions
    if (count($groups) > 0) {
        foreach ($groups as $group_id) {
            $gperm_handler->addRight($perm_name, $itemid, $group_id, $module_id);
        }
    }
}

//檢查有無權限
function check_power($kind = "faq_read", $fcsn = "")
{
    global $xoopsUser, $xoopsModule, $isAdmin;

    //取得目前使用者的群組編號
    if ($xoopsUser) {
        $uid    = $xoopsUser->getVar('uid');
        $groups = $xoopsUser->getGroups();
    } else {
        $uid    = 0;
        $groups = XOOPS_GROUP_ANONYMOUS;
    }

    //if(!$isAdmin ) return false;

    //取得模組編號
    $module_id = $xoopsModule->getVar('mid');

    //取得群組權限功能
    $gperm_handler = &xoops_gethandler('groupperm');

    //權限項目編號
    $perm_itemid = intval($fcsn);
    //依據該群組是否對該權限項目有使用權之判斷 ，做不同之處理

    if (empty($fcsn)) {
        if ($kind == "faq_read") {
            return true;
        } else {
            if ($isAdmin) {
                return true;
            }

        }
    } else {
        if ($gperm_handler->checkRight($kind, $fcsn, $groups, $module_id) or $isAdmin) {
            return true;
        }

    }

    return false;
}

//判斷某類別中有哪些觀看或發表的群組 $mode=name or id
function get_cate_enable_group($kind = "", $fcsn = "", $mode = "id")
{
    global $xoopsDB, $xoopsUser, $xoopsModule, $isAdmin;
    $module_id = $xoopsModule->getVar('mid');

    $sql = "select a.gperm_groupid,b.name from " . $xoopsDB->prefix("group_permission") . " as a left join " . $xoopsDB->prefix("groups") . " as b on a.gperm_groupid=b.groupid where a.gperm_modid='$module_id' and a.gperm_name='$kind' and a.gperm_itemid='{$fcsn}'";

    $result = $xoopsDB->query($sql) or web_error($sql);

    while (list($gperm_groupid, $name) = $xoopsDB->fetchRow($result)) {
        $ok_group[] = $mode == 'name' ? $name : $gperm_groupid;
    }

    return $ok_group;
}
