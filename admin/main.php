<?php
use Xmf\Request;
use XoopsModules\Tadtools\CkEditor;
use XoopsModules\Tadtools\SweetAlert;
use XoopsModules\Tadtools\Utility;
use XoopsModules\Tadtools\Wcag;
/*-----------引入檔案區--------------*/
$GLOBALS['xoopsOption']['template_main'] = 'tad_faq_admin.tpl';
require_once __DIR__ . '/header.php';
require_once dirname(__DIR__) . '/function.php';

/*-----------執行動作判斷區----------*/
$op   = Request::getString('op');
$fcsn = Request::getInt('fcsn');
$fqsn = Request::getInt('fqsn');

switch ($op) {
    //新增資料
    case 'insert_tad_faq_cate':
        insert_tad_faq_cate();
        header("location: {$_SERVER['PHP_SELF']}");
        exit;

    //輸入表格
    case 'tad_faq_cate_form':
        tad_faq_cate_form($fcsn);
        $op = 'list_tad_faq_cate';
        break;

    //刪除資料
    case 'delete_tad_faq_cate':
        delete_tad_faq_cate($fcsn);
        header("location: {$_SERVER['PHP_SELF']}");
        exit;

    //更新資料
    case 'update_tad_faq_cate':
        update_tad_faq_cate($fcsn);
        header("location: {$_SERVER['PHP_SELF']}");
        exit;

    //預設動作
    default:
        list_tad_faq_cate();
        tad_faq_cate_form($fcsn);
        $op = 'list_tad_faq_cate';
        break;
}

$xoTheme->addStylesheet('modules/tadtools/css/my-input.css');
$xoopsTpl->assign('now_op', $op);
require_once __DIR__ . '/footer.php';

/*-----------function區--------------*/
//tad_faq_cate編輯表單
function tad_faq_cate_form($fcsn = '')
{
    global $xoopsTpl, $xoopsModule;
    require_once XOOPS_ROOT_PATH . '/class/xoopsformloader.php';

    //抓取預設值
    if (!empty($fcsn)) {
        $DBV = get_tad_faq_cate($fcsn);
    } else {
        $DBV = [];
    }

    //預設值設定

    $fcsn = (!isset($DBV['fcsn'])) ? '' : $DBV['fcsn'];
    $xoopsTpl->assign('fcsn', $fcsn);

    $of_fcsn = (!isset($DBV['of_fcsn'])) ? '' : $DBV['of_fcsn'];
    $xoopsTpl->assign('of_fcsn', $of_fcsn);

    $title = (!isset($DBV['title'])) ? '' : $DBV['title'];
    $xoopsTpl->assign('title', $title);

    $description = (!isset($DBV['description'])) ? '' : $DBV['description'];
    $xoopsTpl->assign('description', $description);

    $sort = (!isset($DBV['sort'])) ? get_max_sort() : $DBV['sort'];
    $xoopsTpl->assign('sort', $sort);

    $cate_pic = (!isset($DBV['cate_pic'])) ? '' : $DBV['cate_pic'];
    $xoopsTpl->assign('cate_pic', $cate_pic);

    $mod_id = $xoopsModule->getVar('mid');

    $modulepermHandler = xoops_getHandler('groupperm');

    $read_group = $modulepermHandler->getGroupIds('faq_read', $fcsn, $mod_id);
    $post_group = $modulepermHandler->getGroupIds('faq_edit', $fcsn, $mod_id);

    if (empty($read_group)) {
        $read_group = [1, 2, 3];
    }

    if (empty($post_group)) {
        $post_group = [1];
    }

    //可見群組
    $SelectGroup_name = new \XoopsFormSelectGroup('', 'faq_read', true, $read_group, 6, true);
    $SelectGroup_name->setExtra("class='span12 form-control'");
    $faq_read_group = $SelectGroup_name->render();

    //可上傳群組
    $SelectGroup_name = new \XoopsFormSelectGroup('', 'faq_edit', true, $post_group, 6, true);
    $SelectGroup_name->setExtra("class='span12 form-control'");
    $faq_edit_group = $SelectGroup_name->render();

    $CkEditor = new CkEditor('tad_faq', 'description', $description);
    $CkEditor->setHeight(100);
    $editor = $CkEditor->render();

    $op = (empty($fcsn)) ? 'insert_tad_faq_cate' : 'update_tad_faq_cate';

    $xoopsTpl->assign('editor', $editor);
    $xoopsTpl->assign('op', $op);
    $xoopsTpl->assign('faq_read_group', $faq_read_group);
    $xoopsTpl->assign('faq_edit_group', $faq_edit_group);
}

//列出所有tad_faq_cate資料
function list_tad_faq_cate()
{
    global $xoopsDB, $xoopsTpl;

    $SweetAlert = new SweetAlert();
    $SweetAlert->render("delete_tad_faq_cate_func", "main.php?op=delete_tad_faq_cate&fcsn=", 'fcsn');

    $sql    = 'SELECT * FROM `' . $xoopsDB->prefix('tad_faq_cate') . '` ORDER BY `sort`';
    $result = Utility::query($sql) or Utility::web_error($sql, __FILE__, __LINE__);

    $data = [];
    $i    = 0;
    while (list($fcsn, $of_fcsn, $title, $description, $sort, $cate_pic) = $xoopsDB->fetchRow($result)) {
        $faq_read = get_cate_enable_group('faq_read', $fcsn, 'name');
        $faq_edit = get_cate_enable_group('faq_edit', $fcsn, 'name');

        $data[$i]['fcsn']        = $fcsn;
        $data[$i]['of_fcsn']     = $of_fcsn;
        $data[$i]['title']       = $title;
        $data[$i]['description'] = strip_tags($description);
        $data[$i]['sort']        = $sort;
        $data[$i]['cate_pic']    = $cate_pic;
        $data[$i]['faq_read']    = implode(' , ', $faq_read);
        $data[$i]['faq_edit']    = implode(' , ', $faq_edit);

        $i++;
    }
    $xoopsTpl->assign('jquery', Utility::get_jquery(true));
    $xoopsTpl->assign('all_content', $data);
}

//更新tad_faq_cate某一筆資料
function update_tad_faq_cate($fcsn = 0)
{
    global $xoopsDB;

    $description = Wcag::amend($_POST['description']);
    $sql         = 'UPDATE `' . $xoopsDB->prefix('tad_faq_cate') . '` SET `of_fcsn` = ?, `title` = ?, `description` = ?, `cate_pic` = ? WHERE `fcsn` = ?';
    Utility::query($sql, 'isssi', [(int) $_POST['of_fcsn'], (string) $_POST['title'], (string) $description, (string) $_POST['cate_pic'], (int) $fcsn]) or Utility::web_error($sql, __FILE__, __LINE__, true);

    $faq_read = empty($_POST['faq_read']) ? [1, 2, 3] : $_POST['faq_read'];
    $faq_edit = empty($_POST['faq_edit']) ? [1] : $_POST['faq_edit'];

    //寫入權限
    Utility::save_perm($faq_read, $fcsn, 'faq_read');
    Utility::save_perm($faq_edit, $fcsn, 'faq_edit');

    return $fcsn;
}

//刪除tad_faq_cate某筆資料資料
function delete_tad_faq_cate($fcsn = '')
{
    global $xoopsDB;
    $sql = 'DELETE FROM `' . $xoopsDB->prefix('tad_faq_cate') . '` WHERE `fcsn`=?';
    Utility::query($sql, 'i', [$fcsn]) or Utility::web_error($sql, __FILE__, __LINE__);
}

//自動取得新排序
function get_max_sort()
{
    global $xoopsDB;
    $sql    = 'SELECT MAX(`sort`) FROM `' . $xoopsDB->prefix('tad_faq_cate') . '` WHERE `of_fcsn`=?';
    $result = Utility::query($sql, 's', ['']) or Utility::web_error($sql, __FILE__, __LINE__);

    list($sort) = $xoopsDB->fetchRow($result);
    return ++$sort;
}
