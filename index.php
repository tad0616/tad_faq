<?php
use Xmf\Request;
use XoopsModules\Tadtools\CkEditor;
use XoopsModules\Tadtools\Utility;
use XoopsModules\Tadtools\Wcag;

/*-----------引入檔案區--------------*/
require __DIR__ . '/header.php';
$xoopsOption['template_main'] = 'tad_faq_index.tpl';
require_once XOOPS_ROOT_PATH . '/header.php';

/*-----------執行動作判斷區----------*/
$op = Request::getString('op');
$fcsn = Request::getInt('fcsn');
$fqsn = Request::getInt('fqsn');

switch ($op) {
    case 'update_status':
        update_status($fqsn, $_GET['enable']);
        header("location: {$_SERVER['PHP_SELF']}?fcsn=$fcsn");
        exit;

    //刪除資料
    case 'delete_tad_faq_content':
        delete_tad_faq_content($fqsn);
        header("location: {$_SERVER['PHP_SELF']}?fcsn=$fcsn");
        exit;

    //新增資料
    case 'insert_tad_faq_content':
        $fcsn = insert_tad_faq_content();
        header("location: {$_SERVER['PHP_SELF']}?fcsn=$fcsn");
        exit;

    //更新資料
    case 'update_tad_faq_content':
        $fcsn = update_tad_faq_content($fqsn);
        header("location: {$_SERVER['PHP_SELF']}?fcsn=$fcsn");
        exit;

    case 'tad_faq_content_form':
        tad_faq_content_form($fcsn, $fqsn);
        break;

    default:
        if (!empty($fcsn)) {
            list_faq($fcsn);
            $op = 'list_faq';
        } else {
            list_all();
            $op = 'list_all';
        }
        break;
}

$xoopsTpl->assign('now_op', $op);
$xoopsTpl->assign('toolbar', Utility::toolbar_bootstrap($interface_menu));
$xoTheme->addStylesheet('modules/tad_faq/css/module.css');

require_once XOOPS_ROOT_PATH . '/footer.php';

/*-----------function區--------------*/

//列出所有tad_faq_cate資料
function list_all()
{
    global $xoopsDB, $xoopsModule, $xoopsModuleConfig, $xoopsTpl;

    $edit_power = chk_faq_cate_power('faq_edit');
    $xoopsTpl->assign('edit_power', $edit_power);
    $read_power = chk_faq_cate_power('faq_read');

    $counter = get_cate_count();

    $sql = 'SELECT * FROM ' . $xoopsDB->prefix('tad_faq_cate') . ' ORDER BY sort';
    $result = Utility::query($sql) or Utility::web_error($sql, __FILE__, __LINE__);

    $data = [];
    $i = 3;
    while (list($fcsn, $of_fcsn, $title, $description, $sort, $cate_pic) = $xoopsDB->fetchRow($result)) {
        if (!in_array($fcsn, $read_power)) {
            continue;
        }

        if (1 == $i % 3) {
            $img = '001_58.gif';
        } elseif (2 == $i % 3) {
            $img = '001_59.gif';
        } else {
            $img = '001_60.gif';
        }
        $num = (empty($counter[$fcsn])) ? 0 : $counter[$fcsn];
        //$data[$i]['num']     = sprintf(_MD_TADFAQ_FAQ_NUM, $num);
        $data[$i]['num'] = $num;
        $data[$i]['fcsn'] = $fcsn;
        $data[$i]['img'] = $img;
        $data[$i]['title'] = $title;
        $data[$i]['counter'] = $counter[$fcsn];
        $i++;
    }

    $xoopsTpl->assign('faq', $data);
    $xoopsTpl->assign('module_title', $xoopsModuleConfig['module_title']);
}

//列出 FAQ 列表
function list_faq($fcsn = '')
{
    global $xoopsDB, $xoopsUser, $xoopsTpl;
    Utility::get_jquery(true);

    //權限檢查
    $faq_read_power = Utility::power_chk('faq_read', $fcsn);
    $faq_edit_power = Utility::power_chk('faq_edit', $fcsn);

    //依據該群組是否對該權限項目有使用權之判斷 ，做不同之處理
    if (!$faq_read_power) {
        redirect_header($_SERVER['PHP_SELF'], 3, _MD_TADFAQ_NO_ACCESS_POWER);
    }

    $xoopsTpl->assign('fcsn', $fcsn);
    $cates = get_tad_faq_cate();
    Utility::test($cates, 'cates', 'dd');
    $cate = get_tad_faq_cate($fcsn);

    $now_uid = ($xoopsUser) ? $xoopsUser->uid() : 0;

    $sql = 'SELECT * FROM `' . $xoopsDB->prefix('tad_faq_content') . '` WHERE `fcsn`=? ORDER BY `sort`';
    $result = Utility::query($sql, 'i', [$fcsn]) or Utility::web_error($sql, __FILE__, __LINE__);
    $i = 1;
    while ($data = $xoopsDB->fetchArray($result)) {

        $data['i'] = $i;
        $data['update_enable'] = $data['enable'];
        $data['enable_txt'] = $data['enable'] ? _MD_TADFAQ_ENABLE : _MD_TADFAQ_UNABLE;
        $data['edit_power'] = ($faq_edit_power and $now_uid == $data['uid']) ? true : false;

        $faq[$i] = $data;
        $i++;
    }

    $xoopsTpl->assign('cates', $cates);
    $xoopsTpl->assign('cate_title', $cate['title']);
    $xoopsTpl->assign('faq', $faq);
    $xoopsTpl->assign('faq_edit_power', $faq_edit_power);
}

//刪除tad_faq_content某筆資料資料
function delete_tad_faq_content($fqsn = '')
{
    global $xoopsDB;
    $sql = 'delete from ' . $xoopsDB->prefix('tad_faq_content') . " where fqsn=?";
    Utility::query($sql, 'i', [$fqsn]) or Utility::web_error($sql, __FILE__, __LINE__);
}

//啟動或關閉
function update_status($fqsn = '', $enable = '')
{
    global $xoopsDB;
    $sql = 'update ' . $xoopsDB->prefix('tad_faq_content') . " set enable=? where fqsn=?";
    Utility::query($sql, 'si', [$enable, $fqsn]) or Utility::web_error($sql, __FILE__, __LINE__);
}

//分類選單
function get_faq_cate_opt($the_fcsn = '')
{
    global $xoopsDB;
    $opt = '';
    $edit_fcsn = chk_faq_cate_power('faq_edit');
    $sql = 'SELECT fcsn,title FROM ' . $xoopsDB->prefix('tad_faq_cate') . ' ORDER BY sort';
    $result = Utility::query($sql) or Utility::web_error($sql, __FILE__, __LINE__);
    while (list($fcsn, $title) = $xoopsDB->fetchRow($result)) {
        $selected = ($the_fcsn == $fcsn) ? 'selected' : '';
        if ($_SESSION['tad_faq_adm'] or in_array($fcsn, $edit_fcsn)) {
            $opt .= "<option value='$fcsn' $selected>$title</option>";
        }
    }

    return $opt;
}

//tad_faq_content編輯表單
function tad_faq_content_form($fcsn = '', $fqsn = '')
{
    global $xoopsTpl;

    //抓取預設值
    $DBV = !empty($fqsn) ? get_tad_faq_content($fqsn) : [];

    //預設值設定
    $fqsn = (!isset($DBV['fqsn'])) ? $fqsn : $DBV['fqsn'];
    $fcsn = (!isset($DBV['fcsn'])) ? $fcsn : $DBV['fcsn'];
    $title = (!isset($DBV['title'])) ? '' : $DBV['title'];
    $sort = (!isset($DBV['sort'])) ? '' : $DBV['sort'];
    $uid = (!isset($DBV['uid'])) ? '' : $DBV['uid'];
    $post_date = (!isset($DBV['post_date'])) ? '' : $DBV['post_date'];
    $content = (!isset($DBV['content'])) ? '' : $DBV['content'];
    $enable = (!isset($DBV['enable'])) ? '1' : $DBV['enable'];

    $faq_cate_opt = get_faq_cate_opt($fcsn);

    $CkEditor = new CkEditor('tad_faq', 'content', $content);
    $CkEditor->setHeight(400);
    $editor = $CkEditor->render();

    $op = (empty($fqsn)) ? 'insert_tad_faq_content' : 'update_tad_faq_content';
    //$op="replace_tad_faq_content";

    $xoopsTpl->assign('fqsn', $fqsn);
    $xoopsTpl->assign('faq_cate_opt', $faq_cate_opt);
    $xoopsTpl->assign('title', $title);
    $xoopsTpl->assign('editor', $editor);
    $xoopsTpl->assign('enable', $enable);
    $xoopsTpl->assign('op', $op);
}

//新增資料到tad_faq_content中
function insert_tad_faq_content()
{
    global $xoopsDB, $xoopsUser;

    $content = Wcag::amend($_POST['content']);
    $fcsn = !empty($_POST['new_cate']) ? insert_tad_faq_cate($_POST['new_cate']) : $_POST['fcsn'];

    $uid = ($xoopsUser) ? $xoopsUser->uid() : 0;
    $sort = get_max_faq_sort($fcsn);
    $now = date('Y-m-d H:i:s', xoops_getUserTimestamp(time()));
    $sql = 'INSERT INTO ' . $xoopsDB->prefix('tad_faq_content') . " (`fcsn`,`title`,`sort`,`uid`,`post_date`,`content`,`enable`) VALUES(?,?,?,?,?,?,?)";
    Utility::query($sql, 'isiissi', [$fcsn, $_POST['title'], $sort, $uid, $now, $content, $_POST['enable']]) or Utility::web_error($sql, __FILE__, __LINE__);

    return $fcsn;
}

//更新tad_faq_content某一筆資料
function update_tad_faq_content($fqsn = '')
{
    global $xoopsDB;
    $content = Wcag::amend($_POST['content']);
    $fcsn = !empty($_POST['new_cate']) ? insert_tad_faq_cate($_POST['new_cate']) : $_POST['fcsn'];
    $now = date('Y-m-d H:i:s', xoops_getUserTimestamp(time()));
    $sql = 'update ' . $xoopsDB->prefix('tad_faq_content') . " set  `fcsn` = ?, `title` = ?, `post_date` = ?, `content` = ?, `enable` = ? where fqsn=?";
    Utility::query($sql, 'isisii', [$fcsn, $_POST['title'], $now, $content, $_POST['enable'], $fqsn]) or Utility::web_error($sql, __FILE__, __LINE__);

    return $fcsn;
}

//自動取得新排序
function get_max_faq_sort($fcsn = '')
{
    global $xoopsDB;
    $sql = 'SELECT MAX(sort) from ' . $xoopsDB->prefix('tad_faq_content') . " WHERE fcsn=?";
    $result = Utility::query($sql, 'i', [$fcsn]) or Utility::web_error($sql, __FILE__, __LINE__);
    list($sort) = $xoopsDB->fetchRow($result);

    return ++$sort;
}
