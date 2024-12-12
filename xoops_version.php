<?php
$modversion = [];
global $xoopsConfig;

//---模組基本資訊---//
$modversion['name'] = _MI_TADFAQ_NAME;
// $modversion['version'] = 3.0;
$modversion['version'] = $_SESSION['xoops_version'] >= 20511 ? '4.0.0-Stable' : '4.0';
$modversion['description'] = _MI_TADFAQ_DESC;
$modversion['author'] = _MI_TADFAQ_AUTHOR;
$modversion['credits'] = _MI_TADFAQ_CREDITS;
$modversion['help'] = 'page=help';
$modversion['license'] = 'GNU GPL 2.0';
$modversion['license_url'] = 'www.gnu.org/licenses/gpl-2.0.html/';
$modversion['image'] = "images/logo_{$xoopsConfig['language']}.png";
$modversion['dirname'] = basename(__DIR__);

//---模組狀態資訊---//
$modversion['release_date'] = '2024-12-12';
$modversion['module_website_url'] = 'https://tad0616.net/';
$modversion['module_website_name'] = _MI_TAD_WEB;
$modversion['module_status'] = 'release';
$modversion['author_website_url'] = 'https://tad0616.net/';
$modversion['author_website_name'] = _MI_TAD_WEB;
$modversion['min_php'] = 5.4;
$modversion['min_xoops'] = '2.5.10';

//---paypal資訊---//
$modversion['paypal'] = [
    'business' => 'tad0616@gmail.com',
    'item_name' => 'Donation : ' . _MI_TAD_WEB,
    'amount' => 0,
    'currency_code' => 'USD',
];

//---啟動後台管理界面選單---//
$modversion['system_menu'] = 1;

//---資料表架構---//
$modversion['sqlfile']['mysql'] = 'sql/mysql.sql';
$modversion['tables'] = [
    'tad_faq_cate',
    'tad_faq_content',
];

//---安裝設定---//
$modversion['onInstall'] = 'include/onInstall.php';
$modversion['onUpdate'] = 'include/onUpdate.php';
$modversion['onUninstall'] = 'include/onUninstall.php';

//---搜尋設定---//
$modversion['hasSearch'] = 1;
$modversion['search']['file'] = 'include/tad_faq_search.php';
$modversion['search']['func'] = 'tad_faq_search';

//---管理介面設定---//
$modversion['hasAdmin'] = 1;
$modversion['adminindex'] = 'admin/index.php';
$modversion['adminmenu'] = 'admin/menu.php';

//---使用者主選單設定---//
$modversion['hasMain'] = 1;

//---樣板設定---//
$modversion['templates'] = [
    ['file' => 'tad_faq_admin.tpl', 'description' => 'tad_faq_admin.tpl'],
    ['file' => 'tad_faq_index.tpl', 'description' => 'tad_faq_index.tpl'],
];

//---區塊設定 (索引為固定值，若欲刪除區塊記得補上索引，避免區塊重複)---//
$modversion['blocks'] = [
    1 => [
        'file' => 'tad_faq_block.php',
        'name' => _MI_TADFAQ_BNAME1,
        'description' => _MI_TADFAQ_BDESC1,
        'show_func' => 'tad_faq_show',
        'template' => 'tad_faq_block.tpl',
    ],
];

//---偏好設定---//
$modversion['config'] = [
    [
        'name' => 'module_title',
        'title' => '_MI_TADFAQ_MODULE_TITLE',
        'description' => '_MI_TADFAQ_MODULE_TITLE_DESC',
        'formtype' => 'textbox',
        'valuetype' => 'text',
        'default' => _MI_TADFAQ_MODULE_TITLE_VAL,
    ],
];
