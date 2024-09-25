<?php
use Xmf\Request;
use XoopsModules\Tadtools\Utility;
/*-----------引入檔案區--------------*/
$GLOBALS['xoopsOption']['template_main'] = 'tad_faq_adm_sfaq.tpl';
require_once __DIR__ . '/header.php';
require_once dirname(__DIR__) . '/function.php';

/*-----------執行動作判斷區----------*/
$op = Request::getString('op');
$fcsn = Request::getInt('fcsn');
$categoryid = Request::getInt('categoryid');

switch ($op) {
    /*---判斷動作請貼在下方---*/

    case 'copyfaq':
        copyfaq($categoryid);
        header("location: {$_SERVER['PHP_SELF']}");
        exit;

    case 'listfaq':
        listfaq($categoryid);
        break;
    case 'import_faq':
        import_faq($categoryid);
        header("location: {$_SERVER['PHP_SELF']}");
        exit;

    //預設動作
    default:
        list_smartfaq();
        break;
        /*---判斷動作請貼在上方---*/
}

/*-----------秀出結果區--------------*/
$xoTheme->addStylesheet(XOOPS_URL . '/modules/tadtools/css/my-input.css');
require_once __DIR__ . '/footer.php';

/*-----------function區--------------*/

//列出所有tad_faq_board資料
function list_smartfaq()
{
    global $xoopsDB, $xoopsModule, $xoopsTpl;

    //取得某模組編號
    $moduleHandler = xoops_getHandler('module');
    $ThexoopsModule = $moduleHandler->getByDirname('smartfaq');

    if ($ThexoopsModule) {
        $mod_id = $ThexoopsModule->getVar('mid');
        $xoopsTpl->assign('show_error', '0');
    } else {
        $xoopsTpl->assign('show_error', '1');

        return;
    }

    //轉移權限(原權限)
    $sql = 'SELECT gperm_groupid,gperm_itemid,gperm_name FROM `' . $xoopsDB->prefix('group_permission') . "` WHERE `gperm_modid` =?";
    $result = Utility::query($sql, 'i', [$mod_id]) or redirect_header('index.php', 3, $sql);
    while (list($gperm_groupid, $gperm_itemid, $gperm_name) = $xoopsDB->fetchRow($result)) {
        $power[$gperm_itemid][$gperm_name][$gperm_groupid] = $gperm_groupid;
    }

    //轉移權限（新權限）
    $mid = $xoopsModule->getVar('mid');
    $sql = 'SELECT gperm_groupid,gperm_itemid,gperm_name FROM `' . $xoopsDB->prefix('group_permission') . "` WHERE `gperm_modid` =?";

    $result = Utility::query($sql, 'i', [$mid]) or redirect_header('index.php', 3, $sql);
    while (list($gperm_groupid, $gperm_itemid, $gperm_name) = $xoopsDB->fetchRow($result)) {
        $now_power[$gperm_itemid][$gperm_name][$gperm_groupid] = $gperm_groupid;
    }

    $sql = 'SELECT * FROM `' . $xoopsDB->prefix('smartfaq_categories') . '`';
    $result = Utility::query($sql) or redirect_header('index.php', 3, $sql);

    $all_content = [];
    $i = 0;
    while (false !== ($all = $xoopsDB->fetchArray($result))) {
        //以下會產生這些變數： `categoryid`, `parentid`, `name`, `description`, `total`, `weight`, `created`
        foreach ($all as $k => $v) {
            $$k = $v;
        }

        $all_content[$i]['categoryid'] = $categoryid;
        $all_content[$i]['parentid'] = $parentid;
        $all_content[$i]['name'] = $name;
        $all_content[$i]['description'] = $description;
        $all_content[$i]['total'] = $total;
        $all_content[$i]['weight'] = $weight;
        $all_content[$i]['created'] = $created;
        $all_content[$i]['exist'] = tad_faq_cate_exist($categoryid);
        $all_content[$i]['faq_number'] = get_faq_number($categoryid);
        $all_content[$i]['i'] = $i;
        $i++;
    }

    $xoopsTpl->assign('all_content', $all_content);
    $xoopsTpl->assign('add_button', $add_button);
    $xoopsTpl->assign('bar', $bar);
}

function get_faq_number($categoryid = '')
{
    global $xoopsDB;

    $sql = 'select count(*) from `' . $xoopsDB->prefix('tad_faq_content') . "` where fcsn =?";
    $result = Utility::query($sql, 'i', [$categoryid]) or redirect_header('index.php', 3, $sql);
    list($sn) = $xoopsDB->fetchRow($result);

    return $sn;
}

function chkcopy($forum_id)
{
    global $xoopsDB;
    $sql = 'select categoryid from `' . $xoopsDB->prefix('tad_faq_board') . "` where categoryid =?";
    $result = Utility::query($sql, 'i', [$forum_id]) or redirect_header('index.php', 3, $sql);
    list($sn) = $xoopsDB->fetchRow($result);

    return $sn;
}

//新增資料到 tad_faq_cate 中
function copyfaq($categoryid = '')
{
    global $xoopsDB;
    if (empty($categoryid)) {
        $sql = 'select * from `' . $xoopsDB->prefix('smartfaq_categories') . "` ";
        $result = Utility::query($sql) or redirect_header('index.php', 3, $sql);
    } else {
        $sql = 'select * from `' . $xoopsDB->prefix('smartfaq_categories') . "` where categoryid =?";
        $result = Utility::query($sql, 'i', [$categoryid]) or redirect_header('index.php', 3, $sql);
    }

    while (false !== ($all = $xoopsDB->fetchArray($result))) {
        //以下會產生這些變數： `categoryid`, `parentid`, `name`, `description`, `total`, `weight`, `created`
        foreach ($all as $k => $v) {
            $$k = $v;
        }

        $sql = 'replace into `' . $xoopsDB->prefix('tad_faq_cate') . "`
        (`fcsn`, `of_fcsn` ,`title` , `description` , `sort`)
        values(? , ? , ? , ? , ?)";
        Utility::query($sql, 'iisss', [$categoryid, $parentid, $name, $description, $weight]) or redirect_header('index.php', 3, $sql);
    }

    return $categoryid;
}

//有無該分類
function tad_faq_cate_exist($categoryid)
{
    global $xoopsDB;

    $sql = 'select title from `' . $xoopsDB->prefix('tad_faq_cate') . "` where `fcsn`=?";
    $result = Utility::query($sql, 'i', [$categoryid]) or redirect_header('index.php', 3, $sql);
    list($title) = $xoopsDB->fetchRow($result);
    if (!empty($title)) {
        $sql = 'select count(*) from `' . $xoopsDB->prefix('smartfaq_faq') . "` where `categoryid`=?";
        $result = Utility::query($sql, 'i', [$categoryid]) or redirect_header('index.php', 3, $sql);
        list($count) = $xoopsDB->fetchRow($result);

        return $count;
    }

    return false;
}

//列出常見問答
function listfaq($categoryid = '')
{
    global $xoopsDB, $xoopsTpl;

    $sql = 'SELECT a.*, b.answer
    FROM `' . $xoopsDB->prefix('smartfaq_faq') . '` AS a
    LEFT JOIN `' . $xoopsDB->prefix('smartfaq_answers') . '` AS b
    ON a.faqid = b.faqid';

    $params = [];
    $type = '';

    // 如果 categoryid 不為空，添加條件和參數
    if (!empty($categoryid)) {
        $sql .= ' WHERE a.categoryid = ?';
        $params[] = $categoryid;
        $type = 'i'; // categoryid 是整數類型
    }

    // 執行查詢
    $result = Utility::query($sql, $type, $params) or redirect_header('index.php', 3, $sql);

    while (false !== ($all = $xoopsDB->fetchArray($result))) {
        foreach ($all as $k => $v) {
            $$k = $v;
            $all_content[$i][$k] = $v;
        }
        $all_content[$i]['datesub'] = date('Y-m-d H:i:s', $datesub);
        $all_content[$i]['question'] = nl2br($question);
        $all_content[$i]['answer'] = nl2br($answer);
        $i++;
    }

    $xoopsTpl->assign('categoryid', $categoryid);
    $xoopsTpl->assign('all_content', $all_content);
    $xoopsTpl->assign('op', 'listfaq');
}

//匯入常見問答
function import_faq($categoryid = '')
{
    global $xoopsDB;

    $sql = 'SELECT a.*, b.answer
        FROM `' . $xoopsDB->prefix('smartfaq_faq') . '` AS a
        LEFT JOIN `' . $xoopsDB->prefix('smartfaq_answers') . '` AS b
        ON a.faqid = b.faqid';

    $params = [];
    $type = '';

    // 如果 categoryid 不為空，添加條件和參數
    if (!empty($categoryid)) {
        $sql .= ' WHERE a.categoryid = ?';
        $params[] = $categoryid;
        $type = 'i'; // categoryid 是整數類型
    }

    // 執行查詢
    $result = Utility::query($sql, $type, $params) or redirect_header('index.php', 3, $sql);
    while (false !== ($all = $xoopsDB->fetchArray($result))) {
        foreach ($all as $k => $v) {
            $$k = $v;
        }

        $datesub = date('Y-m-d H:i:s', $datesub);
        $question = nl2br($question);
        $answer = nl2br($answer);

        $sql = 'replace into `' . $xoopsDB->prefix('tad_faq_content') . "`
        (`fqsn`, `fcsn`, `title`, `sort`, `uid`, `post_date`, `content`, `enable`, `counter`)
        values(? , ? ,  ? , ? , ? , ? , ? , '1' , ?)";
        Utility::query($sql, 'iisiissi', [$faqid, $categoryid, $question, $weight, $uid, $datesub, $answer, $counter]) or redirect_header('index.php', 3, $sql);
    }
}
